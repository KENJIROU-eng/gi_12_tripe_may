
<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">New Group</h1>
                    </div>
                    {{-- contents --}}
                    <div class="mx-auto h-full mt-8">
                        <form action="/group/store" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4 flex items-center justify-center">
                                <label for="name" class="block text-sm font-semibold text-black ">Group Name</label>
                                <input type="text" name="name" id="name" class="w-3/4 mt-1 p-2 block  rounded-md focus:ring focus:border-blue-300 ml-2" required>
                            </div>
                            <div class="flex justify-center">
                                <div class="container mb-4 w-1/3">
                                    <label class="block text-sm font-semibold text-gray-700 text-center">Group Member</label>
                                    <div class="space-y-2 mt-2 max-h-[500px] overflow-y-auto border p-2 rounded">
                                        @forelse (Auth::User()->following as $user)
                                            <label class="flex w-full justify-between items-center space-x-3 cursor-pointer">
                                                <input type="checkbox" name="members[]" value="{{ $user->following->id }}" class="hidden peer">
                                                <div class="flex items-center space-x-2 max-h-400px">
                                                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-white text-sm font-bold">
                                                        @if ($user->following->avatar)
                                                            <img src="{{ $user->following->avatar }}" alt="{{ $user->following->name }}" class="w-8 h-8 rounded-full">
                                                        @else
                                                            {{ strtoupper(substr($user->following->name, 0, 1)) }}
                                                        @endif
                                                    </div>
                                                    <span class="text-sm text-gray-700">{{ $user->following->name }}</span>
                                                </div>
                                                <div class=" w-4 h-4 rounded-full border-2 border-gray-400 peer-checked:bg-blue-400 peer-checked:border-blue-500 flex items-center justify-center transition">
                                                    <i class="fa-solid fa-check text-white text-xs hidden peer-checked:block"></i>
                                                </div>
                                            </label>
                                        @empty
                                            <p class="text-sm text-gray-500">No Following Users</p>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="container w-1/3 mb-4 ml-4">
                                    <label for="image" class="block text-sm font-semibold text-gray-700 text-center">Group Image</label>
                                    <!--image preview-->
                                    <img id="image-preview" class="w-25 aspect-square rounded-full object-cover border border-gray-300 hidden mx-auto" alt="Preview">
                                    <input type="file" name="image" id="image" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500 text-center">
                                </div>
                            </div>
                            <div class="flex justify-center mt-6">
                                <button type="submit" class="bg-green-500 text-black px-6 py-3 rounded hover:bg-green-600 max-w-md text-lg">
                                    Create New Group
                                </button>
                            </div>
                        </form>
                        <!--image preview-->
                        <script>
                            document.getElementById('image').addEventListener('change', function (event) {
                                const preview = document.getElementById('image-preview');
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
            </div>
        </div>
    </div>
</x-app-layout>

