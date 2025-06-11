{{-- modal content --}}
<div x-show="open" class="fixed inset-0 bg-gray-500/75 z-10 flex items-center justify-center w-full">
    <div class="bg-white p-6 rounded shadow max-w-md w-full">
        {{-- header --}}
        <div class="px-6 py-4 text-center">
            <h1 class="text-3xl font-bold">Create Bill</h1>
        </div>
        <hr class="border-green-500 border-1">
        {{-- body --}}
        <form action="{{ route('goDutch.create', $itinerary->id) }}" class="mx-auto w-full mt-3" method="post">
            @csrf
            <div class="grid grid-cols-6 gap-2">
                <div class="col-span-4 col-start-2 me-auto">
                    <label for="user_pay_name" class="block text-md font-medium text-gray-900">Who pay the bill?</label>
                </div>
                <div class="col-span-2 col-start-2 mb-2">
                    <select id="user_pay_id" name="user_pay_id" autocomplete="user_pay_id" class="w-full appearance-none rounded-md">
                        <option value="" disabled selected>user pay</option>
                        @foreach ($groupMembers as $groupMember)
                            <option value="{{ $groupMember->id }}">{{ $groupMember->name }}</option>
                        @endforeach
                    </select>
                    @error('user_pay_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-span-4 col-start-2 mb-2 me-auto">
                    <label for="bill_name" class="block text-md font-medium text-gray-900 mb-2">What does she or he pay for?</label>
                    <input type="text" name="bill_name" id="bill_name" class="block rounded-md w-full mt-2" placeholder="name">
                    @error('bill_name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-span-4 col-start-2 mb-2 me-auto">
                    <label for="cost" class="block text-md font-medium text-gray-900 mb-2">How much does it cost?</label>
                    <input type="number" name="cost" id="cost" class="block rounded-md w-full mt-2" placeholder="cost">
                    @error('cost')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-span-4 col-start-2 me-auto">
                    <label for="user_paid_name" class="block text-md font-medium text-gray-900">Select members who spill the bill?</label>
                </div>
                <div class="col-span-4 col-start-2 mb-2">
                    <div class="max-h-20 space-y-2 mt-2 max-h-30 overflow-y-auto p-2 rounded">
                        @forelse ($groupMembers as $groupMember)
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="user_paid_id[]" class="user-input" value="{{ $groupMember->id }}" data-user-id="{{ $groupMember->id }}">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-700">{{ $groupMember->name }}</span>
                            </div>
                        </label>
                        @empty
                        <p class="text-sm text-gray-500">No Users</p>
                        @endforelse
                    </div>
                    @error('user_paid_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-span-2 col-start-2 mt-2">
                    <a href="{{ route('goDutch.index', $itinerary->id) }}">
                        <button type="button" class="w-full bg-gray-500 font-semi-bold text-white py-2 rounded text-xl hover:bg-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-500">
                            Cancel
                        </button>
                    </a>
                </div>
                <div class="col-span-2 col-start-4 mt-2">
                    <button type="submit" class="w-full bg-green-500 font-semi-bold rounded text-white py-2 text-xl hover:bg-green-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        Enter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- remove js --}}
<script src="{{ asset('js/removeOption.js') }}" defer></script>


