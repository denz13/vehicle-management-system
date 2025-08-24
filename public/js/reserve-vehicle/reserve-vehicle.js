// Reserve Vehicle JavaScript
console.log('=== RESERVE VEHICLE JAVASCRIPT LOADING ===');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking Tom Select availability...');
    
    // Check if Tom Select is loaded
    if (typeof TomSelect !== 'undefined') {
        console.log('Tom Select is available');
    } else {
        console.error('Tom Select is NOT available');
    }
    
    // Initialize search functionality
    initializeSearch();
    
    // Initialize pagination
    initializePagination();
    
    // Initialize modal events
    initializeModalEvents();
    
    // Set minimum dates for reservation
    setMinimumDates();
    
    // Initialize passenger management
    initializePassengerManagement();
    
    // Initialize map functionality
    initializeMap();
});

// Google Maps variables
let map;
let marker;
let geocoder;

// Initialize Google Maps
function initGoogleMaps() {
    // This function will be called by Google Maps API when it loads
    console.log('Google Maps API loaded');
}

// Initialize map functionality
function initializeMap() {
    // Wait for Google Maps API to load
    if (typeof google === 'undefined') {
        // If Google Maps API is not loaded yet, wait for it
        const checkGoogleMaps = setInterval(() => {
            if (typeof google !== 'undefined') {
                clearInterval(checkGoogleMaps);
                setupMap();
            }
        }, 100);
    } else {
        setupMap();
    }
}

// Setup the map
function setupMap() {
    try {
        // Initialize geocoder
        if (typeof google !== 'undefined' && google.maps.Geocoder) {
            geocoder = new google.maps.Geocoder();
        } else {
            console.warn('Google Geocoder not available');
        }
        
        // Create map centered on a default location (you can change this)
        const defaultLocation = { lat: 14.5995, lng: 120.9842 }; // Manila, Philippines
        
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: defaultLocation,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [
                {
                    featureType: 'poi',
                    elementType: 'labels',
                    stylers: [{ visibility: 'off' }]
                }
            ]
        });
        
        // Add click listener to map
        map.addListener('click', function(event) {
            placeMarker(event.latLng);
            updateCoordinateInputs(event.latLng);
        });
        
        // Add marker drag listener
        map.addListener('dragend', function() {
            if (marker) {
                const position = marker.getPosition();
                updateCoordinateInputs(position);
            }
        });
    } catch (error) {
        console.error('Error setting up map:', error);
        // Show fallback message if map fails to load
        const mapElement = document.getElementById('map');
        if (mapElement) {
            mapElement.innerHTML = `
                <div class="flex items-center justify-center h-full text-slate-500">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <div class="text-lg font-medium">Map Loading Failed</div>
                        <div class="text-sm">Please refresh the page or try again later</div>
                    </div>
                </div>
            `;
        }
    }
}

// Place marker on map
function placeMarker(latLng) {
    // Remove existing marker
    if (marker) {
        marker.setMap(null);
    }
    
    // Create new marker
    marker = new google.maps.Marker({
        position: latLng,
        map: map,
        draggable: true,
        title: 'Selected Location'
    });
    
    // Add drag listener to marker
    marker.addListener('dragend', function() {
        const position = marker.getPosition();
        updateCoordinateInputs(position);
    });
}

// Update coordinate input fields
function updateCoordinateInputs(latLng) {
    const longitudeInput = document.getElementById('longitude');
    const latitudeInput = document.getElementById('latitude');
    
    if (longitudeInput) {
        longitudeInput.value = latLng.lng().toFixed(6);
    }
    
    if (latitudeInput) {
        latitudeInput.value = latLng.lat().toFixed(6);
    }
}

// Initialize modal events
function initializeModalEvents() {
    // Reserve vehicle modal events
    const reserveModal = document.getElementById('reserve-vehicle-modal');
    if (reserveModal) {
        reserveModal.addEventListener('hidden.tw.modal', function() {
            resetReservationForm();
        });
        
        // Reinitialize map when modal is shown
        reserveModal.addEventListener('shown.tw.modal', function() {
            // Small delay to ensure modal is fully rendered
            setTimeout(() => {
                if (map && typeof google !== 'undefined') {
                    google.maps.event.trigger(map, 'resize');
                    // Re-center map if needed
                    if (marker) {
                        map.setCenter(marker.getPosition());
                    }
                }
                
                // Ensure Tom Select is properly initialized
                ensureTomSelectInitialized();
            }, 300);
            
            // Refresh reservation types to ensure latest data
            refreshReservationTypes();
        });
    }
    
    // Vehicle details modal events
    const detailsModal = document.getElementById('vehicle-details-modal');
    if (detailsModal) {
        detailsModal.addEventListener('hidden.tw.modal', function() {
            document.getElementById('vehicle-details-content').innerHTML = '';
        });
    }
}

