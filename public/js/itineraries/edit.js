let dateFieldsContainer = document.getElementById('dateFieldsContainer');
let totalSummary = document.getElementById('totalSummary');
let distanceMatrixService;
let dailyDistances = {};
let dailyDurations = {};

function createInputField(dateKey, index, address = '', lat = '', lng = '', placeId = '', placeName = '', travelMode = 'DRIVING') {
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

    return `
        <div class="destination-item mb-3">
            <div class="flex items-center gap-2">
                <span class="cursor-move drag-handle text-gray-500 text-xl w-1/12 text-center">
                    <i class="fa-solid fa-grip-lines"></i>
                </span>
                <input type="text" name="destinations[${dateKey}][]" value="${address || placeName}"
                    class="p-1 border rounded destination-input w-4/5" placeholder="Please enter a destination" />
                <button type="button" class="ml-auto mx-2 text-red-500 hover:text-red-700 text-xl pr-2 remove-btn">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="ml-10 flex flex-wrap items-center mt-1 travel-mode-container">
                ${radioButtons}
                <span class="route-info text-sm text-gray-600 ml-4"></span>
            </div>

            <input type="hidden" name="destinations_lat[${dateKey}][]" value="${lat}" class="destination-lat" />
            <input type="hidden" name="destinations_lng[${dateKey}][]" value="${lng}" class="destination-lng" />
            <input type="hidden" name="destinations_place_id[${dateKey}][]" value="${placeId}" class="destination-place-id" />
            <input type="hidden" name="destinations_place_name[${dateKey}][]" value="${placeName}" class="destination-place-name" />
        </div>
    `;
}


function updateFirstDestinationVisibility() {
    // 全 destination-item を取得（全日付順）
    const items = Array.from(document.querySelectorAll('.destination-item'));

    // 一旦全部表示
    items.forEach(item => {
        item.querySelector('.travel-mode-container')?.classList.remove('hidden');
        item.querySelector('.route-info')?.classList.remove('hidden');
    });

    // 最初の1件だけを非表示
    const firstItem = items[0];
    if (firstItem) {
        firstItem.querySelector('.travel-mode-container')?.classList.add('hidden');
        firstItem.querySelector('.route-info')?.classList.add('hidden');
    }
}


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
            const placeName = item.querySelector('.destination-place-name')?.value || '';
            const checkedRadio = item.querySelector('input.travel-mode-radio:checked');
            const travelMode = checkedRadio ? checkedRadio.value : 'DRIVING';


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

    console.log('saveCurrentDestinations result:', data);
    return data;
}

function formatDateToDisplay(dateStr) {
    const date = new Date(dateStr);
    const options = { year: 'numeric', month: 'short', day: '2-digit' };
    const parts = date.toLocaleDateString('en-US', options).replace(',', '').split(' ');
    // parts[0] = 'Jun', parts[1] = '05', parts[2] = '2025'
    return `${parts[0]}. ${parts[1]}, ${parts[2]}`;
}

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

    attachTravelModeChangeEvents();

    updateFirstDestinationVisibility();
}


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
        updateFirstDestinationVisibility();
        };
    });
}

function attachRemoveButtons() {
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.onclick = () => {
        btn.closest('.destination-item').remove();
        updateAllDistanceTimes();
        updateMapByCurrentInputs();
        updateFirstDestinationVisibility();
        };
    });
}

function attachInputChangeEvents() {
    document.querySelectorAll('.destination-input').forEach(input => {
        input.onchange = () => {
            updateAllDistanceTimes();
            updateMapByCurrentInputs();
        };
    });

    document.querySelectorAll('.travel-mode-select').forEach(select => {
        select.onchange = () => {
            updateAllDistanceTimes();
            updateMapByCurrentInputs();
        };
    });
}

function attachTravelModeChangeEvents() {
    document.querySelectorAll('.travel-mode-radio').forEach(radio => {
        radio.addEventListener('change', () => {
            updateAllDistanceTimes();
            updateMapByCurrentInputs();
        });
    });
}


function initSortable() {
    document.querySelectorAll('.sortable-container').forEach(container => {
        new Sortable(container, {
        group: 'destinations',
        handle: '.drag-handle',
        animation: 150,
        onEnd: function(evt) {
            const item = evt.item;
            const newDateKey = item.closest('div.mb-4').dataset.date;
            item.querySelectorAll('input').forEach(input => {
            input.name = input.name.replace(/\[\d{4}-\d{2}-\d{2}\]/, `[${newDateKey}]`);
            });
            updateAllDistanceTimes();
            updateMapByCurrentInputs();
            updateFirstDestinationVisibility();
        }
        });
    });
}

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

function updateMapRoutesByLatLngs(latLngs) {
    clearMarkers();
    latLngs.forEach((loc, i) => {
        addMarker(loc, `${i + 1}`);
    });
    drawRoute(latLngs);
}

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
                attachTravelModeChangeEvents(); // ← 追加
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

// Promise対応のupdateAllDistanceTimes
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
                const dKm = (day.distance / 1000).toFixed(2);
                day.summaryEl.textContent = `Total: ${dKm} km / ${formatDuration(day.duration)}`;
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



// フォーム送信制御
const form = document.querySelector('form'); // ここは正しいformセレクタに変更してください
form.addEventListener('submit', async (e) => {
    e.preventDefault(); // まず送信停止

    // 距離時間の更新を待ってから送信
    await updateAllDistanceTimes();

    // もう一度submit（ループ防止のため一時的にリスナー解除）
    form.removeEventListener('submit', arguments.callee);
    form.submit();
});

function formatDuration(seconds) {
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    if (h > 0 && m > 0) return `${h}h ${m}m`;
    if (h > 0) return `${h}h`;
    return `${m}m`;
}





