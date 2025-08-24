// Vehicle Management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize search functionality
    initializeSearch();
    
    // Initialize delete confirmation
    initializeDeleteConfirmation();
    
    // Initialize modal event listeners
    initializeModalEvents();
    
    // Initialize pagination
    initializePagination();
});

// Initialize modal events
function initializeModalEvents() {
    // Add vehicle modal events
    const addModal = document.getElementById('add-vehicle-modal');
    if (addModal) {
        addModal.addEventListener('hidden.tw.modal', function() {
            resetForm();
        });
    }
    
    // Edit vehicle modal events
    const editModal = document.getElementById('edit-vehicle-modal');
    if (editModal) {
        editModal.addEventListener('hidden.tw.modal', function() {
            resetEditForm();
        });
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
                const tableBody = document.getElementById('vehicles-table-body');
                const rows = tableBody.querySelectorAll('tr');
                
                rows.forEach(row => {
                    const vehicleName = row.querySelector('td:nth-child(2) a')?.textContent.toLowerCase() || '';
                    const model = row.querySelector('td:nth-child(3) div:first-child')?.textContent.toLowerCase() || '';
                    const plateNumber = row.querySelector('td:nth-child(4) span')?.textContent.toLowerCase() || '';
                    
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
                refreshVehiclesTable(page);
            }
        }
    });
    
    // Handle per page selector changes
    const perPageSelector = document.querySelector('select.form-select');
    if (perPageSelector) {
        perPageSelector.addEventListener('change', function() {
            const perPage = parseInt(this.value);
            refreshVehiclesTable(1, perPage); // Reset to first page when changing per page
        });
    }
}

// Save vehicle function
function saveVehicle() {
    // Clear previous errors
    clearErrors();
    
    // Get form values
    const vehicleName = document.getElementById('vehicle_name').value.trim();
    const vehicleColor = document.getElementById('vehicle_color').value.trim();
    const model = document.getElementById('model').value.trim();
    const plateNumber = document.getElementById('plate_number').value.trim();
    const capacity = document.getElementById('capacity').value;
    const dateAcquired = document.getElementById('date_acquired').value;
    const status = document.getElementById('status').value;
    const vehicleImage = document.getElementById('vehicle_image').files[0];
    
    // Basic validation
    if (!vehicleName) {
        showError('vehicle_name', 'Vehicle name is required');
        return;
    }
    
    if (!vehicleColor) {
        showError('vehicle_color', 'Vehicle color is required');
        return;
    }
    
    if (!model) {
        showError('model', 'Model is required');
        return;
    }
    
    if (!plateNumber) {
        showError('plate_number', 'Plate number is required');
        return;
    }
    
    if (!capacity) {
        showError('capacity', 'Capacity is required');
        return;
    }
    
    if (!dateAcquired) {
        showError('date_acquired', 'Date acquired is required');
        return;
    }
    
    if (!status) {
        showError('status', 'Status is required');
        return;
    }
    
    // Show loading state
    const saveBtn = document.querySelector('#add-vehicle-modal .btn-primary');
    const originalText = saveBtn.textContent;
    saveBtn.textContent = 'Saving...';
    saveBtn.disabled = true;
    
    // Prepare form data
    const formData = new FormData();
    formData.append('vehicle_name', vehicleName);
    formData.append('vehicle_color', vehicleColor);
    formData.append('model', model);
    formData.append('plate_number', plateNumber);
    formData.append('capacity', capacity);
    formData.append('date_acquired', dateAcquired);
    formData.append('status', status);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
    formData.append('_method', 'POST');
    
    if (vehicleImage) {
        formData.append('vehicle_image', vehicleImage);
    }
    
    // Send AJAX request
    fetch('/vehicle-management', {
        method: 'POST',
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
            showSuccessMessage(data.message);
            
            // Close modal
            const modal = document.getElementById('add-vehicle-modal');
            const modalInstance = tailwind.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Reset form
            resetForm();
            
            // Refresh the vehicles table
            setTimeout(() => {
                refreshVehiclesTable();
            }, 500);
        } else {
            // Show validation errors
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    showError(field, data.errors[field][0]);
                });
                // Show validation error notification
                showValidationErrorMessage();
            } else {
                showErrorMessage(data.message || 'Failed to save vehicle');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while saving the vehicle');
    })
    .finally(() => {
        // Reset button state
        saveBtn.textContent = originalText;
        saveBtn.disabled = false;
    });
}

