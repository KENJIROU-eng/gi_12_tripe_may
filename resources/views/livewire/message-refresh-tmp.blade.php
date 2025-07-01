{{-- @if (!empty($groups) && count($groups) > 0)
    <div x-data="{ notificationOpen: false }" class="relative">
        <button @click.stop="notificationOpen = !notificationOpen" class="relative px-2 sm:ms-4 text-gray-600 dark:text-gray-200 hover:text-yellow-500 focus:outline-none focus:ring-0 focus:border-transparent" >
            <i class="fa-solid fa-bell text-lg"></i>
            @if ($nonReadCount_total > 0)
                <span class="absolute -top-1 -right-1 inline-block w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                    {{ $nonReadCount_total }}
                </span>
            @endif
        </button>
        <div x-show="notificationOpen" @click.outside="notificationOpen = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded shadow-lg z-50">
            @php $hasUnread = false; @endphp
            @foreach ($groups as $group)
                @if ($nonReadCount[$group->id] > 0)
                    @php $hasUnread = true; @endphp
                    <a href="{{ route('message.show', $group->id) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800">
                        {{ $group->name }}
                        <span class="ml-2 inline-block text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                            {{ $nonReadCount[$group->id] }}
                        </span>
                    </a>
                @endif
            @endforeach
            @if (! $hasUnread)
                <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">
                    No notification
                </div>
            @endif
        </div>
    </div>
@endif --}}
<div>
{{$count}}
</div>