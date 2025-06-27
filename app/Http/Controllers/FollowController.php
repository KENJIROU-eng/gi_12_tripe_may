<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    private $user;
    private $follow;
    private $group;

    public function __construct(User $user, Follow $follow, Group $group) {
        $this->user = $user;
        $this->follow = $follow;
        $this->group = $group;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($following_id)
    {
        $this->follow->following_id = $following_id;
        $this->follow->follower_id = Auth::User()->id;
        $this->follow->save();

        $user = $this->user->findOrFail($following_id);
        $group = $this->group->where('user_id', $following_id)->where('name', Auth::User()->name)->first();
            if (!$group) {
                $group = new Group();
                $group->user_id = Auth::User()->id;
                $group->name = $user->name;
                $group->save();
                $group->members()->create([
                    'user_id' => Auth::User()->id,
                ]);
                $group->members()->create([
                    'user_id' => $user->id
                ]);
            };

        return redirect()->route('profile.show', $following_id)
            ->with('group', $group);
    }

    public function create_usersPage($following_id)
    {
        $this->follow->following_id = $following_id;
        $this->follow->follower_id = Auth::User()->id;
        $this->follow->save();

        $user = $this->user->findOrFail($following_id);
        $group = $this->group->where('user_id', $following_id)->where('name', Auth::User()->name)->first();
            if (!$group) {
                $group = new Group();
                $group->user_id = Auth::User()->id;
                $group->name = $user->name;
                $group->save();
                $group->members()->create([
                    'user_id' => Auth::User()->id,
                ]);
                $group->members()->create([
                    'user_id' => $user->id
                ]);
            };

        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Follow $follow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Follow $follow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Follow $follow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($following_id)
    {
        $follow = $this->follow->where('following_id', $following_id)->where('follower_id', Auth::User()->id);
        $follow->delete();

        $user = $this->user->findOrFail($following_id);
        $group = $this->group->where('user_id', Auth::User()->id)->where('name', $user->name)->delete();

        // $user = $this->user->findOrFail($following_id);
        // $all_posts = $user->post()->paginate(6)->onEachSide(2);
        // return view('profile.show')
        //     ->with('user', $user)
        //     ->with('all_posts', $all_posts);
        return redirect()->route('profile.show', $following_id);
    }

    public function destroy_usersPage($following_id)
    {
        $follow = $this->follow->where('following_id', $following_id)->where('follower_id', Auth::User()->id);
        $follow->delete();

        $user = $this->user->findOrFail($following_id);
        $group = $this->group->where('user_id', Auth::User()->id)->where('name', $user->name)->delete();

        // $user = $this->user->findOrFail($following_id);
        // $all_posts = $user->post()->paginate(6)->onEachSide(2);
        // return view('profile.show')
        //     ->with('user', $user)
        //     ->with('all_posts', $all_posts);
        return redirect()->back();
    }

    public function follower_show(User $user) {
        $followers = $user->followers;

        return view('follows.follower')
            ->with('user', $user)
            ->with('followers', $followers);
    }

    public function following_show(User $user) {
        $followings = $user->following;

        return view('follows.following')
            ->with('user', $user)
            ->with('followings', $followings);
    }
}
