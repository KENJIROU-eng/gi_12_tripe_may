<x-app-layout>
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="min-h-screen bg-gray-100 dark:bg-gray-800 py-6 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6">
                    {{-- タイトル --}}
                    <div class="text-center mb-8 border-b border-gray-300 pb-4">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100 flex justify-center items-center gap-2">
                            <i class="fa-solid fa-file-invoice-dollar text-green-500"></i>
                            Trip Payment Summary
                        </h1>
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
                                            <div class="flex gap-1 overflow-x-auto max-w-full">
                                                @foreach ($bill->billUser as $user)
                                                    <img src="{{ $user->userPaid->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg') }}"
                                                        alt="avatar"
                                                        title="{{ $user->userPaid->name }}"
                                                        class="w-7 h-7 rounded-full object-cover border border-gray-300 shadow-sm flex-shrink-0">
                                                @endforeach
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
                            @error('amount')
                                <div class="text-red-500 small text-center font-bold">{{ $message }}</div>
                            @enderror
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
                                        @if (!empty($pays[$detail[0]->id][$detail[1]->id]))
                                            {{-- 状態 --}}
                                            @if (number_format($detail[2] - $pays[$detail[0]->id][$detail[1]->id]->sum('Price'), 0) > 0)
                                                <div class="text-center text-red-500 font-semibold text-md">
                                                    ${{ number_format($detail[2] - $pays[$detail[0]->id][$detail[1]->id]->sum('Price'), 0) }}
                                                </div>
                                                <div class="text-center text-red-600">
                                                    <i class="fa-solid fa-circle-xmark"></i> Unpaid
                                                </div>
                                                {{-- Pay now（説明風バッジ） --}}
                                                <div class="text-center text-sm text-blue-600">
                                                    @if (Auth::User()->id == $detail[0]->id)
                                                        <a href="{{ route('paypal.pay', ['itinerary_id' => $itinerary->id, 'total' => $detail[2], 'user_id' => $detail[1]->id]) }}">
                                                            <i class="fa-brands fa-cc-paypal text-blue-500 text-3xl"></i>
                                                        </a>
                                                        <!-- 現金 -->
                                                        <div x-data="{ open: false, amount: '' }" class="relative">
                                                            <!-- 起動ボタン -->
                                                            <button @click="open = true"
                                                                    class="flex flex-col items-center justify-center">
                                                                <i class="fa-solid fa-money-bill text-yellow-500 text-3xl"></i>
                                                                <span class="text-xs text-yellow-500">Cash</span>
                                                            </button>
                                                            <!-- モーダル -->
                                                            <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                                                                <div class="bg-white rounded-xl p-6 w-80" @click.away="open = false">
                                                                    <!-- ヘッダー -->
                                                                    <div class="flex justify-between items-center mb-4">
                                                                        <h2 class="text-lg font-bold text-center">Enter the amount you pay</h2>
                                                                        <button @click="open = false" class="text-gray-500 hover:text-red-500 text-lg">&times;</button>
                                                                    </div>
                                                                    <!-- 入力表示 -->
                                                                    <div class="bg-gray-100 rounded-lg p-3 mb-4 text-2xl text-right font-mono tracking-wide">
                                                                        $ <span x-text="amount || 0"></span>
                                                                    </div>
                                                                    <!-- 電卓ボタン -->
                                                                    <div class="grid grid-cols-3 gap-3 mb-4">
                                                                        <template x-for="row in [[1,2,3],[4,5,6],[7,8,9]]" :key="row.toString()">
                                                                            <template x-for="n in row" :key="n">
                                                                                <button @click="amount += n"
                                                                                        class="bg-gray-200 hover:bg-gray-300 rounded-lg text-xl py-3">
                                                                                    <span x-text="n"></span>
                                                                                </button>
                                                                            </template>
                                                                        </template>
                                                                        <!-- C / 0 / ← -->
                                                                        <button @click="amount = ''" class="bg-red-200 hover:bg-red-300 rounded-lg text-xl py-3">C</button>
                                                                        <button @click="amount += '0'" class="bg-gray-200 hover:bg-gray-300 rounded-lg text-xl py-3">0</button>
                                                                        <button @click="amount = amount.slice(0, -1)" class="bg-yellow-200 hover:bg-yellow-300 rounded-lg text-xl py-3">←</button>
                                                                    </div>
                                                                    <!-- 送信 -->
                                                                    @php
                                                                        $maxAmount = number_format($detail[2] - $pays[$detail[0]->id][$detail[1]->id]->sum('Price'), 0);
                                                                    @endphp
                                                                    <form method="post" action="{{ route('goDutch.cashPay', ['itinerary_id' => $itinerary->id, 'user_id' => $detail[1]->id, 'detail' => $maxAmount]) }}">
                                                                        @csrf
                                                                        <input type="hidden" name="amount" :value="amount">
                                                                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 disabled:opacity-50"
                                                                                :disabled="!amount">
                                                                            Pay（$<span x-text="amount || 0"></span>）
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-center text-gray-500 font-semibold text-md">
                                                    ${{ number_format($detail[2] - $pays[$detail[0]->id][$detail[1]->id]->sum('Price'), 0) }}
                                                </div>
                                                <div class="text-center text-green-600">
                                                    <i class="fa-solid fa-circle-check text-green-500"></i> paid
                                                </div>
                                                {{-- Pay now（説明風バッジ） --}}
                                                <div class="text-center text-sm text-green-500">
                                                    @if (Auth::User()->id == $detail[0]->id)
                                                        <p>You already complete the payment</p>
                                                    @endif
                                                </div>
                                            @endif
                                        @else
                                            <div class="text-center text-red-500 font-semibold text-md">
                                                ${{ number_format($detail[2], 0) }}
                                            </div>
                                            {{-- 状態 --}}
                                            @if (number_format($detail[2], 0) > 0)
                                                <div class="text-center text-red-600">
                                                    <i class="fa-solid fa-circle-xmark"></i> Unpaid
                                                </div>
                                                {{-- Pay now（説明風バッジ） --}}
                                                <div class="text-center text-sm text-blue-600 flex gap-6 items-center justify-center">
                                                    @if (Auth::User()->id == $detail[0]->id)
                                                        <!-- PayPal -->
                                                        <a href="{{ route('paypal.pay', ['itinerary_id' => $itinerary->id, 'total' => $detail[2], 'user_id' => $detail[1]->id]) }}"
                                                        class="flex flex-col items-center justify-center">
                                                            <i class="fa-brands fa-cc-paypal text-blue-500 text-3xl"></i>
                                                            <span class="text-xs text-blue-500">PayPal</span>
                                                        </a>

                                                        <!-- 現金 -->
                                                        <div x-data="{ open: false, amount: '' }" class="relative">
                                                            <!-- 起動ボタン -->
                                                            <button @click="open = true"
                                                                    class="flex flex-col items-center justify-center">
                                                                <i class="fa-solid fa-money-bill text-yellow-500 text-3xl"></i>
                                                                <span class="text-xs text-yellow-500">Cash</span>
                                                            </button>
                                                            <!-- モーダル -->
                                                            <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                                                                <div class="bg-white rounded-xl p-6 w-80" @click.away="open = false">
                                                                    <!-- ヘッダー -->
                                                                    <div class="flex justify-between items-center mb-4">
                                                                        <h2 class="text-lg font-bold text-center">Enter the amount you pay</h2>
                                                                        <button @click="open = false" class="text-gray-500 hover:text-red-500 text-lg">&times;</button>
                                                                    </div>
                                                                    <!-- 入力表示 -->
                                                                    <div class="bg-gray-100 rounded-lg p-3 mb-4 text-2xl text-right font-mono tracking-wide">
                                                                        $ <span x-text="amount || 0"></span>
                                                                    </div>
                                                                    <!-- 電卓ボタン -->
                                                                    <div class="grid grid-cols-3 gap-3 mb-4">
                                                                        <template x-for="row in [[1,2,3],[4,5,6],[7,8,9]]" :key="row.toString()">
                                                                            <template x-for="n in row" :key="n">
                                                                                <button @click="amount += n"
                                                                                        class="bg-gray-200 hover:bg-gray-300 rounded-lg text-xl py-3">
                                                                                    <span x-text="n"></span>
                                                                                </button>
                                                                            </template>
                                                                        </template>
                                                                        <!-- C / 0 / ← -->
                                                                        <button @click="amount = ''" class="bg-red-200 hover:bg-red-300 rounded-lg text-xl py-3">C</button>
                                                                        <button @click="amount += '0'" class="bg-gray-200 hover:bg-gray-300 rounded-lg text-xl py-3">0</button>
                                                                        <button @click="amount = amount.slice(0, -1)" class="bg-yellow-200 hover:bg-yellow-300 rounded-lg text-xl py-3">←</button>
                                                                    </div>
                                                                    <!-- 送信 -->
                                                                    <form method="post" action="{{ route('goDutch.cashPay', ['itinerary_id' => $itinerary->id, 'user_id' => $detail[1]->id, 'detail' => number_format($detail[2], 0)]) }}">
                                                                        @csrf
                                                                        <input type="hidden" name="amount" :value="amount">
                                                                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 disabled:opacity-50"
                                                                                :disabled="!amount">
                                                                            Pay（$<span x-text="amount || 0"></span>）
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-center text-red-600">
                                                    <i class="fa-solid fa-circle-check text-green-500"></i> paid
                                                </div>
                                                <div class="text-center text-sm text-green-500">
                                                    @if (Auth::User()->id == $detail[0]->id)
                                                        <p>You already complete the payment</p>
                                                    @endif
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            {{-- Finalize ボタン --}}
                            <div class="flex justify-center mt-6">
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
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
