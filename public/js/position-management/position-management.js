// Position Management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize search functionality
    initializeSearch();
    
    // Initialize delete confirmation
    initializeDeleteConfirmation();
    
    // Initialize modal event listeners
    initializeModalEvents();
});

// Initialize modal events
function initializeModalEvents() {
    // Add position modal events
    const addModal = document.getElementById('add-position-modal');
    if (addModal) {
        addModal.addEventListener('hidden.tw.modal', function() {
            resetForm();
        });
    }
    
    // Edit position modal events
    const editModal = document.getElementById('edit-position-modal');
    if (editModal) {
        editModal.addEventListener('hidden.tw.modal', function() {
            resetEditForm();
        });
    }
}

// Search functionality
function initializeSearch() {
    const searchInput = document.getElementById('search-positions');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Set new timeout for better performance
            searchTimeout = setTimeout(() => {
                const searchTerm = this.value.toLowerCase();
                const tableBody = document.getElementById('positions-table-body');
                const rows = tableBody.querySelectorAll('tr');
                
                rows.forEach(row => {
                    const positionName = row.querySelector('td:first-child a')?.textContent.toLowerCase() || '';
                    const description = row.querySelector('td:nth-child(2) div')?.textContent.toLowerCase() || '';
                    
                    if (positionName.includes(searchTerm) || description.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }, 300); // 300ms delay for better performance
        });
    }
}

