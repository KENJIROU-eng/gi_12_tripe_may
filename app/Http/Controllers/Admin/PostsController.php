<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostsController extends Controller
{

    private $post;

    public function __construct(Post $post) {
        $this->post = $post;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_posts = $this->post->paginate(7)->onEachSide(2);

        return view('admin.posts.show')
            ->with('all_posts', $all_posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($post_id)
    {
        $post = $this->post->findOrFail($post_id);
        $post->delete();

        $all_posts = $this->post->paginate(7)->onEachSide(2);

        return view('admin.posts.show')
            ->with('all_posts', $all_posts);
    }
}
