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

        Country::create($request->only(['name', 'code', 'city']));

        return redirect()->back()->with('success', '国を追加しました');
    }

}

