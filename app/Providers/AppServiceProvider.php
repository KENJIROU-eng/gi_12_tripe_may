<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\GroupMember;
use App\Models\Itinerary;

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
            $groups = GroupMember::where('user_id', Auth::User()->id);
            $groupIds = $groups->pluck('group_id')->toArray();
            $itineraries = Itinerary::whereIn('group_id', $groupIds)->get();
            $tripSchedule = [];
            $tripName = [];
            $routeUrls = [];
            foreach ($itineraries as $itinerary) {
                $start_date = new \DateTime($itinerary->start_date);
                $end_date = new \DateTime($itinerary->end_date);
                $tripSchedule[] = [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')];
                $tripName[] = $itinerary->title;
                $routeUrls[] = route('itinerary.show', $itinerary->id);
            }
            $view->with('groupIds', $groupIds)
                ->with('tripSchedule', $tripSchedule)
                ->with('tripName', $tripName)
                ->with('routeUrls', $routeUrls);
        }
    });
    }
}
