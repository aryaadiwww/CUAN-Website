// Toggle show/hide password (SVG eye outline)
function togglePassword(fieldId) {
  var input = document.getElementById(fieldId);
  var btn = input.nextElementSibling;
  var icon = btn.querySelector('.eye-icon');
  if (input.type === 'password') {
    input.type = 'text';
    // Eye-off SVG
    icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7z"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
  } else {
    input.type = 'password';
    // Eye outline SVG
    icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7z"/></svg>';
  }
}
// Landing page entrance animation
window.addEventListener('DOMContentLoaded', function() {
  setTimeout(function() {
    document.querySelectorAll('.fadein-up').forEach(function(el, i) {
      setTimeout(function() {
        el.classList.add('visible');
      }, i * 120);
    });
  }, 120);
});
// Rain Animation
function createRain() {
  const rainContainer = document.querySelector('.rain');
  if (!rainContainer) return;
  rainContainer.innerHTML = '';
  const drops = 60;
  for (let i = 0; i < drops; i++) {
    const drop = document.createElement('div');
    drop.className = 'raindrop';
    const left = Math.random() * 100;
    const delay = Math.random() * 2;
    const duration = 1.2 + Math.random() * 0.8;
    drop.style.left = left + 'vw';
    drop.style.top = (Math.random() * 10 - 10) + 'vh';
    drop.style.animationDuration = duration + 's';
    drop.style.animationDelay = delay + 's';
    drop.style.opacity = 0.3 + Math.random() * 0.5;
    rainContainer.appendChild(drop);
  }
}

window.addEventListener('DOMContentLoaded', createRain);
window.addEventListener('resize', createRain);
document.querySelector('.btn.siswa').addEventListener('click', function () {
  document.getElementById('popup-siswa').style.display = 'block';
});
document.querySelector('.btn.guru').addEventListener('click', function () {
  document.getElementById('popup-guru').style.display = 'block';
});
document.querySelector('.btn.ortu').addEventListener('click', function () {
  document.getElementById('popup-ortu').style.display = 'block';
});

function closePopup(id) {
  document.getElementById(id).style.display = 'none';
}
