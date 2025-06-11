<?php
// APIルートは使用していませんが、ファイルだけ残しています。
 //routes/api.php
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

 //// Laravel Echo + Pusher の認証用エンドポイント
Broadcast::routes(['middleware' => ['auth']]);
