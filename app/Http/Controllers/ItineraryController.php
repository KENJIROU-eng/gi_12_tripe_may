<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Itinerary;
use App\Models\Group;
use App\Models\MapItinerary;
use App\Models\DateItinerary;
use App\Models\BillUser;
use App\Models\Bill;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\CostCalculator;

class ItineraryController extends Controller
{

    private $itinerary;
    private $billUser;
    private $bill;

    public function __construct(Itinerary $itinerary, BillUser $billUser, Bill $bill)
    {
        $this->itinerary = $itinerary;
        $this->billUser = $billUser;
        $this->bill = $bill;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // 自分が作成した行程
        $ownItineraries = Itinerary::with(['user', 'group'])
            ->where('created_by', $user->id)
            ->get();

        // 所属グループの行程
        $groupIds = $user->groups->pluck('id');
        $groupItineraries = Itinerary::with(['user', 'group'])
            ->whereIn('group_id', $groupIds)
            ->get();

        // 合体・重複排除・日付で並び替え
        $merged = $ownItineraries
            ->merge($groupItineraries)
            ->unique('id')
            ->sortByDesc('created_at')
            ->values();

        return view('itineraries.index', [
            'all_itineraries' => $merged,          // フィルター・セレクト用
            'initial_itineraries' => $merged,      // 初期表示（必要なら slice 可）
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $share   = session('share');
        $groupId = session('group_id');

        return view('itineraries.create')->with('share', $share)->with('groupId', $groupId);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::debug('💡 travel_modes:', $request->input('travel_modes', []));

        $validated = $request->validate([
            'title'      => 'required|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $user = auth()->user();

        $destinations         = collect($request->input('destinations', []))->sortKeys();
        $destinationsLat      = $request->input('destinations_lat', []);
        $destinationsLng      = $request->input('destinations_lng', []);
        $destinationsPlaceIds = $request->input('destinations_place_id', []);
        $travelModes          = $request->input('travel_modes', []);

        // dd($travelModes);

        // 最初の地点を初期地点として取得
        $firstPlace = null;
        foreach ($destinations as $places) {
            if (!empty($places)) {
                $firstPlace = $places[0];
                break;
            }
        }

        $firstLat = null;
        $firstLng = null;
        $firstPlaceName = null;
        $cache = [];

        if ($firstPlace) {
            [$firstLat, $firstLng, $firstPlaceName] = $this->fetchPlaceInfo($firstPlace, $cache);
        }

        // グループ処理
        $groupId = session('group_id');

        if (session('share') === 'yes') {
            if (!$groupId) {
                return back()->withErrors(['group' => 'Select a group when sharing.']);
            }
        } else {
            // 共有しない場合は一人グループを取得・または作成
            $personalGroup = \App\Models\Group::getOrCreatePersonalGroup($user->id);
            $groupId = $personalGroup->id;
        }


        // 行程作成
        $itinerary = Itinerary::create([
            'created_by'         => $user->id,
            'group_id'           => $groupId,
            'title'              => $validated['title'],
            'start_date'         => $validated['start_date'],
            'end_date'           => $validated['end_date'],
            'initial_place_name' => $firstPlaceName,
            'initial_latitude'   => $firstLat,
            'initial_longitude'  => $firstLng,
        ]);

        // 日付ごとの DateItinerary 作成
        $dateIds = [];
        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);

        for ($date = $start; $date->lte($end); $date->addDay()) {
            $dateRecord = DateItinerary::create([
                'itinerary_id' => $itinerary->id,
                'date'         => $date->toDateString(),
            ]);
            $dateIds[$date->toDateString()] = $dateRecord->id;
        }

        // 各地点の保存
        $prevPlace = null;

        foreach ($destinations as $date => $places) {
            $dateId = $dateIds[$date] ?? null;
            if (!$dateId || empty($places)) continue;

            $placeCount = count($places);
            for ($i = 0; $i < $placeCount; $i++) {
                $destination = $places[$i];
                if (empty($destination)) continue;

                $lat     = $destinationsLat[$date][$i]      ?? null;
                $lng     = $destinationsLng[$date][$i]      ?? null;
                $placeId = $destinationsPlaceIds[$date][$i] ?? null;
                $travelMode = $travelModes[$date][$i] ?? 'DRIVING';

                // バイクは API 上 driving として扱う
                $modeForApi = $travelMode === 'MOTORCYCLE' ? 'driving' : strtolower($travelMode);

                $placeName = $destination;

                if ($placeId) {
                    $placeDetailResponse = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                        'place_id' => $placeId,
                        'fields'   => 'name',
                        'key'      => env('GOOGLE_MAPS_API_KEY'),
                    ]);

                    if ($placeDetailResponse->successful()) {
                        $placeDetail = $placeDetailResponse->json();
                        if (isset($placeDetail['result']['name'])) {
                            $placeName = $placeDetail['result']['name'];
                        }
                    }
                }

                $distance = null;
                $duration = null;

                if ($prevPlace) {
                    $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                        'origins'      => $prevPlace,
                        'destinations' => $destination,
                        'mode'         => $modeForApi,
                        'key'          => env('GOOGLE_MAPS_API_KEY'),
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        $element = $data['rows'][0]['elements'][0] ?? null;
                        if ($element && $element['status'] === 'OK') {
                            $distance = $element['distance']['value'] / 1000;
                            $duration = $element['duration']['text'] ?? null;
                        }
                    }
                }

                MapItinerary::create([
                    'date_id'       => $dateId,
                    'destination'   => $destination,
                    'place_name'    => $placeName,
                    'latitude'      => $lat,
                    'longitude'     => $lng,
                    'distance_km'   => $distance,
                    'duration_text' => $duration,
                    'place_id'      => $placeId,
                    'travel_mode'   => $travelMode,
                ]);

                $prevPlace = $destination;
            }
        }

        session()->forget(['group_id', 'share']);

        return redirect()->route('itinerary.index')->with('success', 'Itinerary saved');
    }

