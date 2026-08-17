/* ============================================================
   TechFix — Auth Script
   Password Visibility Toggle & Client-Side Validation
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {
    // Password toggle
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password') || document.getElementById('reg-password');

    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            const icon = toggleBtn.querySelector('i');
            if (icon) {
                icon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
            }
        });
    }

    // Form submit feedback
    const authForms = document.querySelectorAll('.auth-form');
    authForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const submitBtn = form.querySelector('.btn-auth-submit');
            if (submitBtn) {
                submitBtn.style.opacity = '0.8';
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
            }
        });
    });
});
