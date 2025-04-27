/*
Name: Megann Nkenglack
MacID: nkenglam
Student Number: 400590482
Date: 04-25-2025
Class: COMPSCI 1XD3 
About: Final Group Project - GiftSpark
*/

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('signupForm');
    const emailInput = document.getElementById('email');
    let emailChecked = false;
    let emailAvailable = false;

    // Load and display any errors from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const errorParam = urlParams.get('errors');
    
    if (errorParam) {
        displayUrlErrors(errorParam);
        // Clean up the URL without reloading
        history.replaceState({}, document.title, window.location.pathname);
    }

    // Real-time email availability check
    emailInput.addEventListener('blur', function() {
        const email = emailInput.value.trim();
        if (!email || !isValidEmail(email)) return;
        
        checkEmailAvailability(email).then(available => {
            emailChecked = true;
            emailAvailable = available;
            
            if (!available) {
                showError(emailInput, 'Email already registered. Please login instead. :)');
            } else {
                clearError(emailInput);
            }
        });
    });

    // Form submission 
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        clearAllErrors();
        
        // Validate all fields
        const isValid = validateAllFields();
        
        if (isValid) {
            await submitFormData(new FormData(form));
        }
    });

    // ===== HELPER FUNCTIONS =====

    function displayUrlErrors(errorParam) {
        const errorContainer = document.createElement('div');
        errorContainer.id = 'form-errors';
        
        errorParam.split('||').forEach(error => {
            const errorText = decodeURIComponent(error);
            const errorElement = document.createElement('p');
            errorElement.className = 'error-message';
            errorElement.textContent = errorText;
            errorContainer.appendChild(errorElement);
            
            const field = guessFieldFromError(errorText);
            if (field) {
                showError(field, errorText);
            }
        });
        
        form.insertBefore(errorContainer, form.firstChild);
    }

    function guessFieldFromError(errorText) {
        const lowerError = errorText.toLowerCase();
        if (lowerError.includes('email')) return emailInput;
        if (lowerError.includes('first')) return document.getElementById('firstName');
        if (lowerError.includes('last')) return document.getElementById('lastName');
        if (lowerError.includes('password')) {
            return lowerError.includes('match') 
                ? document.getElementById('confirmPassword')
                : document.getElementById('password');
        }
        return null;
    }

    async function checkEmailAvailability(email) {
        try {
            emailInput.classList.add('checking');
            const response = await fetch('../giftspark/create_acc/check_email.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email })
            });
            
            if (!response.ok) throw new Error('Network error');
            const data = await response.json();
            return !data.exists;
            
        } catch (error) {
            console.error('Email check failed:', error);
            showError(emailInput, 'Error checking email availability');
            return false;
        } finally {
            emailInput.classList.remove('checking');
        }
    }

    function validateAllFields() {
        let isValid = true;
        const firstName = document.getElementById('firstName');
        const lastName = document.getElementById('lastName');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmPassword');
        const email = emailInput.value.trim();

        // First Name validation
        if (!firstName.value.trim()) {
            showError(firstName, 'First name required');
            isValid = false;
        }

        // Last Name validation
        if (!lastName.value.trim()) {
            showError(lastName, 'Last name required');
            isValid = false;
        }

        // Email validation
        if (!email) {
            showError(emailInput, 'Email required');
            isValid = false;
        } else if (!isValidEmail(email)) {
            showError(emailInput, 'Invalid email format');
            isValid = false;
        } else if (!emailChecked) {
            showError(emailInput, 'Please check email availability');
            isValid = false;
        } else if (!emailAvailable) {
            showError(emailInput, 'Email already registered');
            isValid = false;
        }

        // Password validation
        if (!password.value) {
            showError(password, 'Password required');
            isValid = false;
        } else if (password.value.length < 8) {
            showError(password, 'Password must be at least 8 characters');
            isValid = false;
        } else if (!/[A-Z]/.test(password.value) || !/[0-9]/.test(password.value)) {
            showError(password, 'Password needs 1 uppercase letter and 1 number');
            isValid = false;
        }

        // Confirm Password
        if (password.value !== confirmPassword.value) {
            showError(confirmPassword, 'Passwords must match');
            isValid = false;
        }

        return isValid;
    }

    async function submitFormData(formData) {
        const submitBtn = form.querySelector('button[type="submit"]');
        try {
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Creating Account... <span class="spinner"></span>';
            
            const response = await fetch('../giftspark/create_acc/create_account.php', {
                method: 'POST',
                body: formData
            });
            
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            
            const result = await response.json();
            
            if (result.success) {
                window.location.href = '../giftspark/login/login.php?signup=success';
            } else {
                displayServerErrors(result.errors);
            }
        } catch (error) {
            showGeneralError('Network error. Please try again.' + error);
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Create Account';
        }
    }

    function displayServerErrors(errors) {
        const errorContainer = document.createElement('div');
        errorContainer.className = 'server-errors';
        
        errors.forEach(error => {
            const errorElement = document.createElement('p');
            errorElement.className = 'error-message';
            errorElement.textContent = error;
            errorContainer.appendChild(errorElement);
            
            const field = guessFieldFromError(error);
            if (field) showError(field, error);
        });
        
        form.prepend(errorContainer);
    }

    function showGeneralError(message) {
        const errorContainer = document.createElement('div');
        errorContainer.className = 'general-error';
        errorContainer.textContent = message;
        form.prepend(errorContainer);
    }

    function showError(field, message) {
        clearError(field);
        const errorElement = document.createElement('p');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        field.parentNode.insertBefore(errorElement, field.nextSibling);
        field.classList.add('error');
    }

    function clearError(field) {
        const existingError = field.nextElementSibling;
        if (existingError && existingError.classList.contains('error-message')) {
            existingError.remove();
        }
        field.classList.remove('error');
    }

    function clearAllErrors() {
        document.querySelectorAll('.error-message, .server-errors, .general-error').forEach(el => el.remove());
        document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
});