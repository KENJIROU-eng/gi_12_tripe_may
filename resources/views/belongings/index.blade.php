{{-- progress --}}
@if($totalCount > 0)
    <div class="w-full px-4 mb-4">
        <div class="flex justify-between text-sm mb-1 text-gray-600 dark:text-gray-300">
            <span>{{ $checkedCount }} / {{ $totalCount }} items</span>
            <span>{{ $progressPercent }}%</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5 overflow-hidden">
            <div class="bg-blue-500 h-full transition-all duration-300" style="width: {{ $progressPercent }}%;"></div>
        </div>
    </div>
@endif

{{-- title --}}
<div class="relative flex justify-center items-center mb-4">
    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Belonging List</h2>
    <button id="toggleCheckedBtn" type="button" class="absolute right-2 text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white text-xl" title="Toggle Checked Items">
        <i class="fa-solid fa-eye text-blue-500"></i>
    </button>
</div>

<div class="text-center mt-2">
    <a href="{{ route('belonging.index', $itinerary->id) }}" class="inline-block text-blue-600 hover:underline text-sm font-medium mb-2">
        View All
    </a>
</div>

{{-- main --}}
<ul id="belongingList" class="space-y-1 mb-4 max-h-[250px] overflow-y-auto overflow-x-hidden pr-2">
    @forelse ($all_belongings as $belonging)
        <li class="belonging-item flex justify-between items-center px-1 border rounded bg-white dark:bg-gray-700 shadow-sm {{ $belonging->checked ? 'is-checked opacity-50 pointer-events-none' : '' }}" data-id="{{ $belonging->id }}" data-checked="{{ $belonging->checked ? '1' : '0' }}">
            <div class="flex-grow text-start truncate" title="{{ $belonging->name }}">
                <span class="text-sm text-gray-800 dark:text-gray-100">
                    {{ Str::limit($belonging->name, 25) }}
                </span>
            </div>
        </li>
    @empty
        <li class="text-center text-gray-500 dark:text-gray-400 text-sm">No belongings yet.</li>
    @endforelse
</ul>

