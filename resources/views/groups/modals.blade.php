@foreach ($groups as $group)
    <div x-data="{ showEditModal: false, showDeleteModal: false }">
        <!-- Edit Modal -->
        <div x-show="showEditModal"
            x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Edit Group: {{ $group->name }}</h2>
                <form method="POST" action="#">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $group->name }}" class="w-full border p-2 mb-4 rounded">
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showEditModal = false" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="showDeleteModal"
            x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-red-600">Delete Group: {{ $group->name }}?</h2>
                <p class="mb-4 text-gray-700">Are you sure you want to delete this group? This action cannot be undone.</p>
                <form method="POST" action="{{ route('groups.delete' ,$group->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showDeleteModal = false" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
