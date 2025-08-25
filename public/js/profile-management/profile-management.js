// Profile Management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize modal event listeners
    initializeModalEvents();
    
    // Initialize change password form
    initializeChangePasswordForm();
    
    // Populate user data from the page
    populateUserData();
});

// Populate user data from the page
function populateUserData() {
    // Get user data from data attributes or hidden inputs
    const userDataElement = document.querySelector('[data-user-data]');
    if (userDataElement) {
        try {
            const userData = JSON.parse(userDataElement.textContent);
            window.currentUser = userData;
        } catch (e) {
            console.error('Error parsing user data:', e);
        }
    }
}

// Initialize modal events
function initializeModalEvents() {
    // Edit profile modal events
    const editProfileModal = document.getElementById('edit-profile-modal');
    if (editProfileModal) {
        editProfileModal.addEventListener('hidden.tw.modal', function() {
            resetEditForm();
        });
    }
    
    // Photo upload modal events
    const photoUploadModal = document.getElementById('photo-upload-modal');
    if (photoUploadModal) {
        photoUploadModal.addEventListener('hidden.tw.modal', function() {
            // Reset file input and selected file when modal is closed
            document.getElementById('profile-photo-input').value = '';
            selectedPhotoFile = null;
        });
    }
}

// Initialize change password form
function initializeChangePasswordForm() {
    const changePasswordForm = document.getElementById('change-password-form');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            changePassword();
        });
    }
}

// Edit profile function
function editProfile() {
    // Clear previous errors
    clearEditErrors();
    
    // Show edit modal
    const editModal = document.getElementById('edit-profile-modal');
    const modalInstance = tailwind.Modal.getOrCreateInstance(editModal);
    modalInstance.show();
}

// Update profile function
function updateProfile() {
    // Clear previous errors
    clearEditErrors();
    
    // Get form values
    const name = document.getElementById('edit_name').value.trim();
    const email = document.getElementById('edit_email').value.trim();
    const contactNumber = document.getElementById('edit_contact_number').value.trim();
    const address = document.getElementById('edit_address').value.trim();
    const dateOfBirth = document.getElementById('edit_date_of_birth').value;
    const gender = document.getElementById('edit_gender').value;
    
    // Basic validation - only validate if fields are filled
    if (name && name.trim() === '') {
        showEditError('edit_name', 'Name cannot be empty if provided');
        return;
    }
    
    if (email && email.trim() === '') {
        showEditError('edit_email', 'Email cannot be empty if provided');
        return;
    }
    
    // Get current user data for comparison
    const currentUser = window.currentUser || {};
    
    // Only include fields that have changed
    const changedFields = {};
    if (name !== currentUser.name) changedFields.name = name;
    if (email !== currentUser.email) changedFields.email = email;
    if (contactNumber !== (currentUser.contact_number || '')) changedFields.contact_number = contactNumber;
    if (address !== (currentUser.address || '')) changedFields.address = address;
    if (dateOfBirth !== (currentUser.date_of_birth || '')) changedFields.date_of_birth = dateOfBirth;
    if (gender !== (currentUser.gender || '')) changedFields.gender = gender;
    
    // If no fields changed, show message and return
    if (Object.keys(changedFields).length === 0) {
        showEditSuccessMessage('No changes detected');
        return;
    }
    
    // Show loading state
    const updateBtn = document.querySelector('#edit-profile-modal .btn-primary');
    const originalText = updateBtn.textContent;
    updateBtn.textContent = 'Updating...';
    updateBtn.disabled = true;
    
    // Prepare data with only changed fields
    const formData = {
        ...changedFields,
        _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    };
    
    // Send AJAX request
    fetch('/profile-management/update', {
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
            
            // Update the current user data with new values
            window.currentUser = { ...window.currentUser, ...changedFields };
            
            // Close modal
            const editModal = document.getElementById('edit-profile-modal');
            const modalInstance = tailwind.Modal.getInstance(editModal);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Reset form
            resetEditForm();
            
            // Update the display on the page without refreshing
            updateProfileDisplay(changedFields);
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
                showErrorMessage(data.message || 'Failed to update profile');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while updating the profile');
    })
    .finally(() => {
        // Reset button state
        updateBtn.textContent = originalText;
        updateBtn.disabled = false;
    });
}

