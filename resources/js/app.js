import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';

Alpine.plugin(intersect);
window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const isFinePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!isFinePointer || prefersReducedMotion) {
        document.querySelectorAll('.reveal-section').forEach((el) => el.classList.add('is-visible'));
        return;
    }

    // Light pointer glow + subtle tilt on desktop cards
    document.querySelectorAll('.pointer-glow').forEach((el) => {
        el.addEventListener('pointermove', (event) => {
            const rect = el.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;
            el.style.setProperty('--mx', `${x}px`);
            el.style.setProperty('--my', `${y}px`);

            if (el.classList.contains('tilt-card')) {
                const rx = ((y / rect.height) - 0.5) * -3;
                const ry = ((x / rect.width) - 0.5) * 3;
                el.style.setProperty('--rx', `${rx.toFixed(2)}deg`);
                el.style.setProperty('--ry', `${ry.toFixed(2)}deg`);
            }
        });

        el.addEventListener('pointerleave', () => {
            el.style.setProperty('--rx', '0deg');
            el.style.setProperty('--ry', '0deg');
        });
    });

    // IntersectionObserver reveal
    const revealEls = document.querySelectorAll('.reveal-section');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.08, rootMargin: '0px 0px -24px 0px' }
        );
        revealEls.forEach((el) => observer.observe(el));
    } else {
        revealEls.forEach((el) => el.classList.add('is-visible'));
    }
});
