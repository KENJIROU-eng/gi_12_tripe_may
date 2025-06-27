<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use App\Models\Message;
use App\Models\ReadMessage;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;




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
            'edit_message_id' => 'nullable|exists:messages,id',
        ]);

        // 編集の場合
        if ($request->filled('edit_message_id')) {
            $message = Message::where('id', $request->edit_message_id)
                ->where('user_id', auth()->id()) // 自分のメッセージだけ
                ->firstOrFail();

            $message->message = $request->message;
            $message->save();

            return redirect()->back()->with('status', 'メッセージを更新しました');
        }

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

        $group = Group::findOrFail($request->group_id);
        $groupMembers = $group->members;

        foreach ($groupMembers as $member) {
        ReadMessage::create([
            'user_id' => $member->user->id,
            'message_id' => $message->id,
        ]);
        }


        broadcast(new MessageSent($message));

        return response()->json(['success' => true, 'image_url' => $message->image_url ?? '',]); //成功したらJSONレスポンスを返す
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

        $groups = Group::all();
        $groupKey = [];
        foreach ($group->users as $user) {
            $group_private = $groups->first(function ($group_single) use ($user) {
                return (
                    ($group_single->user_id === Auth::id() && $group_single->name === $user->name) ||
                    ($group_single->user_id === $user->id && $group_single->name === Auth::user()->name)
                );
            });
            if ($group_private) {
                $groupKey[$user->id] = $group_private ? $group_private->id : null;
            };
        };

        // broadcast(new MessageSent($user, $request->message, $imageUrl, $request->group_id))->toOthers();

            return view('groups.show',compact('group','messages', 'groupKey'));
    }

    public function editMessage(Message $message){

        $this->authorize('update', $message);
        return view('groups.chats.edit', compact('message'));
    }

    public function destroyMessage(Message $message){

        $this->authorize('delete', $message);
        $message->delete();

        return response()->json(['success' => true]);
    }

    public function updateMessage(Request $request, Message $message){

    $this->authorize('update', $message);

    $request->validate([
        'message' => 'required|string',
    ]);

    $message->update([
        'message' => $request->input('message'),
    ]);

    return response()->json([ 'status' => 'updated', 'message' => $message->message,]);

}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $groupIds = GroupMember::where('user_id', $user->id)
            ->pluck('group_id')
            ->toArray();

        // group_idごとに最新のメッセージだけ取得
        $latestMessages = Message::whereIn('group_id', $groupIds)
            ->with('group')
            ->latest()
            ->get()
            ->unique('group_id') // 各グループで1つだけ（最新）
            ->values(); // インデックス整理

        // 各メッセージに紐づくグループを取り出す（順番はメッセージ準拠）
        $groups = $latestMessages->map(fn($message) => $message->group);

        //ログインユーザーが所属しているグループを取得
        $groups_ini = Group::whereHas('members',function($quely) use ($user){
            $quely->where('user_id',$user->id);
        })->withCount('members')->get();

        $groups_filtered = $groups_ini->filter(function($group) use ($groups) {
            return !$groups->contains('id', $group->id);
        });

        $users = User::all();

        $nonReadCount = [];
        foreach ($groups as $group) {
            $messages = $group->messages->pluck('id')->toArray();
            $readMessages = ReadMessage::whereIn('message_id', $messages)->get();
            $nonReadCount[$group->id] = $readMessages->where('user_id', Auth::User()->id)->whereNull('read_at')->count();
        }

        return view('groups.list', compact('groups','users', 'nonReadCount', 'latestMessages', 'groups_filtered'));
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
    public function edit(Request $request,Group $group)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $group->name = $validated['name'];

        if ($request->hasFile('image')) {
            if ($group->image) {
                Storage::disk('public')->delete($group->image);
            }

            $path = $request->file('image')->store('group_images', 'public');
            $group->image = $path;
        }

        $group->save();

        // メンバー更新（同期）
        if (isset($validated['members'])) {
            $group->users()->sync($validated['members']);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($group_id)
    {
        $group = Group::findOrFail($group_id);
        if ($group->image) {
                Storage::disk('public')->delete($group->image);
            }
        $group->delete();

    return redirect()->route('groups.index');

    }
}
