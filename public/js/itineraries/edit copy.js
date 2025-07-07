// グローバル変数定義
let dateFieldsContainer = document.getElementById('dateFieldsContainer'); // 日付別の目的地入力欄全体を格納する親要素
let totalSummary = document.getElementById('totalSummary'); // 画面下部に表示される総移動距離・所要時間の要素
let distanceMatrixService; // Google Maps の距離計算サービス（DistanceMatrixService）のインスタンス
let dailyDistances = {}; // 各日付ごとの合計移動距離（メートル単位）を格納する連想配列
let dailyDurations = {}; // 各日付ごとの合計所要時間（秒単位）を格納する連想配列
let draggedTravelMode = null; // 並び替え中に使用する現在ドラッグ中の移動手段名（未使用であればnull）
let draggedTravelModeValue = null; // 並び替え中に使用する現在選択中の移動手段の値（例: 'DRIVING'）
let radioCheckedStateBackup = {}; // 並び替え開始前に保存しておくラジオボタンのチェック状態（placeId をキーに保持）

// 目的地入力フィールドを1件分HTMLで生成
function createInputField(dateKey, index, address = '', lat = '', lng = '', placeId = '', placeName = '', travelMode = 'DRIVING') {
    if (index === undefined || index === null) {
        const container = document.querySelector(`[data-date="${dateKey}"] .destinations`);
        index = container ? container.querySelectorAll('.destination-item').length : 0;
    }

    const travelModes = ['DRIVING', 'MOTORCYCLE', 'WALKING', 'TRANSIT'];
    const travelModeLabels = {
        DRIVING: 'Car',
        MOTORCYCLE: 'Motorcycle',
        WALKING: 'Walk',
        TRANSIT: 'Transit'
    };

    const radioButtons = travelModes.map(mode => `
        <label class="inline-flex items-center mr-4 mb-1 text-sm whitespace-nowrap">
            <input type="radio" name="travel_modes[${dateKey}][${index}]" value="${mode}"
                ${travelMode === mode ? 'checked' : ''} class="travel-mode-radio mr-2">
            ${travelModeLabels[mode]}
        </label>
    `).join('');

    const transitWarning = `
        <div class="transit-warning text-xs text-red-500 mt-1 ${travelMode === 'TRANSIT' ? '' : 'hidden'}">
            <i class="fa-solid fa-asterisk"></i> Distance and duration may not be available for public transit.
        </div>
    `;

    return `
        <div class="destination-item mb-3">
            <div class="flex items-center gap-2">
                <span class="cursor-move drag-handle text-gray-500 text-xl w-1/12 text-center">
                    <i class="fa-solid fa-grip-lines"></i>
                </span>

                <div class="w-full flex flex-col justify-center">
                    <span class="departure-label text-xs text-blue-600 font-bold mb-1 hidden block">
                        <i class="fa-solid fa-flag-checkered text-blue-500"></i> Starting Point
                    </span>
                    <input type="text" name="destinations[${dateKey}][]" value="${address || placeName}"
                        class="p-1 border rounded destination-input w-full" placeholder="Please enter a destination" />
                </div>

                <button type="button" class="ml-auto mx-2 text-red-500 hover:text-red-700 text-xl pr-2 remove-btn flex items-center h-[38px]">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="ml-10 mt-1 flex flex-wrap md:flex-nowrap gap-2 items-center travel-mode-container">
                ${radioButtons}
                ${transitWarning}
                <span class="route-info text-sm text-gray-600 col-span-2 sm:ml-4"></span>
            </div>

            <input type="hidden" name="destinations_lat[${dateKey}][]" value="${lat}" class="destination-lat" />
            <input type="hidden" name="destinations_lng[${dateKey}][]" value="${lng}" class="destination-lng" />
            <input type="hidden" name="destinations_place_id[${dateKey}][]" value="${placeId}" class="destination-place-id" />
            <input type="hidden" name="destinations_place_name[${dateKey}][]" value="${placeName}" class="destination-place-name" />
        </div>
    `;
}

