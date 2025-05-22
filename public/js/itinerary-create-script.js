document.addEventListener('DOMContentLoaded', () => {
    const startInput = document.querySelector('[name="start_date"]');
    const endInput   = document.querySelector('[name="end_date"]');
    const container  = document.getElementById('dateFieldsContainer');

    if (window.initialItineraryData) {
        restoreFormDataFromInitialData(window.initialItineraryData);
    } else {
        restoreFormDataFromLocalStorage();
    }

    restoreFormDataFromLocalStorage();

    startInput?.addEventListener('change', () => {
        renderDateFields();
        saveFormDataToLocalStorage();
    });

    endInput?.addEventListener('change', () => {
        renderDateFields();
        saveFormDataToLocalStorage();
    });

    // Process to restore saved data
    function restoreFormDataFromLocalStorage() {
        const saved = localStorage.getItem('itineraryFormData');
        if (!saved) return;

        const data = JSON.parse(saved);

        if (data.startDate) startInput.value = data.startDate;
        if (data.endDate) endInput.value = data.endDate;

        renderDateFields();

        for (const [date, places] of Object.entries(data.destinations || {})) {
            const container = document.getElementById(`fields-${date}`);
            if(!container) continue;

            container.innerHTML = '';

            for (const place of places) {
                container.insertAdjacentHTML('beforeend', createInputField(date, place));
            }
        }

        updateTotalSummary();
    }

    function restoreFormDataFromInitialData(data) {
        if (data.startDate) startInput.value = data.startDate;
        if (data.endDate) endInput.value = data.endDate;

        renderDateFields();

        for (const [date, places] of Object.entries(data.destinations || {})) {
            const container = document.getElementById(`fields-${date}`);
            if (!container) continue;

            container.innerHTML = '';

            for (const place of places) {
                container.insertAdjacentHTML('beforeend', createInputField(date, place));
            }
        }

        updateTotalSummary();
    }

    function renderDateFields() {
        const startDate = new Date(startInput.value);
        const endDate = new Date(endInput.value);

        if (!startInput.value || !endInput.value) {
            container.innerHTML = '<p class="text-gray-500">Please select dates</p>';
            return;
        }

        if (isNaN(startDate) || isNaN(endDate) || startDate > endDate) {
            container.innerHTML = '';
            return;
        }

        let html = '';
        let currentDate = new Date(startDate);

        while (currentDate <= endDate) {
            const displayDate = currentDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: '2-digit'
            });

            const formattedDate = currentDate.toISOString().split('T')[0];

            html += `
                <div class="border-b p-4 mb-4 date-fields" data-date="${formattedDate}">
                    <h2 class="text-lg font-bold mb-2">${displayDate}</h2>
                    <div id="fields-${formattedDate}" class="sortable-container overflow-auto max-h-60 pr-2">
                        ${createInputField(formattedDate)}
                    </div>
                    <button type="button" onclick="addMoreField('${formattedDate}')" class="mt-4 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                        <i class="fa-solid fa-plus"></i> Add More Field
                    </button>
                </div>
            `;

            currentDate.setDate(currentDate.getDate() + 1);
        }

        //
        html += `
            <div id="totalSummary" class="mt-6 p-4 text-end text-xl font-semibold hidden">
                Total distance: 600km / Total time: 12h00m
            </div>
        `

        container.innerHTML = html;

        bindInputEvents();
        updateTotalSummary();

        // Apply Sortable to each date block
        document.querySelectorAll('.sortable-container').forEach(container => {
            new Sortable(container, {
                group: 'destinations',
                handle: '.drag-handle',
                animation: 150,
                onEnd: () => {
                    updateTotalSummary();
                    saveFormDataToLocalStorage();
                }
            });
        });
    }

    function attachAutocompleteToInput(input) {
        if (!window.google || !google.maps || !google.maps.places) {
            console.warn('Google Maps Places API is not loaded');
            return;
        }
        if (input.dataset.autocompleteAttached === 'true') return;

        const autocomplete = new google.maps.places.Autocomplete(input, {
            types: [],
        });

        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (!place.geometry) return;

            input.dispatchEvent(new Event('input'));
        });

        input.dataset.autocompleteAttached = 'true';
    }



    function bindInputEvents() {
        const inputs = document.querySelectorAll('.destination-input');
        inputs.forEach(input => {
            input.removeEventListener('input', updateTotalSummary);
            input.removeEventListener('input', saveFormDataToLocalStorage);

            input.addEventListener('input', updateTotalSummary);
            input.addEventListener('input', saveFormDataToLocalStorage);

            attachAutocompleteToInput(input);
        });
    }

    function updateTotalSummary() {
        const inputs   = document.querySelectorAll('#dateFieldsContainer input');
        const destinations = Array.from(inputs)
            .map(input => input.value.trim())
            .filter(v => v !== '');

        const summaryDiv = document.getElementById('totalSummary');
        if (!summaryDiv) return;

        if (destinations.length >= 2) {
            summaryDiv.classList.remove('hidden');
            calculateRouteAndDistance(destinations);
        } else {
            summaryDiv.classList.add('hidden');
            clearRouteMarkers();
            directionsRenderer.set('directions', null);
        }
    }

    function saveFormDataToLocalStorage() {
        const start = document.querySelector('[name="start_date"]').value;
        const end = document.querySelector('[name="end_date"]').value;

        const data = {
            startDate: start,
            endDate: end,
            destinations: {}
        };

        const allInputs = document.querySelectorAll('.destination-input');
        allInputs.forEach(input => {
            const section = input.closest('.date-fields');
            if (!section) return;

            const date = section.getAttribute('data-date');
            if (!data.destinations[date]) {
                data.destinations[date] = [];
            }
            data.destinations[date].push(input.value);
        });

        localStorage.setItem('itineraryFormData', JSON.stringify(data));
    }

    // A function that returns the HTML for the input field and delete button
    function createInputField(dateKey, value= '') {
        const id = `input-${dateKey}-${Date.now()}`;
        return `
            <div class="destination-item flex items-center mb-1 gap-2">
                <span class="cursor-move drag-handle text-gray-500 px-2 text-xl"><i class="fa-solid fa-grip-lines"></i></span>
                <input type="text" name="destinations[${dateKey}][]" value="${value}" class="w-2/3 p-2 border rounded destination-input" placeholder="Please enter a destination" />
                <span class="route-info ml-2 text-sm text-gray-600"></span>
                <button type="button" onclick="removeField(this)" class="text-red-500 hover:text-red-700 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        `
    }


    // Global Function: Add Input Field
    window.addMoreField = function(dateKey) {
        const targetDiv = document.getElementById(`fields-${dateKey}`);
        if (!targetDiv) return;

        targetDiv.insertAdjacentHTML('beforeend', createInputField(dateKey));
        bindInputEvents();
        updateTotalSummary();
    };

    //Global Function: Delete Input Field
    window.removeField = function (button) {
        const fieldWrapper = button.closest('.flex');
        fieldWrapper?.remove();
        updateTotalSummary();
    };

    // Fill in the empty fields, or add new ones if they don't exist
    window.insertDestinationToExistingField = function(dateKey, placeName) {
        const container = document.getElementById(`fields-${dateKey}`);
        if (!container) return;

        const inputs = container.querySelectorAll('.destination-input');

        // Find an open input and start typing
        for (const input of inputs) {
            if (input.value.trim() === '') {
                input.dispatchEvent(new Event('input'));
                return;
            }
        }

        // If there is no blank space, add it and enter the information
        addMoreField(dateKey);
        const newInputs = container.querySelectorAll('.destination-input');
        const lastInput = newInputs[newInputs.length - 1];
        lastInput.value = placeName;
        lastInput.dispatchEvent(new Event('input'));
    }

    window.addEventListener('beforeunload', function() {
        this.localStorage.removeItem('itineraryFormData');
    });
});
