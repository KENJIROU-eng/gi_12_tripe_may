let map;
let directionsService;
let markers = [];
let shareMapUrl = null;

window.initShowMap = async function () {
    // 初期マップ（仮の中心）を表示
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 10.3302385, lng: 123.906207 },
        zoom: 10,
    });

    directionsService = new google.maps.DirectionsService();

    const existingData = window.existingData || {};
    const sortedDates = Object.keys(existingData).sort();
    const stepsContainer = document.getElementById('route-steps');
    if (stepsContainer) stepsContainer.innerHTML = '';

    const allPlaces = [];
    let labelCount = 1;
    let hasSetCenter = false; // ← 1回だけ map.setCenter する用

    for (const date of sortedDates) {
        const places = existingData[date];
        if (!places || places.length === 0) continue;

        const first = places[0];
        const lat = parseFloat(first.latitude || first.lat);
        const lng = parseFloat(first.longitude || first.lng);

        // 中心位置設定（最初の地点）
        if (!hasSetCenter && !isNaN(lat) && !isNaN(lng)) {
            map.setCenter({ lat, lng });
            hasSetCenter = true;
        }

        // 天気取得
        if (!isNaN(lat) && !isNaN(lng)) {
            try {
                const weather = await fetchWeather(lat, lng, date);
                const id = `weatherContainer-${date.replaceAll('-', '')}`;
                const container = document.getElementById(id);
                if (container) {
                    const p = document.createElement('p');
                    p.innerHTML = weather;
                    container.appendChild(p);
                }
            } catch (e) {
                console.warn(`Failed to fetch weather for ${date}`, e);
            }
        }

        // ピン追加
        for (const place of places) {
            if (!place.address && place.place_id) {
                try {
                    place.address = await geocodePlaceId(place.place_id);
                } catch (e) {
                    place.address = place.place_name || "";
                }
            }

            const lat = parseFloat(place.latitude || place.lat);
            const lng = parseFloat(place.longitude || place.lng);
            if (!isNaN(lat) && !isNaN(lng)) {
                const item = {
                    lat,
                    lng,
                    label: place.place_name || place.address || "",
                };
                allPlaces.push(item);
                addMarker({ lat, lng }, `${labelCount++}`);
            }
        }
    }

    // 区間ごとのルート描画
    for (let i = 0; i < allPlaces.length - 1; i++) {
        await drawSegmentRoute(allPlaces[i], allPlaces[i + 1]);
    }

    generateShareMapLink(allPlaces);

    // Total summary を表示する（地図描画完了後）
    const summary = document.getElementById('totalSummary');
    if (summary) {
        summary.classList.remove('hidden');
        summary.classList.add('animate-fadeIn');
    }
};

function addMarker(latLng, label) {
    const marker = new google.maps.Marker({
        position: latLng,
        map: map,
        label: label,
    });
    markers.push(marker);
}

async function drawSegmentRoute(origin, destination) {
    const renderer = new google.maps.DirectionsRenderer({
        suppressMarkers: true,
        preserveViewport: true
    });
    renderer.setMap(map);

    try {
        const result = await directionsService.route({
            origin,
            destination,
            travelMode: google.maps.TravelMode.DRIVING,
        });

        console.log('Route result:', result);
        renderer.setDirections(result);

        const steps = result.routes[0].legs[0].steps;
        const label = `${origin.label} → ${destination.label}`;
        showRouteSteps(steps, label);
    } catch (error) {
        console.error('Segment route error:', error);
    }
}

function showRouteSteps(steps, label = "") {
    const stepsContainer = document.getElementById('route-steps');
    if (!stepsContainer) return;

    const heading = document.createElement('li');
    heading.classList.add("font-bold", "mt-4", "text-blue-600");
    heading.textContent = label;
    stepsContainer.appendChild(heading);

    steps.forEach((step) => {
        const li = document.createElement('li');
        li.classList.add("border-b", "pb-1", "flex", "items-start", "gap-2");

        const icon = getManeuverIcon(step.maneuver);

        li.innerHTML = `
            <span class="text-xl">${icon}</span>
            <div>
                <div class="text-sm">${step.instructions}</div>
                <div class="text-xs text-gray-500">(${step.distance.text})</div>
            </div>
        `;
        stepsContainer.appendChild(li);
    });
}

