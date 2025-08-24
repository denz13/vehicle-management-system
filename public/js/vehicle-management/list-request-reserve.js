// List Request Reserve JavaScript
console.log('=== LIST REQUEST RESERVE JAVASCRIPT LOADING ===');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing list request reserve functionality...');
    
    // Initialize search functionality
    initializeSearch();
    
    // Initialize status filters
    initializeStatusFilters();
});

// Search functionality for reservations
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
                    const reservationId = row.querySelector('td:nth-child(1) span')?.textContent.toLowerCase() || '';
                    const vehicleName = row.querySelector('td:nth-child(2) .font-medium')?.textContent.toLowerCase() || '';
                    const requesterName = row.querySelector('td:nth-child(3) .font-medium')?.textContent.toLowerCase() || '';
                    const driverName = row.querySelector('td:nth-child(4) .font-medium')?.textContent.toLowerCase() || '';
                    const destination = row.querySelector('td:nth-child(5) .font-medium')?.textContent.toLowerCase() || '';
                    const reservationType = row.querySelector('td:nth-child(7) .font-medium')?.textContent.toLowerCase() || '';
                    
                    if (reservationId.includes(searchTerm) || 
                        vehicleName.includes(searchTerm) || 
                        requesterName.includes(searchTerm) || 
                        driverName.includes(searchTerm) || 
                        destination.includes(searchTerm) || 
                        reservationType.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }, 300); // 300ms delay for better performance
        });
    }
}

// Initialize status filters
function initializeStatusFilters() {
    // Add status filter buttons if needed
    console.log('Status filters initialized');
}

// Function to filter reservations by status
function filterReservationsByStatus(status) {
    const reservationRows = document.querySelectorAll('#reservations-table-body tr');
    
    reservationRows.forEach(row => {
        const statusCell = row.querySelector('td:nth-child(8)');
        if (statusCell) {
            const reservationStatus = statusCell.textContent.toLowerCase();
            if (status === 'all' || reservationStatus.includes(status.toLowerCase())) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}

// Function to export reservation data
function exportReservationData() {
    console.log('Exporting reservation data...');
    // Implementation for exporting data to CSV or Excel
}

// Function to refresh reservation data
function refreshReservationData() {
    console.log('Refreshing reservation data...');
    // Implementation for refreshing data from server
}

// Function to show reservation details
function showReservationDetails(reservationId) {
    console.log('Showing details for reservation:', reservationId);
    // Implementation for showing detailed reservation information
}

// Function to update reservation status
function updateReservationStatus(reservationId, newStatus) {
    console.log('Updating reservation status:', reservationId, 'to', newStatus);
    // Implementation for updating reservation status
}

// Function to show statistics
function showReservationStatistics() {
    console.log('Showing reservation statistics...');
    // Implementation for displaying statistics
}

// Utility function to format dates
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (error) {
        console.error('Error formatting date:', error);
        return 'Invalid Date';
    }
}

// Utility function to get status color class
function getStatusColorClass(status) {
    const statusColors = {
        'approved': 'text-success',
        'pending': 'text-warning',
        'rejected': 'text-danger',
        'completed': 'text-info',
        'cancelled': 'text-danger',
        'active': 'text-success',
        'inactive': 'text-danger',
        'maintenance': 'text-warning'
    };
    
    return statusColors[status.toLowerCase()] || 'text-slate-500';
}

// Function to initialize tooltips
function initializeTooltips() {
    // Initialize any tooltips or popovers
    console.log('Tooltips initialized');
}

// Function to handle responsive behavior
function handleResponsiveBehavior() {
    // Handle responsive table behavior
    const tables = document.querySelectorAll('.table');
    tables.forEach(table => {
        if (table.scrollWidth > table.clientWidth) {
            table.classList.add('table-responsive');
        }
    });
}

// Export functions for global access
window.filterReservationsByStatus = filterReservationsByStatus;
window.exportReservationData = exportReservationData;
window.refreshReservationData = refreshReservationData;
window.showReservationDetails = showReservationDetails;
window.updateReservationStatus = updateReservationStatus;
window.showReservationStatistics = showReservationStatistics;

// Function to approve reservation
function approveReservation(reservationId) {
    // Show loading state
    const approveBtn = event.target;
    const originalText = approveBtn.textContent;
    approveBtn.textContent = 'Processing...';
    approveBtn.disabled = true;
    
    // Make AJAX request to approve reservation
    fetch(`/vehicle-management/approve-reservation/${reservationId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Reservation has been approved successfully.',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                // Reload the page to show updated status
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to approve reservation');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message || 'Failed to approve reservation. Please try again.',
        });
    })
    .finally(() => {
        // Reset button state
        approveBtn.textContent = originalText;
        approveBtn.disabled = false;
    });
}

// Function to decline reservation
function declineReservation(reservationId) {
    // Show loading state
    const declineBtn = event.target;
    const originalText = declineBtn.textContent;
    declineBtn.textContent = 'Processing...';
    declineBtn.disabled = true;
    
    // Make AJAX request to decline reservation
    fetch(`/vehicle-management/decline-reservation/${reservationId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Reservation has been declined successfully.',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                // Reload the page to show updated status
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to decline reservation');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message || 'Failed to decline reservation. Please try again.',
        });
    })
    .finally(() => {
        // Reset button state
        declineBtn.textContent = originalText;
        declineBtn.disabled = false;
    });
}

