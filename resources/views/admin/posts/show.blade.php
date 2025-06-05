<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100 h-full">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Admin Page</h1>
                    </div>
                    {{-- contents --}}
                    <div class="mx-auto h-4/5 mt-8 overflow-hidden">
                        <div class="grid grid-cols-7 h-full">
                            <div class="flex col-span-2 h-1/4">
                                <div class="grid grid-rows-3 w-full border border-1 border-black rounded-md">
                                    <div class="row-span-1 w-full rounded-md py-2 hover:bg-green-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 {{ request()->is('admin/users*') ? 'bg-green-500':'' }}">
                                        <a href="{{ route('admin.users.show') }}" class="text-center block">Users</a>
                                    </div>
                                    <div class="row-span-1 w-full rounded-md py-2 hover:bg-green-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 {{ request()->is('admin/posts*') ? 'bg-green-500':'' }}">
                                        <a href="{{ route('admin.posts.show') }}" class="text-center block">Posts</a>
                                    </div>
                                    <div class="row-span-1 w-full rounded-md py-2 hover:bg-green-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 {{ request()->is('admin/categories*') ? 'bg-green-500':'' }}">
                                        <a href="{{ route('admin.categories.show') }}" class="text-center block">Categories</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-5 h-[640px]">
                                <table class="table-auto mx-auto border border-gray-300 text-center h-[640px]" style="width:90%;">
                                    <thead class="bg-green-500">
                                        <tr class="h-20">
                                            <th>#</th>
                                            <th></th>
                                            <th>Username</th>
                                            <th></th>
                                            <th>Post title</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($all_posts as $post)
                                            <tr class="h-20 {{ $loop->odd ? 'bg-green-300' : 'bg-green-500' }}">
                                                <td>{{ $post->id }}</td>
                                                @if ($post->user->avatar)
                                                    <td>
                                                        <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="text-base lg:text-lg object-cover w-6 h-6 rounded-full mx-auto">
                                                    </td>
                                                @else
                                                    <td>
                                                        <i class="fa-solid fa-circle-user text-blue-600 text-base lg:text-lg"></i>
                                                    </td>
                                                @endif
                                                <td>{{ $post->user->name }}</td>
                                                    <td>
                                                        <img src="{{ $post->image }}" alt="{{ $post->title }}" class="text-base lg:text-lg object-cover w-6 h-6 rounded-full mx-auto">
                                                    </td>
                                                    <td>{{ $post->title }}</td>
                                                    <td>
                                                        <form action="{{ route('admin.posts.delete', $post->id) }}" method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-1/2 font-semi-bold rounded text-white py-2 text-xl hover">
                                                                <i class="fa-solid fa-trash text-red-500"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- paginate --}}
                    <div class="flex justify-center">
                        {{ $all_posts->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