    private function fetchPlaceInfo(string $address, array &$cache): array
    {
        if (isset($cache[$address])) {
            return $cache[$address];
        }

        $lat = $lng = $placeName = null;

        $geoResponse = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key'     => env('GOOGLE_MAPS_API_KEY'),
        ]);

        if ($geoResponse->successful()) {
            $geoData = $geoResponse->json();
            $result = $geoData['results'][0] ?? null;

            if ($result) {
                $location = $result['geometry']['location'] ?? null;
                $lat = $location['lat'] ?? null;
                $lng = $location['lng'] ?? null;

                $placeId = $result['place_id'] ?? null;
                if ($placeId) {
                    $detailsResponse = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                        'place_id' => $placeId,
                        'key'      => env('GOOGLE_MAPS_API_KEY'),
                        'fields'   => 'name',
                    ]);

                    if ($detailsResponse->successful()) {
                        $placeName = $detailsResponse->json()['result']['name'] ?? null;
                    } else {
                        \Log::error("Place Details failed for $address", ['response' => $detailsResponse->body()]);
                    }
                }
            } else {
                \Log::warning("Geocode returned no result for $address");
            }
        } else {
            \Log::error("Geocoding failed for $address", ['response' => $geoResponse->body()]);
        }

        return $cache[$address] = [$lat, $lng, $placeName];
    }

    /**
     * Display the specified resource.
     */
