<div class="relative flex justify-center items-center mb-4">
    <h2 class="text-xl font-bold">Belonging List</h2>
    <button id="toggleVisibility" class="absolute right-[24px] text-2xl" type="button">
        <i class="fa-solid fa-eye"></i>
    </button>
</div>
<div class="flex space-x-2 p-2">
    <input type="text" id="itemInput" placeholder="Add item..." class="flex-grow border px-2 py-1 rounded" />
    <button id="addItemBtn" class="bg-green-500 text-white px-2 py-1 rounded" type="button">Add</button>
</div>
<ul id="belongingList" class="space-y-1 mb-4 max-h-[140px] overflow-y-auto overflow-x-hidden">
    @foreach ($all_belongings as $belonging)
    <li class="flex justify-between gap-2 p-1 border rounded" data-id="{{ $belonging->id }}">
        <div class="flex-shrink-0">
            <input type="checkbox" class="item-checkbox" data-id="{{ $belonging->id }}" {{ $belonging->checked ? 'checked' : '' }}>
        </div>
        <div class="flex-grow text-start ms-2">
            <span class="item-name {{ $belonging->checked ? 'text-gray-400 line-through' : '' }}" title="{{ $belonging->name }}">
                <span>
                    {{ Str::limit($belonging->name, 20) }}
                </span>
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
<!-- View All Button -->
<div class="text-center">
    <button id="viewAllBtn" class="text-blue-500 underline-none">View All</button>
</div>

<!-- View All Modal -->
<div id="viewAllModal"
    class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden flex justify-center items-center">

    <!-- モーダル本体 -->
    <div class="bg-white p-6 rounded-lg w-full max-w-md mx-auto relative max-h-[80vh] overflow-y-auto shadow-lg">
        <button id="closeModal" class="absolute top-2 right-2 text-gray-600 text-xl">&times;</button>

        <!-- モーダルヘッダー -->
        <div class="relative flex justify-center items-center mb-4">
            <h3 class="text-lg font-bold">All Belongings</h3>
            <button id="modalToggleVisibility" class="absolute right-[24px] text-2xl" title="Toggle checked visibility">
                <i class="fa-solid fa-eye"></i>
            </button>
        </div>

        <ul id="modalBelongingList" class="space-y-2">
            <!-- JavaScriptで動的に描画 -->
        </ul>
    </div>
</div>

@push('scripts')
    <script>
        window.itineraryId = @json($itinerary->id);
    </script>
    <script src="{{ asset('js/itineraries/belonging.js') }}"></script>
@endpush