// ラジオボタンのTRANSIT警告表示制御
function handleTransitWarnings() {
    document.querySelectorAll('.travel-mode-radio').forEach(radio => {
        radio.addEventListener('change', () => {
            const container = radio.closest('.travel-mode-container');
            const warning = container.querySelector('.transit-warning');
            if (warning) {
                if (radio.value === 'TRANSIT' && radio.checked) {
                    warning.classList.remove('hidden');
                } else if (radio.checked) {
                    warning.classList.add('hidden');
                }
            }
        });
    });
}

// 現在入力中の目的地データをすべて保存
function saveCurrentDestinations() {
    const data = {};
    const dateDivs = dateFieldsContainer.children;

for (const dateDiv of dateDivs) {
    const fallbackDateKey = dateDiv.dataset.date;
    if (!data[fallbackDateKey]) data[fallbackDateKey] = [];

    const items = dateDiv.querySelectorAll('.destination-item');
    items.forEach(item => {
        const address = item.querySelector('.destination-input').value;
        const lat = item.querySelector('.destination-lat').value;
        const lng = item.querySelector('.destination-lng').value;
        const placeId = item.querySelector('.destination-place-id').value;
        const placeName = item.querySelector('.destination-place-name')?.value || '';
        const radio = item.querySelector('input.travel-mode-radio:checked');
        let travelMode = 'DRIVING';
        let dateKey = fallbackDateKey;

        if (radio) {
            travelMode = radio.value;
            const nameAttr = radio.name; // e.g. travel_modes[2025-06-13][0]
            const match = nameAttr.match(/travel_modes\[(.+?)\]\[(\d+)\]/);
            if (match) {
                dateKey = match[1];
                if (!data[dateKey]) data[dateKey] = []; // 新しい日付キーへの移動を考慮
            }
        }

        if (address) {
            data[dateKey].push({
                address,
                lat,
                lng,
                placeId,
                placeName,
                travelMode
            });
        }
    });
}
    return data;
}

// yyyy-mm-ddを『Jun. 19, 2025』形式に整形
function formatDateToDisplay(dateStr) {
    const date = new Date(dateStr);
    const options = { year: 'numeric', month: 'short', day: '2-digit' };
    const parts = date.toLocaleDateString('en-US', options).replace(',', '').split(' ');
    return `${parts[0]}. ${parts[1]}, ${parts[2]}`;
}

// 指定された日付範囲に応じて入力フィールドを再生成
async function createDateFields(startDate, endDate, existingData = {}) {
    dateFieldsContainer.innerHTML = '';
    const start = new Date(startDate);
    const end = new Date(endDate);
    const dayCount = (end - start) / (1000 * 3600 * 24) + 1;

    for (let i = 0; i < dayCount; i++) {
        const currentDate = new Date(start);
        currentDate.setDate(start.getDate() + i);
        const dateStr = currentDate.toISOString().split('T')[0];

        const dateDiv = document.createElement('div');
        dateDiv.className = 'mb-4 border-b pb-2';
        dateDiv.dataset.date = dateStr;
        dateDiv.innerHTML = `
            <h3 class="font-bold mb-2">${formatDateToDisplay(dateStr)}</h3>
            <div class="destinations sortable-container"></div>
            <button type="button" class="addDestinationBtn px-2 py-1 bg-green-500 text-white rounded">
                <i class="fa-solid fa-plus"></i> Add More Field
            </button>
            <div class="summary mt-1 text-sm text-end text-gray-600"></div>
        `;
        dateFieldsContainer.appendChild(dateDiv);

        const destinationsContainer = dateDiv.querySelector('.destinations');

        if (existingData[dateStr]) {
            for (let j = 0; j < existingData[dateStr].length; j++) {
                const dest = existingData[dateStr][j];
                let address = dest.address || "";
                let lat = dest.lat || dest.latitude || "";
                let lng = dest.lng || dest.longitude || "";
                let placeId = dest.placeId || dest.place_id || "";
                let placeName = dest.placeName || dest.place_name || "";
                let travelMode = dest.travelMode || dest.travel_mode || "DRIVING";

                const addressOrName = address || placeName || "";

                if ((!lat || !lng) && (address || placeName)) {
                    try {
                        const result = await geocodeAddress(addressOrName);
                        if (result) {
                            lat = result.lat;
                            lng = result.lng;
                            placeId = result.placeId || placeId;
                            address = result.name || address || placeName;
                            placeName = placeName || result.name;
                        }
                    } catch (e) {
                        console.warn("Geocode error:", e);
                    }
                }

                destinationsContainer.insertAdjacentHTML(
                    'beforeend',
                    createInputField(dateStr, j, address, lat, lng, placeId, placeName, travelMode)
                );
            }
        } else {
            destinationsContainer.insertAdjacentHTML(
                'beforeend',
                createInputField(dateStr)
            );
        }

        destinationsContainer.querySelectorAll('.destination-input').forEach(input => {
            attachAutocomplete(input);
        });
    }

    attachAddDestinationButtons();
    attachRemoveButtons();
    attachInputChangeEvents();
    initSortable();
    updateFirstDestinationDisplay();
    handleTransitWarnings();
}

