// My Reservations JavaScript
console.log('=== MY RESERVATIONS JAVASCRIPT LOADING ===');

// Google Maps callback function - this will be called when the API loads
function initUpdateGoogleMaps() {
    console.log('Google Maps API loaded for update modal');
    // The actual initialization will happen when the modal opens
}

// Make the function globally available
window.initUpdateGoogleMaps = initUpdateGoogleMaps;

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing my reservations...');
    
    // Initialize search functionality
    initializeSearch();
    
    // Initialize pagination
    initializePagination();
    
    // Initialize cancel confirmation modal
    initializeCancelModal();
});

// Search functionality
function initializeSearch() {
    const searchInput = document.getElementById('search-reservations');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Set new timeout for better performance
            searchTimeout = setTimeout(() => {
                const searchTerm = this.value.toLowerCase();
                const reservationRows = document.querySelectorAll('#reservations-table-body tr');
                
                reservationRows.forEach(row => {
                    const vehicleName = row.querySelector('td:nth-child(1) .font-medium')?.textContent.toLowerCase() || '';
                    const plateNumber = row.querySelector('td:nth-child(1) .text-xs')?.textContent.toLowerCase() || '';
                    const destination = row.querySelector('td:nth-child(2) .font-medium')?.textContent.toLowerCase() || '';
                    const driver = row.querySelector('td:nth-child(3) .font-medium')?.textContent.toLowerCase() || '';
                    
                    if (vehicleName.includes(searchTerm) || plateNumber.includes(searchTerm) || 
                        destination.includes(searchTerm) || driver.includes(searchTerm)) {
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
                refreshReservations(page);
            }
        }
    });
    
    // Handle per page selector changes
    const perPageSelector = document.getElementById('per-page-selector');
    if (perPageSelector) {
        perPageSelector.addEventListener('change', function() {
            const perPage = parseInt(this.value);
            refreshReservations(1, perPage); // Reset to first page when changing per page
        });
    }
}

// Refresh reservations with pagination
function refreshReservations(page = 1, perPage = 10) {
    const url = `/my-reservation/data?page=${page}&per_page=${perPage}`;
    
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
            updateReservationsDisplay(data.reservations, data.pagination);
        }
    })
    .catch(error => {
        console.error('Error refreshing reservations:', error);
    });
}

// Update reservations display with new data
function updateReservationsDisplay(reservations, pagination = null) {
    const container = document.getElementById('reservations-table-body');
    if (!container) return;
    
    let html = '';
    
    if (reservations.length === 0) {
        html = `
            <tr>
                <td colspan="7" class="text-center py-8 text-slate-500">
                    <div class="flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 mb-3"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <div class="text-lg font-medium">No reservations found</div>
                        <div class="text-sm text-slate-500 mt-1">No reservations match your search criteria</div>
                    </div>
                </td>
            </tr>
        `;
    } else {
        reservations.forEach(reservation => {
            const statusHtml = getStatusHtml(reservation.status);
            const dateTimeHtml = getDateTimeHtml(reservation.start_datetime, reservation.end_datetime);
            
            html += `
                <tr class="intro-x">
                    <td>
                        <div class="flex items-center">
                            <div class="w-10 h-10 image-fit zoom-in bg-slate-200 rounded-full flex items-center justify-center">
                                ${reservation.vehicle?.vehicle_image ? 
                                    `<img src="/storage/vehicles/${reservation.vehicle.vehicle_image}" alt="${reservation.vehicle.vehicle_name || 'Vehicle'}" class="w-full h-full object-cover rounded-full">` : 
                                    `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-slate-400"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21,15 16,10 5,21"></polyline></svg>`
                                }
                            </div>
                            <div class="ml-3">
                                <div class="font-medium">${reservation.vehicle?.vehicle_name || 'N/A'}</div>
                                <div class="text-xs text-slate-500">${reservation.vehicle?.plate_number || 'N/A'}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            <div class="font-medium">${reservation.destination}</div>
                            <div class="text-xs">${reservation.longitude}, ${reservation.latitude}</div>
                        </div>
                    </td>
                    <td>
                        <div class="font-medium">${reservation.driver}</div>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            ${dateTimeHtml}
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center">
                            ${statusHtml}
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="font-medium">${reservation.reservation_type?.reservation_name || 'N/A'}</div>
                    </td>
                    <td class="table-report__action w-56">
                                                 <div class="flex justify-center items-center">
                             <a class="flex items-center mr-3" href="javascript:;" onclick="viewReservationDetails(${reservation.id})">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4 mr-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> Details
                             </a>
                             ${reservation.status === 'pending' ? `
                                 <a class="flex items-center mr-3" href="javascript:;" onclick="updateReservation(${reservation.id})">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit w-4 h-4 mr-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 10.5-10.5z"></path></svg> Update
                                 </a>
                                 <a class="flex items-center text-danger" href="javascript:;" onclick="cancelReservation(${reservation.id})">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4 mr-1"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Cancel
                                 </a>
                             ` : ''}
                         </div>
                    </td>
                </tr>
            `;
        });
    }
    
    container.innerHTML = html;
    
    // Update reservation count with pagination info
    if (pagination) {
        updateReservationCount(pagination);
    }
}

