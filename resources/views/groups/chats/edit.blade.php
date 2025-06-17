<div id="edit-modal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-gray-100 p-4 rounded shadow-md w-1/3">
        <h2 class="text-lg font-semibold mb-4">Edit Message</h2>
        <form action="" method="POST" id="edit-form">
            @csrf
            @method('PATCH')
            <input id="edit-message-id" name="messageId" type="hidden">
            <textarea id="edit-message-text" name="message" class="w-full p-2 mb-4" rows="3"></textarea>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-400 text-gray-900 rounded">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-gray-100 rounded">
                    Edit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function closeEditModal(){
        document.getElementById('edit-modal').classList.add('hidden');
    }
    function openEditModal(id, message){
        document.getElementById('edit-message-id').value = id;
        document.getElementById('edit-message-text').value = message;
        document.getElementById('edit-form').action = `/chat/${id}`;

        document.getElementById('edit-modal').classList.remove('hidden');
    }
</script>