// 住所をGoogle Geocoderで緯度経度に変換
function geocodeAddress(address) {
    return new Promise((resolve, reject) => {
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ address: address }, (results, status) => {
            if (status === "OK" && results[0]) {
                const place = results[0];
                resolve({
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng(),
                    placeId: place.place_id,
                    name: place.formatted_address
                });
            } else {
                reject(status);
            }
        });
    });
}

// inputにGoogle Autocompleteを適用する
function attachAutocomplete(input) {
    const autocomplete = new google.maps.places.Autocomplete(input, {
        fields: ['formatted_address', 'geometry', 'name', 'place_id'],
        types: ['geocode', 'establishment']
    });

    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        if (!place.geometry) {
            alert('Please select an option from the dropdown.');
            input.value = '';
            return;
        }

        const container = input.closest('.destination-item');
        if (container) {
            container.querySelector('.destination-lat').value = place.geometry.location.lat();
            container.querySelector('.destination-lng').value = place.geometry.location.lng();
            container.querySelector('.destination-place-id').value = place.place_id;
        }

        updateAllDistanceTimes();
        updateMapByCurrentInputs();
    });
}

// ＋ボタンで新しい目的地入力を追加する処理
function attachAddDestinationButtons() {
    document.querySelectorAll('.addDestinationBtn').forEach(btn => {
        btn.onclick = () => {
            const dateDiv = btn.closest('div.mb-4');
            const dateKey = dateDiv.dataset.date;
            const container = dateDiv.querySelector('.destinations');
            container.insertAdjacentHTML('beforeend', createInputField(dateKey));
            const newInput = container.lastElementChild.querySelector('.destination-input');
            attachAutocomplete(newInput);
            attachRemoveButtons();
            attachInputChangeEvents();
            handleTransitWarnings();
            updateFirstDestinationDisplay();
        };
    });
}

// ×ボタンで目的地を削除する処理
function attachRemoveButtons() {
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.onclick = () => {
            btn.closest('.destination-item').remove();
            updateAllDistanceTimes();
            updateMapByCurrentInputs();
            updateFirstDestinationDisplay();
            clearSummariesIfNoDestinations();
        };
    });
}

// 入力欄のonchangeイベントに距離再計算・地図更新を追加
function attachInputChangeEvents() {
    document.querySelectorAll('.destination-input').forEach(input => {
        input.onchange = () => {
            updateAllDistanceTimes();
            updateMapByCurrentInputs();
        };
    });
}