public function show($itinerary_id, CostCalculator $costCalculator)
    {
        $itinerary = $this->itinerary
            ->with(['dateItineraries.mapItineraries', 'group.users'])
            ->findOrFail($itinerary_id);

            $all_belongings = $itinerary->belongings;

            $itineraryData = [];

            foreach ($itinerary->dateItineraries as $dateItinerary) {
                $date = $dateItinerary->date instanceof \Carbon\Carbon
                    ? $dateItinerary->date->format('Y-m-d')
                    : (string) $dateItinerary->date;

                $itineraryData['destinations'][$date] = [];

                foreach ($dateItinerary->mapItineraries as $map) {
                    $itineraryData['destinations'][$date][] = [
                        'place_name'    => $map->place_name ?? $map->destination ?? '',
                        'latitude'      => $map->latitude,
                        'longitude'     => $map->longitude,
                        'place_id'      => $map->place_id ?? null,
                        'address'       => $map->destination ?? null,
                        'distance_km'   => $map->distance_km ?? null,
                        'duration_text' => $map->duration_text ?? null,
                        'travel_mode'   => $map->travel_mode ?? 'DRIVING',
                    ];
                }
            }

        $startDate = \Carbon\Carbon::parse($itinerary->start_date);
        $endDate = \Carbon\Carbon::parse($itinerary->end_date);
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);

        //bill calculation
        $total_getPay = [];
        $total_Pay = [];
        if ($itinerary->group_id != null) {
            foreach ($itinerary->group->users as $user) {
                $total_getPay[$user->id] = $costCalculator->total_getPay($itinerary, $user);
                $total_Pay[$user->id] = $costCalculator->total_Pay($itinerary, $this->billUser, $user);
            }
        }

        return view('itineraries.show', [
            'itinerary' => $itinerary,
            'period' => $period,
            'itineraryData' => $itineraryData,
            'all_belongings' => $all_belongings,
            'total_getPay' => $total_getPay,
            'total_Pay' => $total_Pay,
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($itinerary_id)
    {
        $itinerary = $this->itinerary
            ->with(['user', 'group.users', 'dateItineraries.mapItineraries'])
            ->findOrFail($itinerary_id);

        $currentUser = Auth::user();

        $isOwner = $currentUser && $currentUser->id === $itinerary->created_by;

        // アクセス権チェック：作成者 or グループに所属
        $isGroupMember = $itinerary->group && $itinerary->group->users->contains($currentUser->id);
        if (!($isOwner || $isGroupMember)) {
            return redirect()->route('itinerary.index')->with('error', 'アクセス権がありません');
        }

        // 日付ごとの目的地を整形
        $destinationsByDate = $itinerary->dateItineraries->mapWithKeys(function ($dateItinerary) {
            $date = optional($dateItinerary->date)->format('Y-m-d');

            $destinations = $dateItinerary->mapItineraries->map(function ($mapItinerary) {
                return [
                    'place_name' => $mapItinerary->place_name,
                    'latitude'   => $mapItinerary->latitude,
                    'longitude'  => $mapItinerary->longitude,
                    'place_id'   => $mapItinerary->place_id,
                    'address'    => $mapItinerary->destination,
                    'travel_mode' => $mapItinerary->travel_mode,
                ];
            })->filter(function ($dest) {
                return !empty($dest['place_name']);
            })->values()->toArray();

            return [$date => $destinations];
        })->toArray();

        $itineraryData = [
            'start_date'   => optional($itinerary->start_date)->format('Y-m-d'),
            'end_date'     => optional($itinerary->end_date)->format('Y-m-d'),
            'destinations' => $destinationsByDate,
        ];

        // 作成者のみグループ再選択用の一覧を取得
        $allGroups = $currentUser->groups()->get();

        return view('itineraries.edit', [
            'itinerary'       => $itinerary,
            'itineraryData'   => $itineraryData,
            'isOwner'         => $isOwner,
            'allGroups'       => $allGroups,
            'groupMembers'    => $itinerary->group ? $itinerary->group->users : collect(),
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $itinerary_id)
    {
        $validated = $request->validate([
            'title'      => 'required|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'group_id'   => 'nullable|exists:groups,id',
        ]);

        DB::beginTransaction();

        try {
            $itinerary = $this->itinerary->findOrFail($itinerary_id);

            if (Auth::user()->id != $itinerary->created_by && Auth::user()->id != optional($itinerary->group)->user_id) {
                return redirect()->route('itinerary.index')->withErrors(['unauthorized' => 'You are not authorized to update this itinerary.']);
            }

            $groupId = $request->input('group_id');

            if (empty($groupId)) {
                $group = Group::getOrCreatePersonalGroup(Auth::id());
                $groupId = $group->id;
            }

            // 目的地関連データ
            $destinationsAddress    = $request->input('destinations', []);
            $destinationsLat        = $request->input('destinations_lat', []);
            $destinationsLng        = $request->input('destinations_lng', []);
            $destinationsPlaceIds   = $request->input('destinations_place_id', []);
            $destinationsPlaceNames = $request->input('destinations_place_name', []);
            $travelModes            = $request->input('travel_modes', []);

            // 初期地点
            $firstPlace = null;
            foreach ($destinationsAddress as $places) {
                if (!empty($places)) {
                    $firstPlace = $places[0];
                    break;
                }
            }

            $firstLat = null;
            $firstLng = null;
            $firstPlaceName = null;
            $cache = [];

            if ($firstPlace) {
                [$firstLat, $firstLng, $firstPlaceName] = $this->fetchPlaceInfo($firstPlace, $cache);
            }

            // グループ変更前のgroup_idを保持
            $originalGroupId = $itinerary->group_id;

            $itinerary->update([
                'title'              => $validated['title'],
                'start_date'         => $validated['start_date'],
                'end_date'           => $validated['end_date'],
                'group_id'           => $groupId,
                'initial_place_name' => $firstPlaceName,
                'initial_latitude'   => $firstLat,
                'initial_longitude'  => $firstLng,
            ]);

            // グループが変更された場合、GoDutch関連データを削除
            if ($originalGroupId !== $groupId) {
                // GoDutchデータ削除（例: GodutchPayment や GodutchUser などの関連モデル）
                $billIds = $this->bill->where('itinerary_id', $itinerary->id)->pluck('id');
                $this->billUser->whereIn('bill_id', $billIds)->delete();
                $this->bill->where('itinerary_id', $itinerary->id)->delete();

            }

            // 古い日付・目的地を削除
            foreach ($itinerary->dateItineraries as $dateItinerary) {
                MapItinerary::where('date_id', $dateItinerary->id)->delete();
                $dateItinerary->delete();
            }

            // 新しい日付を作成
            $dateIds = [];
            $start = Carbon::parse($validated['start_date']);
            $end   = Carbon::parse($validated['end_date']);

            for ($date = $start; $date->lte($end); $date->addDay()) {
                $dateRecord = DateItinerary::create([
                    'itinerary_id' => $itinerary->id,
                    'date'         => $date->toDateString(),
                ]);
                $dateIds[$date->toDateString()] = $dateRecord->id;
            }

            $prevPlace = null;

            foreach ($destinationsAddress as $date => $places) {
                $dateId = $dateIds[$date] ?? null;
                if (!$dateId || empty($places)) continue;

                foreach ($places as $index => $destination) {
                    if (empty($destination)) continue;

                    $lat        = $destinationsLat[$date][$index]        ?? null;
                    $lng        = $destinationsLng[$date][$index]        ?? null;
                    $placeId    = $destinationsPlaceIds[$date][$index]   ?? null;
                    $placeName  = $destinationsPlaceNames[$date][$index] ?? $destination;
                    $travelMode = $travelModes[$date][$index] ?? 'DRIVING';

                    // Google API で place_name 補完（任意）
                    if ($placeId) {
                        $placeDetailResponse = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                            'place_id' => $placeId,
                            'fields'   => 'name',
                            'key'      => env('GOOGLE_MAPS_API_KEY'),
                        ]);

                        if ($placeDetailResponse->successful()) {
                            $placeDetail = $placeDetailResponse->json();
                            if (isset($placeDetail['result']['name'])) {
                                $placeName = $placeDetail['result']['name'];
                            }
                        }
                    }

                    $distance = null;
                    $duration = null;

                    if ($prevPlace) {
                        $modeForApi = $travelMode === 'MOTORCYCLE' ? 'driving' : strtolower($travelMode);
                        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                            'origins'      => $prevPlace,
                            'destinations' => $destination,
                            'mode'         => $modeForApi,
                            'key'          => env('GOOGLE_MAPS_API_KEY'),
                        ]);

                        if ($response->successful()) {
                            $data = $response->json();
                            $element = $data['rows'][0]['elements'][0] ?? null;
                            if ($element && $element['status'] === 'OK') {
                                $distance = $element['distance']['value'] / 1000;
                                $duration = $element['duration']['text'] ?? null;
                            }
                        }
                    }

                    MapItinerary::create([
                        'date_id'       => $dateId,
                        'destination'   => $destination,
                        'place_name'    => $placeName,
                        'latitude'      => $lat,
                        'longitude'     => $lng,
                        'distance_km'   => $distance,
                        'duration_text' => $duration,
                        'place_id'      => $placeId,
                        'travel_mode'   => $travelMode,
                    ]);

                    $prevPlace = $destination;
                }
            }

            DB::commit();
            return redirect()->route('itinerary.show', ['id' => $itinerary->id])->with('success', 'Itinerary updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Itinerary update failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['update_failed' => 'An error occurred while updating the itinerary.']);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($itinerary_id)
    {
        $this->itinerary->destroy($itinerary_id);
        return redirect()->route('itinerary.index');
    }

    public function shareSelect() {
        $groups = auth()->user()->groups;

        return view('itineraries.share')->with('groups', $groups);
    }

    public function prefill(Request $request) {
        $request->validate([
            'share' => 'required|in:yes,no',
            'group' => 'nullable|exists:groups,id',
        ]);

        session([
            'share'    => $request->share,
            'group_id' => $request->group,
        ]);

        return redirect()->route('itinerary.create');

    }

    // public function loadMore(Request $request)
    // {
    //     $user = Auth::user();
    //     $groupIds = $user->groups->pluck('id');

    //     $page = $request->get('page', 1);

    //     $itineraries = Itinerary::with(['user', 'group'])
    //         ->where('created_by', $user->id)
    //         ->orWhereIn('group_id', $groupIds)
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(10, ['*'], 'page', $page);

    //     return view('itineraries.partials.scroll', [
    //         'all_itineraries' => $itineraries
    //     ]);
    // }

}
