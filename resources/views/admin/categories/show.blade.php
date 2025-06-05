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
                        <div class="grid grid-cols-7 h-1/5 mb-3 w-full">
                            <div class="col-span-2 h-1/4">
                            </div>
                            <div class="col-span-5 h-1/4 w-full">
                                <form action="{{ route('admin.categories.store') }}" method="post" class="w-full">
                                    <div class="grid grid-cols-4 w-full">
                                        @csrf
                                        <div class="col-span-3 w-full">
                                            <input type="text" name="category_name" placeholder="category name" class="block w-4/5 mx-auto rounded-md">
                                        </div>
                                        <div class="col-span-1 w-full">
                                            <button type="submit" class="w-3/5 mx-auto bg-green-500 font-semi-bold rounded text-white py-2 text-xl hover:bg-green-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="grid grid-cols-7 h-4/5">
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
                            <div class="col-span-5 h-[512px]">
                                <table class="table-auto mx-auto border border-gray-300 text-center h-[512px]" style="width:90%;">
                                    <thead class="bg-green-500">
                                        <tr class="h-16">
                                            <th>#</th>
                                            <th>Category Name</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($all_categories as $category)
                                            <tr class="h-16 {{ $loop->odd ? 'bg-green-300' : 'bg-green-500' }}">
                                                <td>{{ $category->id }}</td>
                                                <td>{{ $category->name }}</td>
                                                <td>
                                                    <form action="{{ route('admin.categories.delete', $category->id) }}" method="post">
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
                        {{ $all_categories->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
