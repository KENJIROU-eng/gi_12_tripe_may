let map;
let directionsService;
let directionsRenderer;
let marker;
let selectedPlace = null;
let routeMarkers = [];


// initial map
function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 35.6804, lng: 139.7960 },
        zoom  : 6,
    });

    directionsService  = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({ map: map});

    // Place AutoComplete Settings
    const input        = document.getElementById("searchInput");
    const autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo("bounds", map);

    marker = new google.maps.Marker({
        map,
        anchorPoint: new google.maps.Point(0, -29),
    });

    autocomplete.addListener("place_changed", () => {
        marker.setVisible(false);
        const place   = autocomplete.getPlace();
        selectedPlace = place;

        if (!place.geometry || !place.geometry.location) {
            alert("Location not found.");
            return;
        }

        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(15);
        }

        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        document.getElementById("placeName").textContent    = place.name || "";
        document.getElementById("placeAddress").textContent = place.formatted_address || "";
        document.getElementById("searchResultInfo").classList.remove("hidden");
    });
}

window.addEventListener('load', () => {
    const checkGoogleMapsLoaded = setInterval(() => {
        if (window.google && window.google.maps && window.google.maps.Map) {
            clearInterval(checkGoogleMapsLoaded);
            initMap();
        }
    }, 100);
});

function clearRouteMarkers() {
    routeMarkers.forEach(marker => marker.setMap(null));
    routeMarkers = [];
}

// Draw route from input values ​​and calculate distance
function calculateRouteAndDistance(destinations) {
    if (destinations.length < 2) return;

    const waypoints = destinations.slice(1, -1).map(location => ({
        location,
        stopover: true
    }));

    const request = {
        origin      : destinations[0],
        destination : destinations[destinations.length - 1],
        waypoints   : waypoints,
        travelMode  : google.maps.TravelMode.DRIVING,
    };

    directionsService.route(request, (result, status) => {
        if (status === 'OK') {
            console.log("Got DRIVING route");
            console.log(result);

            directionsRenderer.setDirections(result);

            const totalDistance = result.routes[0].legs.reduce((sum, leg) => sum + leg.distance.value, 0);
            const totalDuration = result.routes[0].legs.reduce((sum, leg) => sum + leg.duration.value, 0);
            const km = (totalDistance / 1000).toFixed(1);
            const hours = Math.floor(totalDuration / 3600);
            const minutes = Math.round((totalDuration % 3600) / 60);

            document.getElementById('totalSummary').textContent =
                `Total distance: ${km} km / Total time: ${hours}h ${minutes}m`;

            clearRouteMarkers();
            const legs = result.routes[0].legs;

            const infoSpans = document.querySelectorAll('.route-info');
            legs.forEach((leg, index) => {
                const distanceText = leg.distance.text;
                const durationText = leg.duration.text;
                if (infoSpans[index + 1]) {
                    infoSpans[index + 1].innerHTML = `${distanceText}<br>${durationText}`;
                }
            });

            legs.forEach((leg, index) => {
                const label = String.fromCharCode('A'.charCodeAt(0) + index);

                const marker = new google.maps.Marker({
                    position: leg.start_location,
                    map: map,
                    label: label,
                });

                routeMarkers.push(marker);
            });

            const lastLeg = legs[legs.length - 1];
            const lastLabel = String.fromCharCode('A'.charCodeAt(0) + legs.length);
            const lastMarker = new google.maps.Marker({
                position: lastLeg.end_location,
                map: map,
                label: lastLabel,
            });

            routeMarkers.push(lastMarker);

        } else {
            console.error('Directions request failed due to ' + status);
        }
    });

}

function updateTotalSummary() {
    const inputs       = document.querySelectorAll('#dateFieldsContainer input');
    const destinations = Array.from(inputs).map(i => i.value.trim()).filter(v => v);

    // const destinations = [
    //     "Tokyo Station, Japan",
    //     "Yokohama Station, Japan"
    // ];

    const summaryDiv = document.getElementById('totalSummary');
    if (!summaryDiv) return;

    if (destinations.length >= 2) {
        summaryDiv.classList.remove('hidden');
        calculateRouteAndDistance(destinations);
    } else {
        summaryDiv.classList.add('hidden');
    }
}

function addToDestinations() {
    if (!selectedPlace) return;

    const firstDateField = document.querySelector('.date-fields');
    if (!firstDateField) {
        alert("Please select a date range first.");
        return;
    }

    const date      = firstDateField.getAttribute('data-date');
    const container = document.getElementById(`fields-${date}`);
    if (!container) {
        alert("Could not find the destination field container.");
        return;
    }

    const inputWrapper = document.createElement('div');
    inputWrapper.className = "flex items-center mb-1 gap-2";

    inputWrapper.innerHTML = `
        <span class="cursor-move drag-handle text-gray-500 px-2 text-xl">
            <i class="fa-solid fa-grip-lines"></i>
        </span>
        <input type="text" name="destinations[${date}][]" value="${selectedPlace.formatted_address || selectedPlace.name || ''}"
            class="w-2/3 p-2 border rounded destination-input" />
        <span class="route-info ml-2 text-sm text-gray-600"></span>
        <button type="button" onclick="removeField(this)" class="text-red-500 hover:text-red-700 text-xl">
            <i class="fa-solid fa-xmark"></i>
        </button>
    `;



    container.appendChild(inputWrapper);

    const input = inputWrapper.querySelector('input');

    attachAutocompleteToInput(input);

    updateTotalSummary();

    document.getElementById("searchResultInfo").classList.add("hidden");



    window.initMap = initMap;
    window.addToDestinations = addToDestinations;
}

function createOutsideMarker(place) {

    const marker = new google.maps.Marker({
        position: place.location,
        map,
        title: place.name,
    });

    const contentString = `
        <div>
            <strong>${place.name}</strong><br>
            ${place.address}<br>
            <a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(place.address)}" target="_blank">View on Google Maps</a><br>
            <button id="addButton" style="margin-top:6px; background:#38bdf8; color:white; border:none; padding:4px 8px; border-radius:4px; cursor:pointer;">
                + Add
            </button>
        </div>
    `;

    const infoWindow = new google.maps.InfoWindow({
        content: contentString
    });

    marker.addListener('click', () => {
        infoWindow.open(map, marker);

        google.maps.event.addListenerOnce(infoWindow, 'domready', () => {
            document.getElementById('addButton').addEventListener('click', () => {
                addPlaceToDestinations(place);
                infoWindow.close();
            });
        });
    });

    return marker;
}

function attachAutocompleteToInput(inputElement) {
        const autocomplete = new google.maps.places.Autocomplete(inputElement);

        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) {
                console.warn("Place has no geometry.");
                return;
            }

            map.setCenter(place.geometry.location);
            map.setZoom(15);

            const marker = new google.maps.Marker({
                position: place.geometry.location,
                map: map,
            });

            routeMarkers.push(marker);
            updateTotalSummary();
        })
    }

