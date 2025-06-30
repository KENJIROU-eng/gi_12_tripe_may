<nav x-data="footerNav()" class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 fixed bottom-0 left-0 right-0 z-40 shadow-inner">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-2">

        {{-- モバイル表示用トグルボタン --}}
        <div class="sm:hidden flex justify-center">
            <button @click="open = !open" class="text-gray-600 dark:text-gray-300 focus:outline-none">
                <i :class="open ? 'fa-solid fa-chevron-down' : 'fa-solid fa-chevron-up'"></i>
            </button>
        </div>

        {{-- フッター本体 --}}
        <div :class="{'max-h-0 overflow-hidden opacity-0': !open && window.innerWidth < 640, 'opacity-100': open || window.innerWidth >= 640}" class="transition-all duration-300 ease-in-out flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600 dark:text-gray-300">

            {{-- ログイン情報 --}}
            @auth
                <div class="flex-1 flex justify-start sm:justify-start items-center">
                    <span class="block sm:inline text-center sm:text-left">Logged in: <strong>{{ Auth::user()->name }}</strong></span>
                </div>
            @endauth

            {{-- ソーシャル + コピーライト --}}
            <div class="flex-1 flex flex-col sm:flex-row items-center justify-center gap-6 sm:gap-6">
                {{-- ソーシャル --}}
                <ul class="flex space-x-5 text-xl text-gray-500 dark:text-gray-300 sm:flex">
                    <li><a href="https://www.instagram.com/" class="hover:text-pink-500"><i class="fa-brands fa-instagram"></i></a></li>
                    <li><a href="https://www.facebook.com/" class="hover:text-blue-600"><i class="fa-brands fa-facebook"></i></a></li>
                    <li><a href="https://www.line.me/en/" class="hover:text-green-500"><i class="fa-brands fa-line"></i></a></li>
                    <li><a href="https://x.com/" class="hover:text-black dark:hover:text-white"><i class="fa-brands fa-x-twitter"></i></a></li>
                </ul>

                {{-- コピーライト（常に表示） --}}
                <p class="text-xs sm:text-sm text-center sm:text-left w-full sm:w-auto whitespace-nowrap">
                    &copy; {{ now()->year }} TeaM GeniuS
                    <span class="text-xs text-gray-400 dark:text-gray-500">&nbsp;{{ config('app.version') }}</span>
                </p>

            </div>

            {{-- 国選択＋天気表示 --}}
            <div class="flex-1 flex sm:flex-row flex-col justify-end sm:justify-end items-center ms-8 gap-4 sm:gap-2">
                <form method="POST" action="{{ route('weather.setCountry') }}" class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full sm:w-auto">
                    @csrf
                    @php
                        $selectedCountryId = old('country_id') ?? request()->cookie('weather_country_id');
                    @endphp

                    <select name="country_id" onchange="this.form.submit()" class="min-w-[10rem] w-full sm:w-60 border rounded px-2 py-1 text-sm" required>
                        @if ($myCountries->isEmpty())
                            <option value="" disabled selected>Add country first</option>
                        @else
                            <option value="" disabled {{ $selectedCountryId ? '' : 'selected' }}>
                                Select country
                            </option>
                            @foreach ($myCountries as $country)
                                <option value="{{ $country->id }}" {{ $selectedCountryId == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>



                    @auth
                        <button type="button" @click="addCountry = true"
                            class="whitespace-nowrap text-indigo-500 hover:underline text-xs ms-4">
                            <i class="fa-solid fa-circle-plus mr-2"></i>Add New Country
                        </button>
                        <button type="button" @click="viewMyCountries = true"
                            class="whitespace-nowrap text-indigo-500 hover:underline text-xs ms-4">
                            <i class="fa-solid fa-globe mr-2"></i>My Countries
                        </button>
                    @endauth
                    {{-- My Countries モーダル --}}
                    <div x-show="viewMyCountries" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                        <div @click.away="viewMyCountries = false"
                            class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-xl w-full max-w-sm space-y-4">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">My Countries</h2>

                            @if ($myCountries->isEmpty())
                                <p class="text-sm text-gray-500 dark:text-gray-400">You haven't added any countries yet.</p>
                            @else
                                <ul class="space-y-2 max-h-64 overflow-y-auto">
                                    @foreach ($myCountries as $country)
                                        <li data-country-id="{{ $country->id }}"
                                            class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 px-3 py-2 rounded text-sm">
                                            <span>{{ $country->name }}</span>
                                            <button @click="deleteCountryInstant({{ $country->id }})"
                                                    class="text-red-500 hover:text-red-700">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="flex justify-end pt-4">
                                <button @click="viewMyCountries = false" class="text-sm text-gray-500 hover:underline">Close</button>
                            </div>
                        </div>
                    </div>
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
                                    <label class="block text-sm text-gray-700 dark:text-gray-200">
                                        <span class="text-red-600">*</span> City name
                                    </label>
                                    <select name="city" id="citySelect" required class="w-full px-3 py-2 border rounded dark:bg-gray-800 dark:text-white">
                                        <option value="">Select a country first</option>
                                    </select>
                                </div>

                                <div class="flex justify-end space-x-2 pt-2">
                                    <button type="button" @click="addCountry = false" class="text-sm text-gray-500 hover:underline">Cancel</button>
                                    <button type="submit" class="text-sm bg-indigo-600 text-white px-4 py-1.5 rounded hover:bg-indigo-700">Add New Country</button>
                                </div>
                            </form>
                        </div>
                    </div>

                {{-- 天気表示 --}}
                <span id="weatherPlaceholder" class="flex items-center gap-1 text-gray-600 dark:text-gray-300 ms-4 whitespace-nowrap">
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
                const citySelect = document.getElementById('citySelect');

                const countryMap = {"Japan":"jp","United States":"us","Canada":"ca","United Kingdom":"gb","France":"fr","Germany":"de","Italy":"it","Spain":"es","Portugal":"pt","Netherlands":"nl","Belgium":"be","Switzerland":"ch","Austria":"at","Sweden":"se","Norway":"no","Denmark":"dk","Finland":"fi","Ireland":"ie","Russia":"ru","Poland":"pl","Czech Republic":"cz","Hungary":"hu","Greece":"gr","Turkey":"tr","Israel":"il","Saudi Arabia":"sa","United Arab Emirates":"ae","Egypt":"eg","South Africa":"za","Morocco":"ma","Nigeria":"ng","Kenya":"ke","India":"in","Pakistan":"pk","Bangladesh":"bd","Sri Lanka":"lk","Nepal":"np","Thailand":"th","Vietnam":"vn","Malaysia":"my","Singapore":"sg","Indonesia":"id","Philippines":"ph","Cambodia":"kh","Laos":"la","Myanmar":"mm","China":"cn","Taiwan":"tw","Hong Kong":"hk","South Korea":"kr","Mongolia":"mn","Australia":"au","New Zealand":"nz","Mexico":"mx","Brazil":"br","Argentina":"ar","Chile":"cl","Peru":"pe","Colombia":"co","Venezuela":"ve","Panama":"pa","Cuba":"cu","Jamaica":"jm","Guatemala":"gt","Ukraine":"ua","Croatia":"hr","Serbia":"rs","Romania":"ro","Bulgaria":"bg","Estonia":"ee","Latvia":"lv","Lithuania":"lt","Iceland":"is","Kazakhstan":"kz","Iran":"ir","Iraq":"iq","Syria":"sy","Jordan":"jo","Lebanon":"lb","Qatar":"qa","Bahrain":"bh","Oman":"om","Kuwait":"kw","Algeria":"dz","Tunisia":"tn","Libya":"ly","Ethiopia":"et","Uganda":"ug","Tanzania":"tz","Zimbabwe":"zw","Angola":"ao","Zambia":"zm","Mozambique":"mz","Sudan":"sd","Cameroon":"cm","Senegal":"sn","Ghana":"gh","Ivory Coast":"ci","Republic of the Congo":"cg","Democratic Republic of the Congo":"cd","Madagascar":"mg","Paraguay":"py","Uruguay":"uy","Bolivia":"bo","Ecuador":"ec","Suriname":"sr","Guyana":"gy"};

                const countryCityMap={"Japan":["Tokyo","Osaka","Kyoto","Aichi","Kumamoto"],"United States":["New York","Los Angeles","Chicago"],"Canada":["Toronto","Vancouver","Montreal"],"United Kingdom":["London","Manchester","Edinburgh"],"Australia":["Sydney","Melbourne","Brisbane"],"Germany":["Berlin","Munich","Hamburg"],"France":["Paris","Lyon","Marseille"],"Italy":["Rome","Milan","Venice"],"Spain":["Madrid","Barcelona","Valencia"],"Mexico":["Mexico City","Cancun","Guadalajara"],"Brazil":["São Paulo","Rio de Janeiro","Brasília"],"Argentina":["Buenos Aires","Córdoba","Rosario"],"South Africa":["Johannesburg","Cape Town","Durban"],"Russia":["Moscow","Saint Petersburg","Novosibirsk"],"China":["Beijing","Shanghai","Guangzhou"],"India":["Mumbai","Delhi","Bangalore"],"Indonesia":["Jakarta","Bali","Surabaya"],"South Korea":["Seoul","Busan","Incheon"],"Turkey":["Istanbul","Ankara","Izmir"],"Netherlands":["Amsterdam","Rotterdam","The Hague"],"Switzerland":["Zurich","Geneva","Basel"],"Sweden":["Stockholm","Gothenburg","Malmö"],"Norway":["Oslo","Bergen","Trondheim"],"Denmark":["Copenhagen","Aarhus","Odense"],"Finland":["Helsinki","Turku","Tampere"],"Belgium":["Brussels","Antwerp","Ghent"],"Austria":["Vienna","Salzburg","Graz"],"Portugal":["Lisbon","Porto","Faro"],"Greece":["Athens","Thessaloniki","Heraklion"],"Poland":["Warsaw","Krakow","Wroclaw"],"Czech Republic":["Prague","Brno","Ostrava"],"Hungary":["Budapest","Debrecen","Szeged"],"Ireland":["Dublin","Cork","Galway"],"New Zealand":["Auckland","Wellington","Christchurch"],"Chile":["Santiago","Valparaiso","Concepción"],"Colombia":["Bogotá","Medellín","Cali"],"Peru":["Lima","Cusco","Arequipa"],"Egypt":["Cairo","Alexandria","Giza"],"United Arab Emirates":["Dubai","Abu Dhabi","Sharjah"],"Saudi Arabia":["Riyadh","Jeddah","Dammam"],"Israel":["Tel Aviv","Jerusalem","Haifa"],"Thailand":["Bangkok","Chiang Mai","Phuket"],"Vietnam":["Hanoi","Ho Chi Minh City","Da Nang"],"Malaysia":["Kuala Lumpur","George Town","Malacca"],"Singapore":["Singapore"],"Philippines":["Manila","Cebu","Davao"],"Pakistan":["Karachi","Lahore","Islamabad"],"Bangladesh":["Dhaka","Chittagong","Sylhet"],"Sri Lanka":["Colombo","Kandy","Galle"],"Nepal":["Kathmandu","Pokhara","Biratnagar"],"Kazakhstan":["Almaty","Nur-Sultan","Shymkent"],"Iran":["Tehran","Mashhad","Isfahan"],"Iraq":["Baghdad","Erbil","Basra"],"Morocco":["Casablanca","Marrakesh","Rabat"],"Kenya":["Nairobi","Mombasa","Kisumu"],"Uganda":["Kampala","Entebbe","Jinja"],"Tanzania":["Dar es Salaam","Zanzibar","Dodoma"],"Ethiopia":["Addis Ababa","Dire Dawa","Gondar"],"Nigeria":["Lagos","Abuja","Port Harcourt"],"Ghana":["Accra","Kumasi","Tamale"],"Cameroon":["Yaounde","Douala","Garoua"],"Senegal":["Dakar","Saint-Louis","Thiès"],"Algeria":["Algiers","Oran","Constantine"],"Tunisia":["Tunis","Sousse","Gabes"],"Libya":["Tripoli","Benghazi","Misurata"],"Zimbabwe":["Harare","Bulawayo","Mutare"],"Zambia":["Lusaka","Ndola","Kitwe"],"Mozambique":["Maputo","Beira","Nampula"],"Angola":["Luanda","Huambo","Lubango"],"Sudan":["Khartoum","Omdurman","Port Sudan"],"Iceland":["Reykjavik","Akureyri","Keflavik"],"Croatia":["Zagreb","Split","Dubrovnik"],"Serbia":["Belgrade","Novi Sad","Niš"],"Romania":["Bucharest","Cluj-Napoca","Timisoara"],"Bulgaria":["Sofia","Plovdiv","Varna"],"Estonia":["Tallinn","Tartu","Narva"],"Latvia":["Riga","Daugavpils","Liepaja"],"Lithuania":["Vilnius","Kaunas","Klaipeda"],"Slovenia":["Ljubljana","Maribor","Koper"],"Slovakia":["Bratislava","Košice","Prešov"],"Ukraine":["Kyiv","Lviv","Odessa"],"Belarus":["Minsk","Gomel","Vitebsk"],"Moldova":["Chișinău","Tiraspol","Bălți"],"Georgia":["Tbilisi","Batumi","Kutaisi"],"Armenia":["Yerevan","Gyumri","Vanadzor"],"Azerbaijan":["Baku","Ganja","Sumqayit"],"Paraguay":["Asunción","Ciudad del Este","Encarnación"],"Uruguay":["Montevideo","Salto","Paysandú"],"Bolivia":["La Paz","Santa Cruz","Cochabamba"],"Ecuador":["Quito","Guayaquil","Cuenca"],"Suriname":["Paramaribo","Nieuw Nickerie","Moengo"],"Guyana":["Georgetown","Linden","New Amsterdam"]};

                countryNameInput.addEventListener('input', function () {
                    // タイトルケースに変換 (e.g. "japan" -> "Japan", "united states" -> "United States")
                    const formatted = this.value
                        .toLowerCase()
                        .replace(/\b\w/g, char => char.toUpperCase());
                    this.value = formatted;

                    const name = formatted;
                    const code = countryMap[name] ?? '';
                    countryCodeInput.value = code;

                    // 都市セレクトを更新
                    const cities = countryCityMap[name] ?? [];
                    citySelect.innerHTML = '';

                    if (cities.length > 0) {
                        const placeholderOption = document.createElement('option');
                        placeholderOption.textContent = 'Choose a city';
                        placeholderOption.disabled = true;
                        placeholderOption.selected = true;
                        citySelect.appendChild(placeholderOption);

                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city;
                            option.textContent = city;
                            citySelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.textContent = 'No city data';
                        option.disabled = true;
                        option.selected = true;
                        citySelect.appendChild(option);
                    }
                });

                // 都市選択で国名欄を「国名 (都市名)」に更新
                citySelect.addEventListener('change', function () {
                    const country = countryNameInput.value.trim();
                    const city = this.value;
                    if (country && city) {
                        countryNameInput.value = `${country} (${city})`;
                    }
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', async () => {
                const placeholder = document.getElementById('weatherPlaceholder');

                const select = document.querySelector('select[name="country_id"]');
                const selectedCountryId = select?.value;

                if (!selectedCountryId) {
                    placeholder.innerText = 'No country selected';
                    return;
                }

                try {
                    const res = await fetch(`/api/weather?country_id=${selectedCountryId}`);
                    const data = await res.json();

                    if (data && typeof data.temp === 'number' && data.icon) {
                        placeholder.innerHTML = `<img src="${data.icon}" alt="icon" class="w-4 h-4"> ${data.temp}℃`;
                    } else if (data.error) {
                        placeholder.innerText = 'No weather data';
                    } else {
                        placeholder.innerText = 'Unknown response';
                    }
                } catch (e) {
                    console.error(e);
                    placeholder.innerHTML = `<span class="text-red-500">Failed to load</span>`;
                }
            });
        </script>
        <script>
            function footerNav() {
                return {
                    open: window.innerWidth >= 640,
                    addCountry: false,
                    viewMyCountries: false,

                    async deleteCountryInstant(id) {
                        try {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            const res = await fetch(`/countries/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                },
                            });

                            if (res.ok) {
                                document.querySelector(`[data-country-id="${id}"]`)?.remove();
                            } else {
                                const data = await res.json();
                                alert(data.message ?? 'Failed to delete.');
                            }
                        } catch (e) {
                            console.error(e);
                            alert('An error occurred.');
                        }
                    },

                    init() {
                        window.addEventListener('resize', () => {
                            this.open = window.innerWidth >= 640;
                        });
                    }
                }
            }
        </script>
    @endpush

</nav>
