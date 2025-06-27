<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class WeatherController extends Controller
{
    public function fetch(Request $request)
    {
        $countryId = session('weather_country_id');
        $country = \App\Models\Country::find($countryId);

        if (!$country || !$country->city) {
            return response()->json(['error' => 'No city selected'], 400);
        }

        try {
            $res = Http::timeout(10)->get('https://api.weatherapi.com/v1/current.json', [
                'key' => config('services.weatherapi.key'),
                'q'   => $country->city,
                'aqi' => 'no',
                'lang' => 'ja'
            ]);

            if ($res->successful()) {
                $data = $res->json();
                return response()->json([
                    'temp' => $data['current']['temp_c'],
                    'icon' => 'https:' . $data['current']['condition']['icon'],
                    'text' => $data['current']['condition']['text'],
                ]);
            } else {
                return response()->json(['error' => 'Weather API failed'], 500);
            }
        } catch (\Throwable $e) {
            \Log::warning('Weather fetch failed: ' . $e->getMessage());
            return response()->json(['error' => 'Exception occurred'], 500);
        }
    }
}

