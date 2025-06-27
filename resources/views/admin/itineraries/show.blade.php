<x-app-layout>
    <div class="mt-5 min-h-screen">
        <div class="w-11/12 md:w-4/5 mx-auto sm:px-4 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <div class="p-4 sm:p-6 text-black dark:text-gray-100">
                    {{-- タイトル --}}
                    <div class="text-center my-5">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold">Admin Page</h1>
                    </div>

                    {{-- コンテンツ --}}
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

                        {{-- メインコンテンツ（ユーザーテーブル） --}}
                        <div class="w-full lg:w-3/4 overflow-x-auto">
                            <table class="min-w-full table-auto border border-gray-300 text-center">
                                <thead class="bg-green-500 text-white text-sm sm:text-base">
                                    <tr>
                                        <th class="py-3 px-2">#</th>
                                        <th class="py-3 px-2">Group Name</th>
                                        <th class="py-3 px-2">Title</th>
                                        <th class="py-3 px-2">Finalize_bill_at</th>
                                        <th class="py-3 px-2">Finish_at</th>
                                        <th class="py-3 px-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($all_itineraries as $itinerary)
                                        <tr class="{{ $loop->odd ? 'bg-green-100' : 'bg-green-50' }} text-sm sm:text-base">
                                            <td class="py-2">{{ $itinerary->id }}</td>
                                            <td class="py-2">
                                                {{ $itinerary->group->name }}
                                            </td>
                                            <td class="py-2">
                                                {{ $itinerary->title }}
                                            </td>
                                            <td class="py-2">
                                                {{ $itinerary->finalize_bill_at }}
                                            </td>
                                            <td class="py-2">
                                                {{ $itinerary->finish_at }}
                                            </td>
                                            <td class="py-2">
                                                <form action="{{ route('admin.itineraries.delete', $itinerary->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this itinerary?')">
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
                        {{ $all_itineraries->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
