<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class GroupController extends Controller
{


    public function sendMessage(Request $request){

        $request->validate([
            'message' => 'nullable|string',
            'image' =>'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $imageUrl = null;

        if($request->hasFile('image')){
            $path = $request->file('image')->store('chat_image','public');
            $imageUrl = asset('storage/' . $path);
        }

        broadcast(new MessageSent($user,$request->message, $imageUrl))->toOthers();

        return response()->json(['status' => 'Message sent!']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        //ログインユーザーが所属しているグループを取得
        $groups = Group::whereHas('members',function($quely) use ($user){
            $quely->where('user_id',$user->id);
        })->withCount('members')->get();

        return view('groups.list', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('groups.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:225',
            'image' =>'nullable|image|max:2048',
            'members' =>'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        //画像の保存
        $imagePath = null;
        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('group_images','public');
        }

        //グループ作成
        $group = Group::create([
            'name' => $request->name,
            'image' => $imagePath,
            'user_id' => Auth::id(),
        ]);

        $group->members()->create([
            'user_id' => auth()->id(),
        ]);

        if($request->filled('members')){
            foreach ($request->members as $memberId){
                if($memberId != auth()->id()){
                    $group->members()->create([
                        'user_id' => $memberId,
                    ]);
                }
            }
        }

        return redirect()->route('groups.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        //
    }
}
