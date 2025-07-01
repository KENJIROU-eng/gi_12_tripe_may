<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ReadMessage;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TrackTransitionChatroom
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentPath = '/' . ltrim(request()->path(), '/');
        $previousPath = '/' . ltrim(session()->get('previous_path', '/'), '/');

        if (preg_match('#^/chat/(\d+)$#', $currentPath, $matches) && !preg_match('#^/chat/(\d+)$#', $previousPath)) {
                $groupId = $matches[1];
                $messageIds = Message::where('group_id', $groupId)->pluck('id')->toArray();
                ReadMessage::whereIn('message_id', $messageIds)->where('user_id', Auth::User()->id)->whereNull('read_at')->update(['read_at' => now()]);
        }
        
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