// Refresh reservation types
function refreshReservationTypes() {
    fetch('/reserve-vehicle/reservation-types', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateReservationTypesDropdown(data.reservationTypes);
        }
    })
    .catch(error => {
        console.error('Error refreshing reservation types:', error);
    });
}

// Update reservation types dropdown
function updateReservationTypesDropdown(reservationTypes) {
    const dropdown = document.getElementById('reservation_type_id');
    if (!dropdown) return;
    
    // Store current selection
    const currentValue = dropdown.value;
    
    // Clear existing options except the first one
    dropdown.innerHTML = '<option value="">Select reservation type</option>';
    
    // Add new options
    reservationTypes.forEach(type => {
        const option = document.createElement('option');
        option.value = type.id;
        option.textContent = type.reservation_name;
        dropdown.appendChild(option);
    });
    
    // Restore selection if it still exists
    if (currentValue) {
        const option = dropdown.querySelector(`option[value="${currentValue}"]`);
        if (option) {
            option.selected = true;
        }
    }
}

// Initialize passenger management
function initializePassengerManagement() {
    try {
        // Populate passenger select dropdown with users
        populatePassengerSelect();
    } catch (error) {
        console.error('Error initializing passenger management:', error);
        // Show fallback message
        const passengerSelect = document.getElementById('passenger-select');
        if (passengerSelect) {
            passengerSelect.innerHTML = '<option value="">Error loading users. Please refresh the page.</option>';
        }
    }
}

// Populate passenger select dropdown with users
function populatePassengerSelect() {
    fetch('/users', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updatePassengerSelect(data.users);
        } else {
            console.error('Failed to load users:', data.message);
        }
    })
    .catch(error => {
        console.error('Error loading users:', error);
    });
}

// Update passenger select dropdown with users
function updatePassengerSelect(users) {
    const passengerSelect = document.getElementById('passenger-select');
    if (!passengerSelect) {
        console.error('Passenger select element not found');
        return;
    }
    
    console.log('Updating passenger select with users:', users);
    
    // Clear existing options
    passengerSelect.innerHTML = '';
    
    // Add user options
    users.forEach(user => {
        const option = document.createElement('option');
        option.value = user.id;
        option.textContent = user.name || user.email || `User ${user.id}`;
        passengerSelect.appendChild(option);
    });
    
    // Initialize Tom Select if not already initialized
    if (!passengerSelect.tomselect && typeof TomSelect !== 'undefined') {
        try {
            console.log('Initializing Tom Select for passenger select');
            passengerSelect.tomselect = new TomSelect('#passenger-select', {
                placeholder: 'Select passengers for this trip',
                plugins: ['remove_button'],
                maxItems: null,
                allowEmptyOption: false,
                create: false,
                persist: false,
                closeAfterSelect: false,
                hideSelected: false,
                duplicateItemsAllowed: false, // Prevent duplicate selections
                onItemAdd: function(value, item) {
                    console.log('Passenger added:', value, item);
                },
                onItemRemove: function(value, item) {
                    console.log('Passenger removed:', value, item);
                }
            });
            console.log('Tom Select initialized successfully');
        } catch (error) {
            console.error('Error initializing Tom Select:', error);
        }
    } else if (passengerSelect.tomselect) {
        try {
            // Refresh the Tom Select instance with new options
            passengerSelect.tomselect.refreshOptions();
            console.log('Tom Select refreshed with new options');
        } catch (error) {
            console.error('Error refreshing Tom Select:', error);
        }
    }
}

