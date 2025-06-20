/**
 * Google Maps を使った旅行プラン表示用マップスクリプト（表示用）
 *
 * 主な機能:
 * - マップ初期化とルート描画の準備
 * - 既存データからマーカー表示とルートを描画
 * - 日付ごとの天気取得と表示
 * - place_id から住所に変換（逆ジオコーディング）
 * - Googleマップ共有リンクの生成
 */

let map; // Google Map インスタンス
let directionsService; // 経路探索サービス
let directionsRenderer; // 経路表示用レンダラー
let markers = []; // 現在のマーカーリスト
let shareMapUrl = null; // Googleマップ共有リンク

// --- 表示用マップの初期化処理（天気・マーカー・ルート） ---
window.initShowMap = async function () {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 10.3302385, lng: 123.906207 },
        zoom: 8,
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({ suppressMarkers: true });
    directionsRenderer.setMap(map);

    const existingData = window.existingData || {}; // { 日付: [地点...] }
    const sortedDates = Object.keys(existingData).sort();
    const weatherResults = {}; // 日付ごとの天気保存用
    let allPlaces = []; // すべての地点を時系列でまとめた配列

    // 日付ごとに処理
    for (const date of sortedDates) {
        const places = existingData[date];
        if (!places || places.length === 0) continue;

        // --- 天気取得と表示 ---
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
                    p.innerHTML = weather;
                    container.appendChild(p);
                } else {
                    console.warn(`Missing container for date ${date}, ID: ${id}`);
                }
            } catch (e) {
                console.warn(`Failed to fetch weather for ${date}`, e);
            }
        }

        // --- 住所補完とマーカー用データ準備 ---
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
                allPlaces.push({
                    lat: lat,
                    lng: lng,
                    label: place.place_name || place.address || "",
                    place_id: place.place_id || null,
                });
            }
        }
    }

    // --- マーカー表示 ---
    allPlaces.forEach((p, i) => addMarker(new google.maps.LatLng(p.lat, p.lng), `${i + 1}`));

    // --- 経路描画 ---
    const latLngs = allPlaces.map(p => new google.maps.LatLng(p.lat, p.lng));
    drawRoute(latLngs);

    // --- Googleマップ共有リンク生成 ---
    generateShareMapLink(allPlaces);
};

// --- 指定位置にマーカーを追加 ---
function addMarker(latLng, label) {
    const marker = new google.maps.Marker({
        position: latLng,
        map: map,
        label: label,
    });
    markers.push(marker);
}

// --- マーカー間の経路を描画 ---
function drawRoute(places) {
    if (places.length < 2) {
        directionsRenderer.set('directions', null); // 起点終点が足りない場合は非表示
        return;
    }

    const origin = places[0];
    const destination = places[places.length - 1];
    const waypoints = places.slice(1, -1).map(place => ({ location: place, stopover: true }));

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

// --- place_id から住所を取得（逆ジオコーディング）---
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

// --- 指定地点・日付から天気情報を取得しHTMLで返す ---
async function fetchWeather(lat, lng, dateStr) {
    const apiKey = weatherApiKey;
    const date = new Date(dateStr);
    const today = new Date();
    const daysDiff = Math.floor((date - today) / (1000 * 60 * 60 * 24));
    const fallbackUrl = `https://api.weatherapi.com/v1/current.json?key=${apiKey}&q=${lat},${lng}`;

    // 天気APIは14日以上先の予報は不可
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

// --- 天気条件に応じたアイコンを返す（FontAwesome使用）---
function getWeatherIcon(condition) {
    condition = condition.toLowerCase();
    if (condition.includes('sun') || condition.includes('clear')) return '<i class="fa-solid fa-sun text-yellow-400"></i>';
    if (condition.includes('rain')) return '<i class="fa-solid fa-umbrella text-blue-500"></i>';
    if (condition.includes('cloud') || condition.includes('overcast')) return '<i class="fa-solid fa-cloud text-gray-500"></i>';
    if (condition.includes('thunder') || condition.includes('storm')) return '<i class="fa-solid fa-cloud-bolt text-yellow-600"></i>';
    if (condition.includes('snow')) return '<i class="fa-solid fa-snowflake text-blue-300"></i>';
    return '<i class="fa-solid fa-question text-gray-400"></i>';
}

// --- Google Maps の共有リンク生成 ---
function generateShareMapLink(places) {
    const btn = document.getElementById('shareMapBtn');
    if (!btn || !places || places.length < 2) return;

    const encode = (place) => {
        if (place.lat && place.lng) return `${place.lat},${place.lng}`;
        return encodeURIComponent(place.label || "");
    };

    const origin = encode(places[0]);
    const destination = encode(places[places.length - 1]);
    const waypoints = places.slice(1, -1).map(p => encode(p)).join('|');

    shareMapUrl = `https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${destination}&waypoints=${waypoints}&travelmode=driving&language=ja`;

    btn.classList.remove('hidden');
    btn.style.display = 'inline-flex';
}

// --- 共有リンクを新しいタブで開く ---
window.openShareMapLink = function () {
    if (shareMapUrl) {
        window.open(shareMapUrl, '_blank');
    } else {
        alert('The map link has not been generated yet.');
    }
};