// Function to update profile display without refreshing the page
function updateProfileDisplay(changedFields) {
    // Update name display
    if (changedFields.name) {
        const nameElements = document.querySelectorAll('.w-24.sm\\:w-40.truncate.sm\\:whitespace-normal.font-medium.text-lg');
        nameElements.forEach(el => el.textContent = changedFields.name);
        
        // Update name in user information section
        const infoNameElement = document.querySelector('#dashboard .flex.items-center .font-medium.w-24');
        if (infoNameElement && infoNameElement.textContent.includes('Name:')) {
            const nameValueElement = infoNameElement.nextElementSibling;
            if (nameValueElement && nameValueElement.classList.contains('ml-3')) {
                nameValueElement.textContent = changedFields.name;
            }
        }
    }
    
    // Update email display
    if (changedFields.email) {
        const emailElements = document.querySelectorAll('.flex.items-center');
        emailElements.forEach(el => {
            const mailIcon = el.querySelector('.lucide-mail');
            if (mailIcon) {
                const emailText = el.textContent.trim();
                if (emailText.includes('@')) {
                    el.innerHTML = el.innerHTML.replace(emailText, changedFields.email);
                }
            }
        });
        
        // Update email in user information section
        const infoEmailElement = document.querySelector('#dashboard .flex.items-center .font-medium.w-24');
        if (infoEmailElement && infoEmailElement.textContent.includes('Email:')) {
            const emailValueElement = infoEmailElement.nextElementSibling;
            if (emailValueElement && emailValueElement.classList.contains('ml-3')) {
                emailValueElement.textContent = changedFields.email;
            }
        }
    }
    
    // Update contact number display
    if (changedFields.contact_number !== undefined) {
        const contactElements = document.querySelectorAll('.flex.items-center');
        contactElements.forEach(el => {
            const phoneIcon = el.querySelector('.lucide-phone');
            if (phoneIcon) {
                const contactText = el.textContent.trim();
                if (contactText.includes('+') || /^\d+$/.test(contactText.replace(/\s+/g, ''))) {
                    if (changedFields.contact_number) {
                        el.innerHTML = el.innerHTML.replace(contactText, changedFields.contact_number);
                    } else {
                        el.style.display = 'none';
                    }
                }
            }
        });
        
        // Update contact in user information section
        const infoContactElement = document.querySelector('#dashboard .flex.items-center .font-medium.w-24');
        if (infoContactElement && infoContactElement.textContent.includes('Contact:')) {
            const contactValueElement = infoContactElement.nextElementSibling;
            if (contactValueElement && contactValueElement.classList.contains('ml-3')) {
                contactValueElement.textContent = changedFields.contact_number || 'Not Set';
            }
        }
    }
    
    // Update address display
    if (changedFields.address !== undefined) {
        const addressElements = document.querySelectorAll('.flex.items-center');
        addressElements.forEach(el => {
            const mapIcon = el.querySelector('.lucide-map-pin');
            if (mapIcon) {
                const addressText = el.textContent.trim();
                if (addressText && !addressText.includes('@') && !addressText.includes('+')) {
                    if (changedFields.address) {
                        el.innerHTML = el.innerHTML.replace(addressText, changedFields.address);
                    } else {
                        el.style.display = 'none';
                    }
                }
            }
        });
        
        // Update address in user information section
        const infoAddressElement = document.querySelector('#dashboard .flex.items-center .font-medium.w-24');
        if (infoAddressElement && infoAddressElement.textContent.includes('Address:')) {
            const addressValueElement = infoAddressElement.nextElementSibling;
            if (addressValueElement && addressValueElement.classList.contains('ml-3')) {
                addressValueElement.textContent = changedFields.address || 'Not Set';
            }
        }
    }
    
    // Update date of birth display
    if (changedFields.date_of_birth !== undefined) {
        const dobElements = document.querySelectorAll('.flex.items-center');
        dobElements.forEach(el => {
            const calendarIcon = el.querySelector('.lucide-calendar');
            if (calendarIcon) {
                const dobText = el.textContent.trim();
                if (dobText && dobText.includes(',')) {
                    if (changedFields.date_of_birth) {
                        const formattedDate = new Date(changedFields.date_of_birth).toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric'
                        });
                        el.innerHTML = el.innerHTML.replace(dobText, formattedDate);
                    } else {
                        el.style.display = 'none';
                    }
                }
            }
        });
        
        // Update DOB in user information section
        const infoDobElement = document.querySelector('#dashboard .flex.items-center .font-medium.w-24');
        if (infoDobElement && infoDobElement.textContent.includes('Date of Birth:')) {
            const dobValueElement = infoDobElement.nextElementSibling;
            if (dobValueElement && dobValueElement.classList.contains('ml-3')) {
                if (changedFields.date_of_birth) {
                    const formattedDate = new Date(changedFields.date_of_birth).toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                    dobValueElement.textContent = formattedDate;
                } else {
                    dobValueElement.textContent = 'Not Set';
                }
            }
        }
    }
    
    // Update gender display
    if (changedFields.gender !== undefined) {
        const genderElements = document.querySelectorAll('.flex.items-center');
        genderElements.forEach(el => {
            const userIcon = el.querySelector('.lucide-user');
            if (userIcon) {
                const genderText = el.textContent.trim();
                if (genderText && ['Male', 'Female', 'Other'].includes(genderText)) {
                    if (changedFields.gender) {
                        el.innerHTML = el.innerHTML.replace(genderText, changedFields.gender.charAt(0).toUpperCase() + changedFields.gender.slice(1));
                    } else {
                        el.style.display = 'none';
                    }
                }
            }
        });
        
        // Update gender in user information section
        const infoGenderElement = document.querySelector('#dashboard .flex.items-center .font-medium.w-24');
        if (infoGenderElement && infoGenderElement.textContent.includes('Gender:')) {
            const genderValueElement = infoGenderElement.nextElementSibling;
            if (genderValueElement && genderValueElement.classList.contains('ml-3')) {
                infoGenderElement.textContent = changedFields.gender ? (changedFields.gender.charAt(0).toUpperCase() + changedFields.gender.slice(1)) : 'Not Set';
            }
        }
    }
}