// Test function to manually test Tom Select
function testTomSelect() {
    console.log('Testing Tom Select component...');
    
    const passengerSelect = document.getElementById('passenger-select');
    if (!passengerSelect) {
        alert('Passenger select element not found!');
        return;
    }
    
    try {
        // Add some test options
        passengerSelect.innerHTML = `
            <option value="1">Test User 1</option>
            <option value="2">Test User 2</option>
            <option value="3">Test User 3</option>
        `;
        
        // Check if Tom Select is initialized
        if (passengerSelect.tomselect) {
            // Refresh with new options
            passengerSelect.tomselect.refreshOptions();
            alert('Tom Select working! You should see a dropdown with test options.');
        } else {
            // Initialize Tom Select
            if (typeof TomSelect !== 'undefined') {
                passengerSelect.tomselect = new TomSelect('#passenger-select', {
                    placeholder: 'Select passengers for this trip',
                    plugins: ['remove_button'],
                    maxItems: null,
                    allowEmptyOption: false,
                    create: false,
                    persist: false,
                    closeAfterSelect: false,
                    hideSelected: false,
                    duplicateItemsAllowed: false, // Prevent duplicate selections
                    onItemAdd: function(value, item) {
                        console.log('Passenger added:', value, item);
                    },
                    onItemRemove: function(value, item) {
                        console.log('Passenger removed:', value, item);
                    }
                });
                alert('Tom Select initialized! You should see a dropdown with test options.');
            } else {
                alert('Tom Select library not available!');
            }
        }
        
    } catch (error) {
        console.error('Tom Select test failed:', error);
        alert('Tom Select test failed: ' + error.message);
    }
}

// Ensure Tom Select is properly initialized
function ensureTomSelectInitialized() {
    const passengerSelect = document.getElementById('passenger-select');
    if (!passengerSelect) return;
    
    // If Tom Select is already initialized, don't reinitialize
    if (passengerSelect.tomselect) {
        console.log('Tom Select already initialized, skipping re-initialization');
        return;
    }
    
    // If Tom Select is not initialized, initialize it
    if (typeof TomSelect !== 'undefined') {
        console.log('Initializing Tom Select for passenger selection');
        try {
            passengerSelect.tomselect = new TomSelect('#passenger-select', {
                placeholder: 'Select Passenger(s)',
                plugins: ['remove_button'],
                maxItems: null,
                allowEmptyOption: false,
                create: false,
                persist: false,
                closeAfterSelect: false,
                hideSelected: false,
                duplicateItemsAllowed: false, // Prevent duplicate selections
                onInitialize: function() {
                    console.log('Tom Select initialized successfully');
                },
                onItemAdd: function(value, item) {
                    console.log('Passenger added:', value, item);
                },
                onItemRemove: function(value, item) {
                    console.log('Passenger removed:', value, item);
                }
            });
        } catch (error) {
            console.error('Error initializing Tom Select:', error);
        }
    } else {
        console.warn('Tom Select library not available');
    }
}

// Set minimum dates for reservation (today and tomorrow)
function setMinimumDates() {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const startDatetime = document.getElementById('start_datetime');
    const endDatetime = document.getElementById('end_datetime');
    
    if (startDatetime) {
        const todayString = today.toISOString().slice(0, 16);
        startDatetime.min = todayString;
        
        // Add change listener to update end datetime minimum
        startDatetime.addEventListener('change', function() {
            updateEndDatetimeMin();
        });
    }
    
    if (endDatetime) {
        updateEndDatetimeMin();
    }
}

// Update end datetime minimum based on start datetime
function updateEndDatetimeMin() {
    const startDatetime = document.getElementById('start_datetime');
    const endDatetime = document.getElementById('end_datetime');
    
    if (startDatetime && endDatetime && startDatetime.value) {
        // Set minimum end time to start time + 1 hour
        const startDate = new Date(startDatetime.value);
        const minEndDate = new Date(startDate);
        minEndDate.setHours(startDate.getHours() + 1);
        
        const minEndString = minEndDate.toISOString().slice(0, 16);
        endDatetime.min = minEndString;
        
        // If current end time is before minimum, update it
        if (endDatetime.value && new Date(endDatetime.value) < minEndDate) {
            endDatetime.value = minEndString;
        }
    }
}

// Search functionality
function initializeSearch() {
    const searchInput = document.getElementById('search-vehicles');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Set new timeout for better performance
            searchTimeout = setTimeout(() => {
                const searchTerm = this.value.toLowerCase();
                const vehicleRows = document.querySelectorAll('#vehicles-table-body tr');
                
                vehicleRows.forEach(row => {
                    const vehicleName = row.querySelector('td:nth-child(2) a')?.textContent.toLowerCase() || '';
                    const model = row.querySelector('td:nth-child(3) .font-medium')?.textContent.toLowerCase() || '';
                    const plateNumber = row.querySelector('td:nth-child(4) .font-medium')?.textContent.toLowerCase() || '';
                    
                    if (vehicleName.includes(searchTerm) || model.includes(searchTerm) || plateNumber.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }, 300); // 300ms delay for better performance
        });
    }
}

