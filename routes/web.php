<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\BelongingController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\PayPalController;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [ProfileController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    #profile
    Route::get('/profile/set', [ProfileController::class, 'set'])->name('profile.set');
    Route::get('/profile/users/list', [ProfileController::class, 'users_index'])->name('profile.users.list');
    Route::get('/profile/users/search', [ProfileController::class, 'search'])->name('profile.users.search');
    Route::patch('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
    Route::get('/profile/{user_id}/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');

    #follow
    Route::post('/follow/{following_id}/create', [FollowController::class, 'create'])->name('follow.create');
    Route::post('/profile/follow/{following_id}/create', [FollowController::class, 'create_usersPage'])->name('profile.follow.create');
    Route::delete('/follow/{following_id}/delete', [FollowController::class, 'destroy'])->name('follow.delete');
    Route::delete('/profile/follow/{following_id}/delete', [FollowController::class, 'destroy_usersPage'])->name('profile.follow.delete');
    Route::get('/follower/{user}/show', [FollowController::class, 'follower_show'])->name('follower.show');
    Route::get('/following/{user}/show', [FollowController::class, 'following_show'])->name('following.show');

    #post
    Route::get('/post/list', [PostController::class, 'index'])->name('post.list');
    Route::get('/post/{post_id}/show', [PostController::class, 'show'])->name('post.show');
    Route::get('/post/{post_id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post/store', [PostController::class, 'store'])->name('post.store');
    Route::post('/post/{post_id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::get('/post/{post_id}/like', [PostController::class, 'like'])->name('post.like');
    Route::delete('/post/{post_id}/like/delete', [PostController::class, 'like_delete'])->name('post.like.delete');
    Route::patch('/post/{post_id}/update', [PostController::class, 'update'])->name('post.update');
    Route::get('/post/search', [PostController::class, 'search'])->name('post.search');
    Route::delete('/post/{post_id}/delete', [PostController::class, 'destroy'])->name('post.delete');
    // Route::get('/post/test', [PostController::class, 'test']);
    // Route::post('/post/broadcast/event', [PostController::class, 'broadcastEvent']);
    // Route::post('/post/broadcast/realtime', [PostController::class, 'broadcast']);

    #comment
    Route::post('/comment/{post_id}/store', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('/comment/{comment_id}/delete', [CommentController::class, 'destroy'])->name('comment.delete');

    #chat
    Route::post('/chat/send', [GroupController::class, 'sendMessage'])->name('message.send');
    Route::get('/chat/{group}', [GroupController::class, 'showMessage'])->name('message.show');
    Route::get('/chat/{message}/edit', [GroupController::class, 'editMessage'])->name('message.edit');
    Route::delete('/chat/{message}/delete', [GroupController::class, 'destroyMessage'])->name('message.destroy');
    Route::patch('/chat/{message}', [GroupController::class, 'updateMessage'])->name('message.update');

    #group
    Route::get('/group', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/group_create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/group/store', [GroupController::class, 'store'])->name('groups.store');
    Route::delete('/group/{group_id}/delete', [GroupController::class, 'destroy'])->name('groups.delete');
    Route::patch('/group/{group}/update', [GroupController::class, 'update'])->name('groups.update');

    #itinerary
    Route::get('/itinerary', [ItineraryController::class, 'index'])->name('itinerary.index');
    Route::get('/itinerary/create', [ItineraryController::class, 'create'])->name('itinerary.create');
    Route::post('/itinerary/store', [ItineraryController::class, 'store'])->name('itinerary.store');
    Route::delete('/itinerary/{id}/delete', [ItineraryController::class, 'destroy'])->name('itinerary.destroy');
    Route::get('/itinerary/share', [ItineraryController::class, 'shareSelect'])->name('itinerary.share');
    Route::get('/itinerary/prefill', [ItineraryController::class, 'prefill'])->name('itinerary.prefill');
    Route::get('/itineraries/{id}/show', [ItineraryController::class, 'show'])->name('itinerary.show');
    Route::get('/itineraries/{id}/edit', [ItineraryController::class, 'edit'])->name('itinerary.edit');
    Route::put('/itineraries/{id}/update', [ItineraryController::class, 'update'])->name('itinerary.update');
    Route::get('/itinerary/load', [ItineraryController::class, 'loadMore'])->name('itinerary.load');

    #belonging
    Route::get('/belonging/check-duplicate', [BelongingController::class, 'checkDuplicate'])->name('belonging.checkDuplicate');
    Route::patch('/belonging/{belonging}/add-members', [BelongingController::class, 'addMembers']);
    Route::post('/belonging/{itinerary_id}/store', [BelongingController::class, 'store'])->name('belonging.store');
    Route::patch('/belonging/{belonging_id}/user/{user}', [BelongingController::class, 'updateCheck']);
    Route::patch('/belonging/{belonging_id}/update', [BelongingController::class, 'update'])->name('belonging.update');
    Route::delete('/belonging/{belonging_id}/destroy', [BelongingController::class, 'destroy'])->name('belonging.destroy');
    Route::get('/belonging/{belonging_id}', [BelongingController::class, 'index'])->name('belonging.index');


    #goDutch
    Route::get('/{itinerary_id}/goDutch', [BillController::class, 'index'])->name('goDutch.index');
    Route::post('/goDutch/{itinerary_id}/create', [BillController::class, 'store'])->name('goDutch.create');
    Route::delete('/goDutch/delete/{bill_id}/{itinerary_id}', [BillController::class, 'destroy'])->name('goDutch.delete');
    Route::patch('/goDutch/update/{bill_id}/{itinerary_id}', [BillController::class, 'update'])->name('goDutch.update');
    Route::get('/goDutch/{itinerary_id}/finalize', [BillController::class, 'finalize'])->name('goDutch.finalize');

    #paypal
    Route::get('/paypal/{itinerary_id}/{total}/pay', [PayPalController::class, 'createTransaction'])->name('paypal.pay');
    Route::get('/paypal/{itinerary_id}/success', [PayPalController::class, 'captureTransaction'])->name('paypal.success');

    #ADMIN Routes
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function(){
        # Users
        Route::get('/users/show', [UsersController::class, 'index'])->name('users.show');
        Route::delete('/users/{user_id}/delete', [UsersController::class, 'destroy'])->name('users.delete');

        # Posts
        Route::get('/posts/show', [PostsController::class, 'index'])->name('posts.show');
        Route::delete('/posts/{id}/delete', [PostsController::class, 'destroy'])->name('posts.delete');

        # CATEGORIES
        Route::get('/categories/show', [CategoriesController::class, 'index'])->name('categories.show');
        Route::post('/categories/store', [CategoriesController::class, 'store'])->name('categories.store');
        Route::delete('/categories/{id}/delete', [CategoriesController::class, 'destroy'])->name('categories.delete');
    });

});

require __DIR__.'/auth.php';
