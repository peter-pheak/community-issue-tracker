// IntersectionObserver for counters and reveal animations
document.addEventListener('DOMContentLoaded', function() {
  // Scroll reveal
  const reveals = document.querySelectorAll('.reveal');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, { threshold: 0.1 });
  reveals.forEach(el => observer.observe(el));

  // Counter animation logic (to be expanded)
  const counters = document.querySelectorAll('.counter');
  // ...
});