// Initialize pagination functionality
function initializePagination() {
    // Handle page number clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('page-link') && !e.target.closest('.page-item.disabled')) {
            e.preventDefault();
            const pageText = e.target.textContent.trim();
            
            if (pageText === '...' || pageText === '') return;
            
            let page = parseInt(pageText);
            if (!isNaN(page)) {
                refreshVehicles(page);
            }
        }
    });
    
    // Handle per page selector changes
    const perPageSelector = document.getElementById('per-page-selector');
    if (perPageSelector) {
        perPageSelector.addEventListener('change', function() {
            const perPage = parseInt(this.value);
            refreshVehicles(1, perPage); // Reset to first page when changing per page
        });
    }
}

// Refresh vehicles with pagination
function refreshVehicles(page = 1, perPage = 12) {
    const url = `/reserve-vehicle/vehicles?page=${page}&per_page=${perPage}`;
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateVehiclesDisplay(data.vehicles, data.pagination);
        }
    })
    .catch(error => {
        console.error('Error refreshing vehicles:', error);
    });
}

// Update vehicles display with new data
function updateVehiclesDisplay(vehicles, pagination = null) {
    const container = document.getElementById('vehicles-table-body');
    if (!container) return;
    
    let html = '';
    
    if (vehicles.length === 0) {
        html = `
            <tr>
                <td colspan="8" class="text-center py-8 text-slate-500">No available vehicles found</td>
            </tr>
        `;
    } else {
        vehicles.forEach(vehicle => {
            const imageSrc = vehicle.vehicle_image 
                ? `/storage/vehicles/${vehicle.vehicle_image}`
                : '';
            
            const dateAcquired = vehicle.date_acquired ? new Date(vehicle.date_acquired).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            }) : 'N/A';
            
            const statusHtml = vehicle.status === 'active' 
                ? `<div class="flex items-center text-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> Active</div>`
                : vehicle.status === 'maintenance'
                ? `<div class="flex items-center text-warning"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="wrench" data-lucide="wrench" class="lucide lucide-wrench w-4 h-4 mr-2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg> Maintenance</div>`
                : `<div class="flex items-center text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x-circle" data-lucide="x-circle" class="lucide lucide-x-circle w-4 h-4 mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> Inactive</div>`;
            
            html += `
                <tr class="intro-x">
                    <td>
                        <div class="flex">
                            <div class="w-10 h-10 image-fit zoom-in bg-slate-200 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-slate-400"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21,15 16,10 5,21"></polyline></svg>
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="" class="font-medium whitespace-nowrap">${vehicle.vehicle_name}</a>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            <div class="font-medium">${vehicle.model}</div>
                            <div class="text-xs">${vehicle.vehicle_color}</div>
                        </div>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            <span class="font-medium">${vehicle.plate_number}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center">
                            <span class="font-medium">${vehicle.capacity}</span>
                        </div>
                    </td>
                    <td class="text-center">${dateAcquired}</td>
                    <td class="text-center">
                        <div class="flex items-center justify-center">
                            ${statusHtml}
                        </div>
                    </td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="javascript:;" onclick="viewVehicleDetails(${vehicle.id})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="eye" class="lucide lucide-eye w-4 h-4 mr-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> Details
                            </a>
                            <a class="flex items-center text-success" href="javascript:;" onclick="reserveVehicle(${vehicle.id})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="calendar-plus" class="lucide lucide-calendar-plus w-4 h-4 mr-1"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><path d="M12 9v6"></path><path d="M9 12h6"></path></svg> Reserve
                            </a>
                        </div>
                    </td>
                </tr>
            `;
        });
    }
    
    container.innerHTML = html;
    
    // Update vehicle count with pagination info
    if (pagination) {
        updateVehicleCount(pagination);
    }
}

// Update vehicle count display with pagination info
function updateVehicleCount(pagination) {
    const countElement = document.querySelector('.intro-y.col-span-12 .hidden.md\\:block.mx-auto.text-slate-500');
    if (countElement && pagination) {
        const { from, to, total } = pagination;
        if (total > 0) {
            countElement.textContent = `Showing ${from} to ${to} of ${total} available vehicles`;
        } else {
            countElement.textContent = 'No available vehicles found';
        }
    }
}

