(() => {
  'use strict';
  const button = document.querySelector('.tfd-menu-toggle');
  const nav = document.getElementById('tfd-nav');
  if (button && nav) {
    const close = () => { button.setAttribute('aria-expanded','false'); nav.classList.remove('is-open'); };
    button.addEventListener('click', () => {
      const open = button.getAttribute('aria-expanded') !== 'true';
      button.setAttribute('aria-expanded',String(open));
      nav.classList.toggle('is-open',open);
    });
    document.addEventListener('keydown', event => { if(event.key === 'Escape') { close(); button.focus(); } });
    document.addEventListener('click', event => { if(!nav.contains(event.target) && !button.contains(event.target)) close(); });
    nav.querySelectorAll('a').forEach(link => link.addEventListener('click',close));
    window.matchMedia('(min-width: 1101px)').addEventListener('change',close);
  }
  document.querySelectorAll('input[name="preferred-date"]').forEach(input => {
    const now = new Date();
    input.min = [now.getFullYear(),String(now.getMonth()+1).padStart(2,'0'),String(now.getDate()).padStart(2,'0')].join('-');
  });
})();
