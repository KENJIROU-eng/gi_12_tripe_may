<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="min-h-screen bg-gray-100 dark:bg-gray-800 py-6 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6">
                    {{-- タイトル --}}
                    <div class="relative text-center mb-8 border-b border-gray-300 pb-4">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100 flex justify-center items-center gap-2">
                            <i class="fa-solid fa-file-invoice-dollar text-green-500"></i>
                            Trip Payment Summary
                        </h1>
                        <a href="{{ route('dashboard') }}" class="absolute right-40 inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold rounded-lg shadow">
                            <i class="fa-solid fa-house mr-2"></i>
                            Add Bill
                        </a>
                        <p class="text-sm text-gray-500 mt-1">Invoice Overview</p>
                    </div>

                    {{-- 支払い履歴 --}}
                    @if (count($all_bills) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200 border border-gray-300">
                            <thead class="bg-gray-200 dark:bg-gray-700 text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-2">Payer</th>
                                    <th class="px-4 py-2">Description</th>
                                    <th class="px-4 py-2">Amount</th>
                                    <th class="px-4 py-2">Split With</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_bills as $bill)
                                    <tr class="border-t border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center space-x-2">
                                                <img src="{{ $bill->userPay->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="avatar" class="w-8 h-8 rounded-full object-cover">
                                                <span>{{ $bill->userPay->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">{{ $bill->name }}</td>
                                        <td class="px-4 py-3 text-red-500 font-semibold">${{ number_format($bill->cost, 0) }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($bill->billUser as $user)
                                                    <img src="{{ $user->userPaid->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" alt="avatar" class="w-6 h-6 rounded-full object-cover">
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            {{-- アイコンで編集・削除 --}}
                                            <div class="flex justify-center items-center space-x-2">
                                                @php
                                                    $hasEditError = $errors->has('user_pay_id_edit') || $errors->has('bill_name_edit') || $errors->has('cost_edit') || $errors->has('user_paid_id_edit');
                                                @endphp
                                                {{-- 編集ボタン --}}
                                                <div x-data="{ open: {{ $hasEditError ? 'true' : 'false' }} }">
                                                    <button
                                                        data-modal-target="modal-{{ $bill->id }}"
                                                        data-modal-toggle="modal-{{ $bill->id }}"
                                                        @click="open = true"
                                                        title="Edit"
                                                        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-200 transition"
                                                    >
                                                        <i class="fa-solid fa-pen text-blue-600 text-lg"></i>
                                                    </button>

                                                    {{-- モーダル --}}
                                                    @include('goDutch.modals.edit', [
                                                        'bill' => $bill,
                                                        'groupMembers' => $groupMembers,
                                                        'itinerary' => $itinerary
                                                    ])
                                                </div>

                                                {{-- 削除ボタン --}}
                                                <form action="{{ route('goDutch.delete', ['bill_id' => $bill->id, 'itinerary_id' => $itinerary->id]) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        title="Delete"
                                                        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-red-100 transition"
                                                    >
                                                        <i class="fa-solid fa-trash text-red-500 text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <p class="text-center text-gray-500 mt-10">No bills recorded yet.</p>
                    @endif

                    @if (count($all_bills) > 0 && !empty($details))
                        <div class="mt-10">
                            <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-100 mb-6 border-b pb-2">
                                <i class="fa-solid fa-calculator text-green-500 mr-2"></i>
                                Calculation Result for Payment
                            </h2>

                            {{-- ヘッダー（PCのみ表示） --}}
                            <div class="hidden sm:grid grid-cols-4 items-center text-center text-sm font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 py-3 rounded-t">
                                <div>Pay → Receive</div>
                                <div>Amount</div>
                                <div>Status</div>
                                <div>Pay Now</div>
                            </div>

                            {{-- 明細本体 --}}
                            <div class="space-y-4 mt-2 max-h-[300px] overflow-y-auto">
                                @foreach ($details as $detail)
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md p-4 shadow-sm">
                                        {{-- 支払人 → 受取人 --}}
                                        <div class="flex justify-center sm:justify-center gap-4 items-center">
                                            {{-- 支払人 --}}
                                            <div class="text-center">
                                                <a href="{{ route('profile.show', $detail[0]->id) }}" class="block w-10 h-10 rounded-full overflow-hidden mx-auto">
                                                    <img src="{{ $detail[0]->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" class="w-full h-full object-cover">
                                                </a>
                                                <p class="text-xs mt-1">{{ $detail[0]->name }}</p>
                                            </div>

                                            {{-- 矢印 --}}
                                            <div class="text-red-500 text-xl">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </div>

                                            {{-- 受取人 --}}
                                            <div class="text-center">
                                                <a href="{{ route('profile.show', $detail[1]->id) }}" class="block w-10 h-10 rounded-full overflow-hidden mx-auto">
                                                    <img src="{{ $detail[1]->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}" class="w-full h-full object-cover">
                                                </a>
                                                <p class="text-xs mt-1">{{ $detail[1]->name }}</p>
                                            </div>
                                        </div>

                                        {{-- 金額 --}}
                                        <div class="text-center text-red-500 font-semibold text-md">
                                            ${{ number_format($detail[2], 0) }}
                                        </div>

                                        {{-- 状態 --}}
                                        <div class="text-center text-red-600">
                                            <i class="fa-solid fa-circle-xmark"></i> Unpaid
                                        </div>

                                        {{-- Pay now（説明風バッジ） --}}
                                        <div class="text-center text-sm text-blue-600">
                                            <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">
                                                Finalize to enable PayPal
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Finalize ボタン --}}
                            <div class="flex justify-center mt-6">
                                <a href="{{ route('goDutch.finalize', $itinerary->id) }}"
                                class="bg-blue-500 text-white text-lg font-bold px-6 py-2 rounded-md hover:bg-blue-600 transition">
                                    <i class="fa-brands fa-paypal mr-2"></i>
                                    Finalize the Bills
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
