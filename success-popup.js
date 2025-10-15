// Success Popup Functionality
function showSuccessPopup() {
  const successPopup = document.getElementById('successPopup');
  
  // Show the popup
  successPopup.style.display = 'flex';
  
  // Auto hide after 2 seconds
  setTimeout(() => {
    hideSuccessPopup();
  }, 3000);
}

function hideSuccessPopup() {
  const successPopup = document.getElementById('successPopup');
  
  // Add fade out animation
  successPopup.style.animation = 'fadeOut 0.3s ease-out forwards';
  
  // Hide after animation completes
  setTimeout(() => {
    successPopup.style.display = 'none';
    successPopup.style.animation = '';
  }, 300);
}

// Add fadeOut animation to CSS
const style = document.createElement('style');
style.textContent = `
  @keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
  }
`;
document.head.appendChild(style);

// Handle form submission for register form
document.addEventListener('DOMContentLoaded', function() {
  const registerForm = document.querySelector('#registerPopup form');
  
  if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
      // Prevent default form submission
      e.preventDefault();
      
      // Validate passwords first
      if (!validatePasswords()) {
        return;
      }
      
      // Get form data
      const formData = new FormData(this);
      
      // Debug: Log form data
      console.log('Form data being sent:');
      for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
      }
      
      // Submit form data to PHP
      fetch('register_test.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        console.log('Response status:', response.status);
        return response.json();
      })
      .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
          // Show success popup
          showSuccessPopup();
          
          // Close the register popup
          closePopup('registerPopup');
          
          // Reset form
          this.reset();
        } else {
          // Show error message with debug info
          const errorMsg = data.message || 'Terjadi kesalahan saat mendaftar.';
          console.log('Error details:', data.debug);
          alert(errorMsg);
        }
      })
      .catch(error => {
        console.error('Fetch Error:', error);
        alert('Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
      });
    });
  }
});

// Password Validation Function
function validatePasswords() {
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirm_password').value;
  
  if (password !== confirmPassword) {
    alert('Password dan Konfirmasi Password tidak cocok!');
    return false;
  }
  
  if (password.length < 6) {
    alert('Password harus minimal 6 karakter!');
    return false;
  }
  
  return true;
}

// Make function globally available
window.showSuccessPopup = showSuccessPopup;
window.hideSuccessPopup = hideSuccessPopup;
window.togglePassword = togglePassword;
window.validatePasswords = validatePasswords; 