// Save position function
function savePosition() {
    // Clear previous errors
    clearErrors();
    
    // Get form values
    const positionName = document.getElementById('position_name').value.trim();
    const description = document.getElementById('description').value.trim();
    const status = document.getElementById('status').value;
    
    // Basic validation
    if (!positionName) {
        showError('position_name', 'Position name is required');
        return;
    }
    
    if (!status) {
        showError('status', 'Status is required');
        return;
    }
    
    // Show loading state
    const saveBtn = document.querySelector('#add-position-modal .btn-primary');
    const originalText = saveBtn.textContent;
    saveBtn.textContent = 'Saving...';
    saveBtn.disabled = true;
    
    // Prepare data
    const formData = {
        position_name: positionName,
        description: description,
        status: status,
        _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        _method: 'POST'
    };
    
    // Send AJAX request
    fetch('/position-management', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showSuccessMessage(data.message);
            
            // Close modal
            const modal = document.getElementById('add-position-modal');
            const modalInstance = tailwind.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Reset form
            resetForm();
            
            // Refresh the positions table
            setTimeout(() => {
                refreshPositionsTable();
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
                showErrorMessage(data.message || 'Failed to save position');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while saving the position');
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
    const inputs = document.querySelectorAll('#add-position-modal .form-control, #add-position-modal .form-select');
    inputs.forEach(input => {
        input.classList.remove('border-danger');
    });
}

// Reset form
function resetForm() {
    document.getElementById('position_name').value = '';
    document.getElementById('description').value = '';
    document.getElementById('status').value = '';
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

// Edit position function
function editPosition(positionId) {
    // Clear previous errors
    clearEditErrors();
    
    // Fetch position data
    fetch(`/position-management/${positionId}/edit`, {
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
            document.getElementById('edit_position_id').value = data.position.id;
            document.getElementById('edit_position_name').value = data.position.position_name;
            document.getElementById('edit_description').value = data.position.description || '';
            document.getElementById('edit_status').value = data.position.status;
            
            // Show edit modal
            const editModal = document.getElementById('edit-position-modal');
            const modalInstance = tailwind.Modal.getOrCreateInstance(editModal);
            modalInstance.show();
        } else {
            showErrorMessage(data.message || 'Failed to load position data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while loading position data');
    });
}

// Update position function
function updatePosition() {
    // Clear previous errors
    clearEditErrors();
    
    // Get form values
    const positionId = document.getElementById('edit_position_id').value;
    const positionName = document.getElementById('edit_position_name').value.trim();
    const description = document.getElementById('edit_description').value.trim();
    const status = document.getElementById('edit_status').value;
    
    // Basic validation
    if (!positionName) {
        showEditError('edit_position_name', 'Position name is required');
        return;
    }
    
    if (!status) {
        showEditError('edit_status', 'Status is required');
        return;
    }
    
    // Show loading state
    const updateBtn = document.querySelector('#edit-position-modal .btn-primary');
    const originalText = updateBtn.textContent;
    updateBtn.textContent = 'Updating...';
    updateBtn.disabled = true;
    
    // Prepare data
    const formData = {
        position_name: positionName,
        description: description,
        status: status,
        _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        _method: 'PUT'
    };
    
    // Send AJAX request
    fetch(`/position-management/${positionId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showEditSuccessMessage(data.message);
            
            // Close modal
            const editModal = document.getElementById('edit-position-modal');
            const modalInstance = tailwind.Modal.getInstance(editModal);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Reset form
            resetEditForm();
            
            // Refresh the positions table
            setTimeout(() => {
                refreshPositionsTable();
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
                showErrorMessage(data.message || 'Failed to update position');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while updating the position');
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
    const errorElements = document.querySelectorAll('#edit-position-modal [id$="_error"]');
    errorElements.forEach(element => {
        element.textContent = '';
        element.style.display = 'none';
    });
    
    // Remove error classes from inputs
    const inputs = document.querySelectorAll('#edit-position-modal .form-control, #edit-position-modal .form-select');
    inputs.forEach(input => {
        input.classList.remove('border-danger');
    });
}

// Reset edit form
function resetEditForm() {
    document.getElementById('edit_position_id').value = '';
    document.getElementById('edit_position_name').value = '';
    document.getElementById('edit_description').value = '';
    document.getElementById('edit_status').value = '';
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

// Delete position function
function deletePosition(positionId) {
    // Store the position ID for deletion
    window.positionToDelete = positionId;
    
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
            const positionId = window.positionToDelete;
            if (positionId) {
                performDelete(positionId);
            }
        });
    }
}

// Perform actual deletion
function performDelete(positionId) {
    // Show loading state
    const deleteBtn = document.getElementById('confirm-delete-btn');
    const originalText = deleteBtn.textContent;
    deleteBtn.textContent = 'Deleting...';
    deleteBtn.disabled = true;
    
    // Send delete request
    fetch(`/position-management/${positionId}`, {
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
            showDeleteSuccessMessage('Position deleted successfully');
            
            // Close modal
            const deleteModal = document.getElementById('delete-confirmation-modal');
            const modalInstance = tailwind.Modal.getInstance(deleteModal);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Refresh the positions table
            setTimeout(() => {
                refreshPositionsTable();
            }, 500);
        } else {
            showErrorMessage(data.message || 'Failed to delete position');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while deleting the position');
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

// Refresh positions table
function refreshPositionsTable() {
    fetch('/position-management/positions', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updatePositionsTable(data.positions);
        }
    })
    .catch(error => {
        console.error('Error refreshing positions:', error);
    });
}

// Update positions table with new data
function updatePositionsTable(positions) {
    const tableBody = document.getElementById('positions-table-body');
    if (!tableBody) return;
    
    let html = '';
    
    if (positions.length === 0) {
        html = '<tr><td colspan="5" class="text-center py-8 text-slate-500">No positions found</td></tr>';
    } else {
        positions.forEach(position => {
            const statusClass = position.status === 'active' ? 'text-success' : 'text-danger';
            const statusIcon = position.status === 'active' 
                ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> Active'
                : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x-circle" data-lucide="x-circle" class="lucide lucide-x-circle w-4 h-4 mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> Inactive';
            
            const createdDate = new Date(position.created_at).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
            
            html += `
                <tr class="intro-x">
                    <td>
                        <a href="" class="font-medium whitespace-nowrap">${position.position_name}</a>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            ${position.description || 'No description'}
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center ${statusClass}">
                            ${statusIcon}
                        </div>
                    </td>
                    <td class="text-center">${createdDate}</td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="javascript:;" onclick="editPosition(${position.id})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" class="lucide lucide-edit w-4 h-4 mr-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit
                            </a>
                            <a class="flex items-center text-danger" href="javascript:;" onclick="deletePosition(${position.id})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2" data-lucide="trash-2" class="lucide lucide-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete
                            </a>
                        </div>
                    </td>
                </tr>
            `;
        });
    }
    
    tableBody.innerHTML = html;
    
    // Update position count
    updatePositionCount(positions.length);
}

// Update position count display
function updatePositionCount(count) {
    const countElement = document.querySelector('.intro-y.col-span-12 .hidden.md\\:block.mx-auto.text-slate-500');
    if (countElement) {
        countElement.textContent = `Showing ${count} position${count === 1 ? '' : 's'}`;
    }
}
