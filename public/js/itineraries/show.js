let map;
let directionsService;
let directionsRenderer;
let markers = [];

window.initShowMap = async function () {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 10.3302385, lng: 123.906207 },
        zoom: 8,
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({ suppressMarkers: true });
    directionsRenderer.setMap(map);

    const existingData = window.existingData || {};
    const sortedDates = Object.keys(existingData).sort();
    const weatherResults = {};
    let allPlaces = [];

    for (const date of sortedDates) {
        const places = existingData[date];
        if (!places || places.length === 0) continue;

        // 天気データ取得（最初の地点のみ）
        const firstPlace = places[0];
        const lat = parseFloat(firstPlace.latitude || firstPlace.lat);
        const lng = parseFloat(firstPlace.longitude || firstPlace.lng);
        if (!isNaN(lat) && !isNaN(lng)) {
            try {
                const weather = await fetchWeather(lat, lng, date);
                weatherResults[date] = weather;

                const id = `weatherContainer-${date.replaceAll('-', '')}`;
                const container = document.getElementById(id);

                if (container) {
                    const p = document.createElement('p');
                    p.innerHTML = weather; // ← 日付削除済み
                    container.appendChild(p);
                } else {
                    console.warn(`Missing container for date ${date}, ID: ${id}`);
                }
            } catch (e) {
                console.warn(`Failed to fetch weather for ${date}`, e);
            }
        }

        // マーカー用の場所一覧を作成
        for (const place of places) {
            if (!place.address && place.place_id) {
                try {
                    place.address = await geocodePlaceId(place.place_id);
                } catch (e) {
                    console.warn(`Failed to geocode place_id: ${place.place_id}`, e);
                    place.address = place.place_name || "";
                }
            }

            const lat = parseFloat(place.latitude || place.lat);
            const lng = parseFloat(place.longitude || place.lng);
            if (!isNaN(lat) && !isNaN(lng)) {
                allPlaces.push(new google.maps.LatLng(lat, lng));
            }
        }
    }

    // マーカーを追加
    allPlaces.forEach((latLng, i) => {
        addMarker(latLng, `${i + 1}`);
    });

    // 経路を描画
    drawRoute(allPlaces);
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

function addMarker(latLng, label) {
    const marker = new google.maps.Marker({
        position: latLng,
        map: map,
        label: label,
    });
    markers.push(marker);
}

function drawRoute(places) {
    if (places.length < 2) {
        directionsRenderer.set('directions', null);
        return;
    }

    const origin = places[0];
    const destination = places[places.length - 1];
    const waypoints = places.slice(1, -1).map(place => ({
        location: place,
        stopover: true
    }));

    directionsService.route(
        {
            origin,
            destination,
            waypoints,
            travelMode: google.maps.TravelMode.DRIVING,
        },
        (response, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(response);
            } else {
                alert('Route drawing failed: ' + status);
            }
        }
    );
}

function getWeatherIcon(condition) {
    condition = condition.toLowerCase();

    if (condition.includes('sun') || condition.includes('clear')) {
        return '<i class="fa-solid fa-sun text-yellow-400"></i>';
    } else if (condition.includes('rain') || condition.includes('shower')) {
        return '<i class="fa-solid fa-umbrella text-blue-500"></i>';
    } else if (condition.includes('cloud') || condition.includes('overcast')) {
        return '<i class="fa-solid fa-cloud text-gray-500"></i>';
    } else if (condition.includes('thunder') || condition.includes('storm')) {
        return '<i class="fa-solid fa-cloud-bolt text-yellow-500"></i>';
    } else if (condition.includes('snow') || condition.includes('sleet') || condition.includes('ice')) {
        return '<i class="fa-solid fa-snowflake text-cyan-300"></i>';
    } else {
        return ''; // fallback: no icon
    }
}

async function fetchWeather(lat, lng, dateStr) {
    const apiKey = weatherApiKey;

    const date = new Date(dateStr);
    const today = new Date();
    const daysDiff = Math.floor((date - today) / (1000 * 60 * 60 * 24));

    const fallbackUrl = `https://api.weatherapi.com/v1/current.json?key=${apiKey}&q=${lat},${lng}`;

    // 14日以上未来 → まだ予測不能
    if (daysDiff > 14) {
        return '※ Weather forecast for this day is not yet available';
    }

    const forecastUrl = `https://api.weatherapi.com/v1/forecast.json?key=${apiKey}&q=${lat},${lng}&dt=${dateStr}`;

    try {
        const response = await fetch(forecastUrl);
        const data = await response.json();

        const forecast = data?.forecast?.forecastday?.[0];
        if (forecast?.day) {
            const condition = forecast.day.condition.text || '不明';
            const temp = forecast.day.avgtemp_c;
            const icon = getWeatherIcon(condition);
            return `${icon} ${condition} / Ave Temp: ${temp}°C`;
        } else {
            const fallbackResponse = await fetch(fallbackUrl);
            const fallbackData = await fallbackResponse.json();

            const condition = fallbackData?.current?.condition?.text || '不明';
            const temp = fallbackData?.current?.temp_c;
            const icon = getWeatherIcon(condition);
            return `${icon} ${condition} (Current) / Avg Temp: ${temp}°C`;

        }
    } catch (error) {
        console.error('Weather information acquisition failure', error);
        return '※ Failed to get weather information';
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


