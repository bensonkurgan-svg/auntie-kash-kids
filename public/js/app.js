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

  // Mobile app-style menu
  const menuBtn = document.querySelector('[data-menu-toggle]');
  const mobileMenu = document.querySelector('[data-mobile-menu]');
  const overlay = document.querySelector('[data-mobile-overlay]');
  const closeBtn = document.querySelector('[data-menu-close]');
  const openMenu = () => {
    mobileMenu && mobileMenu.classList.add('open');
    overlay && overlay.classList.add('open');
    menuBtn && menuBtn.classList.add('active');
    document.body.classList.add('menu-open');
    document.body.style.overflow = 'hidden';
  };
  const closeMenu = () => {
    mobileMenu && mobileMenu.classList.remove('open');
    overlay && overlay.classList.remove('open');
    menuBtn && menuBtn.classList.remove('active');
    document.body.classList.remove('menu-open');
    document.body.style.overflow = '';
  };
  if (menuBtn) {
    menuBtn.addEventListener('click', () => {
      mobileMenu.classList.contains('open') ? closeMenu() : openMenu();
    });
  }
  overlay && overlay.addEventListener('click', closeMenu);
  closeBtn && closeBtn.addEventListener('click', closeMenu);

  // Accordion submenus (Programs, Parents)
  document.querySelectorAll('[data-accordion]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const submenu = btn.nextElementSibling;
      btn.classList.toggle('open');
      submenu && submenu.classList.toggle('open');
    });
  });

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
