// グローバル変数の定義
let dateFieldsContainer = document.getElementById('dateFieldsContainer'); // 日付ごとの入力欄を格納する親要素
let totalSummary = document.getElementById('totalSummary'); // 全体の距離/所要時間表示
let distanceMatrixService; // Googleの距離計算サービス
let dailyDistances = {}; // 各日付ごとの合計距離
let dailyDurations = {}; // 各日付ごとの合計所要時間
const destinationCounts = {}; // 各日付ごとの目的地数

// 目的地の入力フィールドを生成
function createInputField(dateKey, index, address = '', lat = '', lng = '', placeId = '', placeName = '', travelMode = 'DRIVING', isFirst = false) {
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
            ※ Distance and duration may not be available for public transit.
        </div>
    `;

    const startLabel = isFirst
        ? `<div class="text-sm text-blue-600 font-semibold mb-1 flex items-center gap-1">
           </div>`
        : '';

    return `
            <div class="destination-item mb-3 ${isFirst ? 'bg-blue-50 border-l-4 border-blue-400 pl-2' : ''}">
                ${startLabel}
                <div class="flex items-center gap-2">
                    <span class="cursor-move drag-handle text-gray-500 text-xl w-1/12 text-center">
                        <i class="fa-solid fa-grip-lines"></i>
                    </span>
                    <input type="text" name="destinations[${dateKey}][]" value="${address || placeName}"
                        class="p-1 border rounded destination-input w-4/5"
                        placeholder="${isFirst ? 'Please enter your departure point' : 'Please enter your destination'}" />
                    <button type="button" class="ml-auto mx-2 text-red-500 hover:text-red-700 text-xl pr-2 remove-btn">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="ml-10 mt-1 grid grid-cols-2 sm:grid-cols-1 md:flex flex-wrap items-center gap-2 travel-mode-container">
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

// 現在の目的地データを日付ごとにまとめて保存する
function saveCurrentDestinations() {
    const data = {};
    const dateDivs = dateFieldsContainer.children;
    for (const dateDiv of dateDivs) {
        const dateKey = dateDiv.dataset.date;
        data[dateKey] = [];
        const items = dateDiv.querySelectorAll('.destination-item');
        items.forEach(item => {
            const address = item.querySelector('.destination-input').value;
            const lat = item.querySelector('.destination-lat').value;
            const lng = item.querySelector('.destination-lng').value;
            const placeId = item.querySelector('.destination-place-id').value;
            const checkedRadio = item.querySelector('input[name^="travel_modes"]:checked');
            const travelMode = checkedRadio ? checkedRadio.value : 'DRIVING';

            if (address) {
                data[dateKey].push({ address, lat, lng, placeId, travelMode });
            }
        });
    }
    return data;
}

// TRANSIT選択時の警告表示制御
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

// 日付文字列を UI 表示用に整形
function formatDateToDisplay(dateStr) {
    const date = new Date(dateStr);
    const options = { year: 'numeric', month: 'short', day: '2-digit' };
    const parts = date.toLocaleDateString('en-US', options).replace(',', '').split(' ');
    // parts[0] = 'Jun', parts[1] = '05', parts[2] = '2025'
    return `${parts[0]}. ${parts[1]}, ${parts[2]}`;
}

// 指定された開始・終了日付で日付ごとの入力欄を生成（復元も）
function createDateFields(startDate, endDate, existingData = {}) {
    dateFieldsContainer.innerHTML = '';
    const start = new Date(startDate);
    const end = new Date(endDate);
    const dayCount = (end - start) / (1000 * 3600 * 24) + 1;

    let isFirstDestination = true;

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
            <button type="button" class="addDestinationBtn px-2 py-1 bg-green-500 text-white rounded"><i class="fa-solid fa-plus"></i> Add More Field</button>
            <div class="summary mt-1 text-sm text-end text-gray-600"></div>
        `;
        dateFieldsContainer.appendChild(dateDiv);

        const destinationsContainer = dateDiv.querySelector('.destinations');

        // 既存データがあれば復元
        if (existingData[dateStr]) {
            existingData[dateStr].forEach((dest, j) => {
                destinationsContainer.insertAdjacentHTML(
                    'beforeend',
                    createInputField(
                        dateStr, j,
                        dest.address, dest.lat, dest.lng, dest.placeId, dest.placeName, dest.travelMode || 'DRIVING',
                        isFirstDestination
                    )
                );
                isFirstDestination = false;
            });
        } else {
            destinationsContainer.insertAdjacentHTML(
                'beforeend',
                createInputField(dateStr, 0, '', '', '', '', '', 'DRIVING', isFirstDestination)
            );
            isFirstDestination = false;
        }

        // オートコンプリート設定
        destinationsContainer.querySelectorAll('.destination-input').forEach(input => {
            attachAutocomplete(input);
        });
    }

    attachAddDestinationButtons();
    attachRemoveButtons();
    attachInputChangeEvents();
    initSortable();
    updateAllInputFieldNames();
    updateFirstDestinationDisplay();
    handleTransitWarnings();
}

// Googleオートコンプリート設定
function attachAutocomplete(input) {
    const autocomplete = new google.maps.places.Autocomplete(input, {
        fields: ['formatted_address', 'geometry', 'name', 'place_id'],
        types: ['geocode', 'establishment']
    });

    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        console.log('Place ID:', place.place_id);

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

// +ボタンで目的地を追加するボタンの処理
function attachAddDestinationButtons() {
    document.querySelectorAll('.addDestinationBtn').forEach(btn => {
        btn.onclick = () => {
            const dateDiv = btn.closest('div.mb-4');
            const dateKey = dateDiv.dataset.date;
            const container = dateDiv.querySelector('.destinations');

            if (!destinationCounts[dateKey]) destinationCounts[dateKey] = container.children.length;

            const index = destinationCounts[dateKey]++;
            container.insertAdjacentHTML('beforeend', createInputField(dateKey, index));

            const newInput = container.lastElementChild.querySelector('.destination-input');
            attachAutocomplete(newInput);
            attachRemoveButtons();
            attachInputChangeEvents();
            attachTravelModeChangeEvents();
            updateFirstDestinationDisplay();
            updateAllInputFieldNames();
        };
    });
}

// 削除ボタンのイベント設定
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

// 入力変更時にルート情報更新するイベント
function attachInputChangeEvents() {
    document.querySelectorAll('.destination-input, .travel-mode-select').forEach(el => {
        el.onchange = () => {
            updateAllDistanceTimes();
            updateMapByCurrentInputs();
        };
    });
}

// ラジオボタンの移動手段変更を検知
function attachTravelModeChangeEvents() {
    document.querySelectorAll('.travel-mode-radio').forEach(radio => {
        // バインドを防ぐために remove → add ではなく、直接 addEventListener はOK
        radio.addEventListener('change', () => {
            console.log('[DEBUG] travelMode changed:', radio.value);
            updateAllDistanceTimes();
            updateMapByCurrentInputs();
        });
    });
}

// 並べ替え用Sortable.js 初期化
function initSortable() {
    document.querySelectorAll('.sortable-container').forEach(container => {
        new Sortable(container, {
            group: 'destinations',
            handle: '.drag-handle',
            animation: 150,

            onStart: function (evt) {
                const item = evt.item;
                const checkedRadio = item.querySelector('input.travel-mode-radio:checked');
                item.dataset.prevTravelMode = checkedRadio ? checkedRadio.value : '';

                // 全ての目的地の checked 状態を記録
                window.radioCheckedBackup = [];
                document.querySelectorAll('.destination-item').forEach(item => {
                    const checked = item.querySelector('input.travel-mode-radio:checked');
                    window.radioCheckedBackup.push(checked ? checked.value : null);
                });
            },

            onEnd: function (evt) {
                const item = evt.item;
                updateAllInputFieldNames(); // ← ここで name が変わると checked 状態が消える

                // 🔽 name が変わったあとに正確に checked を復元する
                const allItems = [...document.querySelectorAll('.destination-item')];
                if (window.radioCheckedBackup && window.radioCheckedBackup.length === allItems.length) {
                    allItems.forEach((item, i) => {
                        const mode = window.radioCheckedBackup[i];
                        if (mode) {
                            const radio = item.querySelector(`input.travel-mode-radio[value="${mode}"]`);
                            if (radio) radio.checked = true;
                        }
                    });
                }

                updateAllDistanceTimes();
                updateMapByCurrentInputs();
                updateFirstDestinationDisplay();
                clearSummariesIfNoDestinations(); 
            }
        });
    });
}

// 現在入力されている地点に基づいて地図を更新
function updateMapByCurrentInputs() {
    const latLngs = [];
    document.querySelectorAll('.destination-item').forEach(item => {
        const lat = parseFloat(item.querySelector('.destination-lat').value);
        const lng = parseFloat(item.querySelector('.destination-lng').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            latLngs.push(new google.maps.LatLng(lat, lng));
        }
    });

    clearMarkers(); // 常に既存ピンをクリア

    if (latLngs.length === 1) {
        // 1地点だけ → ピンだけ表示
        addMarker(latLngs[0], "1");

        //ルート表示は消す
        directionsRenderer.set('directions', null);
    } else if (latLngs.length >= 2) {
        // 2地点以上 → ルート＋ピンを描画
        updateMapRoutesByLatLngs(latLngs);
    } else {
        // 入力なし → ルートもマーカーもすべて消す
        directionsRenderer.set('directions', null);
    }
}


// 指定LatLngリストに基づいてマップ描画（ルート）
function updateMapRoutesByLatLngs(latLngs) {
    clearMarkers();
    latLngs.forEach((loc, i) => {
        addMarker(loc, `${i + 1}`);
    });
    drawRoute(latLngs);
}

// 所要時間（秒）を h/m 表記に変換
function formatDuration(seconds) {
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    if (h > 0 && m > 0) return `${h}h ${m}m`;
    if (h > 0) return `${h}h`;
    return `${m}m`;
}

// ページ初期化時の処理（start_date/end_date変更にも対応）
window.initializeCreateForm = function() {
    distanceMatrixService = new google.maps.DistanceMatrixService();
    ['start_date', 'end_date'].forEach(id => {
        document.getElementById(id).addEventListener('change', () => {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        if (start && end && start <= end) {

            const saveData = saveCurrentDestinations();
            createDateFields(start, end, saveData);
            attachTravelModeChangeEvents();
            updateAllDistanceTimes();
            updateMapByCurrentInputs();
        }
        });
    });

    const start = document.getElementById('start_date').value;
    const end = document.getElementById('end_date').value;
    if (start && end && start <= end) {
        createDateFields(start, end);
        attachTravelModeChangeEvents();
    }
};

// すべての目的地を対象に距離/時間をGoogle APIで取得して表示
function updateAllDistanceTimes() {
    if (!distanceMatrixService) return;

    let totalDistance = 0;
    let totalDuration = 0;
    let previousLatLng = null;

    dailyDistances = {};
    dailyDurations = {};
    let initialPlace = null;

    const itemsChrono = [];

    const dateDivs = Array.from(dateFieldsContainer.children);
    dateDivs.sort((a, b) => a.dataset.date.localeCompare(b.dataset.date));
    for (const dateDiv of dateDivs) {
        const date = dateDiv.dataset.date;
        const summaryEl = dateDiv.querySelector('.summary');
        const items = Array.from(dateDiv.querySelectorAll('.destination-item'));
        for (const item of items) {
            itemsChrono.push({ item, date, summaryEl });
        }
    }

    function getLatLng(elem) {
        const lat = parseFloat(elem.querySelector('.destination-lat').value);
        const lng = parseFloat(elem.querySelector('.destination-lng').value);
        return (!isNaN(lat) && !isNaN(lng)) ? new google.maps.LatLng(lat, lng) : null;
    }

    const perDayStats = {};
    let pending = 0;

    for (let i = 0; i < itemsChrono.length; i++) {
        const { item, date, summaryEl } = itemsChrono[i];
        const currentLatLng = getLatLng(item);
        if (!currentLatLng) continue;

        if (!initialPlace) {
            const placeId = item.querySelector('.destination-place-id').value;
            initialPlace = {
                lat: currentLatLng.lat(),
                lng: currentLatLng.lng(),
                placeId: placeId || '',
                address: item.querySelector('.destination-input').value || ''
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

            // travelMode の取得
            const checkedRadio = item.querySelector('input[name^="travel_modes"]:checked');
            let travelMode = checkedRadio ? checkedRadio.value.toUpperCase() : 'DRIVING';

            if (travelMode === 'MOTORCYCLE') travelMode = 'DRIVING'; // fallback

            pending++;
            distanceMatrixService.getDistanceMatrix({
                origins: [origin],
                destinations: [destination],
                travelMode: google.maps.TravelMode[travelMode],
            }, (response, status) => {
                pending--;

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
                            info.textContent = `${(distance / 1000).toFixed(2)} km, ${formatDuration(duration)}`;
                        }
                    }
                }
                if (pending === 0) {
                    for (const d in perDayStats) {
                        const stat = perDayStats[d];
                        const dateDiv = document.querySelector(`[data-date="${d}"]`);
                        const items = dateDiv ? dateDiv.querySelectorAll('.destination-item') : [];
                        const destinationCount = items.length;

                        const distanceKm = (stat.distance / 1000).toFixed(2);
                        const durationStr = formatDuration(stat.duration);

                        if (destinationCount === 0) {
                            // ✅ 目的地が完全に削除された日付 → 非表示にする
                            stat.summaryEl.textContent = '';
                        } else if (
                            destinationCount >= 2 ||
                            (destinationCount === 1 && stat.distance > 0 && stat.duration > 0)
                        ) {
                            stat.summaryEl.textContent = `Distance: ${distanceKm} km, Duration: ${durationStr}`;
                        } else {
                            stat.summaryEl.textContent = ''; // 1件のみかつ距離ゼロ → 非表示
                        }

                        dailyDistances[d] = stat.distance;
                        dailyDurations[d] = stat.duration;
                    }

                    totalSummary.textContent = `Total Distance: ${(totalDistance / 1000).toFixed(2)} km, Total Duration: ${formatDuration(totalDuration)}`;
                    totalSummary.classList.remove('hidden');
                }

            });
        }
        previousLatLng = currentLatLng;
    }
    if (itemsChrono.length === 0) {
        totalSummary.textContent = 'No destinations added.';
        totalSummary.classList.remove('hidden');
    }
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


// 1番目の目的地は出発地点として移動手段UIを無効化
function updateFirstDestinationDisplay() {
    const allDateDivs = [...document.querySelectorAll('#dateFieldsContainer > div[data-date]')];
    let firstItem = null;

    // すべての目的地を初期状態に戻す
    document.querySelectorAll('.destination-item').forEach(item => {
        // travel mode 表示を元に戻す
        item.querySelectorAll('.travel-mode-radio').forEach((radio) => {
            radio.disabled = false;
            if (radio.dataset.originalName) {
                radio.name = radio.dataset.originalName;
            }
        });

        const container = item.querySelector('.travel-mode-container');
        if (container) container.style.display = '';
        const routeInfo = item.querySelector('.route-info');
        if (routeInfo) routeInfo.style.display = '';

        // プレースホルダー戻す
        const input = item.querySelector('.destination-input');
        if (input) {
            input.placeholder = 'Please enter your destination';
        }

        // 出発ラベルがあれば削除
        const oldLabel = item.querySelector('.start-label');
        if (oldLabel) oldLabel.remove();

        // 装飾も削除
        item.classList.remove('bg-blue-50', 'border-l-4', 'border-blue-400', 'pl-2');
    });

    // 全期間の先頭の目的地を見つける（最も早い日付）
    for (const dateDiv of allDateDivs) {
        const items = [...dateDiv.querySelectorAll('.destination-item')];
        if (items.length > 0) {
            firstItem = items[0];
            break;
        }
    }

    if (firstItem) {
        // travel mode 無効化・非表示
        firstItem.querySelectorAll('.travel-mode-radio').forEach((radio) => {
            if (!radio.dataset.originalName) {
                radio.dataset.originalName = radio.name;
            }
            radio.disabled = true;
        });

        const container = firstItem.querySelector('.travel-mode-container');
        if (container) container.style.display = 'none';
        const routeInfo = firstItem.querySelector('.route-info');
        if (routeInfo) routeInfo.style.display = 'none';

        // プレースホルダーを「出発地点」に
        const input = firstItem.querySelector('.destination-input');
        if (input) {
            input.placeholder = 'Please enter your departure point';
        }

        // 出発ラベル追加
        const label = document.createElement('div');
        label.className = 'start-label text-sm text-blue-600 font-semibold mb-1 flex items-center gap-1';
        label.innerHTML = '<i class="fa-solid fa-flag-checkered text-blue-500"></i> Departure Point';
        firstItem.prepend(label);

        // 装飾追加
        // firstItem.classList.add('bg-blue-50', 'border-l-4', 'border-blue-400', 'pl-2');
    }
}

// 並び順変更時などに各フィールドのname属性を再設定
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

            // radio buttons: name再割当
            item.querySelectorAll('.travel-mode-radio').forEach((radio) => {
                const newName = `travel_modes[${dateKey}][${index}]`;
                radio.name = newName;
                radio.dataset.originalName = newName; // 無効化復元用
            });
        });
    });
}

// タイトル入力欄の文字数カウント表示（リアルタイム）
document.addEventListener('DOMContentLoaded', () => {
    const titleInput = document.getElementById('title');
    const counter = document.getElementById('titleCharCount');
    const maxLength = parseInt(titleInput.getAttribute('maxlength')) || 100;

    const updateCounter = () => {
        counter.textContent = `${titleInput.value.length} / ${maxLength}`;
    };

    titleInput.addEventListener('input', updateCounter);
    updateCounter();
});