// View vehicle details
function viewVehicleDetails(vehicleId) {
    fetch(`/reserve-vehicle/${vehicleId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const vehicle = data.vehicle;
            console.log('Vehicle data:', vehicle); // Debug log
            
            const dateAcquired = vehicle.date_acquired ? new Date(vehicle.date_acquired).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }) : 'N/A';
            
            const content = `
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 md:col-span-6">
                        <div class="h-64 image-fit rounded-md overflow-hidden">
                            ${vehicle.vehicle_image 
                                ? `<img alt="${vehicle.vehicle_name}" class="w-full h-full object-cover" src="/storage/vehicles/${vehicle.vehicle_image}">`
                                : `<div class="w-full h-full bg-slate-600 rounded-md flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-16 h-16 text-slate-400"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21,15 16,10 5,21"></polyline></svg>
                                   </div>`
                            }
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <h3 class="text-lg font-medium mb-4">${vehicle.vehicle_name}</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car w-5 h-5 mr-3 text-slate-500"><path d="M14 16H9m11 0h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2h-1m-6-4H5a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1m6 4h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2h-1m-6-4H5a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1"></path></svg>
                                <span class="font-medium">Plate Number:</span>
                                <span class="ml-2 text-slate-600">${vehicle.plate_number}</span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tag w-5 h-5 mr-3 text-slate-500"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l7.704 7.704a2.002 2.002 0 0 0 2.828 0l7.704-7.704a2.002 2.002 0 0 0 .586-1.414V4a2 2 0 0 0-2-2h-7.172a2 2 0 0 0-1.414.586z"></path></svg>
                                <span class="font-medium">Model:</span>
                                <span class="ml-2 text-slate-600">${vehicle.model}</span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-5 h-5 mr-3 text-slate-500"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="m22 21-2-2a4 4 0 0 0-3-3h-1a4 4 0 0 0-3 3l-2 2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                                <span class="font-medium">Capacity:</span>
                                <span class="ml-2 text-slate-600">${vehicle.capacity} persons</span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-palette w-5 h-5 mr-3 text-slate-500"><circle cx="13.5" cy="6.5" r=".5"></circle><circle cx="17.5" cy="10.5" r=".5"></circle><circle cx="8.5" cy="7.5" r=".5"></circle><circle cx="6.5" cy="12.5" r=".5"></circle><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"></path></svg>
                                <span class="font-medium">Color:</span>
                                <span class="ml-2 text-slate-600">${vehicle.vehicle_color}</span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar w-5 h-5 mr-3 text-slate-500"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <span class="font-medium">Date Acquired:</span>
                                <span class="ml-2 text-slate-600">${dateAcquired}</span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle w-5 h-5 mr-3 text-slate-500"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                <span class="font-medium">Status:</span>
                                <span class="ml-2 text-success font-medium">Available for Reservation</span>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button class="btn btn-success w-full" onclick="reserveVehicle(${vehicle.id})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-plus w-4 h-4 mr-2"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><path d="M12 9v6"></path><path d="M9 12h6"></path></svg>
                                Reserve This Vehicle
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('vehicle-details-content').innerHTML = content;
            
            // Show modal
            const modal = document.getElementById('vehicle-details-modal');
            const modalInstance = tailwind.Modal.getOrCreateInstance(modal);
            modalInstance.show();
        } else {
            showErrorMessage(data.message || 'Failed to load vehicle details');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while loading vehicle details');
    });
}

// Reserve vehicle function
function reserveVehicle(vehicleId) {
    console.log('=== RESERVE VEHICLE FUNCTION CALLED ===');
    console.log('Vehicle ID:', vehicleId);
    
    // Reset form first
    resetReservationForm();
    
    // Store the vehicle ID for reservation AFTER reset
    const vehicleIdField = document.getElementById('reserve_vehicle_id');
    if (vehicleIdField) {
        vehicleIdField.value = vehicleId;
        console.log('Vehicle ID set to:', vehicleIdField.value);
    } else {
        console.error('Vehicle ID field not found!');
    }
    
    // Show reserve modal
    const modal = document.getElementById('reserve-vehicle-modal');
    const modalInstance = tailwind.Modal.getOrCreateInstance(modal);
    modalInstance.show();
    
    // Ensure Tom Select is initialized after modal is shown
    setTimeout(() => {
        ensureTomSelectInitialized();
    }, 100);
}

