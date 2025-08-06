<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Itinerary;

class ProfileController extends Controller
{

    private $user;
    private $post;
    private $group;

    public function __construct(User $user, Group $group, Post $post) {
        $this->user = $user;
        $this->post = $post;
        $this->group = $group;
    }

    public function set(): View
    {
        $user = $this->user->findOrFail(Auth::user()->id);
        return view('profile.create')
            ->with('user', $user);
    }

    public function users_index()
    {
        $all_users = $this->user->where('is_public', true)->paginate(6)->onEachSide(2);

        return view('profile.users_list')
            ->with('all_users', $all_users);
    }

    public function search(Request $request)
    {
        $request->validate([
            'user_name' => 'required|max:50',
        ]);

        $search = $request->user_name;
        $all_users = $this->user->where('is_public', true)->where('name', 'like', '%'. $request->user_name . '%')->paginate(6)->onEachSide(2);

        return view('profile.users_list_search')
            ->with('search', $search)
            ->with('all_users', $all_users);
    }

    public function create(Request $request) {

        $request->validate([
            'name'          => 'required|max:50',
            'email'         => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            'image'        => 'mimes:jpeg,jpg,png,gif|max:1048',
            'introduction'  => 'max:100',
            'is_public'     => 'required|boolean'
        ]);

        $user = $this->user->findOrFail(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->introduction = $request->introduction;
        $user->is_public = $request->is_public;

        if ($request->image) {
            $user->avatar = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }
        $user->save();

        return redirect()->route('profile.users.list');
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
            'introduction'  => 'max:100',
            'is_public'     => 'required|boolean'
        ]);

        $user = $this->user->findOrFail(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->introduction = $request->introduction;
        $user->is_public = $request->is_public;

        if ($request->image) {
            $user->avatar = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }
        $user->save();

        return redirect()->route('profile.show', $user->id);
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

        // グループ情報取得
        $group = $this->group->where('user_id', Auth::id())->where('name', $user->name)->first();
        if (!$group) {
            $group = $this->group->where('user_id', $user->id)->where('name', Auth::user()->name)->first();
        }

        // 投稿取得（自分ならすべて、それ以外はフィルタ）
        if (Auth::id() === $user->id) {
            $all_posts = $user->posts;
        } else {
            $all_posts = $user->posts->filter(function ($post) {
                return $post->isVisibleTo(Auth::user());
            });
        }

        return view('profile.show')
            ->with('user', $user)
            ->with('group', $group)
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
    $posts = $posts->sortBy(function ($post) use ($postIds) {
        return array_search($post->id, $postIds);
    })->values();
    $likeCounts = [];
    foreach ($posts as $post) {
        $likeCounts[] = $post->likes()->count();
    }

    return view('dashboard')
        ->with('posts', $posts)
        ->with('likeCounts', $likeCounts)
        ->with('top3', $top3);
    }

    public function settings(){
        return view('Settings');
    }

}
