document.addEventListener('DOMContentLoaded', function() {
    //logika modal tambah lagu
    const openModalBtn = document.getElementById('openAddModalBtn');
    const closeModalBtn = document.querySelector('.close-modal-btn');
    const modalOverlay = document.getElementById('addSongModal');
    if (openModalBtn) {
        openModalBtn.addEventListener('click', function() {
            modalOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            closeModal();
        });
    }
    if (modalOverlay) {
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) {
                closeModal();
            }
        });
    }
    function closeModal() {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(alert => {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            });
        }, 4000);
    }

});