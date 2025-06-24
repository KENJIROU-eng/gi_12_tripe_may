@props(['itinerary'])

<div x-data="{ open: false }">
    <button @click="open = true" title="Delete"">
        <i class="fa-solid fa-trash-can text-lg text-red-500 flex"></i>
    </button>

    <div x-show="open" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.outside="open = false" class="bg-white rounded-lg w-full max-w-md overflow-hidden">
            {{-- header --}}
            <div class="bg-red-500 text-white px-6 py-4">
                <h1 class="text-3xl font-bold">Delete Itinerary</h1>
            </div>

            {{-- body --}}
            <div class="px-6 py-4">
                <p class="text-center">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 text-4xl mb-2"></i>
                </p>
                <p class="mb-2 text-center">Are you sure you want to delete this itinerary?</p>

                <div class="text-sm text-gray-500 space-y-1">
                    <div class="flex items-start">
                        <span class="min-w-[100px] font-medium">Created by :</span>
                        <span>{{ $itinerary->user->name }}</span>
                    </div>
                    <div class="flex items-start">
                        <span class="min-w-[100px] font-medium">Title :</span>
                        <span class="break-words whitespace-pre-wrap text-left max-w-[300px]">{{ $itinerary->title }}</span>

                    </div>
                    <div class="flex items-start">
                        <span class="min-w-[100px] font-medium">Date :</span>
                        <span>
                            {{ \Carbon\Carbon::parse($itinerary->start_date)->format('Y-m-d') }} ~
                            {{ \Carbon\Carbon::parse($itinerary->end_date)->format('Y-m-d') }}
                        </span>
                    </div>
                </div>
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
