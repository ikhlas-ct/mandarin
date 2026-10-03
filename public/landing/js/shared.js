/* shared.js – TitikKebaikan */

document.addEventListener('DOMContentLoaded', function () {

  // ── 1. Fade-up on scroll (Intersection Observer)
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.style.animationPlayState = 'running';
        observer.unobserve(e.target);
      }
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.fade-up').forEach(el => {
    el.style.animationPlayState = 'paused';
    observer.observe(el);
  });

  // ── 2. Heart / bookmark toggle
  document.querySelectorAll('.btn-heart').forEach(btn => {
    btn.addEventListener('click', function () {
      const icon = this.querySelector('i');
      if (!icon) return;
      if (icon.classList.contains('bi-heart')) {
        icon.classList.replace('bi-heart', 'bi-heart-fill');
        pulse(this);
      } else if (icon.classList.contains('bi-heart-fill')) {
        icon.classList.replace('bi-heart-fill', 'bi-heart');
      } else if (icon.classList.contains('bi-bookmark')) {
        icon.classList.replace('bi-bookmark', 'bi-bookmark-fill');
        pulse(this);
      } else if (icon.classList.contains('bi-bookmark-fill')) {
        icon.classList.replace('bi-bookmark-fill', 'bi-bookmark');
      }
    });
  });

  function pulse(el) {
    el.style.transform = 'scale(1.3)';
    setTimeout(() => { el.style.transform = ''; }, 200);
  }

  // ── 3. Smooth navbar shadow on scroll
  const navbar = document.querySelector('.navbar');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
      navbar.style.boxShadow = '0 4px 24px rgba(0,0,0,0.08)';
    } else {
      navbar.style.boxShadow = 'none';
    }
  });

  // ── 4. Active nav link highlight based on current page
  const page = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
    const href = link.getAttribute('href');
    if (href === page) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });

});
