document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('confirm_password');
            if (passwordInput.value !== confirmInput.value) {
                event.preventDefault(); 
                if (typeof showPopup === 'function') {
                    showPopup('error', 'Konfirmasi password tidak cocok!');
                } else {
                    alert('Konfirmasi password tidak cocok!');
                }
            }
        });
    }
});
function togglePassword() {
    const p = document.getElementById("password");
    if (p) p.type = (p.type === "password") ? "text" : "password";
}
function toggleConfirm() {
    const c = document.getElementById("confirm_password");
    if (c) c.type = (c.type === "password") ? "text" : "password";
}
function showPopup(type, message) {
    const popup = document.getElementById("popup");
    const title = document.getElementById("popup-title");
    const msg = document.getElementById("popup-message");
    if (popup && title && msg) {
        popup.classList.remove("success", "error");
        popup.classList.add(type);
        title.textContent = (type === "success") ? "Berhasil" : "Gagal";
        msg.textContent = message;
        popup.style.display = "flex";
        setTimeout(() => {
            popup.classList.add("show");
        }, 10);
    } else {
        console.error("Popup elements not found!");
        alert(message);
    }
}
function closePopup() {
    const popup = document.getElementById("popup");
    if (popup) {
        popup.classList.remove('show');
        popup.addEventListener('transitionend', function handler() {
            popup.style.display = "none";
            popup.removeEventListener('transitionend', handler);
            if (popup.classList.contains('success')) {
                window.location.href = 'login.php'; 
            }
        });
    }
}