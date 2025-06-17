<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\GroupMember;
use App\Models\Itinerary;

class ProfileController extends Controller
{

    private $user;
    private $post;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function set(): View
    {
        $user = $this->user->findOrFail(Auth::user()->id);
        return view('profile.create')
            ->with('user', $user);
    }

    public function create(Request $request) {

        $request->validate([
            'name'          => 'required|max:50',
            'email'         => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            'image'        => 'mimes:jpeg,jpg,png,gif|max:1048',
            'introduction'  => 'max:100'
        ]);

        $user = $this->user->findOrFail(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->introduction = $request->introduction;

        if ($request->image) {
            $user->avatar = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }
        $user->save();

        return redirect()->route('dashboard');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }


    /**
     * Update the user's profile information.
     */

    public function update(Request $request) {

        $request->validate([
            'name'          => 'required|max:50',
            'email'         => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            'image'        => 'mimes:jpeg,jpg,png,gif|max:1048',
            'introduction'  => 'max:100'
        ]);

        $user = $this->user->findOrFail(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->introduction = $request->introduction;

        if ($request->image) {
            $user->avatar = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }
        $user->save();

        return redirect()->route('profile.show');
    }
    // public function update(ProfileUpdateRequest $request): RedirectResponse
    // {
    //     $request->user()->fill($request->validated());

    //     if ($request->user()->isDirty('email')) {
    //         $request->user()->email_verified_at = null;
    //     }

    //     $request->user()->save();

    //     return Redirect::route('profile.edit')->with('status', 'profile-updated');
    // }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show($user_id) {
        $user = $this->user->findOrFail($user_id);
        $all_posts = $user->post->all();
        return view('profile.show')
            ->with('user', $user)
            ->with('all_posts', $all_posts);
    }

    public function index() {
        $top3 = DB::table('likes')
    ->select('post_id', DB::raw('count(*) as likes_count'))
    ->groupBy('post_id')
    ->orderByDesc('likes_count')
    ->limit(3)
    ->get();
    $postIds = $top3->pluck('post_id')->toArray();
    $posts = Post::whereIn('id', $postIds)->get();

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

    return view('dashboard')
        ->with('posts', $posts)
        ->with('tripSchedule', $tripSchedule)
        ->with('tripName', $tripName)
        ->with('routeUrls', $routeUrls)
        ->with('top3', $top3);
    }

}
