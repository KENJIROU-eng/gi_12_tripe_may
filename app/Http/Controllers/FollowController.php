<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    private $user;
    private $follow;

    public function __construct(User $user, Follow $follow) {
        $this->user = $user;
        $this->follow = $follow;
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
        $all_posts = $user->post()->paginate(6)->onEachSide(2);
        return view('profile.show')
            ->with('user', $user)
            ->with('all_posts', $all_posts);
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
        $all_posts = $user->post()->paginate(6)->onEachSide(2);
        return view('profile.show')
            ->with('user', $user)
            ->with('all_posts', $all_posts);
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
            ->with('followings', $followings);
    }
}
