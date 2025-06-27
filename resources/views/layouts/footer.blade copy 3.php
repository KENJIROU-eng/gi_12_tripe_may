<nav x-data="{ open: false, addCountry: false }"
    class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 fixed bottom-0 left-0 right-0 z-40 shadow-inner">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-2">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-600 dark:text-gray-300">
            {{-- ステータス（ログイン中・通知） --}}
            @auth
                <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm">
                    <span class="hidden sm:inline">Logged in: <strong>{{ Auth::user()->name }}</strong></span>


                </div>
            @endauth

            {{-- ソーシャル + コピーライト + バージョン --}}
            <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-6">
                {{-- ソーシャル --}}
                <ul class="flex space-x-5 text-xl text-gray-500 dark:text-gray-300">
                    <li><a href="https://www.instagram.com/" class="hover:text-pink-500"><i class="fa-brands fa-instagram"></i></a></li>
                    <li><a href="https://www.facebook.com/" class="hover:text-blue-600"><i class="fa-brands fa-facebook"></i></a></li>
                    <li><a href="https://www.line.me/en/" class="hover:text-green-500"><i class="fa-brands fa-line"></i></a></li>
                    <li><a href="https://x.com/" class="hover:text-black dark:hover:text-white"><i class="fa-brands fa-x-twitter"></i></a></li>
                </ul>

                {{-- コピーライト & バージョン(.envから) --}}
                <p class="text-xs sm:text-sm">&copy; {{ now()->year }} TeaM GeniuS <span class="text-xs text-gray-400 dark:text-gray-500">&nbsp;{{ config('app.version') }}</span></p>
            </div>

            {{-- ユーザー追加可能な国選択＋天気表示 --}}
            <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-6 text-xs sm:text-sm">
                {{-- 天気切り替えセレクト --}}
                <form method="POST" action="{{ route('weather.setCountry') }}" class="flex items-center gap-2 w-full sm:w-auto">
                    @csrf
                    <select name="country_id" onchange="this.form.submit()" class="min-w-[10rem] w-full sm:w-60 border rounded px-2 py-1 text-sm">
                        @foreach ($allCountries as $country)
                            <option value="{{ $country->id }}" @selected(session('weather_country_id') == $country->id)>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                    @auth
                    <button type="button" @click="addCountry = true" class="text-indigo-500 hover:underline text-xs"><i class="fa-solid fa-circle-plus mr-2"></i> Add</button>
                    @endauth
                </form>

                {{-- 国追加モーダル --}}
                <div x-show="addCountry" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                    <div @click.away="addCountry = false" class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-xl w-full max-w-sm space-y-4">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Add New Country</h2>

                        <form method="POST" action="{{ route('countries.store') }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-200"><span class="text-red-600">*</span> Country name (e.g. Japan, United States)</label>
                                <input name="name" id="countryName" required class="w-full px-3 py-2 border rounded dark:bg-gray-800 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-200"><span class="text-red-600">*</span> Code (e.g. jp, us)</label>
                                <input name="code" id="countryCode" required class="w-full px-3 py-2 border rounded dark:bg-gray-800 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-200"><span class="text-red-600">*</span> City name (e.g. Tokyo, New York)</label>
                                <input name="city" required pattern="[A-Za-z\s]+" title="Please enter city names in English (e.g. Tokyo, New York)" placeholder="Please enter city in English (e.g. Tokyo)" class="w-full px-3 py-2 border rounded dark:bg-gray-800 dark:text-white">
                            </div>
                            <div class="flex justify-end space-x-2 pt-2">
                                <button type="button" @click="addCountry = false" class="text-sm text-gray-500 hover:underline">Cancel</button>
                                <button type="submit" class="text-sm bg-indigo-600 text-white px-4 py-1.5 rounded hover:bg-indigo-700">Add Country</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- 天気表示 --}}
                <span id="weatherPlaceholder" class="flex items-center gap-1 text-gray-600 dark:text-gray-300">
                    <i class="fa-solid fa-spinner fa-spin"></i> Weather loading...
                </span>
            </div>

        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const countryNameInput = document.getElementById('countryName');
                const countryCodeInput = document.getElementById('countryCode');

                const countryMap = {"Japan":"jp","United States":"us","Canada":"ca","United Kingdom":"gb","France":"fr","Germany":"de","Italy":"it","Spain":"es","Portugal":"pt","Netherlands":"nl","Belgium":"be","Switzerland":"ch","Austria":"at","Sweden":"se","Norway":"no","Denmark":"dk","Finland":"fi","Ireland":"ie","Russia":"ru","Poland":"pl","Czech Republic":"cz","Hungary":"hu","Greece":"gr","Turkey":"tr","Israel":"il","Saudi Arabia":"sa","United Arab Emirates":"ae","Egypt":"eg","South Africa":"za","Morocco":"ma","Nigeria":"ng","Kenya":"ke","India":"in","Pakistan":"pk","Bangladesh":"bd","Sri Lanka":"lk","Nepal":"np","Thailand":"th","Vietnam":"vn","Malaysia":"my","Singapore":"sg","Indonesia":"id","Philippines":"ph","Cambodia":"kh","Laos":"la","Myanmar":"mm","China":"cn","Taiwan":"tw","Hong Kong":"hk","South Korea":"kr","Mongolia":"mn","Australia":"au","New Zealand":"nz","Mexico":"mx","Brazil":"br","Argentina":"ar","Chile":"cl","Peru":"pe","Colombia":"co","Venezuela":"ve","Panama":"pa","Cuba":"cu","Jamaica":"jm","Guatemala":"gt","Ukraine":"ua","Croatia":"hr","Serbia":"rs","Romania":"ro","Bulgaria":"bg","Estonia":"ee","Latvia":"lv","Lithuania":"lt","Iceland":"is","Kazakhstan":"kz","Iran":"ir","Iraq":"iq","Syria":"sy","Jordan":"jo","Lebanon":"lb","Qatar":"qa","Bahrain":"bh","Oman":"om","Kuwait":"kw","Algeria":"dz","Tunisia":"tn","Libya":"ly","Ethiopia":"et","Uganda":"ug","Tanzania":"tz","Zimbabwe":"zw","Angola":"ao","Zambia":"zm","Mozambique":"mz","Sudan":"sd","Cameroon":"cm","Senegal":"sn","Ghana":"gh","Ivory Coast":"ci","Republic of the Congo":"cg","Democratic Republic of the Congo":"cd","Madagascar":"mg","Paraguay":"py","Uruguay":"uy","Bolivia":"bo","Ecuador":"ec","Suriname":"sr","Guyana":"gy"};

                countryNameInput.addEventListener('input', function () {
                    const name = countryNameInput.value.trim();
                    const code = countryMap[name] ?? '';
                    countryCodeInput.value = code;
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', async () => {
                const placeholder = document.getElementById('weatherPlaceholder');

                try {
                    const res = await fetch('/api/weather');
                    const data = await res.json();

                    if (data && data.temp && data.icon) {
                        placeholder.innerHTML = `<img src="${data.icon}" alt="icon" class="w-4 h-4"> ${data.temp}℃`;
                    } else {
                        placeholder.innerText = 'No weather data';
                    }
                } catch (e) {
                    console.error(e);
                    placeholder.innerHTML = `<span class="text-red-500">Failed to load</span>`;
                }
            });

        </script>
    @endpush

</nav>
