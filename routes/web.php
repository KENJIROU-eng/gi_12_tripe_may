<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\BelongingController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\Controller;
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
    Route::delete('/itinerary/{id}/delete', [ItineraryController::class, 'destroy'])->name('itinerary.destroy');
    Route::get('/itinerary/share', [ItineraryController::class, 'shareSelect'])->name('itinerary.share');
    Route::get('/itinerary/prefill', [ItineraryController::class, 'prefill'])->name('itinerary.prefill');
    Route::get('/itineraries/{id}/show', [ItineraryController::class, 'show'])->name('itinerary.show');
    Route::get('/itineraries/{id}/edit', [ItineraryController::class, 'edit'])->name('itinerary.edit');
    Route::put('/itineraries/{id}/update', [ItineraryController::class, 'update'])->name('itinerary.update');

    #belonging
    Route::get('/belongings/{itinerary_id}', [BelongingController::class, 'index'])->name('belonging.index');
    Route::post('/belongings/store', [BelongingController::class, 'store'])->name('belonging.store');
    Route::put('/belongings/{belonging}', [BelongingController::class, 'update'])->name('belonging.update');
    Route::delete('/belongings/{belonging}', [BelongingController::class, 'destroy'])->name('belonging.destroy');


    #goDutch
    Route::get('/goDutch', [BillController::class, 'index'])->name('goDutch.index');
    Route::post('/goDutch/create', [BillController::class, 'store'])->name('goDutch.create');
    Route::delete('/goDutch/delete/{bill_id}', [BillController::class, 'destroy'])->name('goDutch.delete');
    Route::patch('/goDutch/update/{bill_id}', [BillController::class, 'update'])->name('goDutch.update');

});

require __DIR__.'/auth.php';
