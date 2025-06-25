<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ReadMessage;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class TrackTransitionChatroom
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $previousPath = parse_url(url()->previous(), PHP_URL_PATH);
        $currentPath = $request->path();

        // 前ページが chat/で、今のページが chat/ 以外なら処理する
        if (preg_match('#^/chat/(\d+)$#', $previousPath, $matches) && !preg_match('#^/chat/(\d+)$#', $currentPath)) {
            if (!preg_match('#^/logout#', $currentPath) && $currentPath !== '/') {
                $groupId = $matches[1];
                $group = Group::findOrFail($groupId);
                $messages = $group->messages->pluck('id')->toArray();
                $readMessages = ReadMessage::whereIn('message_id', $messages)->where('user_id', Auth::User()->id)->whereNull('read_at')->update(['read_at' => now()]);
            }
        }

        return $next($request);
    }
}
