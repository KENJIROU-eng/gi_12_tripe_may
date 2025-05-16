<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\MapItinerary;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

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
        return view('itineraries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
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

        $group = $user->groups()->firstOrFail();

        $itinerary = Itinerary::create([
            'created_by'    => $user->id,
            'group_id'      => $group->id,
            'title'         => $validated['title'],
            'start_date'    => $validated['start_date'],
            'end_date'      => $validated['end_date'],
            'initial_place' => $firstPlace,
        ]);

        foreach ($destinations as $date => $places) {
            foreach ($places as $destination) {
                MapItinerary::create([
                    'date_id'     => $itinerary->id,
                    'destination' => $destination,
                ]);
            }
        }

        return response()->json(['message' => 'Itinerary saved']);
    }

    /**
     * Display the specified resource.
     */
    public function show($itinerary_id)
    {
        $itinerary = $this->itinerary->findOrFail($itinerary_id);

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
    public function edit(Itinerary $itinerary)
    {
        //
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
}
