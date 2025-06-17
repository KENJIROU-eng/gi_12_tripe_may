<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewPostCreated;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    private $post;
    private $category;

    public function __construct(Post $post, Category $category) {
        $this->post = $post;
        $this->category = $category;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_posts = $this->post->paginate(6)->onEachSide(2);
        $all_categories = $this->category->all();
        return view('posts.list')
            ->with('all_posts', $all_posts)
            ->with('all_categories', $all_categories);
    }

    public function search(Request $request) {
        $category = $this->category->findOrFail($request->search);
        $all_posts_id = $category->categoryPost->pluck('post_id')->toArray();
        $all_posts = $this->post->whereIn('id', $all_posts_id)->paginate(6)->onEachSide(2);
        // $all_posts = $this->post->where('title', 'like', '%'. $request->search . '%')->paginate(6)->onEachSide(2)->appends(['search' => $request->search]);
        $all_categories = $this->category->all();
        return view('posts.list_search')
            ->with('all_posts', $all_posts)
            ->with('all_categories', $all_categories)
            ->with('search', $request->search);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $all_categories = $this->category->all();
        return view('posts.create')
            ->with('all_categories', $all_categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:1|max:50',
            'description' => 'required|min:1|max:1000',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:1048',
            'category_name' => 'required'
        ]);

        $this->post->user_id = Auth::User()->id;
        $this->post->title = $request->title;
        $this->post->description = $request->description;
        $this->post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        $this->post->save();

        $category_id = [];
        foreach ($request->category_name as $category_name) {
            $category_id[] = ['category_id' => $category_name];
        }
        $this->post->categoryPost()->createMany($category_id);

        $all_posts = $this->post->paginate(6)->onEachSide(2);
        return redirect()->route('post.list')
            ->with('all_posts', $all_posts);
    }

    /**
     * Display the specified resource.
     */
    public function show($post_id)
    {
        $post = $this->post->findOrFail($post_id);
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
        $categoryPost_id = [];

        foreach ($post->categoryPost as $categoryPost) {
            $categoryPost_id[] = $categoryPost->category_id;
        }
        return view('posts.edit')
            ->with('all_categories', $all_categories)
            ->with('categoryPost_id', $categoryPost_id)
            ->with('post', $post);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $post_id)
    {
        $request->validate([
            'title' => 'required|min:1|max:50',
            'description' => 'required|min:1|max:1000',
            'image' => 'mimes:jpeg,jpg,png,gif|max:1048',
            'category_name' => 'required'
        ]);

        $post = $this->post->findOrFail($post_id);
        $post->title = $request->title;
        $post->description = $request->description;
        if ($request->image) {
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        };
        $post->save();

        $category_id = [];
        foreach ($request->category_name as $category_name) {
            $category_id[] = ['category_id' => $category_name];
        }
        $post->categoryPost()->delete();
        $post->categoryPost()->createMany($category_id);

        return view('posts.show')
            ->with('post', $post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($post_id)
    {
        $post = $this->post->findOrFail($post_id);
        $post->delete();
        $all_categories = $this->category->all();

        $all_posts = $this->post->paginate(6)->onEachSide(2);
        return view('posts.list')
            ->with('all_posts', $all_posts)
            ->with('all_categories', $all_categories);
    }

    public function like($post_id) {
        $post = $this->post->findOrFail($post_id);
        $post->likes()->create([
            'user_id' => Auth::User()->id,
        ]);

        return view('posts.show')
            ->with('post', $post);
    }
    public function like_delete($post_id) {
        $post = $this->post->findOrFail($post_id);
        $like = $post->likes()->where('user_id', Auth::User()->id);
        $like->delete();

        return view('posts.show')
            ->with('post', $post);
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
}
