<div x-show="showModal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    x-cloak
    @click.away="showModal = false">
    <div class="bg-white p-6 rounded-2xl shadow-2xl max-w-4xl">
        <h2 class="text-xl font-bold mb-4">Delete Post</h2>
        <hr>
        <p class="mb-4">Are you sure you want to delete this post?</p>
        <div class="flex justify-center">
            <img src="{{ $post->image }}" alt="{{ $post->title }}" class="image-lg">
            <p class="ml-3mt-1 font-semibold">{{ $post->title }}</p>
        </div>
        <div class="flex justify-end space-x-3">
            <button @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <form method="POST" action="{{ route('post.delete', $post->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
            </form>
        </div>
    </div>
</div>
