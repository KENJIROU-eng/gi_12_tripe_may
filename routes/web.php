<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\Controller;
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

});

Route::group(['middleware' => 'auth'], function(){

    #home

    #itinerary
    Route::get('/itinerary', [ItineraryController::class, 'index'])->name('itinerary.index');
    Route::get('/itinerary/create', [ItineraryController::class, 'create'])->name('itinerary.create');
    Route::post('/itinerary/store', [ItineraryController::class, 'store'])->name('itinerary.store');

    Route::get('/itinerary/{id}/show', [ItineraryController::class, 'show'])->name('itinerary.show');
    Route::delete('/itinerary/{id}/delete', [ItineraryController::class, 'destroy'])->name('itinerary.destroy');

    #goDutch
    Route::get('/goDutch', [BillController::class, 'index'])->name('goDutch.index');
    Route::post('/goDutch/create', [BillController::class, 'store'])->name('goDutch.create');
    Route::delete('/goDutch/delete/{bill_id}', [BillController::class, 'destroy'])->name('goDutch.delete');
    Route::patch('/goDutch/update/{bill_id}', [BillController::class, 'update'])->name('goDutch.update');

});
require __DIR__.'/auth.php';
