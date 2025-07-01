<x-app-layout>
    <div class="mt-5 min-h-screen">
        <div class="w-11/12 md:w-4/5 mx-auto sm:px-4 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <div class="p-4 sm:p-6 text-black dark:text-gray-100">
                    {{-- タイトル --}}
                    <div class="text-center my-5">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold">Admin Page</h1>
                    </div>

                    {{-- 入力フォーム --}}
                    <div class="mb-6">
                        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-col md:flex-row items-center gap-4 w-full">
                            @csrf
                            <input type="text" name="category_name" placeholder="New Category Name" required
                                class="w-full md:w-2/3 px-4 py-2 border rounded-md focus:outline-green-500">
                            <button type="submit"
                                    class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded-md">
                                Save
                            </button>
                        </form>
                    </div>
                     @error('category_name')
                        <div class="text-red-500 text-md">{{ $message }}</div>
                    @enderror

                    {{-- コンテンツレイアウト --}}
                    <div class="flex flex-col lg:flex-row gap-6">
                        {{-- サイドメニュー --}}
                        <div class="w-full lg:w-1/4">
                            <div class="space-y-3">
                                @foreach ([
                                    'admin.users.show' => 'Users',
                                    'admin.posts.show' => 'Posts',
                                    'admin.categories.show' => 'Categories',
                                    'admin.itineraries.show' => 'Itineraries',
                                ] as $route => $label)
                                    <a href="{{ route($route) }}"
                                    class="block text-center py-2 rounded-md font-semibold transition
                                            {{ request()->routeIs($route) ? 'bg-green-600 text-white' : 'bg-green-100 hover:bg-green-300' }}">
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- テーブル --}}
                        <div class="w-full lg:w-3/4 overflow-x-auto">
                            <table class="min-w-full table-auto border border-gray-300 text-center">
                                <thead class="bg-green-500 text-white">
                                    <tr class="text-sm sm:text-base h-12">
                                        <th class="px-2">#</th>
                                        <th class="px-2">Category Name</th>
                                        <th class="px-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($all_categories as $category)
                                        <tr class="{{ $loop->odd ? 'bg-green-100' : 'bg-green-50' }} text-sm sm:text-base">
                                            <td class="py-2">{{ $category->id }}</td>
                                            <td class="py-2">{{ $category->name }}</td>
                                            <td class="py-2">
                                                <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ページネーション --}}
                    <div class="flex justify-center mt-6">
                        {{ $all_categories->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
