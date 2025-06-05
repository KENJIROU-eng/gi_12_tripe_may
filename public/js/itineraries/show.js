let map;
let directionsService;
let directionsRenderer;
let markers = [];

window.initShowMap = async function() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 10.3302385, lng: 123.906207 },
        zoom: 8,
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({ suppressMarkers: true });
    directionsRenderer.setMap(map);

    const existingData = window.existingData || {};

    let allPlaces = [];
    console.log(allPlaces);

    const sortedDates = Object.keys(existingData).sort();

    for (const date of sortedDates) {
        const places = existingData[date];

        for (const place of places) {
            if (!place.address && place.place_id) {
                try {
                    place.address = await geocodePlaceId(place.place_id);
                } catch (e) {
                    console.warn(`Failed to get address from place_id: ${place.place_id}`, e);
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

    allPlaces.forEach((latLng, i) => {
        addMarker(latLng, `${i + 1}`);
    });

    drawRoute(allPlaces);
};

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
