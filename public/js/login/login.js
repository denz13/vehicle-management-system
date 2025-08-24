document.addEventListener('DOMContentLoaded', function() {
    const loginForm = {
        email: document.getElementById('login-email'),
        password: document.getElementById('login-password'),
        submitBtn: document.getElementById('login-submit'),
        rememberMe: document.getElementById('remember-me')
    };

    // Handle login form submission
    loginForm.submitBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Validate inputs
        if (!loginForm.email.value.trim()) {
            showToast('warning', 'Please enter your email address');
            loginForm.email.focus();
            return;
        }
        
        if (!loginForm.password.value.trim()) {
            showToast('warning', 'Please enter your password');
            loginForm.password.focus();
            return;
        }

        // Disable submit button and show loading state
        loginForm.submitBtn.disabled = true;
        loginForm.submitBtn.innerHTML = 'Logging in...';

        // Prepare login data
        const loginData = {
            email: loginForm.email.value.trim(),
            password: loginForm.password.value,
            remember: loginForm.rememberMe.checked
        };

        // Send login request
        fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(loginData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message || 'Login successful');
                // Redirect to dashboard after successful login
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 1000);
            } else {
                showToast('error', data.message || 'Login failed');
            }
        })
        .catch(error => {
            console.error('Login error:', error);
            showToast('error', 'An error occurred during login. Please try again.');
        })
        .finally(() => {
            // Re-enable submit button
            loginForm.submitBtn.disabled = false;
            loginForm.submitBtn.innerHTML = 'Login';
        });
    });

    // Handle Enter key press
    loginForm.password.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            loginForm.submitBtn.click();
        }
    });

    // Function to show toast notifications
    function showToast(type, message) {
        const toastId = `login_toast_${type}`;
        const toast = document.getElementById(toastId);
        
        if (toast) {
            // Update message if it's an error toast
            if (type === 'error') {
                const messageSlot = toast.querySelector('#login-error-message-slot');
                if (messageSlot) {
                    messageSlot.textContent = message;
                }
            }
            
            // Show the toast
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    node: toast,
                    duration: 5000,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true
                }).showToast();
            }
        }
    }

    // Auto-focus on email input when page loads
    loginForm.email.focus();
});
