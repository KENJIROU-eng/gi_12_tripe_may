<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Itinerary;
use App\Models\ItineraryMemo;

class ItineraryMemoController extends Controller
{
    public function save(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'nullable|string',
        ]);

        $memo = ItineraryMemo::updateOrCreate(
            ['itinerary_id' => $id],
            ['content' => $validated['content']]
        );

        return response()->json(['message' => 'Saved successfully']);
    }
}