// Sortable.jsによる並び替え機能の初期化
function initSortable() {
    document.querySelectorAll('.sortable-container').forEach(container => {
        new Sortable(container, {
            group: 'destinations',
            handle: '.drag-handle',
            animation: 150,

    onStart: function (evt) {
        const item = evt.item;
        const checkedRadio = item.querySelector('input.travel-mode-radio:checked');
        draggedTravelModeValue = checkedRadio ? checkedRadio.value : null;

        // ラジオ状態バックアップ（place_id をキーに保存）
        radioCheckedStateBackup = {};
        document.querySelectorAll('#dateFieldsContainer > .mb-4').forEach(dateDiv => {
            const dateKey = dateDiv.dataset.date;
            radioCheckedStateBackup[dateKey] = {};

            const items = dateDiv.querySelectorAll('.destination-item');
            items.forEach(item => {
                const placeId = item.querySelector('.destination-place-id')?.value;
                const checked = item.querySelector('input.travel-mode-radio:checked');
                if (placeId) {
                    radioCheckedStateBackup[dateKey][placeId] = checked ? checked.value : null;
                }
            });
        });
    },

    onEnd: function (evt) {
        const item = evt.item;

        updateAllInputFieldNames();

        const parent = item.closest('.mb-4');
        const dateKey = parent?.dataset.date;

        if (dateKey && radioCheckedStateBackup[dateKey]) {
            const items = parent.querySelectorAll('.destination-item');

            items.forEach(item => {
                const placeId = item.querySelector('.destination-place-id')?.value;
                const valueToRestore = placeId ? radioCheckedStateBackup[dateKey][placeId] : null;
                if (!valueToRestore) return;

                item.querySelectorAll('input.travel-mode-radio').forEach(radio => {
                    radio.checked = (radio.value === valueToRestore);
                });
            });
        }

        updateAllDistanceTimes();
        updateMapByCurrentInputs();
        updateFirstDestinationDisplay();
        clearSummariesIfNoDestinations();

        draggedTravelModeValue = null;
        radioCheckedStateBackup = {};
    }

        });
    });
}

// 並び替え後にname属性を更新（サーバー側が配列で受けるため）
function updateAllInputFieldNames() {
    document.querySelectorAll('#dateFieldsContainer > .mb-4').forEach(dateDiv => {
        const dateKey = dateDiv.dataset.date;
        const items = dateDiv.querySelectorAll('.destination-item');

        items.forEach((item, index) => {
            // text input
            const input = item.querySelector('.destination-input');
            input.name = `destinations[${dateKey}][]`;

            // hidden fields
            item.querySelector('.destination-lat').name = `destinations_lat[${dateKey}][]`;
            item.querySelector('.destination-lng').name = `destinations_lng[${dateKey}][]`;
            item.querySelector('.destination-place-id').name = `destinations_place_id[${dateKey}][]`;
            item.querySelector('.destination-place-name').name = `destinations_place_name[${dateKey}][]`;

            // radio buttons
            item.querySelectorAll('input.travel-mode-radio').forEach(radio => {
                radio.name = `travel_modes[${dateKey}][${index}]`;
            });
        });
    });
}

// 現在の全目的地をもとに地図を更新する
function updateMapByCurrentInputs() {
    const latLngs = [];
    document.querySelectorAll('.destination-item').forEach(item => {
        const lat = parseFloat(item.querySelector('.destination-lat').value);
        const lng = parseFloat(item.querySelector('.destination-lng').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            latLngs.push(new google.maps.LatLng(lat, lng));
        }
    });

    if (latLngs.length >= 2) {
        updateMapRoutesByLatLngs(latLngs);
    } else {
        clearMarkers();
        directionsRenderer.set('directions', null);
    }
}

// 緯度経度からマーカーとルートを描画する
function updateMapRoutesByLatLngs(latLngs) {
    clearMarkers();
    latLngs.forEach((loc, i) => {
        addMarker(loc, `${i + 1}`);
    });
    drawRoute(latLngs);
}

// フォーム読み込み時の初期化（日付範囲など）
window.initializeCreateForm = function() {
    distanceMatrixService = new google.maps.DistanceMatrixService();
    ['start_date', 'end_date'].forEach(id => {
        document.getElementById(id).addEventListener('change', () => {
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;
            if (start && end && start <= end) {
                const saveData = saveCurrentDestinations();
                const filteredData = filterDataByDateRange(saveData, start, end);
                createDateFields(start, end, filteredData);
                updateAllDistanceTimes();
                updateMapByCurrentInputs();
            }
        });
    });

    const start = document.getElementById('start_date').value;
    const end = document.getElementById('end_date').value;
    if (start && end && start <= end) {
        const filteredData = filterDataByDateRange(window.existingData || {}, start, end);
        createDateFields(start, end, filteredData);
        updateAllDistanceTimes();
        updateMapByCurrentInputs();
    }
};