// Show error message for specific field
function showError(fieldName, message) {
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

// Clear all errors
function clearErrors() {
    const errorElements = document.querySelectorAll('[id$="_error"]');
    errorElements.forEach(element => {
        element.textContent = '';
        element.style.display = 'none';
    });
    
    // Remove error classes from inputs
    const inputs = document.querySelectorAll('#add-vehicle-modal .form-control, #add-vehicle-modal .form-select');
    inputs.forEach(input => {
        input.classList.remove('border-danger');
    });
}

// Reset form
function resetForm() {
    document.getElementById('vehicle_name').value = '';
    document.getElementById('vehicle_color').value = '';
    document.getElementById('model').value = '';
    document.getElementById('plate_number').value = '';
    document.getElementById('capacity').value = '';
    document.getElementById('date_acquired').value = '';
    document.getElementById('status').value = '';
    document.getElementById('vehicle_image').value = '';
    clearErrors();
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

// Edit vehicle function
function editVehicle(vehicleId) {
    // Clear previous errors
    clearEditErrors();
    
    // Fetch vehicle data
    fetch(`/vehicle-management/${vehicleId}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Populate form fields
            document.getElementById('edit_vehicle_id').value = data.vehicle.id;
            document.getElementById('edit_vehicle_name').value = data.vehicle.vehicle_name;
            document.getElementById('edit_vehicle_color').value = data.vehicle.vehicle_color;
            document.getElementById('edit_model').value = data.vehicle.model;
            document.getElementById('edit_plate_number').value = data.vehicle.plate_number;
            document.getElementById('edit_capacity').value = data.vehicle.capacity;
            document.getElementById('edit_date_acquired').value = data.vehicle.date_acquired;
            document.getElementById('edit_status').value = data.vehicle.status;
            
            // Show current image if exists
            const currentImageDisplay = document.getElementById('current_image_display');
            const currentImage = document.getElementById('current_image');
            if (data.vehicle.vehicle_image) {
                currentImage.src = `/storage/vehicles/${data.vehicle.vehicle_image}`;
                currentImageDisplay.style.display = 'block';
            } else {
                currentImageDisplay.style.display = 'none';
            }
            
            // Show edit modal
            const editModal = document.getElementById('edit-vehicle-modal');
            const modalInstance = tailwind.Modal.getOrCreateInstance(editModal);
            modalInstance.show();
        } else {
            showErrorMessage(data.message || 'Failed to load vehicle data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while loading vehicle data');
    });
}

// Update vehicle function
function updateVehicle() {
    // Clear previous errors
    clearEditErrors();
    
    // Get form values
    const vehicleId = document.getElementById('edit_vehicle_id').value;
    const vehicleName = document.getElementById('edit_vehicle_name').value.trim();
    const vehicleColor = document.getElementById('edit_vehicle_color').value.trim();
    const model = document.getElementById('edit_model').value.trim();
    const plateNumber = document.getElementById('edit_plate_number').value.trim();
    const capacity = document.getElementById('edit_capacity').value;
    const dateAcquired = document.getElementById('edit_date_acquired').value;
    const status = document.getElementById('edit_status').value;
    const vehicleImage = document.getElementById('edit_vehicle_image').files[0];
    
    // Basic validation
    if (!vehicleName) {
        showEditError('edit_vehicle_name', 'Vehicle name is required');
        return;
    }
    
    if (!vehicleColor) {
        showEditError('edit_vehicle_color', 'Vehicle color is required');
        return;
    }
    
    if (!model) {
        showEditError('edit_model', 'Model is required');
        return;
    }
    
    if (!plateNumber) {
        showEditError('edit_plate_number', 'Plate number is required');
        return;
    }
    
    if (!capacity) {
        showEditError('edit_capacity', 'Capacity is required');
        return;
    }
    
    if (!dateAcquired) {
        showEditError('edit_date_acquired', 'Date acquired is required');
        return;
    }
    
    if (!status) {
        showEditError('edit_status', 'Status is required');
        return;
    }
    
    // Show loading state
    const updateBtn = document.querySelector('#edit-vehicle-modal .btn-primary');
    const originalText = updateBtn.textContent;
    updateBtn.textContent = 'Updating...';
    updateBtn.disabled = true;
    
    // Prepare form data
    const formData = new FormData();
    formData.append('vehicle_name', vehicleName);
    formData.append('vehicle_color', vehicleColor);
    formData.append('model', model);
    formData.append('plate_number', plateNumber);
    formData.append('capacity', capacity);
    formData.append('date_acquired', dateAcquired);
    formData.append('status', status);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
    formData.append('_method', 'PUT');
    
    if (vehicleImage) {
        formData.append('vehicle_image', vehicleImage);
    }
    
    // Send AJAX request
    fetch(`/vehicle-management/${vehicleId}`, {
        method: 'POST',
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
            showEditSuccessMessage(data.message);
            
            // Close modal
            const editModal = document.getElementById('edit-vehicle-modal');
            const modalInstance = tailwind.Modal.getInstance(editModal);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Reset form
            resetEditForm();
            
            // Refresh the vehicles table
            setTimeout(() => {
                refreshVehiclesTable();
            }, 500);
        } else {
            // Show validation errors
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const editField = 'edit_' + field;
                    showEditError(editField, data.errors[field][0]);
                });
                // Show validation error notification
                showValidationErrorMessage();
            } else {
                showErrorMessage(data.message || 'Failed to update vehicle');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while updating the vehicle');
    })
    .finally(() => {
        // Reset button state
        updateBtn.textContent = originalText;
        updateBtn.disabled = false;
    });
}

// Show error message for edit form fields
function showEditError(fieldName, message) {
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

// Clear all edit form errors
function clearEditErrors() {
    const errorElements = document.querySelectorAll('#edit-vehicle-modal [id$="_error"]');
    errorElements.forEach(element => {
        element.textContent = '';
        element.style.display = 'none';
    });
    
    // Remove error classes from inputs
    const inputs = document.querySelectorAll('#edit-vehicle-modal .form-control, #edit-vehicle-modal .form-select');
    inputs.forEach(input => {
        input.classList.remove('border-danger');
    });
}

// Reset edit form
function resetEditForm() {
    document.getElementById('edit_vehicle_id').value = '';
    document.getElementById('edit_vehicle_name').value = '';
    document.getElementById('edit_vehicle_color').value = '';
    document.getElementById('edit_model').value = '';
    document.getElementById('edit_plate_number').value = '';
    document.getElementById('edit_capacity').value = '';
    document.getElementById('edit_date_acquired').value = '';
    document.getElementById('edit_status').value = '';
    document.getElementById('edit_vehicle_image').value = '';
    document.getElementById('current_image_display').style.display = 'none';
    clearEditErrors();
}

// Show edit success message using notification toast
function showEditSuccessMessage(message) {
    if (typeof showNotification_edit_success !== 'undefined') {
        showNotification_edit_success();
    } else if (typeof showNotification_success !== 'undefined') {
        // Fallback to general success notification
        showNotification_success();
    } else {
        // Fallback to console if notification system not available
        console.log('Success:', message);
    }
}

// Show delete success message using notification toast
function showDeleteSuccessMessage(message) {
    if (typeof showNotification_delete_success !== 'undefined') {
        showNotification_delete_success();
    } else if (typeof showNotification_success !== 'undefined') {
        // Fallback to general success notification
        showNotification_success();
    } else {
        // Fallback to console if notification system not available
        console.log('Success:', message);
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

// Delete vehicle function
function deleteVehicle(vehicleId) {
    // Store the vehicle ID for deletion
    window.vehicleToDelete = vehicleId;
    
    // Show delete confirmation modal
    const deleteModal = document.getElementById('delete-confirmation-modal');
    const modalInstance = tailwind.Modal.getOrCreateInstance(deleteModal);
    modalInstance.show();
}

// Initialize delete confirmation
function initializeDeleteConfirmation() {
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            const vehicleId = window.vehicleToDelete;
            if (vehicleId) {
                performDelete(vehicleId);
            }
        });
    }
}

// Perform actual deletion
function performDelete(vehicleId) {
    // Show loading state
    const deleteBtn = document.getElementById('confirm-delete-btn');
    const originalText = deleteBtn.textContent;
    deleteBtn.textContent = 'Deleting...';
    deleteBtn.disabled = true;
    
    // Send delete request
    fetch(`/vehicle-management/${vehicleId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showDeleteSuccessMessage('Vehicle deleted successfully');
            
            // Close modal
            const deleteModal = document.getElementById('delete-confirmation-modal');
            const modalInstance = tailwind.Modal.getInstance(deleteModal);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Refresh the vehicles table
            setTimeout(() => {
                refreshVehiclesTable();
            }, 500);
        } else {
            showErrorMessage(data.message || 'Failed to delete vehicle');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while deleting the vehicle');
    })
    .finally(() => {
        // Reset button state
        deleteBtn.textContent = originalText;
        deleteBtn.disabled = false;
    });
}

// Add CSRF token to all AJAX requests
function getCSRFToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

// Refresh vehicles table
function refreshVehiclesTable(page = 1, perPage = 10) {
    const url = `/vehicle-management/vehicles?page=${page}&per_page=${perPage}`;
    
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
            updateVehiclesTable(data.vehicles, data.pagination);
        }
    })
    .catch(error => {
        console.error('Error refreshing vehicles:', error);
    });
}

