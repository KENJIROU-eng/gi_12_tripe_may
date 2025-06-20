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
                            @include('goDutch.modals.create', ['all_bills' => $all_bills, 'groupMembers' => $groupMembers, 'itinerary' => $itinerary])
                        </div>
                    </div>
                    {{-- contents --}}
                    <div class="mx-auto h-4/5 mt-6 overflow-hidden">
                        @if (count($all_bills) > 0)
                            <h1 class="text-xl font-bold italic text-center">History of payment of the trip</h1>
                            <div class="grid grid-cols-5 items-center text-center text-md mb-4 mt-8 gap-4 pb-4 border-b-2 border-t-2 border-green-500 py-2 font-bold">
                                {{-- user avatar --}}
                                <div class="col-span-1">
                                    {{-- user show --}}
                                    <p>The User who pay for the bill</p>
                                </div>
                                {{-- bill name --}}
                                <div class="col-span-1">
                                    <p>Item description</p>
                                </div>
                                {{-- bill cost --}}
                                <div class="col-span-1">
                                    <p>Price</p>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-lg my-60">
                                <h2 class="mb-4 text-gray-500">No Bill created yet.</h2>
                            </div>
                        @endif
                        <div class="max-h-[200px] overflow-y-auto">
                        @foreach ($all_bills as $bill)
                            <div class="grid grid-cols-5 items-center text-center text-md my-4 gap-4 pb-4">
                                {{-- user avatar --}}
                                <div class="col-span-1">
                                    {{-- user show --}}
                                    <a href="{{ route('profile.show', $bill->userPay->id) }}" class="block w-6 h-6 rounded-full overflow-hidden mx-auto">
                                        @if ($bill->userPay->avatar)
                                            <img src="{{ $bill->userPay->avatar }}" alt="{{ $bill->userPay->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-solid fa-circle-user"></i>
                                        @endif
                                    </a>
                                    <p>{{ $bill->userPay->name }}</p>
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
                                <div class="col-span-1">
                                    @php
                                        $hasEditError = $errors->has('user_pay_id_edit') || $errors->has('bill_name_edit') || $errors->has('cost_edit') || $errors->has('user_paid_id_edit');
                                    @endphp
                                    <div x-data="{ open: {{ $hasEditError ? 'true' : 'false' }} }">
                                        <button data-modal-target="modal-{{ $bill->id }}" data-modal-toggle="modal-{{ $bill->id }}" @click="open = true" class="w-1/2 font-semi-bold text-white py-2 rounded text-xl hover">
                                            <i class="fa-solid fa-pen-to-square text-black"></i>
                                        </button>
                                        {{-- modal content --}}
                                        @include('goDutch.modals.edit', ['bill' => $bill, 'groupMembers' => $groupMembers, 'itinerary' => $itinerary])
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <form action="{{ route('goDutch.delete', ['itinerary_id' => $itinerary->id, 'bill_id' => $bill->id]) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-1/2 font-semi-bold rounded text-white py-2 text-xl hover">
                                            <i class="fa-solid fa-trash text-red-500"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        @endforeach
                        </div>
                        @if (count($all_bills) > 0)
                        <h1 class="text-xl font-bold italic text-center mt-3">Calculation Result for payment per each person</h1>
                        <div class="grid grid-cols-4 items-center text-center text-md mb-4 mt-8 gap-4 pb-4 border-b-2 border-t-2 border-green-500 py-2 font-bold">
                            {{-- user avatar --}}
                            <div class="col-span-1">
                                {{-- user show --}}
                                <p>User name</p>
                            </div>
                            <div class="col-span-1">
                                <p>Price</p>
                            </div>
                            <div class="col-span-1">
                                <p>Pay status</p>
                            </div>
                            <div class="col-span-1">
                                <p>Pay now (PayPal)</p>
                            </div>
                        </div>
                        <div class="max-h-[200px] mt-2 overflow-y-auto">
                                @forelse ($groupMembers as $groupMember)
                                    <div class="grid grid-cols-4 items-center text-center text-md my-4 gap-4 pb-4">
                                        {{-- user avatar --}}
                                        <div class="col-span-1">
                                            {{-- user show --}}
                                            <a href="{{ route('profile.show', $groupMember->id) }}" class="block w-6 h-6 rounded-full overflow-hidden mx-auto">
                                                @if ($groupMember->avatar)
                                                    <img src="{{ $groupMember->avatar }}" alt="{{ $groupMember->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <i class="fa-solid fa-circle-user"></i>
                                                @endif
                                            </a>
                                            <p>{{ $groupMember->name }}</p>
                                        </div>
                                        <div class="col-span-1">
                                            @if ($groupMember->id != Auth::User()->id)
                                                @if ($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id] > 0)
                                                    <span class="text-md text-gray-700 text-green-500">Get ${{  number_format(($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id]), 0) }}</span>
                                                @elseif ($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id] < 0)
                                                    <span class="text-md text-gray-700 text-red-500">Pay ${{  number_format(abs($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id]), 0) }}</span>
                                                @else
                                                    <span class="text-md text-gray-700 text-gray-500">$0</span>
                                                @endif
                                            @else
                                                @if ( number_format(($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id] + $price), 0) > 0)
                                                    <span class="text-md text-gray-700 text-green-500">Get ${{  number_format(($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id] + $price), 0) }}</span>
                                                @elseif (number_format(($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id] + $price), 0) < 0)
                                                    <span class="text-md text-gray-700 text-red-500">Pay ${{  number_format(abs($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id] + $price), 0) }}</span>
                                                @else
                                                    <span class="text-md text-gray-700 text-gray-500">$0</span>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="col-span-1">
                                            @if ($groupMember->id != Auth::User()->id)
                                                @if ($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id] > 0)
                                                    <a href="{{ route('message.show', $itinerary->group_id) }}" class="hover:text-blue-500">
                                                        <i class="fa-solid fa-comment"></i>
                                                        <p>Go to chat to request payment</p>
                                                    </a>
                                                @elseif ($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id] < 0)
                                                    <i class="fa-solid fa-circle-xmark text-red-500"></i>
                                                @else
                                                    <i class="fa-solid fa-circle-check text-green-500"></i>
                                                @endif
                                            @else
                                                @if (number_format(($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id] + $price), 0) > 0)
                                                    <a href="{{ route('message.show', $itinerary->group_id) }}" class="hover:text-blue-500">
                                                        <i class="fa-solid fa-comment"></i> Go to chat to request payment
                                                    </a>
                                                @elseif (number_format(($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id] + $price), 0) < 0)
                                                    <i class="fa-solid fa-circle-xmark text-red-500"></i>
                                                @else
                                                    <i class="fa-solid fa-circle-check text-green-500"></i>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="col-span-1">
                                            @if (Auth::User()->id == $groupMember->id && number_format(($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id] + $price), 0) < 0)
                                                <a href="{{ route('paypal.pay', ['itinerary_id' => $itinerary->id, 'total' => abs($total_getPay[$groupMember->id] - $total_Pay[$groupMember->id])]) }}">
                                                    <i class="fa-brands fa-cc-paypal text-blue-500 text-3xl"></i>
                                                </a>
                                            @endif
                                        </div>
                                        {{-- {{ $pay->Price }} --}}
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">$ {{ $total_Pay_alone }}</p>
                                @endforelse
                            </div>
                        </div>
                        @endif
                    </div>
                    {{-- paginate --}}
                    {{-- <div class="flex justify-center">
                        {{ $all_bills->links('vendor.pagination.custom') }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