// Test function to check if JavaScript is working
function testSubmitFunction() {
    console.log('=== TEST FUNCTION CALLED ===');
    console.log('submitReservation function available:', typeof submitReservation);
    console.log('Form element exists:', document.getElementById('reservation-form'));
    console.log('Current page URL:', window.location.href);
    
    if (typeof submitReservation === 'function') {
        alert('submitReservation function is available!');
    } else {
        alert('submitReservation function is NOT available!');
    }
}

// Submit reservation
function submitReservation() {
    console.log('=== SUBMIT RESERVATION FUNCTION CALLED ===');
    
    // Clear previous errors
    clearReservationErrors();
    console.log('Errors cleared');
    
    // Get form values
    const form = document.getElementById('reservation-form');
    console.log('Form element:', form);
    
    if (!form) {
        console.error('Form not found!');
        alert('Form not found. Please refresh the page.');
        return;
    }
    
    const formData = new FormData(form);
    console.log('FormData created:', formData);
    
    // Manually capture passenger values from Tom Select
    const passengerSelectElement = document.getElementById('passenger-select');
    if (passengerSelectElement && passengerSelectElement.tomselect) {
        const selectedPassengers = passengerSelectElement.tomselect.getValue();
        console.log('Selected passengers from Tom Select:', selectedPassengers);
        
        if (selectedPassengers && Array.isArray(selectedPassengers)) {
            // Remove duplicates and filter out empty values
            const uniquePassengers = [...new Set(selectedPassengers)].filter(id => id && id !== '');
            console.log('Unique passengers after deduplication:', uniquePassengers);
            
            // Additional check: ensure no duplicates exist
            if (uniquePassengers.length !== selectedPassengers.length) {
                console.warn('Duplicates detected and removed:', {
                    original: selectedPassengers,
                    unique: uniquePassengers
                });
            }
            
            // Clear existing passengers from FormData and add them manually
            formData.delete('passengers');
            uniquePassengers.forEach(passengerId => {
                formData.append('passengers[]', passengerId);
            });
            console.log('Unique passengers added to FormData');
        }
    } else {
        // Fallback: try to get values from the regular select element
        console.log('Tom Select not initialized, trying fallback method');
        const selectedOptions = passengerSelectElement ? passengerSelectElement.querySelectorAll('option:checked') : [];
        if (selectedOptions.length > 0) {
            const uniquePassengers = [...new Set(Array.from(selectedOptions).map(option => option.value))].filter(id => id && id !== '');
            formData.delete('passengers');
            uniquePassengers.forEach(passengerId => {
                formData.append('passengers[]', passengerId);
            });
            console.log('Unique passengers added via fallback method');
        }
    }
    
    // Debug: Log all form values
    console.log('=== FORM VALUES DEBUG ===');
    console.log('vehicle_id:', formData.get('vehicle_id'));
    console.log('requested_user_id:', formData.get('requested_user_id'));
    console.log('destination:', formData.get('destination'));
    console.log('driver:', formData.get('driver'));
    console.log('start_datetime:', formData.get('start_datetime'));
    console.log('end_datetime:', formData.get('end_datetime'));
    console.log('reason:', formData.get('reason'));
    console.log('reservation_type_id:', formData.get('reservation_type_id'));
    console.log('passengers:', formData.get('passengers'));
    console.log('passengers[]:', formData.getAll('passengers[]'));
    console.log('=== END FORM VALUES DEBUG ===');
    
    // Basic validation
    if (!formData.get('vehicle_id')) {
        console.log('Vehicle ID missing');
        showReservationError('reserve_vehicle_id', 'Vehicle ID is missing');
        return;
    }
    
    if (!formData.get('requested_user_id')) {
        console.log('User ID missing');
        showReservationError('requested_user_id', 'User ID is missing');
        return;
    }
    
    if (!formData.get('destination').trim()) {
        console.log('Destination missing');
        showReservationError('destination', 'Destination is required');
        return;
    }
    
    if (!formData.get('driver')) {
        console.log('Driver missing');
        showReservationError('driver', 'Driver is required');
        return;
    }
    
    if (!formData.get('start_datetime')) {
        console.log('Start datetime missing');
        showReservationError('start_datetime', 'Start date & time is required');
        return;
    }
    
    if (!formData.get('end_datetime')) {
        console.log('End datetime missing');
        showReservationError('end_datetime', 'End date & time is required');
        return;
    }
    
    if (new Date(formData.get('end_datetime')) <= new Date(formData.get('start_datetime'))) {
        console.log('Invalid datetime range');
        showReservationError('end_datetime', 'End date & time must be after start date & time');
        return;
    }
    
    if (!formData.get('reason').trim()) {
        console.log('Reason missing');
        showReservationError('reason', 'Purpose/reason is required');
        return;
    }
    
    if (!formData.get('reservation_type_id')) {
        console.log('Reservation type missing');
        showReservationError('reservation_type_id', 'Reservation type is required');
        return;
    }
    
    // Check if at least one passenger is selected
    const passengerSelect = document.getElementById('passenger-select');
    console.log('Passenger select element:', passengerSelect);
    
    if (!passengerSelect || !passengerSelect.value || passengerSelect.value.length === 0) {
        console.log('No passengers selected');
        showReservationError('passenger-select', 'At least one passenger is required');
        return;
    }
    
    console.log('All validation passed, proceeding with submission...');
    
    // Show loading state
    const reserveBtn = document.querySelector('#reserve-vehicle-modal .btn-success');
    const originalText = reserveBtn.textContent;
    reserveBtn.textContent = 'Reserving...';
    reserveBtn.disabled = true;
    
    console.log('Making AJAX request to /reserve-vehicle');
    
    // Send AJAX request
    fetch('/reserve-vehicle', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log('Response received:', response);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Show success message
            showSuccessMessage(data.message);
            
            // Display QR code if available
            if (data.qrcode_path) {
                displayQRCode(data.qrcode_path, data.reservation_id);
            }
            
            // Close modal
            const modal = document.getElementById('reserve-vehicle-modal');
            const modalInstance = tailwind.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Reset form
            resetReservationForm();
        } else {
            // Show validation errors
            if (data.errors) {
                console.log('=== VALIDATION ERRORS DETAILED ===');
                Object.keys(data.errors).forEach(field => {
                    console.log(`Field: ${field}, Error: ${data.errors[field][0]}`);
                    showReservationError(field, data.errors[field][0]);
                });
                console.log('=== END VALIDATION ERRORS ===');
                // Show validation error notification
                showValidationErrorMessage();
            } else {
                showErrorMessage(data.message || 'Failed to reserve vehicle');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while reserving the vehicle');
    })
    .finally(() => {
        // Reset button state
        reserveBtn.textContent = originalText;
        reserveBtn.disabled = false;
    });
}