// start/end範囲外のデータを除外するフィルタ処理
function filterDataByDateRange(data, startDate, endDate) {
    const start = new Date(startDate);
    const end = new Date(endDate);
    const filtered = {};

    for (const dateKey in data) {
        const dateObj = new Date(dateKey);
        if (dateObj >= start && dateObj <= end) {
            filtered[dateKey] = data[dateKey];
        }
    }

    return filtered;
}

// Google DistanceMatrixを使って距離時間を日別・合計で計算
function updateAllDistanceTimes() {
    return new Promise((resolve) => {
        const totalSummary = document.getElementById('totalSummary');

        if (!distanceMatrixService) {
            resolve();
            return;
        }

        let totalDistance = 0;
        let totalDuration = 0;

        const dailyDistances = {};
        const dailyDurations = {};

        const itemsChrono = [];

        const dateDivs = Array.from(dateFieldsContainer.children).sort(
            (a, b) => a.dataset.date.localeCompare(b.dataset.date)
        );

        for (const dateDiv of dateDivs) {
            const date = dateDiv.dataset.date;
            const summaryEl = dateDiv.querySelector('.summary');
            const items = Array.from(dateDiv.querySelectorAll('.destination-item'));

            for (const item of items) {
                itemsChrono.push({
                    item,
                    date,
                    summaryEl
                });
            }
        }

        function getLatLng(elem) {
            const lat = parseFloat(elem.querySelector('.destination-lat').value);
            const lng = parseFloat(elem.querySelector('.destination-lng').value);
            return (!isNaN(lat) && !isNaN(lng)) ? new google.maps.LatLng(lat, lng) : null;
        }

        const perDayStats = {};
        let previousLatLng = null;
        let initialPlace = null;

        const promises = [];

        for (let i = 0; i < itemsChrono.length; i++) {
            const { item, date, summaryEl } = itemsChrono[i];
            const currentLatLng = getLatLng(item);
            if (!currentLatLng) continue;

            const checkedRadio = item.querySelector('input.travel-mode-radio:checked');
            let travelMode = checkedRadio ? checkedRadio.value : 'DRIVING';


            if (!initialPlace) {
                const placeId = item.querySelector('.destination-place-id')?.value || '';
                initialPlace = {
                    lat: currentLatLng.lat(),
                    lng: currentLatLng.lng(),
                    placeId: placeId,
                    address: item.querySelector('.destination-input')?.value || ''
                };
                const initialInput = document.getElementById('initial_place');
                if (initialInput) {
                    initialInput.value = JSON.stringify(initialPlace);
                }
            }

            if (!perDayStats[date]) {
                perDayStats[date] = {
                    distance: 0,
                    duration: 0,
                    summaryEl: summaryEl
                };
            }

            if (previousLatLng) {
                const origin = previousLatLng;
                const destination = currentLatLng;
                const info = item.querySelector('.route-info');

                // バイクはAPI上DRIVING扱い
                const modeForApi = travelMode === 'MOTORCYCLE' ? 'DRIVING' : travelMode;

                const p = new Promise((res) => {
                    distanceMatrixService.getDistanceMatrix({
                        origins: [origin],
                        destinations: [destination],
                        travelMode: google.maps.TravelMode[modeForApi] || google.maps.TravelMode.DRIVING
                    }, (response, status) => {
                        if (status === 'OK') {
                            const result = response.rows[0].elements[0];
                            if (result.status === 'OK') {
                                const distance = result.distance.value;
                                const duration = result.duration.value;

                                perDayStats[date].distance += distance;
                                perDayStats[date].duration += duration;
                                totalDistance += distance;
                                totalDuration += duration;

                                if (info) {
                                    info.textContent = `${(distance / 1000).toFixed(2)} km / ${formatDuration(duration)}`;
                                }
                            }
                            res();
                        } else {
                            console.error('DistanceMatrix status:', status);
                            res();
                        }
                    });
                });
                promises.push(p);
            }

            previousLatLng = currentLatLng;
        }

        Promise.all(promises).then(() => {
            for (const key in perDayStats) {
                const day = perDayStats[key];
                const dateDiv = document.querySelector(`[data-date="${key}"]`);
                const items = dateDiv ? dateDiv.querySelectorAll('.destination-item') : [];
                const destinationCount = items.length;

                const dKm = (day.distance / 1000).toFixed(2);
                const dDuration = formatDuration(day.duration);

                // 表示条件:
                // - 目的地が2つ以上ある
                // - または1つでも距離と時間が0でない
                if (
                    destinationCount >= 2 ||
                    (destinationCount === 1 && day.distance > 0 && day.duration > 0)
                ) {
                    day.summaryEl.textContent = `Total: ${dKm} km / ${dDuration}`;
                } else {
                    day.summaryEl.textContent = '';
                }

                dailyDistances[key] = day.distance;
                dailyDurations[key] = day.duration;
            }

            const dailyDistancesInput = document.getElementById('daily_distances');
            const dailyDurationsInput = document.getElementById('daily_durations');
            const totalDistanceInput = document.getElementById('total_distance');
            const totalDurationInput = document.getElementById('total_duration');

            if (dailyDistancesInput) dailyDistancesInput.value = JSON.stringify(dailyDistances);
            if (dailyDurationsInput) dailyDurationsInput.value = JSON.stringify(dailyDurations);
            if (totalDistanceInput) totalDistanceInput.value = totalDistance;
            if (totalDurationInput) totalDurationInput.value = totalDuration;

            if (totalSummary) {
                const totalKm = (totalDistance / 1000).toFixed(2);
                totalSummary.textContent = `Total Distance: ${totalKm} km / Total Time: ${formatDuration(totalDuration)}`;
                totalSummary.classList.remove('hidden');
            }

            updateMapByCurrentInputs();
            resolve();
        });
    });
}