// Change password function
function changePassword() {
    // Clear previous errors
    clearPasswordErrors();
    
    // Get form values
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    // Basic validation
    if (!currentPassword) {
        showPasswordError('current_password', 'Current password is required');
        return;
    }
    
    if (!newPassword) {
        showPasswordError('new_password', 'New password is required');
        return;
    }
    
    if (newPassword.length < 8) {
        showPasswordError('new_password', 'New password must be at least 8 characters');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showPasswordError('confirm_password', 'Passwords do not match');
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('#change-password-form .btn-primary');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Changing...';
    submitBtn.disabled = true;
    
    // Prepare data
    const formData = {
        current_password: currentPassword,
        new_password: newPassword,
        new_password_confirmation: confirmPassword,
        _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        _method: 'POST'
    };
    
    // Send AJAX request
    fetch('/profile-management/change-password', {
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
            showPasswordSuccessMessage(data.message);
            
            // Reset form
            resetPasswordForm();
        } else {
            // Show validation errors
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    showPasswordError(field, data.errors[field][0]);
                });
                // Show validation error notification
                showValidationErrorMessage();
            } else {
                showPasswordErrorMessage(data.message || 'Failed to change password');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showPasswordErrorMessage('An error occurred while changing the password');
    })
    .finally(() => {
        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
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
    const errorElements = document.querySelectorAll('#edit-profile-modal [id$="_error"]');
    errorElements.forEach(element => {
        element.textContent = '';
        element.style.display = 'none';
    });
    
    // Remove error classes from inputs
    const inputs = document.querySelectorAll('#edit-profile-modal .form-control, #edit-profile-modal .form-select');
    inputs.forEach(input => {
        input.classList.remove('border-danger');
    });
}

// Reset edit form
function resetEditForm() {
    // Reset form fields to current user values
    const user = window.currentUser || {};
    document.getElementById('edit_name').value = user.name || '';
    document.getElementById('edit_email').value = user.email || '';
    document.getElementById('edit_contact_number').value = user.contact_number || '';
    document.getElementById('edit_address').value = user.address || '';
    document.getElementById('edit_date_of_birth').value = user.date_of_birth || '';
    document.getElementById('edit_gender').value = user.gender || '';
    
    clearEditErrors();
}

// Show error message for password form fields
function showPasswordError(fieldName, message) {
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

// Clear all password form errors
function clearPasswordErrors() {
    const errorElements = document.querySelectorAll('#change-password-form [id$="_error"]');
    errorElements.forEach(element => {
        element.textContent = '';
        element.style.display = 'none';
    });
    
    // Remove error classes from inputs
    const inputs = document.querySelectorAll('#change-password-form .form-control');
    inputs.forEach(input => {
        input.classList.remove('border-danger');
    });
}

// Reset password form
function resetPasswordForm() {
    document.getElementById('current_password').value = '';
    document.getElementById('new_password').value = '';
    document.getElementById('confirm_password').value = '';
    clearPasswordErrors();
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

// Show password success message using notification toast
function showPasswordSuccessMessage(message) {
    if (typeof showNotification_password_success !== 'undefined') {
        showNotification_password_success();
    } else if (typeof showNotification_success !== 'undefined') {
        // Fallback to general success notification
        showNotification_success();
    } else {
        // Fallback to console if notification system not available
        console.log('Success:', message);
    }
}

// Show password error message using notification toast
function showPasswordErrorMessage(message) {
    if (typeof showNotification_password_error !== 'undefined') {
        showNotification_password_error();
    } else if (typeof showNotification_error !== 'undefined') {
        // Fallback to general error notification
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

// Show photo upload success message using notification toast
function showPhotoUploadSuccessMessage(message) {
    if (typeof showNotification_photo_success !== 'undefined') {
        showNotification_photo_success();
    } else if (typeof showNotification_success !== 'undefined') {
        // Fallback to general success notification
        showNotification_success();
    } else {
        // Fallback to console if notification system not available
        console.log('Success:', message);
    }
}

// Show photo upload error message using notification toast
function showPhotoUploadErrorMessage(message) {
    if (typeof showNotification_photo_error !== 'undefined') {
        showNotification_photo_error();
    } else if (typeof showNotification_error !== 'undefined') {
        // Fallback to general error notification
        showNotification_error();
    } else {
        // Fallback to console if notification system not available
        console.log('Error:', message);
    }
}

// Add CSRF token to all AJAX requests
function getCSRFToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

// Global variable to store the selected file
let selectedPhotoFile = null;

// Confirm photo upload function
function confirmPhotoUpload(input) {
    const file = input.files[0];
    if (!file) return;
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
        showPhotoUploadErrorMessage('Please select an image file');
        input.value = '';
        return;
    }
    
    // Validate file size (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        showPhotoUploadErrorMessage('Image size should be less than 5MB');
        input.value = '';
        return;
    }
    
    // Store the selected file globally
    selectedPhotoFile = file;
    
    // Create preview URL
    const reader = new FileReader();
    reader.onload = function(e) {
        // Show photo preview
        document.getElementById('photo-preview').src = e.target.result;
        document.getElementById('photo-filename').textContent = file.name;
        document.getElementById('photo-filesize').textContent = formatFileSize(file.size);
        
        // Show confirmation modal
        const modal = document.getElementById('photo-upload-modal');
        const modalInstance = tailwind.Modal.getOrCreateInstance(modal);
        modalInstance.show();
    };
    reader.readAsDataURL(file);
}

// Proceed with photo upload after confirmation
function proceedWithPhotoUpload() {
    if (!selectedPhotoFile) {
        showPhotoUploadErrorMessage('No photo selected');
        return;
    }
    
    // Close the confirmation modal
    const modal = document.getElementById('photo-upload-modal');
    const modalInstance = tailwind.Modal.getInstance(modal);
    if (modalInstance) {
        modalInstance.hide();
    }
    
    // Create FormData
    const formData = new FormData();
    formData.append('profile_photo', selectedPhotoFile);
    formData.append('_token', getCSRFToken());
    
    // Show loading state
    const cameraIcon = document.querySelector('.absolute.mb-1.mr-1');
    const originalHTML = cameraIcon.innerHTML;
    cameraIcon.innerHTML = '<svg class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></span></svg>';
    
    // Send AJAX request
    fetch('/profile-management/upload-photo', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCSRFToken()
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showPhotoUploadSuccessMessage('Profile photo updated successfully');
            
            // Update the profile image
            const profileImage = document.querySelector('.w-20.h-20.sm\\:w-24.sm\\:h-24.lg\\:w-32.lg\\:h-32 img');
            if (profileImage) {
                profileImage.src = data.photo_url + '?t=' + new Date().getTime(); // Add timestamp to prevent caching
            }
            
            // Update the profile image in the header/avatar area if it exists
            const headerProfileImage = document.querySelector('.w-8.h-8 img, .w-10.h-10 img');
            if (headerProfileImage) {
                headerProfileImage.src = data.photo_url + '?t=' + new Date().getTime();
            }
        } else {
            showPhotoUploadErrorMessage(data.message || 'Failed to upload profile photo');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showPhotoUploadErrorMessage('An error occurred while uploading the photo');
    })
    .finally(() => {
        // Reset camera icon
        cameraIcon.innerHTML = originalHTML;
        
        // Reset file input and selected file
        document.getElementById('profile-photo-input').value = '';
        selectedPhotoFile = null;
    });
}

// Format file size for display
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Store current user data for form reset
window.currentUser = {
    name: '',
    email: '',
    contact_number: '',
    address: '',
    date_of_birth: '',
    gender: ''
};

// Make functions globally available
window.confirmPhotoUpload = confirmPhotoUpload;
window.proceedWithPhotoUpload = proceedWithPhotoUpload;
window.editProfile = editProfile;
window.updateProfile = updateProfile;
window.changePassword = changePassword;
