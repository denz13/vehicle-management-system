// Reservation Type Management JavaScript
console.log('=== RESERVATION TYPE MANAGEMENT JAVASCRIPT LOADING ===');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing reservation type management...');
    initializeDeleteModal();
});

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

// Save new reservation type
function saveReservationType() {
    const reservationName = document.getElementById('reservation_name').value.trim();
    const description = document.getElementById('description').value.trim();
    const status = document.getElementById('status').value;
    
    if (!reservationName || !status) {
        showValidationErrorMessage();
        return;
    }
    
    const saveBtn = document.querySelector('#add-reservation-type-modal .btn-primary');
    saveBtn.textContent = 'Saving...';
    saveBtn.disabled = true;
    
    fetch('/reservation-type', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            reservation_name: reservationName,
            description: description,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showSuccessMessage(data.message);
            
            // Close modal
            const modal = document.getElementById('add-reservation-type-modal');
            const modalInstance = tailwind.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Reset form
            resetForm();
            
            // Refresh the page after a short delay
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            // Show validation errors
            if (data.errors) {
                // Show validation error notification
                showValidationErrorMessage();
            } else {
                showErrorMessage(data.message || 'Failed to save reservation type');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while saving the reservation type');
    })
    .finally(() => {
        saveBtn.textContent = 'Save';
        saveBtn.disabled = false;
    });
}

// Reset form
function resetForm() {
    document.getElementById('reservation_name').value = '';
    document.getElementById('description').value = '';
    document.getElementById('status').value = '';
}

// Edit reservation type
function editReservationType(id) {
    fetch(`/reservation-type/${id}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('edit_reservation_type_id').value = data.reservationType.id;
            document.getElementById('edit_reservation_name').value = data.reservationType.reservation_name;
            document.getElementById('edit_description').value = data.reservationType.description || '';
            document.getElementById('edit_status').value = data.reservationType.status;
            
            // Show edit modal
            const editModal = document.getElementById('edit-reservation-type-modal');
            const modalInstance = tailwind.Modal.getOrCreateInstance(editModal);
            modalInstance.show();
        } else {
            showErrorMessage(data.message || 'Failed to load reservation type data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while loading reservation type data');
    });
}

// Update reservation type
function updateReservationType() {
    const id = document.getElementById('edit_reservation_type_id').value;
    const reservationName = document.getElementById('edit_reservation_name').value.trim();
    const description = document.getElementById('edit_description').value.trim();
    const status = document.getElementById('edit_status').value;
    
    if (!reservationName || !status) {
        showValidationErrorMessage();
        return;
    }
    
    const updateBtn = document.querySelector('#edit-reservation-type-modal .btn-primary');
    updateBtn.textContent = 'Updating...';
    updateBtn.disabled = true;
    
    fetch(`/reservation-type/${id}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            reservation_name: reservationName,
            description: description,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showEditSuccessMessage(data.message);
            
            // Close modal
            const editModal = document.getElementById('edit-reservation-type-modal');
            const modalInstance = tailwind.Modal.getInstance(editModal);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Reset form
            resetEditForm();
            
            // Refresh the page after a short delay
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            // Show validation errors
            if (data.errors) {
                // Show validation error notification
                showValidationErrorMessage();
            } else {
                showErrorMessage(data.message || 'Failed to update reservation type');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while updating the reservation type');
    })
    .finally(() => {
        updateBtn.textContent = 'Update';
        updateBtn.disabled = false;
    });
}

// Reset edit form
function resetEditForm() {
    document.getElementById('edit_reservation_type_id').value = '';
    document.getElementById('edit_reservation_name').value = '';
    document.getElementById('edit_description').value = '';
    document.getElementById('edit_status').value = '';
}

// Delete reservation type
function deleteReservationType(id) {
    // Store the reservation type ID for deletion
    window.reservationTypeToDelete = id;
    
    // Show delete confirmation modal
    const deleteModal = document.getElementById('delete-confirmation-modal');
    const modalInstance = tailwind.Modal.getOrCreateInstance(deleteModal);
    modalInstance.show();
}

// Initialize delete confirmation modal
function initializeDeleteModal() {
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            const reservationTypeId = window.reservationTypeToDelete;
            if (reservationTypeId) {
                performDeleteReservationType(reservationTypeId);
            }
        });
    }
}

// Perform the actual deletion
function performDeleteReservationType(id) {
    const confirmBtn = document.getElementById('confirm-delete-btn');
    confirmBtn.textContent = 'Deleting...';
    confirmBtn.disabled = true;
    
    fetch(`/reservation-type/${id}`, {
        method: 'DELETE',
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
            showDeleteSuccessMessage('Reservation type deleted successfully');
            
            // Close modal
            const deleteModal = document.getElementById('delete-confirmation-modal');
            const modalInstance = tailwind.Modal.getInstance(deleteModal);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Refresh the page after a short delay
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            showErrorMessage(data.message || 'Failed to delete reservation type');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while deleting the reservation type');
    })
    .finally(() => {
        confirmBtn.textContent = 'Delete';
        confirmBtn.disabled = false;
    });
}
