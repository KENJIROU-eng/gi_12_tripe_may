<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Country;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Itinerary;
use App\Models\Message;
use App\Models\ReadMessage;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
        if (Auth::check()) {
            $groupMembers = GroupMember::where('user_id', Auth::User()->id);
            $groupIds = $groupMembers->pluck('group_id')->toArray();
            $groups = Group::whereIn('id', $groupIds)->get();
            $itineraries = Itinerary::whereIn('group_id', $groupIds)->orderBy('start_date')->get();
            $tripSchedule = [];
            $tripName = [];
            $tripId = [];
            $routeUrls = [];
            foreach ($itineraries as $itinerary) {
                $start_date = new \DateTime($itinerary->start_date);
                $end_date = new \DateTime($itinerary->end_date);
                $tripSchedule[] = [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')];
                $tripName[] = $itinerary->title;
                $tripId[] = $itinerary->id;
                $routeUrls[] = route('itinerary.show', $itinerary->id);
            }
            $nonReadCount = [];
            $nonReadCount_total = 0;
            foreach ($groupIds as $groupId) {
                $messageIds = Message::where('group_id', $groupId)->pluck('id')->toArray();
                $readmessages = ReadMessage::whereIn('message_id', $messageIds)->get();
                $nonReadCount[$groupId] = $readmessages->where('user_id', Auth::User()->id)->whereNull('read_at')->count();
                $nonReadCount_total = $nonReadCount_total + $readmessages->where('user_id', Auth::User()->id)->whereNull('read_at')->count();
            }
            $today = Carbon::today()->toDateString();
            $user = Auth::user();

            $todayItineraries = Itinerary::whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->where(function ($query) use ($user, $groupIds) {
                    $query->where('created_by', $user->id)
                        ->orWhereIn('group_id', $groupIds);
                })
                ->get();


            // バージョンは config に書かれていることを前提
            $appVersion = config('app.version', 'v1.0.0');

            // 国選択 & 天気情報
            $countryId = session('weather_country_id');
            $country = Country::find($countryId) ?? Country::first();

            $weather = null;
            if ($country) {
                $apiKey = config('services.weatherapi.key'); // .envに設定しておくこと
                $res = Http::get("https://api.weatherapi.com/v1/current.json", [
                    'key' => $apiKey,
                    'q' => $country->city,
                    'lang' => 'ja',
                ]);

                    if ($res->ok()) {
                        $data = $res->json();
                        $weather = [
                            'temp' => round($data['current']['temp_c']),
                            'desc' => $data['current']['condition']['text'],
                            'icon' => 'https:' . $data['current']['condition']['icon'],
                        ];
                    } else {
                        \Log::error('Weather API failed', [
                            'status' => $res->status(),
                            'body' => $res->body(),
                            'city' => $country->city,
                        ]);
                    }

            }

            $view->with('groupIds', $groupIds)
                ->with('groups', $groups)
                ->with('itineraries', $itineraries)
                ->with('today', $today)
                ->with('nonReadCount', $nonReadCount)
                ->with('nonReadCount_total', $nonReadCount_total)
                ->with('tripSchedule', $tripSchedule)
                ->with('tripName', $tripName)
                ->with('routeUrls', $routeUrls)
                ->with('weather', $weather)
                ->with('todayItineraries', $todayItineraries)
                ->with('allCountries', Country::all())
                ->with('tripId', $tripId)
                ->with('routeUrls', $routeUrls);
        }
    });
    }
}
