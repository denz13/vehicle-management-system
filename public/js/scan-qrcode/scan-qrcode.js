// Reset modal form
function resetModalForm() {
    // Clear any form fields or reset modal state
    const scheduleInfo = document.getElementById('schedule-info');
    if (scheduleInfo) {
        scheduleInfo.innerHTML = '';
    }
    
    // Reset current schedule
    currentSchedule = null;
}

// Initialize modal events
function initializeModalEvents() {
    // Departure/Arrival modal events
    const departureArrivalModal = document.getElementById('departureArrivalModal');
    if (departureArrivalModal) {
        departureArrivalModal.addEventListener('hidden.tw.modal', function() {
            resetModalForm();
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeModalEvents();
    
    let html5QrcodeScanner = null;
    let currentSchedule = null;
    
    // Initialize device scanner
    const deviceScanner = document.getElementById('deviceScanner');
    if (deviceScanner) {
        deviceScanner.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                scanQrCode(this.value);
            }
        });
    }
    
    // Initialize camera scanner
    const openQrScanner = document.getElementById('openQrScanner');
    if (openQrScanner) {
        openQrScanner.addEventListener('click', function() {
            openCameraScanner();
        });
    }
    
    // Initialize show QR buttons
    const showQrButtons = document.querySelectorAll('.show-qr');
    showQrButtons.forEach(button => {
        button.addEventListener('click', function() {
            const scheduleId = this.getAttribute('data-schedule-id');
            showQrCode(scheduleId);
        });
    });
    
    // Initialize download QR button
    const downloadQr = document.getElementById('download-qr');
    if (downloadQr) {
        downloadQr.addEventListener('click', function() {
            downloadQrCode();
        });
    }

    function scanQrCode(qrcode) {
        fetch('/scan-qrcode/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ qrcode: qrcode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentSchedule = data.schedule;
                showScheduleDetails(data.schedule, data.scanRecord);
                // Removed showSuccessModal to prevent duplicate modals
            } else {
                // Check if it's a schedule not found error
                if (data.message && data.message.includes('Approved schedule not found')) {
                    showScheduleNotFoundMessage();
                } else {
                    showErrorModal(data.message);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorModal('Error scanning QR code');
        });
    }

    function openCameraScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
            html5QrcodeScanner = null;
        }

        const modal = document.getElementById('qrScannerModal');
        const modalInstance = tailwind.Modal.getInstance(modal);
        modalInstance.show();

        // Initialize HTML5 QR Scanner
        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            },
            false
        );

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    function onScanSuccess(decodedText, decodedResult) {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
            html5QrcodeScanner = null;
        }

        // Close camera modal
        const modal = document.getElementById('qrScannerModal');
        const modalInstance = tailwind.Modal.getInstance(modal);
        modalInstance.hide();

        // Scan the decoded QR code
        scanQrCode(decodedText);
    }

    function onScanFailure(error) {
        // Handle scan failure silently
        console.warn(`QR scan failed: ${error}`);
    }

    function showScheduleDetails(schedule, scanRecord) {
        const modal = document.getElementById('departureArrivalModal');
        const scheduleInfo = document.getElementById('schedule-info');
        
        if (!scheduleInfo) return;
        
        // Clear previous content
        scheduleInfo.innerHTML = '';
        
        // Create schedule details HTML
        const scheduleHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-3">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user w-5 h-5 mr-2 text-primary">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span class="font-medium">Driver:</span>
                        <span class="ml-2">${schedule.driver_user_id && schedule.driver ? schedule.driver.name : (schedule.driver || 'No Driver Assigned')}</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck w-5 h-5 mr-2 text-primary">
                            <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                            <circle cx="9" cy="10" r="2"></circle>
                            <path d="m8 14 6-6"></path>
                            <path d="m16 14 6-6"></path>
                        </svg>
                        <span class="font-medium">Vehicle:</span>
                        <span class="ml-2">${schedule.vehicle ? schedule.vehicle.plate_number : 'No Vehicle Assigned'}</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user w-5 h-5 mr-2 text-primary">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span class="font-medium">Requester:</span>
                        <span class="ml-2">${schedule.user ? schedule.user.name : 'Unknown User'}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-5 h-5 mr-2 text-primary">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span class="font-medium">Destination:</span>
                        <span class="ml-2">${schedule.destination || 'No Destination'}</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar w-5 h-5 mr-2 text-primary">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span class="font-medium">Start:</span>
                        <span class="ml-2">${new Date(schedule.start_datetime).toLocaleString()}</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar w-5 h-5 mr-2 text-primary">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span class="font-medium">End:</span>
                        <span class="ml-2">${new Date(schedule.end_datetime).toLocaleString()}</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 p-3 bg-slate-50 rounded-lg">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info w-5 h-5 mr-2 text-primary">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span class="font-medium">Current Status:</span>
                    <span class="ml-2 ${scanRecord && scanRecord.workstate == 1 ? 'text-success' : scanRecord && scanRecord.workstate == 0 ? 'text-warning' : 'text-slate-500'}">
                        ${scanRecord && scanRecord.workstate == 1 ? 'Arrived' : scanRecord && scanRecord.workstate == 0 ? 'Departed' : 'Pending'}
                    </span>
                </div>
            </div>
        `;
        
        scheduleInfo.innerHTML = scheduleHTML;
        
        // Show modal
        const modalInstance = tailwind.Modal.getOrCreateInstance(modal);
        modalInstance.show();
        
        // Set up event listeners for departure and arrival buttons
        const btnDeparture = document.getElementById('btnDeparture');
        const btnArrival = document.getElementById('btnArrival');
        
        if (btnDeparture && btnArrival) {
            // Remove old event listeners by cloning the buttons
            const newBtnDeparture = btnDeparture.cloneNode(true);
            const newBtnArrival = btnArrival.cloneNode(true);
            
            btnDeparture.parentNode.replaceChild(newBtnDeparture, btnDeparture);
            btnArrival.parentNode.replaceChild(newBtnArrival, btnArrival);
            
            // Add new event listeners
            newBtnDeparture.addEventListener('click', () => markDeparture(schedule.id));
            newBtnArrival.addEventListener('click', () => markArrival(schedule.id));
            
            // Update button visibility based on current status
            if (scanRecord && scanRecord.workstate == 1) {
                // Already arrived, hide both buttons
                newBtnDeparture.style.display = 'none';
                newBtnArrival.style.display = 'none';
            } else if (scanRecord && scanRecord.workstate == 0) {
                // Already departed, only show arrival button
                newBtnDeparture.style.display = 'none';
                newBtnArrival.style.display = 'inline-flex';
            } else {
                // Pending, show both buttons
                newBtnDeparture.style.display = 'inline-flex';
                newBtnArrival.style.display = 'inline-flex';
            }
        }
    }

    function markDeparture(scheduleId) {
        fetch('/scan-qrcode/mark-departure', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ schedule_id: scheduleId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal immediately
                const modal = document.getElementById('departureArrivalModal');
                const modalInstance = tailwind.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
                
                // Show success message
                showDepartureSuccessMessage();
                
                // Refresh page after a short delay
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                // Check if it's a schedule not found error
                if (data.message && data.message.includes('Approved schedule not found')) {
                    showScheduleNotFoundMessage();
                } else {
                    showErrorModal(data.message);
                }
            }
        })
        .catch(error => {
            console.error('Error marking departure:', error);
            showErrorModal('Error marking departure');
        });
    }

    function markArrival(scheduleId) {
        fetch('/scan-qrcode/mark-arrival', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ schedule_id: scheduleId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal immediately
                const modal = document.getElementById('departureArrivalModal');
                const modalInstance = tailwind.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
                
                // Show success message
                showArrivalSuccessMessage();
                
                // Refresh page after a short delay
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                // Check if it's a schedule not found error
                if (data.message && data.message.includes('Approved schedule not found')) {
                    showScheduleNotFoundMessage();
                } else {
                    showErrorModal(data.message);
                }
            }
        })
        .catch(error => {
            console.error('Error marking arrival:', error);
            showErrorModal('Error marking arrival');
        });
    }

    function showQrCode(scheduleId) {
        // Find the schedule data
        const scheduleElement = document.querySelector(`[data-schedule-id="${scheduleId}"]`);
        if (!scheduleElement) {
            showScheduleNotFoundMessage();
            return;
        }

        // First, fetch the schedule details to get the actual QR code value
        fetch(`/scan-qrcode/get-schedule/${scheduleId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.schedule) {
                    const schedule = data.schedule;
                    const qrcodeDisplay = document.getElementById('qrcode-display');
                    const scheduleDetails = document.getElementById('schedule-details');
                    
                    // Clear previous content
                    qrcodeDisplay.innerHTML = '';
                    scheduleDetails.innerHTML = '';

                    // Generate QR code with JSON structure
                    const qrCodeData = {
                        type: "vehicle_reservation",
                        reservation_id: schedule.id,
                        vehicle_id: schedule.vehicle_id || null,
                        user_id: schedule.user_id || null,
                        driver_id: schedule.driver_user_id || null,
                        destination: schedule.destination || null,
                        start_datetime: schedule.start_datetime || null,
                        end_datetime: schedule.end_datetime || null
                    };

                    const qr = new QRious({
                        element: document.createElement('canvas'),
                        value: JSON.stringify(qrCodeData), // Use JSON structure
                        size: 200,
                        level: 'H'
                    });

                    qrcodeDisplay.appendChild(qr.element);

                    // Show schedule details
                    scheduleDetails.innerHTML = `
                        <div class="text-sm text-slate-600">
                            <p><strong>Schedule ID:</strong> ${scheduleId}</p>
                            <p><strong>QR Code Type:</strong> Vehicle Reservation</p>
                            <p><strong>Vehicle ID:</strong> ${schedule.vehicle_id || 'N/A'}</p>
                            <p><strong>User ID:</strong> ${schedule.user_id || 'N/A'}</p>
                            <p><strong>Scan this QR code to mark departure/arrival</strong></p>
                        </div>
                    `;

                    // Show modal
                    const modal = document.getElementById('qrCodeModal');
                    const modalInstance = tailwind.Modal.getInstance(modal);
                    modalInstance.show();
                } else {
                    showScheduleNotFoundMessage();
                }
            })
            .catch(error => {
                console.error('Error fetching schedule:', error);
                showErrorModal('Error loading schedule details');
            });
    }

    function downloadQrCode() {
        const img = document.querySelector('#qrcode-display img');
        if (img && img.src) {
            // Create a temporary link to download the image
            const link = document.createElement('a');
            link.download = 'schedule-qr.png';
            link.href = img.src;
            
            // For cross-origin images, we need to fetch and convert to blob
            if (img.src.startsWith('http')) {
                fetch(img.src)
                    .then(response => response.blob())
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        link.href = url;
                        link.click();
                        window.URL.revokeObjectURL(url);
                    })
                    .catch(error => {
                        console.error('Error downloading image:', error);
                        showErrorModal('Error downloading QR code image');
                    });
            } else {
                // Direct download for same-origin images
                link.click();
            }
        } else {
            showErrorModal('No QR code image available to download');
        }
    }

    // Show success message using notification toast
    function showSuccessModal(message) {
        if (typeof showNotification_success !== 'undefined') {
            showNotification_success();
        } else {
            // Fallback to console if notification system not available
            console.log('Success:', message);
        }
    }

    // Show error message using notification toast
    function showErrorModal(message) {
        if (typeof showNotification_error !== 'undefined') {
            showNotification_error();
        } else {
            // Fallback to console if notification system not available
            console.log('Error:', message);
        }
    }

    // Show schedule not found message using notification toast
    function showScheduleNotFoundMessage() {
        if (typeof showNotification_schedule_not_found !== 'undefined') {
            showNotification_schedule_not_found();
        } else if (typeof showNotification_error !== 'undefined') {
            // Fallback to general error notification
            showNotification_error();
        } else {
            // Fallback to console if notification system not available
            console.log('Schedule Not Found: Approved schedule not found. Only approved schedules can be scanned.');
        }
    }

    // Show departure success message using notification toast
    function showDepartureSuccessMessage() {
        if (typeof showNotification_departure_success !== 'undefined') {
            showNotification_departure_success();
        } else if (typeof showNotification_success !== 'undefined') {
            // Fallback to general success notification
            showNotification_success();
        } else {
            // Fallback to console if notification system not available
            console.log('Success: Departure marked successfully');
        }
    }

    // Show arrival success message using notification toast
    function showArrivalSuccessMessage() {
        if (typeof showNotification_arrival_success !== 'undefined') {
            showNotification_arrival_success();
        } else if (typeof showNotification_success !== 'undefined') {
            // Fallback to general success notification
            showNotification_success();
        } else {
            // Fallback to console if notification system not available
            console.log('Success: Arrival marked successfully');
        }
    }

    // Close camera scanner when modal is closed
    const qrScannerModal = document.getElementById('qrScannerModal');
    if (qrScannerModal) {
        qrScannerModal.addEventListener('hidden.bs.modal', function() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
            }
        });
    }
});
