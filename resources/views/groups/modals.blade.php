{{-- Edit Modal --}}
<div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-3xl">
        <div class="relative flex items-center justify-center h-16 my-5">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold absolute left-1/2 transform -translate-x-1/2">Edit Group</h1>
        </div>

        <form method="POST" action="{{ route('groups.update', $group->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="mb-4 flex items-center justify-center">
                <label for="name-{{ $group->id }}" class="block text-sm font-semibold text-black">Group Name</label>
                <input type="text" name="name" id="name-{{ $group->id }}" value="{{ $group->name }}"
                        class="w-3/4 mt-1 p-2 block rounded-md focus:ring focus:border-blue-300 ml-2" required>
            </div>

            <div class="flex justify-center">
                {{-- Members --}}
                <div class="container mb-4 w-3/4 sm:w-2/3 md:w-1/2 lg:w-1/3  mr-2">
                    <label class="block text-sm font-semibold text-gray-700 text-center">Group Members</label>

                    <div class="space-y-2 mt-2 max-h-64 overflow-y-auto border p-2 rounded">
                        @if ($group->isBocciFor(auth()->id()))
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                            </div>
                        @else
                            @foreach ($users as $user)
                                @php
                                    $memberIds = [];
                                    foreach($group->users as $member) {
                                        $memberIds[] = $member->id;
                                    }
                                @endphp
                                @if ($user->isFollowed() || in_array($user->id, $memberIds))
                                <label class="flex w-full justify-between items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="members[]" value="{{ $user->id }}" class="hidden peer"
                                        {{ $group->users->contains($user->id) ? 'checked' : '' }}>

                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-white text-sm font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm text-gray-700">{{ $user->name }}</span>
                                    </div>

                                    <div class="w-4 h-4 rounded-full border-2 border-gray-400 peer-checked:bg-blue-400 peer-checked:border-blue-500 flex items-center justify-center transition">
                                        <i class="fa-solid fa-check text-white text-xs hidden peer-checked:block"></i>
                                    </div>
                                </label>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Image --}}
                <div class="container w-1/3 mb-4 ml-4">
                    <label for="image-{{ $group->id }}" class="block text-sm font-semibold text-gray-700 text-center">Group Image</label>

                    <img id="image-preview-{{ $group->id }}" src="{{ $group->image ? asset('storage/' . $group->image) : '' }}"
                        class="w-25 aspect-square rounded-full object-cover border border-gray-300 mx-auto {{ $group->image ? '' : 'hidden' }}" alt="Preview">

                    <input type="file" name="image" id="image-{{ $group->id }}" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 text-center">
                </div>
            </div>

            <div class="flex justify-end mt-6 gap-2">
                <button type="button" @click="showEditModal = false" class="bg-gray-400 text-black px-4 py-2 rounded hover:bg-gray-500">Cancel</button>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Update Group</button>
            </div>
        </form>

        <script>
            document.getElementById('image-{{ $group->id }}')?.addEventListener('change', function (event) {
                const preview = document.getElementById('image-preview-{{ $group->id }}');
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.src = '';
                    preview.classList.add('hidden');
                }
            });
        </script>
    </div>
</div>

{{-- Delete Modal --}}
<div x-show="showDeleteModal" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4 text-red-600">Delete Group: {{ $group->name }}</h2>
        <p class="mb-4 text-gray-700">Are you sure you want to delete this group? This action cannot be undone.</p>
        <form method="POST" action="{{ route('groups.delete', $group->id) }}">
            @csrf
            @method('DELETE')
            <div class="flex justify-end gap-2">
                <button type="button" @click="showDeleteModal = false" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
            </div>
        </form>
    </div>
</div>
