/* ============================================================
   AgroNGO Components JS v2.0
   Shared interactions for the entire platform.
   ============================================================ */

(function() {
    'use strict';

    // ── Navbar scroll effect ──
    function initNavbar() {
        const navbar = document.querySelector('.agro-navbar');
        if (!navbar) return;

        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 10);
        }, { passive: true });

        // Mobile toggle
        const toggle = document.querySelector('.agro-navbar__toggle');
        const mobileMenu = document.querySelector('.agro-navbar__mobile-menu');
        if (toggle && mobileMenu) {
            toggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('open');
                toggle.classList.toggle('active');
            });

            // Close on outside click
            document.addEventListener('click', (e) => {
                if (!toggle.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.remove('open');
                    toggle.classList.remove('active');
                }
            });
        }
    }

    // ── Dropdown toggles ──
    function initDropdowns() {
        document.querySelectorAll('.agro-dropdown').forEach(dropdown => {
            const trigger = dropdown.querySelector('[data-dropdown-trigger]') || dropdown.firstElementChild;
            if (!trigger) return;

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                // Close all other dropdowns
                document.querySelectorAll('.agro-dropdown.open').forEach(d => {
                    if (d !== dropdown) d.classList.remove('open');
                });
                dropdown.classList.toggle('open');
            });
        });

        // Close dropdowns on outside click
        document.addEventListener('click', () => {
            document.querySelectorAll('.agro-dropdown.open').forEach(d => {
                d.classList.remove('open');
            });
        });
    }

    // ── Scroll reveal animation ──
    function initScrollReveal() {
        const reveals = document.querySelectorAll('.agro-reveal');
        if (!reveals.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

        reveals.forEach(el => observer.observe(el));
    }

    // ── Smooth scroll for anchor links ──
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                const target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    // ── Toast notification system ──
    window.AgroToast = {
        _container: null,

        _getContainer() {
            if (!this._container) {
                this._container = document.createElement('div');
                this._container.className = 'agro-toast-container';
                document.body.appendChild(this._container);
            }
            return this._container;
        },

        show(message, type = 'success', duration = 4000) {
            const container = this._getContainer();
            const icons = {
                success: '✓',
                error: '✗',
                warning: '⚠',
                info: 'ℹ'
            };

            const toast = document.createElement('div');
            toast.className = `agro-toast agro-toast--${type}`;
            toast.innerHTML = `
                <span style="font-size:1.2em;font-weight:700;">${icons[type] || ''}</span>
                <span class="agro-toast__message">${message}</span>
                <button class="agro-toast__close" onclick="this.parentElement.remove()">&times;</button>
            `;

            container.appendChild(toast);

            // Auto dismiss
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100px)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        },

        success(msg, dur) { this.show(msg, 'success', dur); },
        error(msg, dur)   { this.show(msg, 'error', dur);   },
        warning(msg, dur) { this.show(msg, 'warning', dur); },
        info(msg, dur)    { this.show(msg, 'info', dur);    }
    };

    // ── Password visibility toggle ──
    function initPasswordToggles() {
        document.querySelectorAll('[data-toggle-password]').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.querySelector(btn.dataset.togglePassword);
                if (!input) return;
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                btn.textContent = isPassword ? '🙈' : '👁️';
            });
        });
    }

    // ── Quantity steppers ──
    function initQuantitySteppers() {
        document.querySelectorAll('.agro-qty-stepper').forEach(stepper => {
            const minus = stepper.querySelector('[data-qty-minus]');
            const plus = stepper.querySelector('[data-qty-plus]');
            const input = stepper.querySelector('input');
            if (!minus || !plus || !input) return;

            minus.addEventListener('click', () => {
                const val = parseInt(input.value) || 1;
                if (val > 1) input.value = val - 1;
                input.dispatchEvent(new Event('change'));
            });

            plus.addEventListener('click', () => {
                const val = parseInt(input.value) || 1;
                const max = parseInt(input.max) || 9999;
                if (val < max) input.value = val + 1;
                input.dispatchEvent(new Event('change'));
            });
        });
    }

    // ── Form validation visual feedback ──
    function initFormValidation() {
        document.querySelectorAll('.agro-input[required], .agro-select[required]').forEach(input => {
            input.addEventListener('blur', () => {
                if (!input.value.trim()) {
                    input.classList.add('agro-input--error');
                } else {
                    input.classList.remove('agro-input--error');
                }
            });

            input.addEventListener('input', () => {
                input.classList.remove('agro-input--error');
            });
        });
    }

    // ── Animated counter ──
    window.AgroCounter = {
        animate(element, target, duration = 2000) {
            let start = 0;
            const startTime = performance.now();

            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic

                const current = Math.round(eased * target);
                element.textContent = current.toLocaleString();

                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }

            requestAnimationFrame(update);
        }
    };

    // ── Init all on DOM ready ──
    function init() {
        initNavbar();
        initDropdowns();
        initScrollReveal();
        initSmoothScroll();
        initPasswordToggles();
        initQuantitySteppers();
        initFormValidation();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
