// Buka popup login
function openPopup(popupId) {
  document.getElementById(popupId).style.display = 'block';
}

// Tutup popup login atau register
function closePopup(popupId) {
  document.getElementById(popupId).style.display = 'none';
}

// Menutup popup saat klik di luar area popup
window.onclick = function(event) {
  const modals = document.querySelectorAll('.modal, .popup');
  modals.forEach(modal => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
}
