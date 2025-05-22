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
        $validated = $request->validate([
            'title'       => 'required|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $user = auth()->user();

        $destinations = collect($request->input('destinations', []))->sortKeys();

        $firstPlace = '';
        foreach ($destinations as $date => $places) {
            if (!empty($places)) {
                $firstPlace = $places[0];
                break;
            }
        }

        $groupId = session('group_id');

        if (session('share') !== 'yes') {
            $groupId = null;
        }

        if (session('share') === 'yes' && !$groupId) {
            return back()->withErrors(['group' => 'Select a group when sharing.']);
        }

        $itinerary = Itinerary::create([
            'created_by'    => $user->id,
            'group_id'      => $groupId,
            'title'         => $validated['title'],
            'start_date'    => $validated['start_date'],
            'end_date'      => $validated['end_date'],
            'initial_place' => $firstPlace,
        ]);

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

        $prevPlace = null;

        foreach ($destinations as $date => $places) {
            $dateId = $dateIds[$date] ?? null;
            if (!$dateId || empty($places)) continue;

            foreach ($places as $i => $destination) {
                if (empty($destination)) continue;

                $distance  = null;
                $duration  = null;
                $placeName = null;

                if ($prevPlace !== null) {
                    $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                        'origins'      => $prevPlace,
                        'destinations' => $destination,
                        'key'          => env('GOOGLE_MAPS_API_KEY'),
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        $element = $data['rows'][0]['elements'][0] ?? null;
                        if ($element && $element['status'] === 'OK') {
                            $distance = isset($element['distance']['value']) ? $element['distance']['value'] / 1000 : null;
                            $duration = $element['duration']['text'] ?? null;
                        }
                    }
                }

                $geoResponse = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                    'address' => $destination,
                    'key'     => env('GOOGLE_MAPS_API_KEY'),
                ]);

                if ($geoResponse->successful()) {
                    $geoData = $geoResponse->json();
                    $results = $geoData['results'][0] ?? null;

                    if ($results && isset($results['place_id'])) {
                        $placeId = $results['place_id'];

                        $detailsResponse = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                            'place_id' => $placeId,
                            'key'      => env('GOOGLE_MAPS_API_KEY'),
                            'fields'   => 'name',
                        ]);

                        if ($detailsResponse->successful()) {
                            $detailsData = $detailsResponse->json();
                            $placeName   = $detailsData['result']['name'] ?? null;
                        }
                    }
                }

                MapItinerary::create([
                    'date_id'     => $dateId,
                    'destination' => $destination,
                    'place_name' => $placeName,
                    'distance_km'    => $distance,
                    'duration_text'    => $duration,
                ]);

                $prevPlace = $destination;
            }
        }

        session()->forget(['group_id', 'share']);

        return redirect()->route('itinerary.index')->with('success', 'Itinerary saved');
    }

    /**
     * Display the specified resource.
     */
    public function show($itinerary_id)
    {
        $itinerary = $this->itinerary->with(['dateItineraries.mapItineraries'])->findOrFail($itinerary_id);

        // Convert start and end dates to Carbon instances
        $startDate = Carbon::parse($itinerary->start_date);
        $endDate   = Carbon::parse($itinerary->end_date);

        // Create an array of dates with CarbonPeriod
        $period = CarbonPeriod::create($startDate, $endDate);

        return view('itineraries.show')->with('itinerary', $itinerary)->with('period', $period);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($itinerary_id)
    {
        $itinerary = $this->itinerary->findOrFail($itinerary_id);

        $itineraryData = [
            'startDate' => $itinerary->start_date->format('Y-m-d'),
            'endDate' => $itinerary->end_date->format('Y-m-d'),
            'destinations' => $itinerary->dateItineraries->groupBy('date')->map(function ($items) {
                return $items->pluck('place');
            }),
        ];

        if (Auth::user()->id != $itinerary->user->id && Auth::user()->id != $itinerary->group->user_id) {
            return redirect()->route('itinerary.index');
        }

        return view('itineraries.edit')->with('itinerary', $itinerary)->with('itineraryData', $itineraryData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Itinerary $itinerary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($itinerary_id)
    {
        $this->itinerary->destroy($itinerary_id);
        return redirect()->back();
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