// Show error message for reservation form fields
function showReservationError(fieldName, message) {
    const errorElement = document.getElementById(fieldName + '_error');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
    
    // Add error class to input
    const inputElement = document.getElementById(fieldName);
    if (inputElement) {
        inputElement.classList.add('border-danger');
    }
}

// Clear all reservation form errors
function clearReservationErrors() {
    const errorElements = document.querySelectorAll('#reserve-vehicle-modal [id$="_error"]');
    errorElements.forEach(element => {
        element.textContent = '';
        element.style.display = 'none';
    });
    
    // Remove error classes from inputs
    const inputs = document.querySelectorAll('#reserve-vehicle-modal .form-control, #reserve-vehicle-modal .form-select');
    inputs.forEach(input => {
        input.classList.remove('border-danger');
    });
}

// Reset reservation form
function resetReservationForm() {
    // Clear vehicle ID
    const vehicleIdField = document.getElementById('reserve_vehicle_id');
    if (vehicleIdField) vehicleIdField.value = '';
    
    // Keep the requested_user_id as is (logged-in user)
    // document.getElementById('requested_user_id').value = ''; // Don't clear this
    
    // Clear destination
    const destinationField = document.getElementById('destination');
    if (destinationField) destinationField.value = '';
    
    // Clear coordinates
    const longitudeField = document.getElementById('longitude');
    if (longitudeField) longitudeField.value = '';
    
    const latitudeField = document.getElementById('latitude');
    if (latitudeField) latitudeField.value = '';
    
    // Clear driver selection
    const driverField = document.getElementById('driver');
    if (driverField) driverField.value = '';
    
    // Clear datetime fields
    const startDatetimeField = document.getElementById('start_datetime');
    if (startDatetimeField) startDatetimeField.value = '';
    
    const endDatetimeField = document.getElementById('end_datetime');
    if (endDatetimeField) endDatetimeField.value = '';
    
    // Clear reason
    const reasonField = document.getElementById('reason');
    if (reasonField) reasonField.value = '';
    
    // Clear reservation type
    const reservationTypeField = document.getElementById('reservation_type_id');
    if (reservationTypeField) reservationTypeField.value = '';
    
    // Clear map marker and reset map
    if (marker) {
        marker.setMap(null);
        marker = null;
    }
    
    // Reset map to default location
    if (map) {
        const defaultLocation = { lat: 14.5995, lng: 120.9842 }; // Manila, Philippines
        map.setCenter(defaultLocation);
        map.setZoom(13);
    }
    
    // Reset passenger list
    const passengerSelect = document.getElementById('passenger-select');
    if (passengerSelect) {
        // Clear Tom Select if it's initialized
        if (passengerSelect.tomselect) {
            try {
                passengerSelect.tomselect.clear();
                console.log('Tom Select values cleared');
            } catch (error) {
                console.error('Error clearing Tom Select:', error);
                // Fallback: clear the underlying select
                passengerSelect.value = '';
            }
        } else {
            // Fallback for regular select
            passengerSelect.value = '';
        }
        
        // Also clear any selected options
        const selectedOptions = passengerSelect.querySelectorAll('option[selected]');
        selectedOptions.forEach(option => {
            option.selected = false;
        });
    }
    
    clearReservationErrors();
    setMinimumDates(); // This will now properly set the end datetime minimum
}

