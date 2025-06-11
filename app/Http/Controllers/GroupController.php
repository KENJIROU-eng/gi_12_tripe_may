<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;




class GroupController extends Controller
{

    use AuthorizesRequests;
    //メッセージを送信
    //MessageSentイベントをリアルタイムで全クライアント(js)に送信
    public function sendMessage(Request $request){

        $request->validate([
            'message' => 'nullable|string',
            'image' =>'nullable|image|max:2048',
            'group_id' => 'required|exists:groups,id',
        ]);

        $user = auth()->user();
        $imageUrl = null;
        $imagePath = null;

        if($request->hasFile('image')){
            $path = $request->file('image')->store('chat_image','public');
            $imageUrl = asset('storage/' . $path);
        }

        $message = Message::create([
            'user_id' => $user->id,
            'group_id' => $request->group_id,
            'message' => $request->message,
            'image_url' => $imageUrl,
        ]);

        broadcast(new MessageSent($message));

        return response()->json(['success' => true]); //成功したらJSONレスポンスを返す
        //return back();
        //return response()->json(['status' => 'Message sent!']);
    }

    //メッセージを表示
    public function showMessage(Group $group){

        $this->authorize('view',$group);

        $messages = Message::where('group_id',$group->id)
            ->with('user')
            ->orderBy('created_at')
            ->get();

        // broadcast(new MessageSent($user, $request->message, $imageUrl, $request->group_id))->toOthers();

            return view('groups.show',compact('group','messages'));
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

    }
}
