<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewPostCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    private $post;
    private $category;
    private $user;

    public function __construct(Post $post, Category $category, User $user) {
        $this->post = $post;
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_posts = $this->post->latest()->get()->filter(function ($post) {
            return $post->isVisibleTo(Auth::user());
        });
        $all_categories = $this->category->all();
        return view('posts.list')
            ->with('all_posts', $all_posts)
            ->with('all_categories', $all_categories);
    }

    public function search(Request $request) {
        $request->validate([
            'search' => 'required',
        ]);
        if ($request->search == '#') {
            $all_categories = $this->category->all();
            $all_posts = $this->post->all();

            return view('posts.list_search')
            ->with('all_posts', $all_posts)
            ->with('all_categories', $all_categories);

        }else {
            $category_search = $this->category->findOrFail($request->search);
            $all_posts_id = $category_search->categoryPost->pluck('post_id')->toArray();
            $all_posts = $this->post->whereIn('id', $all_posts_id)->latest()->get();
            // $all_posts = $this->post->where('title', 'like', '%'. $request->search . '%')->paginate(6)->onEachSide(2)->appends(['search' => $request->search]);
            $all_categories = $this->category->all();
            return view('posts.list_search')
                ->with('all_posts', $all_posts)
                ->with('all_categories', $all_categories)
                ->with('category_search', $category_search);
        };
    }

    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    $all_categories = $this->category->all();
    $all_users = User::where('id', '!=', Auth::id())->get();
    $user = Auth::user();
    $user_groups = $user->groups ?? collect();

    // 自分のItinerary
    $ownItineraries = \App\Models\Itinerary::where('created_by', $user->id)->get();

    // グループメンバーのuser_idを取得
    $groupMemberIds = $user->groups()
        ->with('members.user')
        ->get()
        ->flatMap(fn($group) => $group->members->pluck('user_id'))
        ->unique()
        ->reject(fn($id) => $id === $user->id) // 自分を除外する場合（含めるならこの行を削除）
        ->values();

    // グループメンバーのItinerary
    $groupItineraries = \App\Models\Itinerary::whereIn('created_by', $groupMemberIds)->get();

    // マージしてリレーションロード
    $user_itineraries = $ownItineraries->merge($groupItineraries)->unique('id')->load('dateItineraries.mapItineraries');

    return view('posts.create', compact('all_categories', 'all_users', 'user_groups', 'user_itineraries'));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:1|max:30',
            'description' => 'required|min:1|max:500',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:5120',
        ]);

        $this->post->user_id = Auth::id();
        $this->post->title = $request->title;
        $this->post->description = $request->description;
        $this->post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        $this->post->visibility = $request->visibility;
        $this->post->save();

        $user = Auth::user();

        if ($request->visibility === 'custom') {
            $this->post->visibleUsers()->sync($request->visible_users ?? []);
        }

        if ($request->visibility === 'groups') {
            $this->post->visibleGroups()->sync($request->visible_groups ?? []);
        }

        if ($request->visibility === 'followers_groups') {
            // フォロワーとグループメンバーを結合し、重複と本人を除外
            $followers = $user->followers()->get();
            $groupMembers = $user->groups()
                ->with('members.user')
                ->get()
                ->flatMap(fn($group) => $group->members->map(fn($member) => $member->user));

            $uniqueUserIds = $followers
                ->merge($groupMembers)
                ->whereNotNull('id')
                ->where('id', '!=', $user->id)
                ->unique('id')
                ->pluck('id');

            $groupIds = $user->groups->pluck('id');

            $this->post->visibleUsers()->sync($uniqueUserIds);
            $this->post->visibleGroups()->sync($groupIds);
        }

        if (!empty($request->category_name))  {
            $category_id = collect($request->category_name)
                ->map(fn($id) => ['category_id' => $id])
                ->all();
            $this->post->categoryPost()->createMany($category_id);
        }

        $post->itinerary_id = $request->itinerary_id ?: null;
        $post->save();

        if (!empty($request->map_itinerary_ids)) {
            $this->post->mapItineraries()->sync($request->map_itinerary_ids);
        }

        return redirect()->route('post.list');
    }

    /**
     * Display the specified resource.
     */
    public function show($post_id)
    {
        $post = $this->post->with([
            'itinerary',
            'mapItineraries'
        ])->findOrFail($post_id);

        return view('posts.show')
            ->with('post', $post);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($post_id)
    {
        $all_categories = $this->category->all();
        $post = $this->post->findOrFail($post_id);
        $categoryPost_id = $post->categoryPost->pluck('category_id')->toArray();
        $postOwner = $post->user;

        // フォロワー
        $followers = $postOwner->followers()->get();

        // グループメンバー（GroupMember→Userに変換）
        $groupMembers = $postOwner->groups()
            ->with('members.user')
            ->get()
            ->flatMap(fn($group) => $group->members->map(fn($member) => $member->user));

        // 投稿者以外のフォロワー・グループメンバー
        $filteredUsers = $followers
            ->merge($groupMembers)
            ->whereNotNull('id')
            ->where('id', '!=', $postOwner->id)
            ->unique('id')
            ->values();

        $user_groups = $postOwner->groups ?? collect();

        // 投稿者の Itinerary
        $ownItineraries = \App\Models\Itinerary::where('created_by', $postOwner->id)->get();

        // グループメンバーの user_id を取得
        $groupMemberIds = $postOwner->groups()
            ->with('members.user')
            ->get()
            ->flatMap(fn($group) => $group->members->pluck('user_id'))
            ->unique()
            ->reject(fn($id) => $id === $postOwner->id) // 投稿者自身を除外
            ->values();

        // グループメンバーの Itinerary
        $groupItineraries = \App\Models\Itinerary::whereIn('created_by', $groupMemberIds)->get();

        // 統合
        $user_itineraries = $ownItineraries->merge($groupItineraries)->unique('id')->load('dateItineraries.mapItineraries');

        // 選択された MapItinerary ID（中間テーブル）
        $selected_map_itinerary_ids = $post->mapItineraries->pluck('id')->toArray();

        return view('posts.edit')
            ->with('all_categories', $all_categories)
            ->with('categoryPost_id', $categoryPost_id)
            ->with('post', $post)
            ->with('filteredUsers', $filteredUsers)
            ->with('user_groups', $user_groups)
            ->with('user_itineraries', $user_itineraries)
            ->with('selected_map_itinerary_ids', $selected_map_itinerary_ids);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $post_id)
    {
        $request->validate([
            'title' => 'required|min:1|max:30',
            'description' => 'required|min:1|max:500',
            'image' => 'mimes:jpeg,jpg,png,gif|max:5120',
        ]);

        $post = $this->post->findOrFail($post_id);
        $post->title = $request->title;
        $post->description = $request->description;

        if ($request->image) {
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }

        $post->visibility = $request->visibility;
        $post->save();

        // 公開範囲ごとの処理
        if ($request->visibility === 'followers_groups') {
            $user = $post->user;

            // フォロワー（Userのコレクション）
            $followers = $user->followers()->get();

            // グループメンバー（GroupMember -> User）
            $groupMembers = $user->groups()
                ->with('members.user')
                ->get()
                ->flatMap(function ($group) {
                    return $group->members->map(function ($member) {
                        return $member->user;
                    });
                });

            // ユニークなユーザーID（投稿者除く）
            $uniqueUserIds = $followers
                ->merge($groupMembers)
                ->whereNotNull('id')
                ->where('id', '!=', $user->id)
                ->unique('id')
                ->pluck('id');

            $groupIds = $user->groups->pluck('id');

            $post->visibleUsers()->sync($uniqueUserIds);
            $post->visibleGroups()->sync($groupIds);
        } else {
            // 通常の個別選択
            $post->visibleUsers()->sync($request->visible_users ?? []);
            $post->visibleGroups()->sync($request->visible_groups ?? []);
        }

        // カテゴリ関連付け更新
        $post->categoryPost()->delete();

        if (!empty($request->category_name)) {
            $category_id = collect($request->category_name)
                ->map(fn($id) => ['category_id' => $id])
                ->all();
            $post->categoryPost()->createMany($category_id);
        }

        $post->itinerary_id = $request->itinerary_id ?: null;
        $post->save();

        if (!empty($request->map_itinerary_ids)) {
            $post->mapItineraries()->sync($request->map_itinerary_ids);
        } else {
            $post->mapItineraries()->detach();
        }


        return view('posts.show')->with('post', $post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($post_id)
    {
        $post = $this->post->findOrFail($post_id);
        $post->delete();

        return redirect()->route('post.list');

    }

    // 以降不要
    public function broadcastEvent(Request $request)
    {
        $post = $this->post->find($request->postId);
        event(new \App\Events\NewPostCreated($post));

        return response()->json(['success' => true, 'post_id' => $post->id]);
    }

    public function test()
    {
        return view('test');
    }

    public function broadcast(Request $request)
    {
        logger('リクエスト受信:', $request->all()); // デバッグログ出力

        // new Postに新データ（フォームデータ）を作成
        $post = Post::create([
            'title' => $request->title,
            'user_id' => Auth::User()->id,
            'description' => 'test',
            'image' => 'test',
        ]);
        // 作成したデータをリアルタイムイベントに
        // この「broadcast()」でイベントを発火して、
        // NewPostCreatedクラスに書かれた情報（どのチャンネルに送るか、どんなデータを送るか）を元に、リアルタイム通知が行われる、というイメージです

        Log::debug('NewPostCreated イベント発火: ', ['title' => $post->title]);

        // broadcast(new NewPostCreated($post));
        event(new \App\Events\NewPostCreated($post));

        return response()->json(['success' => true, 'post_id' => $post->id]);
    }

    public function searchByLocation(Request $request)
    {

        \Log::info('Search Params:', [
            'lat' => $request->latitude,
            'lng' => $request->longitude,
            'radius' => $request->radius,
            'address' => $request->address,
        ]);
        
        $lat = $request->latitude;
        $lng = $request->longitude;
        $radius = $request->radius ?? 20;
        $address = $request->address;
        $all_categories = $this->category->all();

        // Post に関連する MapItinerary の場所から取得
        $posts = Post::whereHas('itinerary.dateItineraries.mapItineraries', function ($query) use ($lat, $lng, $radius) {
            $query->whereRaw("
                6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                ) < ?
            ", [$lat, $lng, $lat, $radius]);
        })->get();

        return view('posts.search_location_results', [
            'posts' => $posts,
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radius,
            'address' => $address,
            'all_categories' => $all_categories,
        ]);
    }

}
