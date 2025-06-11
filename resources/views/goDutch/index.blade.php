<div class="relative flex justify-center items-center mb-4">
    <h2 class="text-xl font-bold">Bill</h2>
    <button id="toggleVisibility" class="absolute right-6 text-2xl" type="button">
        <i class="fa-solid fa-eye"></i>
    </button>
</div>
<ul id="itemList" class="space-y-1 mb-4 max-h-72 overflow-y-auto overflow-x-hidden">
    @forelse ($groupMembers as $groupMember)
        <li class="flex items-between gap-2 p-1 border rounded">
            <div class="flex">
                <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-300 bg-gray-100 flex items-center justify-center text-gray-400">
                    @if ($member->avatar)
                        <a href="{{ route('profile.show', $member->id) }}">
                            <img src="{{ $member->avatar }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                        </a>
                    @else
                        <a href="{{ route('profile.show', $member->id) }}"><i class="fa-regular fa-circle-user fa-lg"></i></a>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-md text-gray-700">{{ $groupMember->name }}</span>
                    @if ($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id] > 0)
                        <span class="text-md text-green-500 ml-auto text-end">
                            Get ${{ number_format($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id], 0) }}
                        </span>
                    @elseif ($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id] < 0)
                        <span class="text-md text-red-500 ml-auto text-end">
                            Pay ${{ number_format(abs($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id]), 0) }}
                        </span>
                    @else
                        <span class="text-md ml-auto text-end">$ 0</span>
                    @endif
                </div>
            </div>
        </li>
    @empty
        <p class="text-sm text-gray-500">No Bills</p>
    @endforelse
</ul>
<div class="flex space-x-2 p-2">
    <a href="{{ route('goDutch.index', $itinerary->id) }}" class="mx-auto text-xl text-blue-500">
        add Bill
    </a>
</div>
@push('scripts')
    <script>
        const itineraryId = @json($itinerary->id);
    </script>
    <script src="{{ asset('js/itineraries/belonging.js') }}"></script>
@endpush
