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
    // For now, show a simple alert. You can implement a full details modal later
    alert(`Viewing details for reservation ID: ${reservationId}`);
    
    // TODO: Implement full details modal with:
    // - Vehicle information
    // - Passenger list
    // - QR code display
    // - Full reservation details
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
    // For driver field, we need to find the user ID by name since driver stores the name, not ID
    // We'll leave it empty and let the user select again, or find the user by name
    const driverSelect = document.getElementById('update_driver');
    if (driverSelect) {
        // Find the option that matches the driver name
        const options = Array.from(driverSelect.options);
        const driverOption = options.find(option => option.text === reservation.driver);
        if (driverOption) {
            driverSelect.value = driverOption.value;
        } else {
            driverSelect.value = ''; // Leave empty if no match found
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
    const reservationId = window.updateReservationId;
    if (!reservationId) {
        alert('No reservation selected for update');
        return;
    }
    
    // Get form data
    const formData = new FormData();
    formData.append('destination', document.getElementById('update_destination').value.trim());
    formData.append('longitude', document.getElementById('update_longitude').value.trim());
    formData.append('latitude', document.getElementById('update_latitude').value.trim());
    formData.append('driver', document.getElementById('update_driver').value.trim());
    formData.append('start_datetime', document.getElementById('update_start_datetime').value);
    formData.append('end_datetime', document.getElementById('update_end_datetime').value);
    formData.append('reason', document.getElementById('update_reason').value.trim());
    formData.append('reservation_type_id', document.getElementById('update_reservation_type_id').value);
    
    // Get selected passengers
    const passengerSelect = document.getElementById('update_passengers');
    if (passengerSelect && passengerSelect.tomselect) {
        const selectedPassengers = passengerSelect.tomselect.getValue();
        if (selectedPassengers && selectedPassengers.length > 0) {
            selectedPassengers.forEach(passengerId => {
                formData.append('passengers[]', passengerId);
            });
        }
    }
    
    // Validate form data
    if (!formData.get('destination') || !formData.get('driver') || 
        !formData.get('start_datetime') || !formData.get('end_datetime') || 
        !formData.get('reason') || !formData.get('reservation_type_id')) {
        alert('Please fill in all required fields');
        return;
    }
    
    if (formData.getAll('passengers[]').length === 0) {
        alert('Please select at least one passenger');
        return;
    }
    
    // Show loading state
    const updateBtn = document.querySelector('#update-reservation-modal .btn-primary');
    let originalText = 'Update';
    if (updateBtn) {
        originalText = updateBtn.textContent;
        updateBtn.textContent = 'Updating...';
        updateBtn.disabled = true;
    }
    
    // Submit update request
    fetch(`/my-reservation/${reservationId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            if (typeof showNotification_success !== 'undefined') {
                showNotification_success();
            }
            
            // Close modal using Tailwind modal system
            const modal = document.getElementById('update-reservation-modal');
            if (modal) {
                // Find and click the close button
                const closeButton = modal.querySelector('[data-tw-dismiss="modal"]');
                if (closeButton) {
                    closeButton.click();
                }
            }
            
            // Refresh the page to show updated data
            location.reload();
        } else {
            // Show error message
            if (typeof showNotification_error !== 'undefined') {
                showNotification_error();
            }
            alert(data.message || 'Failed to update reservation');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof showNotification_error !== 'undefined') {
            showNotification_error();
        }
        alert('An error occurred while updating the reservation');
    })
    .finally(() => {
        // Reset button state
        if (updateBtn) {
            updateBtn.textContent = originalText;
            updateBtn.disabled = false;
        }
    });
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
            // Show success message
            if (typeof showNotification_cancel_success !== 'undefined') {
                showNotification_cancel_success();
            }
            
            // Close modal using Tailwind modal system
            const modal = document.getElementById('cancel-confirmation-modal');
            if (modal) {
                // Find and click the close button
                const closeButton = modal.querySelector('[data-tw-dismiss="modal"]');
                if (closeButton) {
                    closeButton.click();
                }
            }
            
            // Refresh the page to show updated status
            location.reload();
        } else {
            // Show error message
            if (typeof showNotification_error !== 'undefined') {
                showNotification_error();
            }
            alert(data.message || 'Failed to cancel reservation');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof showNotification_error !== 'undefined') {
            showNotification_error();
        }
        alert('An error occurred while cancelling the reservation');
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
