<?php
namespace App\Providers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
                $user = Auth::user();
                $userId = $user->id;
                $cacheKeyPrefix = 'shared_data_user_' . $userId;
                $tripSchedule = [];
                $tripName = [];
                $tripId = [];
                $routeUrls = [];
                // グループ情報
                $groupIds = Cache::remember("$cacheKeyPrefix:group_ids", 0, function () use ($userId) {
                    return GroupMember::where('user_id', $userId)->pluck('group_id')->toArray();
                });

                $groups = Cache::remember("$cacheKeyPrefix:groups", 1, fn () =>
                    Group::whereIn('id', $groupIds)->get()
                );

                // 旅程情報
                $itineraries = Cache::remember("$cacheKeyPrefix:itineraries", 1, fn () =>
                    Itinerary::whereIn('group_id', $groupIds)->orderBy('start_date')->get()
                );

                foreach ($itineraries as $itinerary) {
                    $start_date = new \DateTime($itinerary->start_date);
                    $end_date = new \DateTime($itinerary->end_date);
                    $tripSchedule[] = [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')];
                    $tripName[] = $itinerary->title;
                    $tripId[] = $itinerary->id;
                    $routeUrls[] = route('itinerary.show', $itinerary->id);
                }

                // 通知数（リアルタイム取得推奨）
                $nonReadCount = [];
                $nonReadCount_total = 0;
                foreach ($groupIds as $groupId) {
                    $messageIds = Message::where('group_id', $groupId)->pluck('id');
                    $count = ReadMessage::whereIn('message_id', $messageIds)
                        ->where('user_id', $userId)
                        ->whereNull('read_at')
                        ->count();
                    $nonReadCount[$groupId] = $count;
                    $nonReadCount_total += $count;
                }

                // 今日の予定
                $today = Carbon::today()->toDateString();
                $todayItineraries = Itinerary::whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today)
                    ->where(function ($query) use ($user, $groupIds) {
                        $query->where('created_by', $user->id)
                            ->orWhereIn('group_id', $groupIds);
                    })->get();
                // 天気情報
                $countryId = session('weather_country_id');
                $country = Country::find($countryId) ?? Country::first();
                $weather = null;

                // 今日の予定
                $today = Carbon::today()->toDateString();
                $todayItineraries = Itinerary::with('group')  // ← 追加
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today)
                    ->where(function ($query) use ($user, $groupIds) {
                        $query->where('created_by', $user->id)
                            ->orWhereIn('group_id', $groupIds);
                    })->get();


                // 天気情報
                $countryId = request()->cookie('weather_country_id');
                $country = Country::find($countryId) ?? Country::first();
                $weather = null;

                if ($country) {
                    $weather = Cache::remember('weather_' . $country->city, now()->addHour(), function () use ($country) {
                        $apiKey = config('services.weatherapi.key');
                        $res = Http::get("https://api.weatherapi.com/v1/current.json", [
                            'key' => $apiKey,
                            'q' => $country->city,
                            'lang' => 'ja',
                        ]);

                        if ($res->ok()) {
                            $data = $res->json();
                            return [
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
                            return null;
                        }
                    });
                }

                // ビュー共有
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
                ->with('tripId', $tripId)
                ->with('routeUrls', $routeUrls)
                ->with('myCountries', Country::where('user_id', $userId)->get())
                ->with('tripId', $tripId);
            }
        });
    }
}
