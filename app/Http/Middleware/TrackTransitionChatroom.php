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

        return $next($request);
    }
}
