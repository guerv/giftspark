document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = document.getElementById('login_email').value;
    const password = document.getElementById('login_password').value;
    const errorContainer = document.getElementById('login_errors');
    
    // Clear previous errors
    errorContainer.innerHTML = '';
    
    // Simple validation
    if (!email.includes('@') || !email.includes('.')) {
        showError('Please enter a valid email address');
        e.preventDefault();
    }
    
    if (password.length < 8) {
        showError('Password must be at least 8 characters');
        e.preventDefault();
    }
    
    function showError(message) {
        const error = document.createElement('p');
        error.className = 'error-message';
        error.textContent = message;
        errorContainer.appendChild(error);
    }
});