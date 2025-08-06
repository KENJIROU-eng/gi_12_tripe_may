/**
 * Google Maps を使った旅行プラン表示用マップスクリプト
 *
 * 主な機能:
 * - 初期化時にマップとルート描画の準備をする
 * - 既存データからマーカー表示とルートを描画
 * - place_id を住所に変換（逆ジオコーディング）
 * - 任意の住所リストからマップ更新
 * - マーカー管理、ルート描画、住所変換処理
 */

let map; // Google Map インスタンス
let directionsService; // 経路探索サービス
let directionsRenderer; // 経路表示用レンダラー
let markers = []; // 現在のマーカーリスト

// --- マップ初期化処理 ---
window.initMap = async function() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 10.3302385, lng: 123.906207 },
        zoom: 8,
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({ suppressMarkers: true });
    directionsRenderer.setMap(map);

    // 非同期フォーム初期化（必要であれば）
    if (typeof initializeCreateForm === "function") {
        await initializeCreateForm();
    }

    // 既存データ（日付ごとの地点配列）を取得
    const existingData = window.existingData || {};

    for (const date in existingData) {
        const places = existingData[date];

        // place_id から住所を補完（なければ place_name で代用）
        await Promise.all(places.map(async (place) => {
            if (!place.address && place.place_id) {
                try {
                    place.address = await geocodePlaceId(place.place_id);
                } catch (e) {
                    console.warn(`Failed to get address from place_id: ${place.place_id}`, e);
                    place.address = place.place_name || "";
                }
            }
        }));

        // 緯度経度情報から LatLng 配列を生成
        const latLngs = places.map(p => new google.maps.LatLng(parseFloat(p.latitude || p.lat), parseFloat(p.longitude || p.lng)));

        // 各地点に番号付きマーカーを表示
        latLngs.forEach((latLng, i) => {
            addMarker(latLng, `${i + 1}`);
        });

        // ルートを描画（複数日でも1本で繋げたい場合は別途調整）
        drawRoute(latLngs);
    }
};

// --- すべてのマーカーをマップから削除する ---
function clearMarkers() {
    markers.forEach(marker => marker.setMap(null));
    markers = [];
}

// --- 住所から緯度経度を取得（正ジオコーディング）---
function geocodeAddress(geocoder, address) {
    return new Promise((resolve, reject) => {
        geocoder.geocode({ address: address }, (results, status) => {
            if (status === 'OK' && results[0]) {
                resolve(results[0].geometry.location);
            } else {
                reject(status);
            }
        });
    });
}

// --- place_id から住所を取得（逆ジオコーディング）---
function geocodePlaceId(placeId) {
    return new Promise((resolve, reject) => {
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ placeId: placeId }, (results, status) => {
            if (status === 'OK' && results[0]) {
                resolve(results[0].formatted_address);
            } else {
                reject(status);
            }
        });
    });
}

// --- 複数の住所からマーカーを設置し、ルートを描画 ---
async function updateMapRoutes(addresses) {
    clearMarkers(); // 既存マーカーを除去

    if (addresses.length < 2) {
        directionsRenderer.set('directions', null); // 2地点未満ならルート非表示
        return;
    }

    const geocoder = new google.maps.Geocoder();
    try {
        const locations = [];
        for (const addr of addresses) {
            const loc = await geocodeAddress(geocoder, addr); // 住所 → 緯度経度
            locations.push(loc);
        }

        // 各地点に番号付きマーカーを表示
        locations.forEach((loc, i) => {
            addMarker(loc, `${i + 1}`);
        });

        drawRoute(locations); // 経路描画
    } catch (error) {
        alert('Error in geocoding address: ' + error);
    }
}

// --- 指定位置にマーカーを追加 ---
function addMarker(latLng, label) {
    const marker = new google.maps.Marker({
        position: latLng,
        map: map,
        label: label,
    });
    markers.push(marker);
}

// --- マーカー間の経路を描画する ---
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
            origin: origin,
            destination: destination,
            waypoints: waypoints,
            travelMode: google.maps.TravelMode.DRIVING,
        },
        (response, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(response); // ルート表示
            } else {
                alert('Route drawing failed: ' + status);
            }
        }
    );
}
