// Music player logic for persistent background music across all pages
(function(){
  // Only run once per browser tab
  if (window.__CUAN_MUSIC_PLAYER__) return;
  window.__CUAN_MUSIC_PLAYER__ = true;

  // Create audio element
  var audio = document.createElement('audio');
  audio.src = '../guruku.mp3';
  audio.id = 'cuan-music-audio';
  audio.loop = true;
  audio.preload = 'auto';
  audio.style.display = 'none';
  document.body.appendChild(audio);

  // Try to restore playback position if available
  var lastTime = sessionStorage.getItem('cuan-music-time');
  if (lastTime) {
    audio.currentTime = parseFloat(lastTime);
  }

  // Play if previously playing
  var wasPlaying = sessionStorage.getItem('cuan-music-playing');
  if (wasPlaying === 'true') {
    setTimeout(function(){ audio.play(); }, 300);
  }

  // Save playback position on navigation
  window.addEventListener('beforeunload', function() {
    sessionStorage.setItem('cuan-music-time', audio.currentTime);
    sessionStorage.setItem('cuan-music-playing', !audio.paused);
  });

  // Create button
  var btn = document.createElement('button');
  btn.id = 'cuan-music-btn';
  btn.title = 'Putar Musik';
  btn.style.position = 'fixed';
  btn.style.left = '14px';
  btn.style.bottom = '24px';
  btn.style.zIndex = '9999';
  btn.style.background = 'linear-gradient(135deg,#dc97a586  100%,#dc97a586 100%)';
  btn.style.color = '#fff';
  btn.style.border = 'none';
  btn.style.borderRadius = '50%';
  btn.style.width = '40px';
  btn.style.height = '40px';
  btn.style.boxShadow = '0 2px 12px #b8355633';
  btn.style.display = 'flex';
  btn.style.alignItems = 'center';
  btn.style.justifyContent = 'center';
  btn.style.fontSize = '2rem';
  btn.style.cursor = 'pointer';
  btn.style.outline = 'none';
  btn.style.transition = 'background 0.2s,transform 0.2s';


  // Play and Pause icons (white)
  var playIcon = '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="6,4 20,12 6,20 6,4" fill="#fff"/></svg>';
  var pauseIcon = '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16" fill="#fff"/><rect x="14" y="4" width="4" height="16" fill="#fff"/></svg>';
  function updateBtnIcon() {
    if (!audio.paused) {
      btn.innerHTML = pauseIcon;
    } else {
      btn.innerHTML = playIcon;
    }
  }
  updateBtnIcon();


  btn.addEventListener('mouseenter', function(){
    btn.style.background = 'linear-gradient(135deg,#dc97a586  100%,#dc97a586  100%)';
    btn.style.transform = 'scale(1.08)';
  });
  btn.addEventListener('mouseleave', function(){
    btn.style.background = 'linear-gradient(135deg,#dc97a586  100%,#dc97a586  100%)';
    btn.style.transform = 'scale(1)';
  });



  btn.addEventListener('click', function(){
    if (audio.paused) {
      audio.play();
      sessionStorage.setItem('cuan-music-playing', 'true');
    } else {
      audio.pause();
      sessionStorage.setItem('cuan-music-playing', 'false');
    }
    updateBtnIcon();
  });

  // Update icon on play/pause events
  audio.addEventListener('play', updateBtnIcon);
  audio.addEventListener('pause', updateBtnIcon);



  document.body.appendChild(btn);
})();
