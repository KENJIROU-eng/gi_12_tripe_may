let map;
let directionsService;
let directionsRenderer;
let markers = [];

// 初期化関数
window.initMap = async function() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 10.3302385, lng: 123.906207 },
        zoom: 8,
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({ suppressMarkers: true });
    directionsRenderer.setMap(map);

    if (typeof initializeCreateForm === "function") {
        await initializeCreateForm();  // もしフォーム初期化が非同期ならawait
    }

    // 既存データ（objectの配列など）を取得（例: window.existingData）
    // 形式例: { "2025-06-04": [ {latitude, longitude, place_id, place_name, address?}, ... ], ... }
    const existingData = window.existingData || {};

    // 日付ごとに住所補完＆マーカー描画など
    for (const date in existingData) {
        const places = existingData[date];
        // place_idから住所がなければ補完
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

        // 緯度経度でLatLng配列を作成
        const latLngs = places.map(p => new google.maps.LatLng(parseFloat(p.latitude || p.lat), parseFloat(p.longitude || p.lng)));

        // マーカー追加（番号付きラベル）
        latLngs.forEach((latLng, i) => {
            addMarker(latLng, `${i + 1}`);
        });

        // ルート描画（複数の日付があっても繋がる形で描画したい場合は調整が必要）
        drawRoute(latLngs);
    }
};

function clearMarkers() {
    markers.forEach(marker => marker.setMap(null));
    markers = [];
}

// 住所から緯度経度を取得
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

// place_idから住所を取得（逆ジオコーディング）
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

// 住所リストからマーカーとルート描画を更新
async function updateMapRoutes(addresses) {
    clearMarkers();

    if (addresses.length < 2) {
        directionsRenderer.set('directions', null);
        return;
    }

    const geocoder = new google.maps.Geocoder();
    try {
        const locations = [];
        for (const addr of addresses) {
            const loc = await geocodeAddress(geocoder, addr);
            locations.push(loc);
        }

        locations.forEach((loc, i) => {
            addMarker(loc, `${i + 1}`);
        });

        drawRoute(locations);
    } catch (error) {
        alert('Error in geocoding address: ' + error);
    }
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
                directionsRenderer.setDirections(response);
            } else {
                alert('Route drawing failed: ' + status);
            }
        }
    );
}
