<div x-show="showModal" x-transition.opacity
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    x-cloak
    @click.away="showModal = false">
    <div class="bg-white p-6 rounded-2xl shadow-2xl max-w-4xl">
        <h2 class="text-xl font-bold mb-4">Delete Post</h2>
        <div class="h-[2px] w-full bg-gradient-to-r from-red-700 via-red-500 to-red-400 my-4"></div>
        <p class="my-3">Are you sure you want to delete this post?</p>
        {{-- <div class="flex justify-center">
            <img src="{{ $post->image }}" alt="{{ $post->title }}" class="">
            <p class="ml-3mt-1 font-semibold">{{ $post->title }}</p>
        </div> --}}
        <div class="flex justify-center items-center flex-wrap gap-4 mb-4 ">
            <img src="{{ $post->image }}" alt="{{ $post->title }}"
                class="max-w-[120px] sm:max-w-[150px] md:max-w-[200px] lg:max-w-[240px] xl:max-w-[280px] h-auto shadow">
            <p class="font-semibold">{{ $post->title }}</p>
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
