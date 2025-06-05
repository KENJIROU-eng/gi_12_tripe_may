<div class="relative flex justify-center items-center mb-4">
    <h2 class="text-xl font-bold">Belonging List</h2>
    <button id="toggleVisibility" class="absolute right-6 text-2xl" type="button">
        <i class="fa-solid fa-eye"></i>
    </button>
</div>


<div class="flex space-x-2 p-2">
    <input type="text" id="itemInput" placeholder="Add item..." class="flex-grow border px-2 py-1 rounded" />
    <button id="addItemBtn" class="bg-green-500 text-white px-4 py-1 rounded" type="button">Add</button>
</div>

<ul id="itemList" class="space-y-1 mb-4 max-h-72 overflow-y-auto overflow-x-hidden">
    @foreach ($all_belongings as $belonging)
    <li class="flex items-between gap-2 p-1 border rounded" data-id="{{ $belonging->id }}">
        <div class="flex-shrink-0">
            <input type="checkbox" class="item-checkbox" data-id="{{ $belonging->id }}" {{ $belonging->checked ? 'checked' : '' }}>
        </div>
        <div class="flex-grow text-center">
            <span class="item-name {{ $belonging->checked ? 'text-gray-400 line-through' : '' }}">
                {{ $belonging->name }}
            </span>
        </div>
        <div class="flex-shrink-0 flex gap-2">
            <button type="button" class="edit-btn" data-id="{{ $belonging->id }}">
                <i class="fa-solid fa-pen text-yellow-300"></i>
            </button>
            <button type="button" class="delete-btn" data-id="{{ $belonging->id }}">
                <i class="fa-solid fa-trash-can text-red-500"></i>
            </button>
        </div>
    </li>
    @endforeach
</ul>


@push('scripts')
    <script>
        const itineraryId = @json($itinerary->id);
    </script>
    <script src="{{ asset('js/itineraries/belonging.js') }}"></script>
@endpush
