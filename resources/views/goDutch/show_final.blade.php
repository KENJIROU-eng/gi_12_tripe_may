<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100 h-full">
                    {{-- title --}}
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Bill</h1>
                        {{-- modal --}}
                        {{-- @php
                            $hasCreateError = $errors->has('user_pay_id') || $errors->has('bill_name') || $errors->has('cost') || $errors->has('user_paid_id');
                        @endphp
                        <div x-data="{ open: {{ $hasCreateError ? 'true' : 'false' }} }"> --}}
                            {{-- trigger --}}
                            {{-- <button @click="open = true" class="absolute right-40">
                                <i class="fa-solid fa-circle-plus text-lg"></i>
                            </button> --}}
                            {{-- modal content --}}
                            {{-- @include('goDutch.modals.create', ['all_bills' => $all_bills, 'groupMembers' => $groupMembers, 'itinerary' => $itinerary])
                        </div> --}}
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
                                <div class="col-span-1">
                                    <p>The Users who split the bill</p>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-lg my-60">
                                <h2 class="mb-4 text-gray-500">No Bill created yet.</h2>
                            </div>
                        @endif
                        <div class="max-h-[180px] overflow-y-auto">
                            @foreach ($all_bills as $bill)
                                <div class="grid grid-cols-5 items-center text-center text-md my-4 gap-4 pb-4">
                                    {{-- user avatar --}}
                                    <div class="col-span-1">
                                        {{-- user show --}}
                                        <a href="{{ route('profile.show', $bill->userPay->id) }}" class="block w-10 h-10 rounded-full overflow-hidden mx-auto">
                                            @if ($bill->userPay->avatar)
                                                <img src="{{ $bill->userPay->avatar }}" alt="{{ $bill->userPay->name }}" class="w-full h-full object-cover">
                                            @else
                                                <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="w-full h-full object-cover">
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
                                    <div class="col-span-1 flex">
                                        <div class="overflow-x-auto">
                                            <div class="flex items-center space-x-4 w-max px-2">
                                                @foreach ($bill->billUser as $billUser)
                                                    <div class="text-center min-w-[64px]">
                                                        <a href="{{ route('profile.show', $billUser->userPaid->id) }}"
                                                        class="block w-10 h-10 rounded-full overflow-hidden mx-auto bg-gray-200 flex items-center justify-center">
                                                            @if ($billUser->userPaid->avatar)
                                                                <img src="{{ $billUser->userPaid->avatar }}"
                                                                    alt="{{ $billUser->userPaid->name }}"
                                                                    class="w-full h-full object-cover">
                                                            @else
                                                                <img src="{{  asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg')  }}"
                                                                    alt="default avatar"
                                                                    class="w-full h-full object-cover">
                                                            @endif
                                                        </a>
                                                        <p class="text-xs mt-1 text-center truncate max-w-[72px]">{{ $billUser->userPaid->name }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
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
                        <div class="max-h-[160px] mt-2 overflow-y-auto">
                                @if (!empty($details))
                                    @foreach ($details as $detail)
                                        <div class="grid grid-cols-4 items-center text-center text-md my-4 gap-4 pb-4">
                                            {{-- user avatar --}}
                                            <div class="col-span-1 flex items-center justify-center space-x-4">
                                                {{-- 左側ユーザー --}}
                                                <div class="text-center">
                                                    <a href="{{ route('profile.show', $detail[0]->id) }}" class="block w-10 h-10 rounded-full overflow-hidden mx-auto">
                                                        @if ($detail[0]->avatar)
                                                            <img src="{{ $detail[0]->avatar }}" alt="{{ $detail[0]->name }}" class="w-full h-full object-cover">
                                                        @else
                                                            <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="w-full h-full object-cover">
                                                        @endif
                                                    </a>
                                                    <p class="text-sm mt-1">{{ $detail[0]->name }}</p>
                                                </div>
                                                {{-- 矢印 --}}
                                                <div class="text-red-500 text-xl">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </div>
                                                {{-- 右側ユーザー（仮に同じ人物を表示）--}}
                                                <div class="text-center">
                                                    <a href="{{ route('profile.show', $detail[1]->id) }}" class="block w-10 h-10 rounded-full overflow-hidden mx-auto">
                                                        @if ($detail[1]->avatar)
                                                            <img src="{{ $detail[1]->avatar }}" alt="{{ $detail[1]->name }}" class="w-full h-full object-cover">
                                                        @else
                                                            <img src="{{ asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="default avatar" class="w-full h-full object-cover">
                                                        @endif
                                                    </a>
                                                    <p class="text-sm mt-1">{{ $detail[1]->name }}</p>
                                                </div>
                                            </div>
                                            <div class="col-span-1">
                                                @if ($detail[2] - $price > 0)
                                                    <span class="text-md text-red-500">${{  $detail[2] - $price  }}</span>
                                                @else
                                                    <span class="text-md text-gray-500">${{  $detail[2] - $price  }}</span>
                                                @endif
                                                {{-- @if ($detail[0]->id != Auth::User()->id)
                                                    @if ($total_getPay[$detail[0]->id] - $total_Pay[$detail[0]->id] > 0)
                                                        <span class="text-md text-gray-700 text-green-500">Get ${{  number_format(($total_getPay[$detail[0]->id] - $total_Pay[$detail[0]->id]), 0) }}</span>
                                                    @elseif ($total_getPay[$detail[0]->id] - $total_Pay[$detail[0]->id] < 0)
                                                        <span class="text-md text-gray-700 text-red-500">Pay ${{  number_format(abs($total_getPay[$detail[0]->id] - $total_Pay[$detail[0]->id]), 0) }}</span>
                                                    @else
                                                        <span class="text-md text-gray-700 text-gray-500">$0</span>
                                                    @endif
                                                @else
                                                    @if ( number_format(($total_getPay[$detail[0]->id] - $total_Pay[$detail[0]->id] - $price), 0) > 0)
                                                        <span class="text-md text-gray-700 text-green-500">Get ${{  number_format(($total_getPay[$detail[0]->id] - $total_Pay[$detail[0]->id] - $price), 0) }}</span>
                                                    @elseif (number_format(($total_getPay[$detail[0]->id] - $total_Pay[$detail[0]->id] + $price), 0) < 0)
                                                        <span class="text-md text-gray-700 text-red-500">Pay ${{  number_format(abs($total_getPay[$detail[0]->id] - $total_Pay[$detail[0]->id] + $price), 0) }}</span>
                                                    @else
                                                        <span class="text-md text-gray-700 text-gray-500">$0</span>
                                                    @endif
                                                @endif --}}
                                            </div>
                                            <div class="col-span-1">
                                                {{-- @if ($detail[0]->id != Auth::User()->id)
                                                    @if ($total_getPay[$detail[0]->id] - $total_Pay[$detail[0]->id] > 0)
                                                        <a href="{{ route('message.show', $itinerary->group_id) }}" class="hover:text-blue-500">
                                                            <i class="fa-solid fa-comment"></i>
                                                            <p>Go to chat to request payment</p>
                                                        </a>
                                                    @elseif ($total_getPay[$detail[0]->id] - $total_Pay[$detail[0]->id] < 0)
                                                        <i class="fa-solid fa-circle-xmark text-red-500"></i>
                                                    @else
                                                        <i class="fa-solid fa-circle-check text-green-500"></i>
                                                    @endif
                                                @else --}}
                                                    {{-- @if (number_format(($total_getPay[$detail[0]->id] - $total_Pay[$detail[0]->id] + $price), 0) > 0)
                                                        <a href="{{ route('message.show', $itinerary->group_id) }}" class="hover:text-blue-500">
                                                            <i class="fa-solid fa-comment"></i> Go to chat to request payment
                                                        </a>
                                                    @elseif (number_format(($total_getPay[$detail[0]->id] - $total_Pay[$detail[0]->id] + $price), 0) < 0)
                                                        <i class="fa-solid fa-circle-xmark text-red-500"></i>
                                                    @else
                                                        <i class="fa-solid fa-circle-check text-green-500"></i>
                                                    @endif
                                                @endif --}}
                                                @if ($detail[2] - $price > 0)
                                                    <i class="fa-solid fa-circle-xmark text-red-500"></i>
                                                @else
                                                    <i class="fa-solid fa-circle-check text-green-500"></i>
                                                @endif
                                            </div>
                                            <div class="col-span-1">
                                                @if (Auth::User()->id == $detail[0]->id)
                                                    <a href="{{ route('paypal.pay', ['itinerary_id' => $itinerary->id, 'total' => $detail[2]]) }}">
                                                        <i class="fa-brands fa-cc-paypal text-blue-500 text-3xl"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="flex-row justify-center mt-3">
                            <span class="text-black font-bold text-center text-lg block">
                                The bills are already finalized. Please pay each payment by using paypal or cash.
                            </span>
                            <a href="{{ route('message.show', $itinerary->group_id) }}" class="hover:text-blue-600">
                                <span class="text-blue-500 font-bold text-center text-lg block">
                                    If you don't finish the payment, please go to chat to request payment <i class="fa-solid fa-comment"></i>
                                </span>
                            </a>
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