// Show success message using notification toast
function showSuccessMessage(message) {
    if (typeof showNotification_success !== 'undefined') {
        showNotification_success();
    } else {
        // Fallback to console if notification system not available
        console.log('Success:', message);
    }
}

// Show error message using notification toast
function showErrorMessage(message) {
    if (typeof showNotification_error !== 'undefined') {
        showNotification_error();
    } else {
        // Fallback to console if notification system not available
        console.log('Error:', message);
    }
}

// Show validation error message using notification toast
function showValidationErrorMessage() {
    if (typeof showNotification_validation_error !== 'undefined') {
        showNotification_validation_error();
    } else if (typeof showNotification_error !== 'undefined') {
        // Fallback to general error notification
        showNotification_error();
    } else {
        // Fallback to console if notification system not available
        console.log('Validation Error: Please check the form for errors');
    }
}

// Display QR code after successful reservation
function displayQRCode(qrCodePath, reservationId) {
    // Create QR code modal content
    const qrModalContent = `
        <div class="text-center">
            <h3 class="text-lg font-medium mb-4">Reservation QR Code</h3>
            
            <div class="flex justify-center mb-4">
                <div class="border-2 border-slate-200 rounded-lg p-4 bg-white">
                    <img src="/storage/${qrCodePath}" alt="Reservation QR Code" class="w-64 h-64 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div class="hidden w-64 h-64 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><path d="M9 9h.01"></path><path d="M15 9h.01"></path><path d="M9 15h.01"></path><path d="M15 15h.01"></path><path d="M9 12h6"></path><path d="M12 9v6"></path></svg>
                        <p class="text-xs text-slate-500 mt-2">QR Code Generated</p>
                    </div>
                </div>
            </div>
            <p class="text-sm text-slate-500 mb-4">Scan this QR code to view reservation details</p>
            <div class="flex justify-center space-x-2">
                <button onclick="downloadQRCode('${qrCodePath}', ${reservationId})" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download w-4 h-4 mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7,10 12,15 17,10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Download QR Code
                </button>
                <button onclick="closeQRModal()" class="btn btn-secondary">
                    Close
                </button>
            </div>
        </div>
    `;
    
    // Show QR code in a modal
    const qrModal = document.getElementById('qr-code-modal');
    if (qrModal) {
        document.getElementById('qr-code-content').innerHTML = qrModalContent;
        const modalInstance = tailwind.Modal.getOrCreateInstance(qrModal);
        modalInstance.show();
    } else {
        // Fallback: show in alert if modal doesn't exist
        alert(`Reservation successful! QR Code saved at: ${qrCodePath}`);
    }
}

// Download QR code
function downloadQRCode(qrCodePath, reservationId) {
    const link = document.createElement('a');
    link.href = `/storage/${qrCodePath}`;
    link.download = `reservation_${reservationId}_qr.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Close QR code modal
function closeQRModal() {
    const qrModal = document.getElementById('qr-code-modal');
    if (qrModal) {
        const modalInstance = tailwind.Modal.getInstance(qrModal);
        if (modalInstance) {
            modalInstance.hide();
        }
    }
}