// Get status HTML
function getStatusHtml(status) {
    switch(status) {
        case 'pending':
            return `<div class="flex items-center text-warning">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-4 h-4 mr-2"><circle cx="12" cy="12" r="10"></circle><polyline points="12,6 12,12 16,14"></polyline></svg> Pending
            </div>`;
        case 'approved':
            return `<div class="flex items-center text-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle w-4 h-4 mr-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Approved
            </div>`;
        case 'rejected':
            return `<div class="flex items-center text-danger">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle w-4 h-4 mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> Rejected
            </div>`;
        case 'completed':
            return `<div class="flex items-center text-info">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> Completed
            </div>`;
        case 'cancelled':
            return `<div class="flex items-center text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4 mr-1"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Cancelled
            </div>`;
        default:
            return `<div class="flex items-center text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-help-circle w-4 h-4 mr-2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> ${status.charAt(0).toUpperCase() + status.slice(1)}
            </div>`;
    }
}

// Get date time HTML
function getDateTimeHtml(startDatetime, endDatetime) {
    const startDate = new Date(startDatetime);
    const endDate = new Date(endDatetime);
    
    return `
        <div class="font-medium">${startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</div>
        <div class="text-xs">${startDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })} - ${endDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}</div>
    `;
}

// Update reservation count display with pagination info
function updateReservationCount(pagination) {
    const countElement = document.querySelector('.intro-y.col-span-12 .hidden.md\\:block.mx-auto.text-slate-500');
    if (countElement && pagination) {
        const { from, to, total } = pagination;
        if (total > 0) {
            countElement.textContent = `Showing ${from} to ${to} of ${total} reservations`;
        } else {
            countElement.textContent = 'No reservations found';
        }
    }
}

// View reservation details
function viewReservationDetails(reservationId) {
    // Store the reservation ID for the details modal
    window.detailsReservationId = reservationId;
    
    // Show details modal using Tailwind modal system
    const modal = document.getElementById('reservation-details-modal');
    if (modal) {
        // Use Tailwind modal toggle
        const toggleButton = document.createElement('button');
        toggleButton.setAttribute('data-tw-toggle', 'modal');
        toggleButton.setAttribute('data-tw-target', '#reservation-details-modal');
        toggleButton.style.display = 'none';
        document.body.appendChild(toggleButton);
        
        // Trigger the modal
        toggleButton.click();
        
        // Remove the temporary button
        document.body.removeChild(toggleButton);
        
        // Load reservation details
        loadReservationDetails(reservationId);
    }
}

