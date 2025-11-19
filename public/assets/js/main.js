document.addEventListener('DOMContentLoaded', function() {
    const addModal = document.getElementById('addSongModal');
    const editModal = document.getElementById('editSongModal');
    const openAddBtn = document.getElementById('openAddModalBtn');
    const closeBtns = document.querySelectorAll('.close-modal-btn');
    const overlays = document.querySelectorAll('.modal-overlay');
    function closeAllModals() {
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.classList.remove('active');
            modal.style.display = '';
        });
        document.body.style.overflow = ''; 
    }
    //logika buka modal tambah
    if (openAddBtn && addModal) {
        openAddBtn.addEventListener('click', function() {
            closeAllModals();
            addModal.style.display = ''; 
            addModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    //logika buka modal edit
    document.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.btn-edit');
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation();
            if (!editModal) {
                console.error("Modal Edit tidak ditemukan!");
                return;
            }
            closeAllModals();
            const data = editBtn.dataset;
            const form = document.getElementById('editSongForm');
            if (form) {
                form.querySelector('#edit_lagu_id').value = data.id || '';
                form.querySelector('#edit_judul').value = data.judul || '';
                form.querySelector('#edit_artist_name').value = data.artist || '';
                form.querySelector('#edit_tahun').value = data.tahun || '';
                let genres = [];
                try {
                    if (data.genres && data.genres !== "null") {
                        genres = JSON.parse(data.genres);
                    }
                } catch (err) {
                    console.error("Gagal parse genre", err);
                }
                const checkboxes = form.querySelectorAll('.edit-genre-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = false;
                    if (genres.includes(parseInt(cb.value))) {
                        cb.checked = true;
                    }
                });
            }
            editModal.style.display = ''; 
            editModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    });
    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => closeAllModals());
    });
    overlays.forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeAllModals();
        });
    });
    document.addEventListener('click', function(e) {
        const toggle = e.target.closest('.dropdown-toggle');
        if (toggle) {
            e.preventDefault();
            e.stopPropagation();
            const currentMenu = toggle.nextElementSibling;
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                if (menu !== currentMenu) menu.classList.remove('show');
            });
            if (currentMenu) currentMenu.classList.toggle('show');
        } else {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(alert => {
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            });
        }, 4000);
    }
});