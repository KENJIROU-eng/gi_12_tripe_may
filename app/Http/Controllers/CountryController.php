<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function setCountry(Request $request)
    {
        session(['weather_country_id' => $request->input('country_id')]);
        return back();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:5|alpha',
            'city' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-z\s]+$/'
            ],
        ], [
            'city.regex' => 'Please enter city names in English (e.g. Tokyo, New York)',
        ]);

        Country::create([
            'name' => $request->name,
            'code' => $request->code,
            'city' => $request->city,
            'user_id' => auth()->id(), // ← 追加
        ]);

        return redirect()->back()->with('success', '国を追加しました');
    }

    public function destroy($id)
    {
        $country = Country::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $country->delete();

        // AJAXでリアルタイム削除用のJSONレスポンス
        return response()->json(['message' => 'Country deleted.']);
    }




}

