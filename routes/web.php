<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\GroupController;


use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    #post
    Route::get('/post_list', [PostController::class, 'list'])->name('posts.list');
    Route::get('/post_show', [PostController::class, 'show'])->name('posts.show');
    Route::get('/post_edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::get('/post_create', [PostController::class, 'create'])->name('post.create');

    #chat
    Route::post('/chat/send', [GroupController::class, 'sendMessage'])->name('chat.send');

    #group
    Route::get('/group', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/group_create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/group/store', [GroupController::class, 'store'])->name('groups.store');





    #itinerary
    Route::get('/itinerary', [ItineraryController::class, 'index'])->name('itinerary.index');
    Route::get('/itinerary/create', [ItineraryController::class, 'create'])->name('itinerary.create');
    Route::post('/itinerary/store', [ItineraryController::class, 'store'])->name('itinerary.store');
    Route::delete('/itinerary/{itinerary_id}/delete', [ItineraryController::class, 'destroy'])->name('itinerary.destroy');
    Route::get('/itinerary/share', [ItineraryController::class, 'shareSelect'])->name('itinerary.share');
    Route::get('/itinerary/prefill', [ItineraryController::class, 'prefill'])->name('itinerary.prefill');
    Route::get('/itineraries/{id}/show', [ItineraryController::class, 'show'])->name('itinerary.show');
    Route::get('/itineraries/{id}/edit', [ItineraryController::class, 'edit'])->name('itinerary.edit');




});

require __DIR__.'/auth.php';
