@props(['itinerary'])

<div x-data="{ open: false}">
    <button @click="open = true">
        <i class="fa-solid fa-trash-can text-red-500 flex"></i>
    </button>

    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.outside="open = false" class="bg-white rounded-lg w-full max-w-md overflow-hidden">
            {{-- header --}}
            <div class="bg-red-500 text-white px-6 py-4">
                <h1 class="text-3xl font-bold">Delete Itinerary</h1>
            </div>
            {{-- body --}}
            <div class="px-6 py-4">
                <p><i class="fa-solid fa-triangle-exclamation text-red-500 text-4xl mb-2"></i></p>
                <p class="mb-2">Are you sure you want to delete this itinerary?</p>
                <p class="text-sm text-gray-500 mb-2">Created by: {{ $itinerary->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $itinerary->start_date }} ~ {{ $itinerary->end_date }}</p>
                <p class="text-sm text-gray-500">{{ $itinerary->title }}</p>
            </div>

            {{-- footer --}}
            <div class="mt-4 px-6 py-3 flex justify-end gap-2">
                <button @click="open = false" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                <form action="{{ route('itinerary.destroy', $itinerary->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
