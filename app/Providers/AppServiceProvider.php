<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\GroupMember;
use App\Models\Itinerary;
use App\Models\Message;
use App\Models\Group;
use App\Models\ReadMessage;

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
            $nonReadCount = [];
            $nonReadCount_total = 0;
            foreach ($groupIds as $groupId) {
                $messageIds = Message::where('group_id', $groupId)->pluck('id')->toArray();
                $readmessages = ReadMessage::whereIn('message_id', $messageIds)->get();
                $nonReadCount[$groupId] = $readmessages->where('user_id', Auth::User()->id)->whereNull('read_at')->count();
                $nonReadCount_total = $nonReadCount_total + $readmessages->where('user_id', Auth::User()->id)->whereNull('read_at')->count();
            }
            $view->with('groupIds', $groupIds)
                ->with('groups', $groups)
                ->with('nonReadCount', $nonReadCount)
                ->with('nonReadCount_total', $nonReadCount_total)
                ->with('tripSchedule', $tripSchedule)
                ->with('tripName', $tripName)
                ->with('routeUrls', $routeUrls);
        }
    });
    }
}