// Load reservation details for display
function loadReservationDetails(reservationId) {
    // Show loading state
    const loadingDiv = document.getElementById('details-loading');
    const contentDiv = document.getElementById('details-content');
    
    if (loadingDiv) loadingDiv.classList.remove('hidden');
    if (contentDiv) contentDiv.classList.add('hidden');
    
    // Fetch reservation details
    fetch(`/my-reservation/${reservationId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateDetailsModal(data.reservation);
        } else {
            showDetailsErrorMessage(data.message || 'Failed to load reservation details');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showDetailsErrorMessage('An error occurred while loading reservation details');
    })
    .finally(() => {
        // Hide loading state
        if (loadingDiv) loadingDiv.classList.add('hidden');
        if (contentDiv) contentDiv.classList.remove('hidden');
    });
}

// Populate the details modal with reservation data
function populateDetailsModal(reservation) {
    // Vehicle Information
    const vehicleImage = document.getElementById('details-vehicle-image');
    const vehicleName = document.getElementById('details-vehicle-name');
    const plateNumber = document.getElementById('details-plate-number');
    
    if (vehicleImage) {
        if (reservation.vehicle.vehicle_image) {
            vehicleImage.innerHTML = `<img src="/storage/vehicles/${reservation.vehicle.vehicle_image}" alt="${reservation.vehicle.vehicle_name}" class="w-full h-full object-cover rounded-full">`;
        } else {
            vehicleImage.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-gray-400"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21,15 16,10 5,21"></polyline></svg>`;
        }
    }
    
    if (vehicleName) vehicleName.textContent = reservation.vehicle.vehicle_name;
    if (plateNumber) plateNumber.textContent = reservation.vehicle.plate_number;
    
    // Reservation Details
    if (document.getElementById('details-destination')) document.getElementById('details-destination').textContent = reservation.destination;
    if (document.getElementById('details-driver')) document.getElementById('details-driver').textContent = reservation.driver;
    if (document.getElementById('details-reason')) document.getElementById('details-reason').textContent = reservation.reason;
    if (document.getElementById('details-reservation-type')) document.getElementById('details-reservation-type').textContent = reservation.reservation_type.reservation_name;
    if (document.getElementById('details-requested-by')) document.getElementById('details-requested-by').textContent = reservation.requested_by;
    
    // Format and display dates
    if (document.getElementById('details-start-datetime')) {
        const startDate = new Date(reservation.start_datetime);
        document.getElementById('details-start-datetime').textContent = startDate.toLocaleString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }
    
    if (document.getElementById('details-end-datetime')) {
        const endDate = new Date(reservation.end_datetime);
        document.getElementById('details-end-datetime').textContent = endDate.toLocaleString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }
    
    // Status with color coding
    const statusElement = document.getElementById('details-status');
    if (statusElement) {
        const status = reservation.status;
        let statusHtml = '';
        
        switch(status) {
            case 'pending':
                statusHtml = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>`;
                break;
            case 'approved':
                statusHtml = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Approved</span>`;
                break;
            case 'rejected':
                statusHtml = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>`;
                break;
            case 'completed':
                statusHtml = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Completed</span>`;
                break;
            case 'cancelled':
                statusHtml = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Cancelled</span>`;
                break;
            default:
                statusHtml = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
        }
        
        statusElement.innerHTML = statusHtml;
    }
    
    // Passengers
    const passengersContainer = document.getElementById('details-passengers');
    if (passengersContainer) {
        if (reservation.passengers && reservation.passengers.length > 0) {
            let passengersHtml = '';
            reservation.passengers.forEach(passenger => {
                passengersHtml += `
                    <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-b-0">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">${passenger.name}</div>
                                <div class="text-sm text-gray-500">Passenger</div>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">${passenger.status}</span>
                    </div>
                `;
            });
            passengersContainer.innerHTML = passengersHtml;
        } else {
            passengersContainer.innerHTML = '<div class="text-center py-4 text-gray-500">No passengers assigned</div>';
        }
    }
    
    // QR Code
    const qrcodeContainer = document.getElementById('details-qrcode');
    if (qrcodeContainer) {
        if (reservation.qrcode) {
            // Check if it's a file path (starts with 'qrcodes/')
            if (reservation.qrcode.startsWith('qrcodes/')) {
                qrcodeContainer.innerHTML = `<img src="/storage/${reservation.qrcode}" alt="QR Code" class="w-32 h-32">`;
            } else {
                // If it's JSON data, show a placeholder or generate QR code
                qrcodeContainer.innerHTML = '<div class="text-gray-500">QR Code data available</div>';
            }
        } else {
            qrcodeContainer.innerHTML = '<div class="text-gray-500">No QR Code available</div>';
        }
    }
    
    // Coordinates
    if (document.getElementById('details-longitude')) document.getElementById('details-longitude').textContent = reservation.longitude;
    if (document.getElementById('details-latitude')) document.getElementById('details-latitude').textContent = reservation.latitude;
}

// Update reservation
function updateReservation(reservationId) {
    // Store the reservation ID for the update modal
    window.updateReservationId = reservationId;
    
    // Show update modal using Tailwind modal system
    const modal = document.getElementById('update-reservation-modal');
    if (modal) {
        // Use Tailwind modal toggle
        const toggleButton = document.createElement('button');
        toggleButton.setAttribute('data-tw-toggle', 'modal');
        toggleButton.setAttribute('data-tw-target', '#update-reservation-modal');
        toggleButton.style.display = 'none';
        document.body.appendChild(toggleButton);
        
        // Trigger the modal
        toggleButton.click();
        
        // Remove the temporary button
        document.body.removeChild(toggleButton);
        
        // Load reservation data for editing
        loadReservationForUpdate(reservationId);
    }
}

// Load reservation data for editing
function loadReservationForUpdate(reservationId) {
    // Show loading state
    const updateBtn = document.querySelector('#update-reservation-modal .btn-primary');
    if (updateBtn) {
        const originalText = updateBtn.textContent;
        updateBtn.textContent = 'Loading...';
        updateBtn.disabled = true;
        
        // Fetch reservation data
        fetch(`/my-reservation/${reservationId}/edit`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateUpdateForm(data.reservation);
            } else {
                alert(data.message || 'Failed to load reservation data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading reservation data');
            // Reset button state on error
            if (updateBtn) {
                updateBtn.textContent = originalText;
                updateBtn.disabled = false;
            }
        })
        .finally(() => {
            // Reset button state
            if (updateBtn) {
                updateBtn.textContent = originalText;
                updateBtn.disabled = false;
            }
        });
    } else {
        // If button not found, just fetch data without loading state
        fetch(`/my-reservation/${reservationId}/edit`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateUpdateForm(data.reservation);
            } else {
                alert(data.message || 'Failed to load reservation data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading reservation data');
        });
    }
}

// Populate the update form with reservation data
function populateUpdateForm(reservation) {
    // Populate form fields
    document.getElementById('update_destination').value = reservation.destination || '';
    document.getElementById('update_longitude').value = reservation.longitude || '';
    document.getElementById('update_latitude').value = reservation.latitude || '';
    // Set driver field using driver_user_id if available
    const driverSelect = document.getElementById('update_driver');
    if (driverSelect) {
        if (reservation.driver_user_id) {
            // Use the driver_user_id directly
            driverSelect.value = reservation.driver_user_id;
        } else {
            // Fallback: try to find by name (for existing data)
            const options = Array.from(driverSelect.options);
            const driverOption = options.find(option => option.text === reservation.driver);
            if (driverOption) {
                driverSelect.value = driverOption.value;
            } else {
                driverSelect.value = ''; // Leave empty if no match found
            }
        }
    }
    document.getElementById('update_start_datetime').value = reservation.start_datetime ? reservation.start_datetime.slice(0, 16) : '';
    document.getElementById('update_end_datetime').value = reservation.end_datetime ? reservation.end_datetime.slice(0, 16) : '';
    document.getElementById('update_reason').value = reservation.reason || '';
    document.getElementById('update_reservation_type_id').value = reservation.reservation_type_id || '';
    
    // Store passenger data for later population after Tom Select is initialized
    window.pendingPassengerData = reservation.passengers || [];
    console.log('Stored passenger data:', window.pendingPassengerData);
    console.log('Passenger data structure:', window.pendingPassengerData.length > 0 ? Object.keys(window.pendingPassengerData[0]) : 'No passengers');
    
    // Debug: Log the reservation data
    console.log('Reservation data received:', reservation);
    console.log('Driver field:', reservation.driver);
    console.log('Driver user ID:', reservation.driver_user_id);
    
    // Update map marker if coordinates exist and map is ready
    if (reservation.longitude && reservation.latitude) {
        // Wait for map to be ready before updating
        const checkMapReady = setInterval(() => {
            if (updateMap && updateMarker && typeof google !== 'undefined') {
                try {
                    const lat = parseFloat(reservation.latitude);
                    const lng = parseFloat(reservation.longitude);
                    
                    if (!isNaN(lat) && !isNaN(lng)) {
                        // Update marker position
                        const newPosition = { lat: lat, lng: lng };
                        updateMarker.setPosition(newPosition);
                        
                        // Center map on marker
                        updateMap.setCenter(newPosition);
                        updateMap.setZoom(15);
                        
                        console.log('Map updated with coordinates:', newPosition);
                        clearInterval(checkMapReady);
                    } else {
                        console.warn('Invalid coordinates:', reservation.latitude, reservation.longitude);
                        clearInterval(checkMapReady);
                    }
                } catch (error) {
                    console.error('Error updating map coordinates:', error);
                    clearInterval(checkMapReady);
                }
            }
        }, 100);
        
        // Stop checking after 5 seconds
        setTimeout(() => {
            clearInterval(checkMapReady);
        }, 5000);
    }
    
    // Set minimum dates for datetime inputs
    setUpdateMinimumDates();
    
    // Try to populate passengers if Tom Select is already available
    populatePassengersIfReady();
    
    // Ensure Tom Select is initialized
    ensureUpdateModalTomSelectInitialized();
    
    // Set up a periodic check to populate passengers if Tom Select becomes available later
    if (window.pendingPassengerData && window.pendingPassengerData.length > 0) {
        const passengerCheckInterval = setInterval(() => {
            if (populatePassengersIfReady()) {
                clearInterval(passengerCheckInterval);
            }
        }, 500); // Check every 500ms
        
        // Stop checking after 10 seconds
        setTimeout(() => {
            clearInterval(passengerCheckInterval);
        }, 10000);
    }
}

// Handle update form submission
function submitUpdateForm() {
    console.log('=== SUBMIT UPDATE FORM FUNCTION CALLED ===');
    console.log('Function execution started at:', new Date().toISOString());
    
    try {
        const reservationId = window.updateReservationId;
        console.log('Reservation ID from window:', reservationId);
        
        if (!reservationId) {
            alert('No reservation selected for update');
            return;
        }
        
        // Get form data - only include fields that have values
        const formData = new FormData();
        console.log('FormData object created:', formData);
        
        // No method spoofing needed since we're using POST
        console.log('Using POST method for update');
        
        // Add CSRF token to FormData
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        formData.append('_token', csrfToken);
        console.log('CSRF token added to FormData');
        
        // Debug: Check if form elements exist and their values
        console.log('=== FORM ELEMENTS CHECK ===');
        console.log('Form submission started - checking elements...');
        console.log('update_destination element:', document.getElementById('update_destination'));
        console.log('update_driver element:', document.getElementById('update_driver'));
        console.log('update_start_datetime element:', document.getElementById('update_start_datetime'));
        
        console.log('=== COLLECTING FORM VALUES ===');
        
        const destination = document.getElementById('update_destination').value.trim();
        console.log('Destination value:', destination);
        if (destination) {
            formData.append('destination', destination);
            console.log('Destination added to FormData');
        }
        
        const longitude = document.getElementById('update_longitude').value.trim();
        console.log('Longitude value:', longitude);
        if (longitude) {
            formData.append('longitude', longitude);
            console.log('Longitude added to FormData');
        }
        
        const latitude = document.getElementById('update_latitude').value.trim();
        console.log('Latitude value:', latitude);
        if (latitude) {
            formData.append('latitude', latitude);
            console.log('Latitude added to FormData');
        }
        
        const driver = document.getElementById('update_driver').value.trim();
        console.log('Driver value:', driver);
        if (driver) {
            formData.append('driver', driver);
            console.log('Driver added to FormData');
        }
        
        const startDatetime = document.getElementById('update_start_datetime').value;
        console.log('Start datetime value:', startDatetime);
        if (startDatetime) {
            formData.append('start_datetime', startDatetime);
            console.log('Start datetime added to FormData');
        }
        
        const endDatetime = document.getElementById('update_end_datetime').value;
        console.log('End datetime value:', endDatetime);
        if (endDatetime) {
            formData.append('end_datetime', endDatetime);
            console.log('End datetime added to FormData');
        }
        
        const reason = document.getElementById('update_reason').value.trim();
        console.log('Reason value:', reason);
        if (reason) {
            formData.append('reason', reason);
            console.log('Reason added to FormData');
        }
        
        const reservationTypeId = document.getElementById('update_reservation_type_id').value;
        console.log('Reservation type ID value:', reservationTypeId);
        if (reservationTypeId) {
            formData.append('reservation_type_id', reservationTypeId);
            console.log('Reservation type ID added to FormData');
        }
        
        // Get selected passengers - only include if passengers are selected
        const passengerSelect = document.getElementById('update_passengers');
        if (passengerSelect && passengerSelect.tomselect) {
            const selectedPassengers = passengerSelect.tomselect.getValue();
            if (selectedPassengers && selectedPassengers.length > 0) {
                // Filter out empty values and add to form data
                selectedPassengers.filter(passengerId => passengerId && passengerId.trim() !== '').forEach(passengerId => {
                    formData.append('passengers[]', passengerId);
                });
            }
        }
        
        // Validate form data - make validation more flexible for partial updates
        let hasAtLeastOneField = false;
        
        // Check if at least one field is filled
        if (formData.get('destination') || formData.get('driver') || 
            formData.get('start_datetime') || formData.get('end_datetime') || 
            formData.get('reason') || formData.get('reservation_type_id') ||
            formData.getAll('passengers[]').length > 0) {
            hasAtLeastOneField = true;
        }
        
        if (!hasAtLeastOneField) {
            alert('Please fill in at least one field to update');
            return;
        }
        
        // Additional validation for datetime fields if both are provided
        if (formData.get('start_datetime') && formData.get('end_datetime')) {
            const startDate = new Date(formData.get('start_datetime'));
            const endDate = new Date(formData.get('end_datetime'));
            
            if (startDate >= endDate) {
                alert('End date must be after start date');
                return;
            }
        }
        
        // Show loading state
        const updateBtn = document.querySelector('#update-reservation-modal .btn-primary');
        let originalText = 'Update';
        if (updateBtn) {
            originalText = updateBtn.textContent;
            updateBtn.textContent = 'Updating...';
            updateBtn.disabled = true;
        }
        
        // Debug: Log what's being sent
        console.log('=== FORM DATA CONTENTS ===');
        
        // Convert FormData to array to check length and iterate
        const formDataArray = Array.from(formData.entries());
        console.log('FormData entries count:', formDataArray.length);
        console.log('Submitting update with data:');
        formDataArray.forEach(([key, value]) => {
            console.log(`FormData entry: ${key} = ${value}`);
        });
        
        // Check if FormData is empty
        if (formDataArray.length === 0) {
            console.error('ERROR: FormData is empty! No data to send.');
            alert('No form data collected. Please check the form fields.');
            return;
        }
    
        // Submit update request
        fetch(`/my-reservation/${reservationId}`, {
            method: 'POST', // Use POST with method spoofing (_method: 'PUT')
            headers: {
                'Accept': 'application/json'
                // Don't set Content-Type - let the browser set it for FormData
                // CSRF token is now in FormData
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message using notification helper
                showUpdateSuccessMessage(data.message || 'Reservation updated successfully!');
                
                // Close modal using Tailwind modal system
                const modal = document.getElementById('update-reservation-modal');
                if (modal) {
                    // Find and click the close button
                    const closeButton = modal.querySelector('[data-tw-dismiss="modal"]');
                    if (closeButton) {
                        closeButton.click();
                    }
                }
                
                // Delay page reload to allow notification toast to be visible
                setTimeout(() => {
                    location.reload();
                }, 2000); // 2 second delay
            } else {
                // Show error message using notification helper
                showErrorMessage(data.message || 'Failed to update reservation');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('An error occurred while updating the reservation');
        })
        .finally(() => {
            // Reset button state
            if (updateBtn) {
                updateBtn.textContent = originalText;
                updateBtn.disabled = false;
            }
        });
    } catch (error) {
        console.error('Error in submitUpdateForm:', error);
        alert('An error occurred while processing the form: ' + error.message);
        
        // Reset button state
        const updateBtn = document.querySelector('#update-reservation-modal .btn-primary');
        if (updateBtn) {
            updateBtn.textContent = 'Update';
            updateBtn.disabled = false;
        }
    }
}

// Set minimum dates for update form
function setUpdateMinimumDates() {
    const startInput = document.getElementById('update_start_datetime');
    const endInput = document.getElementById('update_end_datetime');
    
    if (startInput && endInput) {
        // Set minimum start date to today
        const today = new Date();
        const todayString = today.toISOString().slice(0, 16);
        startInput.min = todayString;
        
        // Set minimum end date to start date
        startInput.addEventListener('change', function() {
            const startDate = new Date(this.value);
            const minEndDate = new Date(startDate.getTime() + (60 * 60 * 1000)); // 1 hour after start
            endInput.min = minEndDate.toISOString().slice(0, 16);
        });
    }
}

// Cancel reservation
function cancelReservation(reservationId) {
    // Store the reservation ID for confirmation
    window.cancelReservationId = reservationId;
    
    // Show confirmation modal using Tailwind modal system
    const modal = document.getElementById('cancel-confirmation-modal');
    if (modal) {
        // Use Tailwind modal toggle
        const toggleButton = document.createElement('button');
        toggleButton.setAttribute('data-tw-toggle', 'modal');
        toggleButton.setAttribute('data-tw-target', '#cancel-confirmation-modal');
        toggleButton.style.display = 'none';
        document.body.appendChild(toggleButton);
        
        // Trigger the modal
        toggleButton.click();
        
        // Remove the temporary button
        document.body.removeChild(toggleButton);
    }
}

// Initialize cancel confirmation modal
function initializeCancelModal() {
    const confirmCancelBtn = document.getElementById('confirm-cancel-btn');
    if (confirmCancelBtn) {
        confirmCancelBtn.addEventListener('click', function() {
            const reservationId = window.cancelReservationId;
            if (reservationId) {
                performCancelReservation(reservationId);
            }
        });
    }
}

// Perform the actual cancellation
function performCancelReservation(reservationId) {
    // Show loading state
    const confirmBtn = document.getElementById('confirm-cancel-btn');
    const originalText = confirmBtn.textContent;
    confirmBtn.textContent = 'Cancelling...';
    confirmBtn.disabled = true;
    
    // Make API call to cancel reservation
    fetch(`/my-reservation/${reservationId}/cancel`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
            .then(data => {
            if (data.success) {
                // Show success message using notification helper
                showCancelSuccessMessage(data.message || 'Reservation cancelled successfully!');
                
                // Close modal using Tailwind modal system
                const modal = document.getElementById('cancel-confirmation-modal');
                if (modal) {
                    // Find and click the close button
                    const closeButton = modal.querySelector('[data-tw-dismiss="modal"]');
                    if (closeButton) {
                        closeButton.click();
                    }
                }
                
                // Delay page reload to allow notification toast to be visible
                setTimeout(() => {
                    location.reload();
                }, 2000); // 2 second delay
            } else {
                // Show error message using notification helper
                showErrorMessage(data.message || 'Failed to cancel reservation');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('An error occurred while cancelling the reservation');
        })
    .finally(() => {
        // Reset button state
        confirmBtn.textContent = originalText;
        confirmBtn.disabled = false;
    });
}

// Initialize Tom Select for update modal
function initializeUpdateModalTomSelect() {
    const passengerSelect = document.getElementById('update_passengers');
    if (!passengerSelect) {
        console.error('Passenger select element not found');
        return;
    }
    
    // If Tom Select is already initialized, don't reinitialize
    if (passengerSelect.tomselect) {
        console.log('Tom Select already initialized, skipping re-initialization');
        return;
    }
    
    // If Tom Select is not initialized, initialize it
    if (typeof TomSelect !== 'undefined') {
        console.log('Initializing Tom Select for passenger selection in update modal');
        try {
            passengerSelect.tomselect = new TomSelect('#update_passengers', {
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
                    console.log('Tom Select initialized successfully in update modal');
                    // Now populate the passengers if we have pending data
                    if (window.pendingPassengerData && window.pendingPassengerData.length > 0) {
                        console.log('Populating passengers after initialization:', window.pendingPassengerData);
                        
                        // Clear existing selections first
                        this.clear();
                        
                        // Add each passenger
                        window.pendingPassengerData.forEach(passenger => {
                            console.log('Adding passenger:', passenger);
                            this.addItem(passenger.user_id);
                        });
                        
                        // Clear the pending data
                        window.pendingPassengerData = null;
                    }
                },
                onItemAdd: function(value, item) {
                    console.log('Passenger added in update modal:', value, item);
                },
                onItemRemove: function(value, item) {
                    console.log('Passenger removed in update modal:', value, item);
                }
            });
        } catch (error) {
            console.error('Error initializing Tom Select in update modal:', error);
        }
    } else {
        console.warn('Tom Select library not available');
    }
}

// Initialize Tom Select when update modal is shown
document.addEventListener('DOMContentLoaded', function() {
    const updateModal = document.getElementById('update-reservation-modal');
    if (updateModal) {
        // Use MutationObserver to detect when modal is shown
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    const modal = mutation.target;
                    if (modal.style.display !== 'none') {
                        // Modal is shown
                        setTimeout(() => {
                            console.log('Modal shown, initializing Tom Select...');
                            ensureUpdateModalTomSelectInitialized();
                            
                            // Initialize map if not already done
                            if (!updateMap) {
                                setTimeout(() => {
                                    initUpdateGoogleMaps();
                                }, 100); // Small delay to ensure map container is fully rendered
                            }
                        }, 300); // Increased delay to ensure modal is fully rendered
                    }
                }
            });
        });
        
        // Start observing the modal for style changes
        observer.observe(updateModal, {
            attributes: true,
            attributeFilter: ['style']
        });
        
        // Also listen for modal open events as backup
        updateModal.addEventListener('shown.bs.modal', function() {
            console.log('Modal shown event triggered');
            setTimeout(() => {
                ensureUpdateModalTomSelectInitialized();
                if (!updateMap) {
                    setTimeout(() => {
                        initUpdateGoogleMaps();
                    }, 100);
                }
            }, 300);
        });
        
        // Listen for Tailwind modal events
        updateModal.addEventListener('show', function() {
            console.log('Tailwind modal show event triggered');
            setTimeout(() => {
                ensureUpdateModalTomSelectInitialized();
                if (!updateMap) {
                    setTimeout(() => {
                        initUpdateGoogleMaps();
                    }, 100);
                }
            }, 300);
        });
        
        updateModal.addEventListener('shown', function() {
            console.log('Tailwind modal shown event triggered');
            setTimeout(() => {
                ensureUpdateModalTomSelectInitialized();
                if (!updateMap) {
                    setTimeout(() => {
                        initUpdateGoogleMaps();
                    }, 100);
                }
            }, 300);
        });
        
        // Listen for modal close events to reset map
        updateModal.addEventListener('hide', function() {
            console.log('Modal closing, resetting map...');
            resetUpdateMap();
        });
        
        updateModal.addEventListener('hidden', function() {
            console.log('Modal hidden, map reset complete');
        });
    }
});

// Google Maps functionality for update modal
let updateMap = null;
let updateMarker = null;

// Initialize Google Maps for update modal
function initUpdateGoogleMaps() {
    console.log('Initializing Google Maps for update modal...');
    
    // Check if map container exists
    const mapContainer = document.getElementById('update_map');
    if (!mapContainer) {
        console.error('Map container not found');
        return;
    }
    
    // Check if map container has dimensions
    const rect = mapContainer.getBoundingClientRect();
    if (rect.width === 0 || rect.height === 0) {
        console.log('Map container has no dimensions, waiting...');
        setTimeout(() => {
            initUpdateGoogleMaps();
        }, 200);
        return;
    }
    
    // Check if Google Maps is available
    if (typeof google === 'undefined' || !google.maps) {
        console.error('Google Maps not available');
        return;
    }
    
    // Set default coordinates (Manila, Philippines)
    const defaultLat = 14.5995;
    const defaultLng = 120.9842;
    
    try {
        // Create map
        updateMap = new google.maps.Map(mapContainer, {
        center: { lat: defaultLat, lng: defaultLng },
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            }
        ]
    });
    
    // Create marker (using deprecated Marker for compatibility, but can be updated to AdvancedMarkerElement later)
    updateMarker = new google.maps.Marker({
        position: { lat: defaultLat, lng: defaultLng },
        map: updateMap,
        draggable: true,
        title: 'Click to set location',
        animation: google.maps.Animation.DROP
    });
    
    // Set initial coordinates
    document.getElementById('update_longitude').value = defaultLng.toFixed(6);
    document.getElementById('update_latitude').value = defaultLat.toFixed(6);
    
    // Add click listener to map
    updateMap.addListener('click', function(event) {
        const lat = event.latLng.lat();
        const lng = event.latLng.lng();
        
        // Update marker position
        updateMarker.setPosition(event.latLng);
        
        // Update coordinate inputs
        document.getElementById('update_longitude').value = lng.toFixed(6);
        document.getElementById('update_latitude').value = lat.toFixed(6);
        
        // Reverse geocode to get address
        reverseGeocode(lat, lng);
    });
    
    // Add drag listener to marker
    updateMarker.addListener('dragend', function(event) {
        const lat = event.latLng.lat();
        const lng = event.latLng.lng();
        
        // Update coordinate inputs
        document.getElementById('update_longitude').value = lng.toFixed(6);
        document.getElementById('update_latitude').value = lat.toFixed(6);
        
        // Reverse geocode to get address
        reverseGeocode(lat, lng);
    });
    
        console.log('Google Maps initialized for update modal');
        
        // Trigger a resize event to ensure proper rendering
        setTimeout(() => {
            if (updateMap) {
                google.maps.event.trigger(updateMap, 'resize');
                console.log('Map resize triggered');
            }
        }, 100);
        
    } catch (error) {
        console.error('Error initializing Google Maps:', error);
        updateMap = null;
        updateMarker = null;
    }
}

// Ensure Tom Select is properly initialized in update modal
function ensureUpdateModalTomSelectInitialized() {
    const passengerSelect = document.getElementById('update_passengers');
    if (!passengerSelect) return;
    
    // If Tom Select is already initialized, don't reinitialize
    if (passengerSelect.tomselect) {
        console.log('Tom Select already initialized in update modal, skipping re-initialization');
        // Check if we need to populate passengers
        if (window.pendingPassengerData && window.pendingPassengerData.length > 0) {
            console.log('Tom Select already initialized, populating passengers...');
            populatePassengersIfReady();
        }
        return;
    }
    
    // If Tom Select is not initialized, initialize it
    if (typeof TomSelect !== 'undefined') {
        console.log('Initializing Tom Select for passenger selection in update modal');
        try {
            passengerSelect.tomselect = new TomSelect('#update_passengers', {
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
                    console.log('Tom Select initialized successfully in update modal');
                    // Now populate the passengers if we have pending data
                    if (window.pendingPassengerData && window.pendingPassengerData.length > 0) {
                        console.log('Populating passengers after initialization:', window.pendingPassengerData);
                        
                        // Clear existing selections first
                        this.clear();
                        
                        // Add each passenger
                        window.pendingPassengerData.forEach(passenger => {
                            console.log('Adding passenger:', passenger);
                            this.addItem(passenger.passenger_id);
                        });
                        
                        // Clear the pending data
                        window.pendingPassengerData = null;
                    }
                },
                onItemAdd: function(value, item) {
                    console.log('Passenger added in update modal:', value, item);
                },
                onItemRemove: function(value, item) {
                    console.log('Passenger removed in update modal:', value, item);
                }
            });
        } catch (error) {
            console.error('Error initializing Tom Select in update modal:', error);
        }
    } else {
        console.warn('Tom Select library not available');
    }
}

// Populate passengers if Tom Select is ready
function populatePassengersIfReady() {
    const passengerSelect = document.getElementById('update_passengers');
    if (passengerSelect && passengerSelect.tomselect && window.pendingPassengerData && window.pendingPassengerData.length > 0) {
        console.log('Populating passengers in populatePassengersIfReady...');
        
        const tomSelect = passengerSelect.tomselect;
        
        // Clear existing selections first
        tomSelect.clear();
        
        // Add each passenger
        window.pendingPassengerData.forEach(passenger => {
            console.log('Adding passenger in populatePassengersIfReady:', passenger);
            tomSelect.addItem(passenger.passenger_id);
        });
        
        // Clear the pending data
        window.pendingPassengerData = null;
        return true; // Successfully populated
    }
    return false; // Not ready or no data
}

// Reset map when modal is closed
function resetUpdateMap() {
    if (updateMap) {
        updateMap = null;
    }
    if (updateMarker) {
        updateMarker = null;
    }
}

// Reverse geocoding function
function reverseGeocode(lat, lng) {
    if (typeof google !== 'undefined' && google.maps && google.maps.Geocoder) {
        const geocoder = new google.maps.Geocoder();
        
        geocoder.geocode({ location: { lat: lat, lng: lng } }, function(results, status) {
            if (status === 'OK' && results[0]) {
                const address = results[0].formatted_address;
                document.getElementById('update_destination').value = address;
            }
        });
    }
}

// Notification helper functions (matching vehicle management pattern)
function showSuccessMessage(message) {
    if (typeof showNotification_success !== 'undefined') {
        showNotification_success();
    } else {
        // Fallback to console if notification system not available
        console.log('Success:', message);
    }
}

function showErrorMessage(message) {
    if (typeof showNotification_error !== 'undefined') {
        showNotification_error();
    } else {
        // Fallback to console if notification system not available
        console.log('Error:', message);
    }
}

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

function showUpdateSuccessMessage(message) {
    if (typeof showNotification_update_success !== 'undefined') {
        showNotification_update_success();
    } else if (typeof showNotification_success !== 'undefined') {
        // Fallback to general success notification
        showNotification_success();
    } else {
        // Fallback to console if notification system not available
        console.log('Success:', message);
    }
}

function showCancelSuccessMessage(message) {
    if (typeof showNotification_cancel_success !== 'undefined') {
        showNotification_cancel_success();
    } else if (typeof showNotification_success !== 'undefined') {
        // Fallback to general success notification
        showNotification_success();
    } else {
        // Fallback to console if notification system not available
        console.log('Success:', message);
    }
}

function showDetailsErrorMessage(message) {
    if (typeof showNotification_details_error !== 'undefined') {
        showNotification_details_error();
    } else if (typeof showNotification_error !== 'undefined') {
        // Fallback to general error notification
        showNotification_error();
    } else {
        // Fallback to console if notification system not available
        console.log('Error:', message);
    }
}
