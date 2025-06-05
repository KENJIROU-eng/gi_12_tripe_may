<?php

namespace App\Http\Controllers;

use App\Models\Belonging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BelongingController extends Controller
{
    public function index($itinerary_id)
    {
        $all_belongings = Belonging::where('itinerary_id', $itinerary_id)->latest()->get();

        return view('belongings.index', [
            'all_belongings' => $all_belongings,
            'itinerary_id' => $itinerary_id,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'itinerary_id' => 'required|integer|exists:itineraries,id',
        ]);

        $belonging = Belonging::create([
            'itinerary_id' => $validated['itinerary_id'],
            'name' => $validated['name'],
            'checked' => false,
        ]);

        return response()->json($belonging);
    }

    public function update(Request $request, Belonging $belonging)
    {
        $data = $request->only(['name', 'is_checked']);

        // 型の強制（is_checked は boolean にキャスト）
        if ($request->has('is_checked')) {
            $data['checked'] = filter_var($request->is_checked, FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->has('name')) {
            $request->validate(['name' => 'required|string|max:255']);
        }

        $belonging->update($data);

        return response()->json($belonging);
    }

    public function destroy(Belonging $belonging)
    {
        $belonging->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
