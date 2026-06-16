// Auntie Kash Kids — interactivity
document.addEventListener('DOMContentLoaded', () => {
  // Scroll progress bar
  const bar = document.querySelector('.scroll-progress');
  if (bar) {
    window.addEventListener('scroll', () => {
      const h = document.documentElement;
      const scrolled = (h.scrollTop) / (h.scrollHeight - h.clientHeight) * 100;
      bar.style.width = scrolled + '%';
    });
  }

  // Mobile menu toggle
  const menuBtn = document.querySelector('[data-menu-toggle]');
  const mobileMenu = document.querySelector('[data-mobile-menu]');
  if (menuBtn && mobileMenu) {
    menuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
  }

  // Staggered reveal on scroll
  const io = new IntersectionObserver((entries) => {
    entries.forEach((e, i) => {
      if (e.isIntersecting) { e.target.classList.add('fade-in'); io.unobserve(e.target); }
    });
  }, { threshold: 0.1 });
  document.querySelectorAll('[data-reveal]').forEach(el => io.observe(el));

  // Counter animation
  document.querySelectorAll('[data-counter]').forEach(el => {
    const target = parseInt(el.dataset.counter);
    const cio = new IntersectionObserver((entries) => {
      if (entries[0].isIntersecting) {
        let cur = 0;
        const step = Math.ceil(target / 40);
        const tick = () => { cur += step; if (cur >= target) { el.textContent = target + (el.dataset.suffix||''); } else { el.textContent = cur + (el.dataset.suffix||''); requestAnimationFrame(tick); } };
        tick(); cio.unobserve(el);
      }
    }, { threshold: 0.5 });
    cio.observe(el);
  });
});
