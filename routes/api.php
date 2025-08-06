<?php
 //routes/api.php
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BelongingController;

 //// Laravel Echo + Pusher の認証用エンドポイント
Broadcast::routes(['middleware' => ['auth']]);

Route::middleware('auth:sanctum')->get('/itineraries/{itinerary}/belongings-with-users', [BelongingController::class, 'withUsers']);