function clearSummariesIfNoDestinations() {
    const dateDivs = document.querySelectorAll('#dateFieldsContainer > [data-date]');
    dateDivs.forEach(dateDiv => {
        const destinations = dateDiv.querySelectorAll('.destination-item');
        const summary = dateDiv.querySelector('.summary');
        if (destinations.length === 0 && summary) {
            summary.textContent = '';
        }
    });
}


// フォーム送信時、距離更新を待ってから送信する（submit防止＋再送信）
const form = document.querySelector('form'); // ここは正しいformセレクタに変更してください
form.addEventListener('submit', async (e) => {
    e.preventDefault(); // まず送信停止

    updateAllInputFieldNames();

    // 距離時間の更新を待ってから送信
    await updateAllDistanceTimes();

    // もう一度submit（ループ防止のため一時的にリスナー解除）
    form.removeEventListener('submit', arguments.callee);
    form.submit();
});

// 秒数を "1h 12m" 形式に変換
function formatDuration(seconds) {
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    if (h > 0 && m > 0) return `${h}h ${m}m`;
    if (h > 0) return `${h}h`;
    return `${m}m`;
}

// 最初の目的地は移動がないため、移動手段UIを非表示化
function updateFirstDestinationDisplay() {
    const allDateDivs = [...document.querySelectorAll('#dateFieldsContainer > div[data-date]')];
    let firstItem = null;

    // 最初の1件目を見つける（全体で）
    for (const dateDiv of allDateDivs) {
        const items = [...dateDiv.querySelectorAll('.destination-item')];
        if (items.length > 0) {
            firstItem = items[0];
            break;
        }
    }

    // 全てをリセット（通常目的地に戻す）
    document.querySelectorAll('.destination-item').forEach(item => {
        // travel mode UI 表示・有効化
        item.querySelectorAll('.travel-mode-radio').forEach(radio => {
            radio.disabled = false;
            if (radio.dataset.originalName) {
                radio.name = radio.dataset.originalName;
            }
        });

        const container = item.querySelector('.travel-mode-container');
        if (container) container.style.display = '';

        const routeInfo = item.querySelector('.route-info');
        if (routeInfo) routeInfo.style.display = '';

        // プレースホルダーと出発ラベルをリセット
        const input = item.querySelector('.destination-input');
        if (input) input.placeholder = 'Please enter your destination';

        const label = item.querySelector('.departure-label');
        if (label) label.classList.add('hidden');
    });

    // 最初の1件目を出発地点として処理
    if (firstItem) {
        firstItem.querySelectorAll('.travel-mode-radio').forEach(radio => {
            if (!radio.dataset.originalName) {
                radio.dataset.originalName = radio.name;
            }
            radio.disabled = true;
        });

        const container = firstItem.querySelector('.travel-mode-container');
        if (container) container.style.display = 'none';

        const routeInfo = firstItem.querySelector('.route-info');
        if (routeInfo) routeInfo.style.display = 'none';

        const input = firstItem.querySelector('.destination-input');
        if (input) input.placeholder = 'Please enter your starting point';

        const label = firstItem.querySelector('.departure-label');
        if (label) label.classList.remove('hidden');
    }
}


