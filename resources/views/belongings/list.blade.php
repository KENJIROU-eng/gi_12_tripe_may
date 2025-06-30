<x-app-layout class="h-screen flex flex-col overflow-hidden">
    <div class="min-h-screen bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('https://res.cloudinary.com/dpwrycc89/image/upload/v1750757614/pexels-jplenio-1133505_ijwxpn.jpg');">
        <div class="pt-8 flex-1 overflow-y-auto flex flex-col lg:flex-row gap-4 max-w-screen-3xl mx-auto px-4 pb-32">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4 mx-auto w-full max-w-6xl">
            {{-- タイトル・戻る・トグルを横並び --}}
            <div class="flex items-center justify-between mb-4">
                {{-- 左：戻るボタン --}}
                <a href="{{ route('itinerary.show', $itineraryId) }}" class="inline-flex items-center text-sm text-blue-500 hover:underline">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Back
                </a>

                {{-- 中央：タイトル --}}
                <h1 class="text-4xl md:text-6xl font-bold text-gray-800 dark:text-gray-100 animate-fadeIn text-center flex-1">
                    <i class="fa-solid fa-b"></i>
                    <i class="fa-solid fa-e"></i>
                    <i class="fa-solid fa-l"></i>
                    <i class="fa-solid fa-o"></i>
                    <i class="fa-solid fa-n"></i>
                    <i class="fa-solid fa-g"></i>
                    <i class="fa-solid fa-i"></i>
                    <i class="fa-solid fa-n"></i>
                    <i class="fa-solid fa-g"></i>
                </h1>

                {{-- 右：チェックトグルボタン --}}
                <button id="toggleCheckedBtn" class="me-2 text-gray-700 dark:text-gray-300 whitespace-nowrap text-xl">
                    <i class="fas fa-eye text-blue-500"></i>
                </button>
            </div>


                {{-- New Belonging Form --}}
                <form id="belongingForm" action="{{ route('belonging.store', $itineraryId) }}" method="POST" class="space-y-1" name="belongingForm">
                    @csrf

                    <div class="flex flex-col md:flex-row gap-2">
                        <div class="md:w-1/2">
                            <x-input-label for="item">
                                <span class="text-red-500 ml-1">*</span>Item Name
                            </x-input-label>
                            <x-text-input id="item" name="item" required maxlength="50" class="w-full" placeholder="e.g. Passport, Wallet, Charger" />
                            <div id="itemCharCount" class="right-2 top-2 text-sm text-gray-400">
                                0 / 50
                            </div>
                            @error('item')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:w-1/2">
                            <x-input-label for="description" class="text-gray-700" value="Description" />
                            <textarea id="description" name="description" rows="1" maxlength="500" class="w-full rounded-md border-gray-300" placeholder="Details about the item..."></textarea>
                            <div id="descriptionCharCount" class="right-2 top-2 text-sm text-gray-400">
                                0 / 500
                            </div>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        {{-- 全選択 / チェック済み解除ボタン --}}
                        <div class="flex justify-between items-center mb-1">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-users"></i> <span class="text-red-500 ml-1">*</span>Assign to Members
                            </label>
                            <button type="button" id="toggleSelectAllMembers" class="text-sm text-blue-600 hover:underline">
                                Select All
                            </button>
                        </div>

                        <div class="max-h-40 overflow-y-auto border rounded-md p-2 bg-white dark:bg-gray-700">
                            <div class="grid grid-cols-2 gap-1">
                                @foreach ($members as $member)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="members[]" value="{{ $member->id }}" class="member-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="text-sm text-gray-800 dark:text-gray-100">{{ $member->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @error('members')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- progress + addボタン横並び --}}
                    @if($totalCount > 0)
                        <div class="pt-2 md:flex md:items-center md:justify-between">
                            {{-- プログレスバー --}}
                            <div class="w-full px-4 mb-4">
                                <div class="flex justify-between text-sm mb-1 text-gray-600 dark:text-gray-300">
                                    <span class="progress-count-text">{{ $checkedCount }} / {{ $totalCount }} items</span>
                                    <span class="progress-percent-text">{{ $progressPercent }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5 overflow-hidden">
                                    <div class="progress-bar-fill bg-blue-500 h-full transition-all duration-300" style="width: {{ $progressPercent }}%;"></div>
                                </div>
                            </div>

                            {{-- Addボタン --}}
                            <div class="text-right md:w-auto">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">
                                    Add
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="pt-2 text-right">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">
                                Add
                            </button>
                        </div>
                    @endif

                </form>

                {{-- Belonging List --}}
                <div class="mt-8 max-h-[470px] overflow-y-auto pr-1 space-y-6" id="belongingListScrollArea">
                    @forelse ($all_belongings as $belonging)
                        <div class="relative border p-2 rounded-md bg-white dark:bg-gray-700 shadow-sm belonging-item" data-belonging-id="{{ $belonging->id }}" data-belonging-name="{{ $belonging->name }}" data-belonging-description="{{ $belonging->description }}" data-belonging-users='@json($belonging->users->pluck("id"))' data-checked="{{ $belonging->users->every(fn($u) => $u->pivot->is_checked) ? '1' : '0' }}">
                            <div class="absolute top-2 right-2 flex space-x-2">
                                <button class="edit-btn text-yellow-500 hover:text-yellow-700" title="Edit" data-belonging-id="{{ $belonging->id }}">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="delete-btn text-red-500 hover:text-red-700" title="Delete" data-belonging-id="{{ $belonging->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <div class="font-bold text-lg text-gray-800 dark:text-gray-100 belonging-name">
                                {{ $belonging->name }}
                            </div>
                            <div class="text-sm text-gray-500 mb-2 break-words whitespace-pre-line">
                                {{ $belonging->description }}
                            </div>

                            {{-- Member Checklist --}}
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-1 mt-2 max-h-20 overflow-y-auto pr-1">
                                @foreach ($belonging->users as $user)
                                    @php $isOwn = $user->id === Auth::id(); @endphp
                                    <label class="flex items-center gap-2 px-3 py-1 rounded-lg border
                                        {{ $isOwn ? 'bg-indigo-50 border-indigo-400' : 'bg-gray-50 dark:bg-gray-700 border-gray-300' }}">
                                        <input type="checkbox" class="member-checkbox h-4 w-4 {{ !$isOwn ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}" data-belonging-id="{{ $belonging->id }}" data-user-id="{{ $user->id }}" {{ $user->pivot->is_checked ? 'checked' : '' }} {{ !$isOwn ? 'disabled' : '' }}>
                                        <span class="text-sm {{ $isOwn ? 'text-indigo-600 font-bold' : 'text-gray-800 dark:text-gray-100' }}">
                                            {{ $user->name }}
                                            @if ($isOwn)
                                                <span class="text-xs bg-indigo-100 text-indigo-800 px-1 rounded ml-1"><i class="fa-regular fa-user"></i></span>
                                            @endif
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                        </div>
                    @empty
                        <p class="text-gray-500 text-center">No belongings yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll to Top Button --}}
    <button id="scrollToTopBtn"
        class="fixed bottom-12 left-1/2 transform -translate-x-1/2 z-50 bg-green-400 text-white rounded-full p-1 shadow-lg transition-opacity duration-300 opacity-0 pointer-events-none md:hidden"
        aria-label="Scroll to top">
        <i class="fa-solid fa-arrow-up"></i> Go to Top
    </button>

    {{-- Edit Modal --}}
    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-md w-full max-w-lg">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">
                <i class="fa-solid fa-e"></i>
                <i class="fa-solid fa-d"></i>
                <i class="fa-solid fa-i"></i>
                <i class="fa-solid fa-t"></i>
                <span class="mx-2"></span>
                <i class="fa-solid fa-b"></i>
                <i class="fa-solid fa-e"></i>
                <i class="fa-solid fa-l"></i>
                <i class="fa-solid fa-o"></i>
                <i class="fa-solid fa-n"></i>
                <i class="fa-solid fa-g"></i>
                <i class="fa-solid fa-i"></i>
                <i class="fa-solid fa-n"></i>
                <i class="fa-solid fa-g"></i>
            </h2>
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="editBelongingId">
                <div>
                    <label class="block mb-1 font-medium text-sm"><span class="text-red-500 ml-1">*</span>Item Name</label>
                    <input type="text" id="editName" class="w-full rounded-md border-gray-300" required maxlength="50">
                    <div id="editNameCharCount" class="right-2 top-2 text-sm text-gray-400">
                        0 / 50
                    </div>
                </div>
                <div>
                    <label class="block mb-1 font-medium text-sm">Description</label>
                    <textarea id="editDescription" class="w-full rounded-md border-gray-300" rows="2" maxlength="500"></textarea>
                    <div id="editDescriptionCharCount" class="right-2 top-2 text-sm text-gray-400">
                        0 / 500
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block mb-1 font-medium text-sm"><i class="fa-solid fa-users"></i> <span class="text-red-500 ml-1">*</span>Assign to Members</label>
                        <button type="button" id="editToggleSelectAll" class="text-sm text-blue-600 hover:underline">
                            Select All
                        </button>
                    </div>
                    <div class="max-h-40 overflow-y-auto border rounded-md p-3 bg-white dark:bg-gray-700">
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($members as $member)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" class="edit-member-checkbox" value="{{ $member->id }}">
                                    <span>{{ $member->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" id="cancelEdit" class="px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Duplicate Modal --}}
    <div id="duplicateModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-md w-full max-w-md">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">
                This item name already exists.
            </h2>
            <p class="mb-4 text-gray-600 dark:text-gray-300">
                Do you want to add members to the existing item or create a new item with the same name?
            </p>
            <div class="flex justify-end space-x-2">
                <button id="addToExistingBtn" class="bg-green-600 text-white px-4 py-2 rounded-md">Add to Existing</button>
                <button id="createNewBtn" class="bg-indigo-600 text-white px-4 py-2 rounded-md">Create New</button>
                <button id="cancelDuplicate" class="bg-gray-300 px-4 py-2 rounded-md">Cancel</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="{{ asset('js/itineraries/belonging.js') }}?v={{ now()->timestamp }}"></script>
    @endpush

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</x-app-layout>