function getManeuverIcon(maneuver) {
    const icons = {
        'turn-right': '➡️',
        'turn-left': '⬅️',
        'uturn-right': '↪️',
        'uturn-left': '↩️',
        'straight': '⬆️',
        'merge': '🔀',
        'ramp-right': '↗️',
        'ramp-left': '↖️',
        'fork-right': '⤴️',
        'fork-left': '⤵️',
        'roundabout-left': '🔄',
        'roundabout-right': '🔄',
    };
    return icons[maneuver] || '➡️';
}

function generateShareMapLink(places) {
    const btn = document.getElementById('shareMapBtn');
    if (!btn || !places || places.length < 2) return;

    const encode = (place) => place.lat && place.lng ? `${place.lat},${place.lng}` : encodeURIComponent(place.label || "");
    const origin = encode(places[0]);
    const destination = encode(places[places.length - 1]);
    const waypoints = places.slice(1, -1).map(p => encode(p)).join('|');

    shareMapUrl = `https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${destination}&waypoints=${waypoints}&travelmode=driving&language=ja`;

    btn.classList.remove('hidden');
    btn.style.display = 'inline-flex';
}

window.openShareMapLink = function () {
    if (shareMapUrl) {
        window.open(shareMapUrl, '_blank');
    } else {
        alert('The map link has not been generated yet.');
    }
};

function geocodePlaceId(placeId) {
    return new Promise((resolve, reject) => {
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ placeId }, (results, status) => {
            if (status === 'OK' && results[0]) {
                resolve(results[0].formatted_address);
            } else {
                reject(status);
            }
        });
    });
}

async function fetchWeather(lat, lng, dateStr) {
    const apiKey = weatherApiKey;
    const date = new Date(dateStr);
    const today = new Date();
    const daysDiff = Math.floor((date - today) / (1000 * 60 * 60 * 24));
    const fallbackUrl = `https://api.weatherapi.com/v1/current.json?key=${apiKey}&q=${lat},${lng}`;

    if (daysDiff > 14) return 'N/A';

    const forecastUrl = `https://api.weatherapi.com/v1/forecast.json?key=${apiKey}&q=${lat},${lng}&dt=${dateStr}`;

    try {
        const response = await fetch(forecastUrl);
        const data = await response.json();
        const forecast = data?.forecast?.forecastday?.[0];
        if (forecast?.day) {
            const condition = forecast.day.condition.text || '';
            const temp = forecast.day.avgtemp_c.toFixed(1);
            const icon = getWeatherIcon(condition);
            return `${icon} ${temp}°C`;
        } else {
            const fallbackResponse = await fetch(fallbackUrl);
            const fallbackData = await fallbackResponse.json();
            const condition = fallbackData?.current?.condition?.text || '';
            const temp = fallbackData?.current?.temp_c?.toFixed(1);
            const icon = getWeatherIcon(condition);
            return `${icon} ${temp}°C`;
        }
    } catch (error) {
        console.error('Weather information acquisition failure', error);
        return 'N/A';
    }
}

function getWeatherIcon(condition) {
    condition = condition.toLowerCase();
    if (condition.includes('sun') || condition.includes('clear')) return '<i class="fa-solid fa-sun text-yellow-400"></i>';
    if (condition.includes('rain')) return '<i class="fa-solid fa-umbrella text-blue-500"></i>';
    if (condition.includes('cloud') || condition.includes('overcast')) return '<i class="fa-solid fa-cloud text-gray-500"></i>';
    if (condition.includes('thunder') || condition.includes('storm')) return '<i class="fa-solid fa-cloud-bolt text-yellow-600"></i>';
    if (condition.includes('snow')) return '<i class="fa-solid fa-snowflake text-blue-300"></i>';
    return '<i class="fa-solid fa-question text-gray-400"></i>';
}