// === 移動手段ラジオの変更時に距離・地図を再計算 ===
document.addEventListener('change', function (e) {
    if (e.target.classList.contains('travel-mode-radio')) {
        updateAllDistanceTimes();
        updateMapByCurrentInputs();
    }
});

// DOM読み込み後にタイトル文字数カウントとグループ変更モーダル処理
document.addEventListener('DOMContentLoaded', () => {
    const groupSelect = document.getElementById('group_id');
    const groupModal = document.getElementById('groupModal');
    const cancelBtn = document.getElementById('cancelGroupChange');
    const confirmBtn = document.getElementById('confirmGroupChange');

    let previousGroupValue = groupSelect.value;
    let confirmed = false;

    groupSelect.addEventListener('focus', () => {
        previousGroupValue = groupSelect.value;
    });

    groupSelect.addEventListener('change', () => {
        const original = window.originalGroupId ?? '';
        const current = groupSelect.value;

        if (current !== original && !confirmed) {
            groupModal.classList.remove('hidden');
            groupModal.classList.add('flex');
        } else {
            previousGroupValue = current;
        }
    });

    cancelBtn.addEventListener('click', () => {
        groupSelect.value = previousGroupValue;
        groupModal.classList.remove('flex');
        groupModal.classList.add('hidden');
    });

    confirmBtn.addEventListener('click', () => {
        confirmed = true;
        previousGroupValue = groupSelect.value;
        groupModal.classList.remove('flex');
        groupModal.classList.add('hidden');
    });

    const titleInput = document.getElementById('title');
    const counter = document.getElementById('titleCharCount');
    const maxLength = titleInput.getAttribute('maxlength') || 100;

    const updateCounter = () => {
        const length = titleInput.value.length;
        counter.textContent = `${length} / ${maxLength}`;
    };

    titleInput.addEventListener('input', updateCounter);
    updateCounter(); // 初期表示
});

const scrollToTopBtn = document.getElementById('scrollToTopBtn');

// go to top
let hideTimeout;

window.addEventListener('scroll', () => {
    const isMobile = window.innerWidth < 768;
    const isAtTop = window.scrollY === 0;
    const btn = scrollToTopBtn;

    if (!btn) return;

    if (isMobile && !isAtTop) {
        btn.classList.remove('opacity-0', 'pointer-events-none');

        // 既存の非表示タイマーがあればリセット
        clearTimeout(hideTimeout);

        // 一定時間（2秒）後に自動で非表示
        hideTimeout = setTimeout(() => {
            btn.classList.add('opacity-0', 'pointer-events-none');
        }, 2000);
    } else {
        btn.classList.add('opacity-0', 'pointer-events-none');
        clearTimeout(hideTimeout);
    }
});

scrollToTopBtn?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
