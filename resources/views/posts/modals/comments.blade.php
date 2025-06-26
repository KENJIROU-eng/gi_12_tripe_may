<div x-show="open" class="fixed inset-0 bg-gray-500/75 z-10 flex items-center justify-center w-full">
    <div class="bg-white p-6 rounded shadow max-w-md w-full max-h-[90vh] flex flex-col overflow-hidden">
        {{-- header --}}
        <div class="flex px-6 py-4 justify-between items-center">
            <h1 class="text-2xl font-bold">Comments</h1>
            <a href="{{ route('post.show', $post->id) }}">
                <i class="fa-solid fa-xmark text-red-500 text-2xl"></i>
            </a>
        </div>

        <hr class="border-green-500 border-1 mb-2">

        {{-- body全体 --}}
        <div class="flex-1 overflow-y-auto">
            {{-- コメントフォーム --}}
            <form action="{{ route('comment.store', $post->id) }}" method="POST" class="mb-4">
                @csrf
                <div class="flex items-start gap-2">
                    <textarea
                        name="comment_body{{ $post->id }}"
                        rows="2"
                        class="w-full p-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                        placeholder="Add a comment..."
                    >{{ old('comment_body' . $post->id) }}</textarea>

                    <button
                        type="submit"
                        class="shrink-0 px-3 py-2 text-sm text-white bg-blue-500 hover:bg-blue-600 rounded-md transition"
                        title="Post"
                    >
                        <i class="fa-regular fa-paper-plane"></i>
                    </button>
                </div>

                @error('comment_body' . $post->id)
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </form>

            {{-- コメント一覧 --}}
            @if ($post->comments->isNotEmpty())
                <ul class="space-y-3">
                    @foreach ($post->comments as $comment)
                        <li class="bg-gray-100 dark:bg-gray-800 p-3 rounded-md shadow-sm text-sm break-words">
                            <div class="flex justify-between items-start flex-wrap">
                                <div class="min-w-0 w-full">
                                    <a href="{{ route('profile.show', $comment->user->id) }}"
                                        class="font-semibold text-blue-600 hover:underline break-all">
                                        {{ $comment->user->name }}
                                    </a>
                                    <span class="ml-2 text-gray-700 dark:text-gray-300 break-words">
                                        {{ $comment->body }}
                                    </span>
                                </div>

                                @if (Auth::id() === $comment->user->id)
                                    <form action="{{ route('comment.delete', $comment->id) }}" method="POST" class="ml-auto mt-1 shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-500 text-xs hover:underline"
                                                onclick="return confirm('Are you sure you want to delete this comment?')">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="text-xs text-gray-500 mt-1">
                                {{ $comment->created_at->format('M d, Y') }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
