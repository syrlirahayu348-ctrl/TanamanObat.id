// ─── TanamanObat.id — Main JavaScript ─────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {

    // ── Navbar scroll effect ────────────────────────────────
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        });
    }

    // ── User Dropdown Menu ──────────────────────────────────
    const userMenu = document.querySelector('.user-menu');
    if (userMenu) {
        const trigger = userMenu.querySelector('.user-menu__trigger');
        trigger?.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle('open');
        });
        document.addEventListener('click', () => userMenu.classList.remove('open'));
    }

    // ── Mobile nav toggle ───────────────────────────────────
    const mobileToggle = document.querySelector('.navbar__mobile-toggle');
    const mobileNav = document.querySelector('.mobile-nav');
    if (mobileToggle && mobileNav) {
        mobileToggle.addEventListener('click', () => {
            mobileNav.classList.toggle('open');
        });
    }

    // ── Sidebar toggle (admin) ──────────────────────────────
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });
    }

    // ── Scroll Reveal Animations ────────────────────────────
    const reveals = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    reveals.forEach(el => revealObserver.observe(el));

    // ── Floating Particles (Hero) ───────────────────────────
    const particlesContainer = document.querySelector('.hero__particles');
    if (particlesContainer) {
        const leafEmojis = ['🌿', '🍃', '🌱', '🌾', '🍀', '🌵', '🪴', '🌿'];
        for (let i = 0; i < 18; i++) {
            const el = document.createElement('span');
            el.className = 'particle';
            el.textContent = leafEmojis[Math.floor(Math.random() * leafEmojis.length)];
            el.style.left = `${Math.random() * 100}%`;
            el.style.animationDuration = `${8 + Math.random() * 12}s`;
            el.style.animationDelay = `${Math.random() * 10}s`;
            el.style.fontSize = `${16 + Math.random() * 20}px`;
            particlesContainer.appendChild(el);
        }
    }

    // ── Favorite Toggle (AJAX) ──────────────────────────────
    document.querySelectorAll('[data-favorite]').forEach(btn => {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();

            if (!this.dataset.auth) {
                showToast('Silakan login untuk menyimpan favorit.', 'info');
                setTimeout(() => window.location.href = '/login', 1200);
                return;
            }

            const plantId = this.dataset.plantId;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            try {
                const response = await fetch(`/favorites/${plantId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                const data = await response.json();

                if (data.success) {
                    // Update all favorite buttons for this plant on page
                    document.querySelectorAll(`[data-favorite][data-plant-id="${plantId}"]`).forEach(el => {
                        el.classList.toggle('active', data.favorited);
                        
                        const iconEl = el.querySelector('.fav-icon');
                        if (el.classList.contains('btn-favorite')) {
                            // Floating image button
                            if (iconEl) iconEl.textContent = data.favorited ? '❤️' : '🤍';
                        } else {
                            // Sidebar button or other textual buttons
                            if (iconEl) {
                                iconEl.textContent = data.favorited ? '❤️ Hapus Favorit' : '🤍 Simpan Favorit';
                            }
                            if (data.favorited) {
                                el.classList.remove('btn-primary');
                                el.classList.add('btn-danger');
                            } else {
                                el.classList.remove('btn-danger');
                                el.classList.add('btn-primary');
                            }
                        }
                    });

                    showToast(data.message, 'success');

                    const countEl = document.querySelector('.fav-count');
                    if (countEl) countEl.textContent = data.count;
                }
            } catch (err) {
                showToast('Terjadi kesalahan. Coba lagi.', 'error');
            }
        });
    });

    // ── Image Preview (Upload) ──────────────────────────────
    const imageInputs = document.querySelectorAll('[data-image-preview]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function () {
            const previewId = this.dataset.imagePreview;
            const preview = document.getElementById(previewId);
            if (preview && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // ── Live Search (debounce) ──────────────────────────────
    const searchInputs = document.querySelectorAll('[data-live-search]');
    searchInputs.forEach(input => {
        let timer;
        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(() => {
                const form = this.closest('form');
                if (form) form.submit();
            }, 500);
        });
    });

    // ── Auto dismiss alerts ─────────────────────────────────
    document.querySelectorAll('.alert[data-dismiss]').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.4s ease, height 0.4s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 400);
        }, 4000);
    });

    // ── Confirm Delete modals ───────────────────────────────
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            const msg = this.dataset.confirm || 'Apakah Anda yakin?';
            if (!confirm(msg)) e.preventDefault();
        });
    });

    // ── Number counter animation ────────────────────────────
    const counters = document.querySelectorAll('[data-count]');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseInt(entry.target.dataset.count);
                animateCount(entry.target, target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    counters.forEach(el => counterObserver.observe(el));

    function animateCount(el, target) {
        let current = 0;
        const step = target / 60;
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                el.textContent = target.toLocaleString('id-ID');
                clearInterval(timer);
            } else {
                el.textContent = Math.floor(current).toLocaleString('id-ID');
            }
        }, 20);
    }

    // ── Modal handlers ──────────────────────────────────────
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.modalOpen;
            document.getElementById(id)?.classList.add('open');
        });
    });
    document.querySelectorAll('[data-modal-close], .modal-overlay').forEach(el => {
        el.addEventListener('click', function (e) {
            if (e.target === this || this.hasAttribute('data-modal-close')) {
                this.closest('.modal-overlay')?.classList.remove('open');
                document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('open'));
            }
        });
    });
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', e => e.stopPropagation());
    });

    // ── Toast Notification ──────────────────────────────────
    // Show server flash toasts
    const flashMessages = document.querySelectorAll('[data-toast]');
    flashMessages.forEach(el => {
        showToast(el.dataset.toast, el.dataset.toastType || 'success');
    });
});

// ── Global Toast function ───────────────────────────────
function showToast(message, type = 'success') {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const icons = { success: '✅', error: '❌', info: 'ℹ️', warning: '⚠️' };
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `<span>${icons[type] || icons.success}</span><span>${message}</span>`;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(20px)';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}
