<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItineraryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/post_list', function () {
    return view('posts.list');
})->name('posts.list');

Route::get('/post_show', function () {
    return view('posts.show');
})->name('posts.show');

Route::get('/post_edit', function () {
    return view('posts.edit');
})->name('posts.edit');

Route::get('/post_create', function () {
    return view('posts.create');
})->name('posts.create');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    #post




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
