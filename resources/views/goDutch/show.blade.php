<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100 h-full">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Bill</h1>
                        {{-- modal --}}
                        @php
                            $hasCreateError = $errors->has('user_pay_id') || $errors->has('bill_name') || $errors->has('cost') || $errors->has('user_paid_id');
                        @endphp
                        <div x-data="{ open: {{ $hasCreateError ? 'true' : 'false' }} }">
                            {{-- trigger --}}
                            <button @click="open = true" class="absolute right-40">
                                <i class="fa-solid fa-circle-plus text-lg"></i>
                            </button>
                            {{-- modal content --}}
                            @include('goDutch.modals.create', ['all_bills' => $all_bills, 'groupMembers' => $groupMembers])
                        </div>

                    </div>
                    {{-- contents --}}
                    <div class="mx-auto h-4/5 mt-8 overflow-hidden">

                        @forelse ($all_bills as $bill)

                            <div class="grid grid-cols-7 items-center text-center text-md my-4 gap-4 pb-4">
                                {{-- user avatar --}}
                                <div class="col-span-1">
                                    {{-- user show --}}
                                    <a href="#" class="text-xl w-6 h-6 rounded-full">
                                        @if ($bill->userPay->avatar)
                                            <img src="{{ $bill->userPay->avatar }}" alt="{{ $bill->userPay->name }}">
                                        @else
                                            <i class="fa-solid fa-circle-user"></i>
                                        @endif
                                    </a>
                                </div>
                                {{-- bill name --}}
                                <div class="col-span-1">
                                    <p>{{ $bill->name }}</p>
                                </div>
                                {{-- bill cost --}}
                                <div class="col-span-1">
                                    <p>{{ $bill->cost }}</p>
                                </div>
                                {{--edit and delete button --}}
                                <div class="col-span-1 col-start-6">
                                    @php
                                        $hasEditError = $errors->has('user_pay_id_edit') || $errors->has('bill_name_edit') || $errors->has('cost_edit') || $errors->has('user_paid_id_edit');
                                    @endphp
                                    <div x-data="{ open: {{ $hasEditError ? 'true' : 'false' }} }">
                                        <button data-modal-target="modal-{{ $bill->id }}" data-modal-toggle="modal-{{ $bill->id }}" @click="open = true" class="w-1/2 font-semi-bold text-white py-2 rounded text-xl hover">
                                            <i class="fa-solid fa-pen-to-square text-black"></i>
                                        </button>
                                        {{-- modal content --}}
                                        @include('goDutch.modals.edit', ['bill' => $bill, 'groupMembers' => $groupMembers])
                                    </div>
                                </div>
                                <div class="col-span-2 col-start-7">
                                    <form action="{{ route('goDutch.delete', $bill->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-1/2 font-semi-bold rounded text-white py-2 text-xl hover">
                                            <i class="fa-solid fa-trash text-red-500"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-lg my-60">
                                <h2 class="mb-4 text-gray-500">No Bill created yet.</h2>
                                <div class="text-blue-500">
                                    <div x-data="{ open: {{ $hasCreateError ? 'true' : 'false' }} }">
                                        {{-- trigger --}}
                                        <button @click="open = true">
                                            <i class="fa-solid fa-plus"></i>
                                            add Bill
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- paginate --}}
                    <div class="flex justify-center">
                        {{ $all_bills->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
