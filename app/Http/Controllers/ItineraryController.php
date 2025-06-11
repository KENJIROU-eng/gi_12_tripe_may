<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Itinerary;
use App\Models\group;
use App\Models\MapItinerary;
use App\Models\DateItinerary;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class ItineraryController extends Controller
{

    private $itinerary;

    public function __construct(Itinerary $itinerary) {
        $this->itinerary = $itinerary;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_itineraries = $this->itinerary->latest()->paginate(10)->onEachSide(2);

        return view('itineraries.index')->with('all_itineraries', $all_itineraries);
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
        if (session('share') !== 'yes') {
            $groupId = null;
        }
        if (session('share') === 'yes' && !$groupId) {
            return back()->withErrors(['group' => 'Select a group when sharing.']);
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
    public function show($itinerary_id)
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

            $groupName = $itinerary->group ? $itinerary->group->name : null;
            $groupMembers = $itinerary->group ? $itinerary->group->users : collect();

            $maxDisplay = 3;
            $displayMembers = $groupMembers->take($maxDisplay);
            $remainingCount = max(0, $groupMembers->count() - $maxDisplay);

            return view('itineraries.show', [
                'itinerary' => $itinerary,
                'period' => $period,
                'itineraryData' => $itineraryData,
                'all_belongings' => $all_belongings,
                'displayMembers' => $displayMembers,
                'remainingCount' => $remainingCount,
                'groupName'      => $groupName,
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
    // dd([
    //     'current_user_id' => $currentUser ? $currentUser->id : null,
    //     'itinerary_user_id' => $itinerary->created_by,  // ← ここを修正
    //     'isOwner' => $currentUser && $currentUser->id === $itinerary->created_by,
    // ]);
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
        $groupId = $groupId === '' ? null : $groupId;

        $destinationsAddress    = $request->input('destinations', []);
        $destinationsLat        = $request->input('destinations_lat', []);
        $destinationsLng        = $request->input('destinations_lng', []);
        $destinationsPlaceIds   = $request->input('destinations_place_id', []);
        $destinationsPlaceNames = $request->input('destinations_place_name', []);
        $travelModes            = $request->input('travel_modes', []);

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

        $itinerary->update([
            'title'              => $validated['title'],
            'start_date'         => $validated['start_date'],
            'end_date'           => $validated['end_date'],
            'group_id'           => $groupId,
            'initial_place_name' => $firstPlaceName,
            'initial_latitude'   => $firstLat,
            'initial_longitude'  => $firstLng,
        ]);

        foreach ($itinerary->dateItineraries as $dateItinerary) {
            MapItinerary::where('date_id', $dateItinerary->id)->delete();
            $dateItinerary->delete();
        }

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

            $placeCount = count($places);
            for ($i = 0; $i < $placeCount; $i++) {
                $destination = $places[$i] ?? null;
                if (empty($destination)) continue;

                $lat         = $destinationsLat[$date][$i]        ?? null;
                $lng         = $destinationsLng[$date][$i]        ?? null;
                $placeId     = $destinationsPlaceIds[$date][$i]   ?? null;
                $placeName   = $destinationsPlaceNames[$date][$i] ?? $destination;
                $travelMode  = $travelModes[$date][$i] ?? 'DRIVING';

                $modeForApi = $travelMode === 'MOTORCYCLE' ? 'driving' : strtolower($travelMode);

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
        return redirect()->view('itineraries.index');
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
}
