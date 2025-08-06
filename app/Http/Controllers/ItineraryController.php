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

        $prevLat = null;
        $prevLng = null;
        $prevPlaceName = null;
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
                // 緯度経度ベースでの距離取得
                if (!is_null($prevLat) && !is_null($prevLng) && !is_null($lat) && !is_null($lng)) {
                    $origin = "{$prevLat},{$prevLng}";
                    $destinationCoords = "{$lat},{$lng}";
                    $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                        'origins'      => $origin,
                        'destinations' => $destinationCoords,
                        'mode'         => $modeForApi,
                        'key'          => env('GOOGLE_MAPS_API_KEY'),
                    ]);
                    \Log::debug(":物差し: DistanceMatrix API call:", compact('origin', 'destinationCoords', 'modeForApi'));
                    if ($response->successful()) {
                        $data = $response->json();
                        $element = $data['rows'][0]['elements'][0] ?? null;
                        if ($element && $element['status'] === 'OK') {
                            $distance = $element['distance']['value'] / 1000;
                            $duration = $element['duration']['text'] ?? null;
                        } else {
                            \Log::warning("DistanceMatrix element invalid:", ['element' => $element, 'response' => $data]);
                        }
                    } else {
                        \Log::error("DistanceMatrix failed:", ['response' => $response->body()]);
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
                $prevLat = $lat;
                $prevLng = $lng;
                $prevPlaceName = $placeName;
            }
        }

        session()->forget(['group_id', 'share']);

        return redirect()->route('itinerary.show', ['itinerary_id' => $itinerary->id])->with('success', 'Itinerary saved');
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
        $user = Auth::user();

        $itinerary = $this->itinerary
            ->with(['dateItineraries.mapItineraries', 'group.users', 'bills.billUser'])
            ->findOrFail($itinerary_id);

        // 所属グループID取得（nullを除外）
        $userGroupIds = $user->groups->pluck('id')->filter()->all();

        // 次の旅程（IDが大きい）
        $next = Itinerary::where(function ($query) use ($user, $userGroupIds) {
                $query->where('created_by', $user->id)
                    ->orWhereIn('group_id', $userGroupIds);
            })
            ->where('id', '>', $itinerary->id)
            ->orderBy('id')
            ->first();

        // 前の旅程（IDが小さい）
        $previous = Itinerary::where(function ($query) use ($user, $userGroupIds) {
                $query->where('created_by', $user->id)
                    ->orWhereIn('group_id', $userGroupIds);
            })
            ->where('id', '<', $itinerary->id)
            ->orderBy('id', 'desc')
            ->first();

        // belongings と進捗計算
        $all_belongings = $itinerary->belongings;
        $totalCount = $all_belongings->count();
        $checkedCount = $all_belongings->where('checked', true)->count();
        $progressPercent = $totalCount > 0 ? floor(($checkedCount / $totalCount) * 100) : 0;

        // map data
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

        // bill計算
        $total_getPay = [];
        $total_Pay = [];
        if ($itinerary->group_id != null) {
            foreach ($itinerary->group->users as $member) {
                $total_getPay[$member->id] = $costCalculator->total_getPay($itinerary, $member);
                $total_Pay[$member->id] = $costCalculator->total_Pay($itinerary, $this->billUser, $member);
            }
        }

        $memo = $itinerary->memo?->content ?? '';

        return view('itineraries.show', [
            'itinerary' => $itinerary,
            'previous' => $previous,
            'next' => $next,
            'period' => $period,
            'itineraryData' => $itineraryData,
            'all_belongings' => $all_belongings,
            'total_getPay' => $total_getPay,
            'total_Pay' => $total_Pay,
            'totalCount' => $totalCount,
            'checkedCount' => $checkedCount,
            'progressPercent' => $progressPercent,
            'itineraryMemo' => $memo,
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
        \Log::debug('Validated data:', $validated);

        DB::beginTransaction();

        try {
            $itinerary = $this->itinerary->findOrFail($itinerary_id);

            if (Auth::user()->id !== $itinerary->created_by && Auth::user()->id !== optional($itinerary->group)->user_id) {
                return redirect()->route('itinerary.index')->withErrors(['unauthorized' => 'You are not authorized to update this itinerary.']);
            }

            // 保存前の group_id を保持しておく（比較用）
            $originalGroupId = $itinerary->group_id;
            $groupId = $request->input('group_id');

            if (empty($groupId)) {
                $group = Group::getOrCreatePersonalGroup(Auth::id());
                $groupId = $group->id;
            }


            // グループが変更されたかを判定
            $groupChanged = (int) $originalGroupId !== (int) $groupId;

            // Google Maps 関連取得
            $destinationsAddress    = $request->input('destinations', []);
            $destinationsLat        = $request->input('destinations_lat', []);
            $destinationsLng        = $request->input('destinations_lng', []);
            $destinationsPlaceIds   = $request->input('destinations_place_id', []);
            $destinationsPlaceNames = $request->input('destinations_place_name', []);
            $travelModes            = $request->input('travel_modes', []);

            // 初期地点処理
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

            // update（group_idもここで更新するが、変更前と比較済み）
            $itinerary->update([
                'title'              => $validated['title'],
                'start_date'         => $validated['start_date'],
                'end_date'           => $validated['end_date'],
                'group_id'           => $groupId,
                'initial_place_name' => $firstPlaceName,
                'initial_latitude'   => $firstLat,
                'initial_longitude'  => $firstLng,
            ]);

            // ✅ グループが変更された場合のみ GoDutchデータ削除
            if ($groupChanged) {
                $billIds = $this->bill->where('itinerary_id', $itinerary->id)->pluck('id');
                $this->billUser->whereIn('bill_id', $billIds)->delete();
                $this->bill->where('itinerary_id', $itinerary->id)->delete();
            }

            // 古い MapItinerary・DateItinerary を削除
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

            // ルート情報登録
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

                    // Google APIで正式名称取得
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

                    // 距離と所要時間を取得
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
            return redirect()->route('itinerary.show', ['itinerary_id' => $itinerary->id])->with('success', 'Itinerary updated successfully.');
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

    public function toggleFinish(Itinerary $itinerary)
    {
        if (auth()->id() !== $itinerary->created_by) {
            abort(403, 'Unauthorized action.');
        }

        // 完了→未完了（nullに）、未完了→完了（今の日時に）
        $itinerary->finish_at = $itinerary->finish_at ? null : now();
        $itinerary->save();

        return back()->with('success', 'Itinerary status updated.');
    }
}