// Update vehicles table with new data
function updateVehiclesTable(vehicles, pagination = null) {
    const tableBody = document.getElementById('vehicles-table-body');
    if (!tableBody) return;
    
    let html = '';
    
    if (vehicles.length === 0) {
        html = '<tr><td colspan="8" class="text-center py-8 text-slate-500">No vehicles found</td></tr>';
    } else {
        vehicles.forEach(vehicle => {
            const statusClass = vehicle.status === 'active' ? 'text-success' : vehicle.status === 'maintenance' ? 'text-warning' : 'text-danger';
            const statusIcon = vehicle.status === 'active' 
                ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> Active'
                : vehicle.status === 'maintenance'
                ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="wrench" data-lucide="wrench" class="lucide lucide-wrench w-4 h-4 mr-2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg> Maintenance'
                : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x-circle" data-lucide="x-circle" class="lucide lucide-x-circle w-4 h-4 mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> Inactive';
            
            const imageHtml = vehicle.vehicle_image 
                ? `<div class="w-10 h-10 image-fit zoom-in"><img alt="Vehicle Image" class="tooltip rounded-full" src="/storage/vehicles/${vehicle.vehicle_image}" title="${vehicle.vehicle_name}"></div>`
                : `<div class="w-10 h-10 image-fit zoom-in bg-slate-200 rounded-full flex items-center justify-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-slate-400"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21,15 16,10 5,21"></polyline></svg></div>`;
            
            const dateAcquired = vehicle.date_acquired ? new Date(vehicle.date_acquired).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            }) : 'N/A';
            
            html += `
                <tr class="intro-x">
                    <td>
                        <div class="flex">
                            ${imageHtml}
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
                            <div class="flex items-center ${statusClass}">
                                ${statusIcon}
                            </div>
                        </div>
                    </td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="javascript:;" onclick="editVehicle(${vehicle.id})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" class="lucide lucide-edit w-4 h-4 mr-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit
                            </a>
                            <a class="flex items-center text-danger" href="javascript:;" onclick="deleteVehicle(${vehicle.id})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2" data-lucide="trash-2" class="lucide lucide-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete
                            </a>
                        </div>
                    </td>
                </tr>
            `;
        });
    }
    
    tableBody.innerHTML = html;
    
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
            countElement.textContent = `Showing ${from} to ${to} of ${total} vehicles`;
        } else {
            countElement.textContent = 'No vehicles found';
        }
    }
}
