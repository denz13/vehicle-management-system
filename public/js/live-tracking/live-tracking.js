// Live Tracking JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize live tracking functionality
    initializeLiveTracking();
});

function initializeLiveTracking() {
    // Auto-refresh reservations every 30 seconds
    setInterval(refreshReservations, 30000);
    
    console.log('Live tracking system initialized');
}

function refreshReservations() {
    const refreshButton = document.querySelector('button[onclick="refreshReservations()"]');
    const originalText = refreshButton.innerHTML;
    
    // Show loading state
    refreshButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-2 w-4 h-4 mr-1 animate-spin"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg> Refreshing...';
    refreshButton.disabled = true;
    
    // Fetch updated reservations
    fetch('/live-tracking/get-reservations', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateReservationsDisplay(data.reservations);
            showSuccessMessage('Reservations refreshed successfully');
        } else {
            showErrorMessage('Failed to refresh reservations: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error refreshing reservations:', error);
        showErrorMessage('Error refreshing reservations');
    })
    .finally(() => {
        // Restore button state
        refreshButton.innerHTML = originalText;
        refreshButton.disabled = false;
    });
}

function updateReservationsDisplay(reservations) {
    const container = document.getElementById('reservations-container');
    
    if (!container) return;
    
    if (reservations.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-slate-100 dark:bg-darkmode-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-x text-slate-400"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><line x1="10" y1="14" x2="10" y2="18"></line><line x1="14" y1="14" x2="14" y2="18"></line></svg>
                </div>
                <div class="text-lg font-medium text-slate-600 dark:text-slate-400 mb-2">No Approved Trips</div>
                <div class="text-slate-500">You don't have any approved vehicle reservations at the moment.</div>
            </div>
        `;
        return;
    }
    
    let html = '';
    reservations.forEach(reservation => {
        const startDate = new Date(reservation.start_datetime);
        const endDate = new Date(reservation.end_datetime);
        
        html += `
            <div class="border rounded-lg p-4 hover:bg-slate-50 dark:hover:bg-darkmode-400 transition-colors">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <div class="w-10 h-10 bg-primary/10 text-primary flex items-center justify-center rounded-full mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16,8 20,8 23,11 23,16 16,16 16,8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                            </div>
                            <div>
                                <div class="font-medium">${reservation.vehicle?.plate_number || 'N/A'}</div>
                                <div class="text-slate-500 text-sm">${reservation.vehicle?.vehicle_type || 'N/A'}</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm mb-3">
                            <div>
                                <span class="text-slate-500">From:</span>
                                <div class="font-medium">${reservation.requested_name || 'N/A'}</div>
                            </div>
                            <div>
                                <span class="text-slate-500">To:</span>
                                <div class="font-medium">${reservation.destination || 'N/A'}</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm mb-3">
                            <div>
                                <span class="text-slate-500">Date:</span>
                                <div class="font-medium">${startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</div>
                            </div>
                            <div>
                                <span class="text-slate-500">Time:</span>
                                <div class="font-medium">${startDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })} - ${endDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })}</div>
                            </div>
                        </div>
                        ${reservation.passengers && reservation.passengers.length > 0 ? `
                        <div class="mt-3">
                            <span class="text-slate-500 text-sm">Passengers:</span>
                            <div class="flex flex-wrap gap-2 mt-1">
                                ${reservation.passengers.map(passenger => `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-success/10 text-success">
                                        ${passenger.passenger_name || passenger.passenger?.name || 'Unknown'}
                                    </span>
                                `).join('')}
                            </div>
                        </div>
                        ` : ''}
                    </div>
                    <div class="ml-4 text-right">
                        <div class="text-xs text-slate-400 mb-1">Status</div>
                        <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-success/10 text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle w-3 h-3 mr-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            Approved
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-slate-200/60 dark:border-darkmode-400">
                    <div class="text-xs text-slate-400">
                        Requested by: ${reservation.user?.name || 'N/A'}
                    </div>
                    <div class="flex space-x-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="viewTripDetails(${reservation.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4 mr-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            View Details
                        </button>
                        <button class="btn btn-success btn-sm" onclick="startTrip(${reservation.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-play w-4 h-4 mr-1"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            Start Trip
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function viewTripDetails(reservationId) {
    // For now, just show a simple message
    // You can implement this to show more detailed information
    showInfoMessage('Trip details for reservation #' + reservationId + ' - Feature coming soon!');
}

function startTrip(reservationId) {
    // For now, just show a success message
    // You can implement this to actually start the trip tracking
    showSuccessMessage('Trip #' + reservationId + ' started successfully!');
}

// Notification functions
function showSuccessMessage(message) {
    const toast = document.getElementById('success');
    if (toast) {
        const messageElement = toast.querySelector('[data-message]');
        if (messageElement) {
            messageElement.textContent = message;
        }
        // Trigger the toast (you might need to adjust this based on your toast implementation)
        if (typeof showToast === 'function') {
            showToast('success', message);
        }
    }
}

function showErrorMessage(message) {
    const toast = document.getElementById('error');
    if (toast) {
        const messageElement = toast.querySelector('[data-message]');
        if (messageElement) {
            messageElement.textContent = message;
        }
        // Trigger the toast
        if (typeof showToast === 'function') {
            showToast('error', message);
        }
    }
}

function showInfoMessage(message) {
    const toast = document.getElementById('info');
    if (toast) {
        const messageElement = toast.querySelector('[data-message]');
        if (messageElement) {
            messageElement.textContent = message;
        }
        // Trigger the toast
        if (typeof showToast === 'function') {
            showToast('info', message);
        }
    }
}
