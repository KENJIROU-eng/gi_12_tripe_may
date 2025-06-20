<div class="relative flex justify-center items-center mb-4">
    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Belonging List</h2>
    <button id="toggleCheckedBtn" type="button" class="absolute right-6 text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white text-xl" title="Toggle Checked Items">
        <i class="fa-solid fa-eye"></i>
    </button>
</div>

<div class="text-center mt-2">
    <a href="{{ route('belonging.index', $itinerary->id) }}" class="inline-block text-blue-600 hover:underline text-sm font-medium mb-2">
        View All
    </a>
</div>

<ul id="belongingList" class="space-y-1 mb-4 max-h-[300px] overflow-y-auto overflow-x-hidden pr-2">
    @forelse ($all_belongings as $belonging)
        <li class="belonging-item flex justify-between items-center px-1 border rounded bg-white dark:bg-gray-700 shadow-sm {{ $belonging->checked ? 'is-checked opacity-50 pointer-events-none' : '' }}" data-id="{{ $belonging->id }}" data-checked="{{ $belonging->checked ? '1' : '0' }}">
            <div class="flex-grow text-start truncate" title="{{ $belonging->name }}">
                <span class="text-sm text-gray-800 dark:text-gray-100">
                    {{ Str::limit($belonging->name, 20) }}
                </span>
            </div>
        </li>
    @empty
        <li class="text-center text-gray-500 dark:text-gray-400 text-sm">No belongings yet.</li>
    @endforelse
        </ul>


