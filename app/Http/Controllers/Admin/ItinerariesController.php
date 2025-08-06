<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Itinerary;

class ItinerariesController extends Controller
{
    private $itinerary;

    public function __construct(Itinerary $itinerary) {
        $this->itinerary = $itinerary;
    }

    public function index() {
        $all_itineraries = $this->itinerary->paginate(6)->onEachSide(2);

        return view('admin.itineraries.show')
            ->with('all_itineraries', $all_itineraries);
    }

    public function destroy($itinerary_id) {
        $itinerary = $this->itinerary->findOrFail($itinerary_id);
        $itinerary->delete();

        return redirect()->route('admin.itineraries.show');
    }
}