// Export the new functions for global access
window.approveReservation = approveReservation;
window.declineReservation = declineReservation;

// View reservation details
function viewReservationDetails(reservationId) {
    // Debug: Log the reservation ID
    console.log('View details called with ID:', reservationId);
    console.log('Type of ID:', typeof reservationId);
    
    // Store the reservation ID for the details modal
    window.detailsReservationId = reservationId;
    
    // Show details modal using Tailwind modal system
    const modal = document.getElementById('view-details-modal');
    if (modal) {
        // Use Tailwind modal toggle
        const toggleButton = document.createElement('button');
        toggleButton.setAttribute('data-tw-toggle', 'modal');
        toggleButton.setAttribute('data-tw-target', '#view-details-modal');
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
    fetch(`/list-request-reserve/${reservationId}`, {
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
    const vehicleName = document.getElementById('details-vehicle-name');
    const plateNumber = document.getElementById('details-plate-number');
    const reservationType = document.getElementById('details-reservation-type');
    
    if (vehicleName) vehicleName.textContent = reservation.vehicle?.vehicle_name || 'N/A';
    if (plateNumber) plateNumber.textContent = reservation.vehicle?.plate_number || 'N/A';
    if (reservationType) reservationType.textContent = reservation.reservation_type?.reservation_name || 'N/A';
    
    // Reservation Details
    if (document.getElementById('details-destination')) document.getElementById('details-destination').textContent = reservation.destination || 'N/A';
    if (document.getElementById('details-driver')) document.getElementById('details-driver').textContent = reservation.driver || 'N/A';
    if (document.getElementById('details-reason')) document.getElementById('details-reason').textContent = reservation.reason || 'N/A';
    if (document.getElementById('details-reason-details')) document.getElementById('details-reason-details').textContent = reservation.reason || 'N/A';
    if (document.getElementById('details-requested-by')) document.getElementById('details-requested-by').textContent = reservation.user?.name || 'N/A';
    
    // Reservation ID
    if (document.getElementById('details-reservation-id')) {
        document.getElementById('details-reservation-id').textContent = `#${reservation.id}`;
    }
    
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
    const passengersTable = document.getElementById('details-passengers-table');
    
    if (passengersTable) {
        if (reservation.passengers && reservation.passengers.length > 0) {
            const passengersRows = reservation.passengers.map(passenger => {
                return `<tr class="border-b dark:border-darkmode-400">
                    <td class="py-3 px-4">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-slate-400 mr-2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span class="font-medium">${passenger.passenger?.name || 'Unknown'}</span>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-600 rounded">Passenger</span>
                    </td>
                </tr>`;
            }).join('');
            
            passengersTable.innerHTML = passengersRows;
        } else {
            passengersTable.innerHTML = `<tr>
                <td class="border-b dark:border-darkmode-400 text-center py-4" colspan="3">
                    <div class="text-slate-500">No passengers assigned</div>
                </td>
            </tr>`;
        }
    }
    
    // QR Code
    const qrcodeContainer = document.getElementById('details-qrcode');
    if (qrcodeContainer) {
        if (reservation.qrcode) {
            // Check if it's a file path (starts with 'qrcodes/')
            if (reservation.qrcode.startsWith('qrcodes/')) {
                qrcodeContainer.innerHTML = `<img src="/storage/${reservation.qrcode}" alt="QR Code" class="w-40 h-40">`;
            } else {
                // If it's JSON data, show a placeholder or generate QR code
                qrcodeContainer.innerHTML = '<div class="text-gray-500">QR Code data available</div>';
            }
        } else {
            qrcodeContainer.innerHTML = '<div class="text-gray-500">No QR Code available</div>';
        }
    }
    
    // Coordinates
    if (document.getElementById('details-longitude')) document.getElementById('details-longitude').textContent = reservation.longitude || 'N/A';
    if (document.getElementById('details-latitude')) document.getElementById('details-latitude').textContent = reservation.latitude || 'N/A';
}

// Show details error message
function showDetailsErrorMessage(message) {
    console.error('Details Error:', message);
    // You can implement a proper error notification system here
    alert('Error: ' + message);
}

// Export the new functions for global access
window.viewReservationDetails = viewReservationDetails;
window.loadReservationDetails = loadReservationDetails;
window.populateDetailsModal = populateDetailsModal;
window.showDetailsErrorMessage = showDetailsErrorMessage;