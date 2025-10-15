<!-- Navbar/Header Orang Tua CUAN -->
<style>
header {
  color: #fff;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 2rem;
  border-bottom-left-radius: 0;
  box-shadow: 0 2px 8px rgba(80, 40, 20, 0.13);
  transition: background 0.4s;
   background: linear-gradient(135deg, #4d3528ff  0%, #4d3528a2  100%);
  height: 60px;
  z-index: 100;
}

.profile-menu {
  position: relative;
  display: flex;
  align-items: center;
}
.profile-button {
  background: linear-gradient(90deg, #a97c50 0%, #7c4a1e 100%);
  border: none;
  color: #fff;
  cursor: pointer;
  font-weight: bold;
  border-radius: 50px;
  padding: 5px 16px 5px 10px;
  display: flex;
  align-items: center;
  box-shadow: 0 2px 8px #b8355633;
  font-size: 1rem;
  transition: background 0.3s, color 0.3s, box-shadow 0.3s, transform 0.2s;
  position: relative;
}
.profile-button:hover {
  background: linear-gradient(90deg, #7c4a1e 0%, #a97c50 100%);
  color: #fff;
  box-shadow: 0 4px 16px #b8355655;
  transform: scale(1.06) rotate(-2deg);
}
.profile-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: #fff;
  border: 2px solid #a97c50;
  object-fit: cover;
  box-shadow: 0 2px 8px #b8355633;
  margin-right: 4px;
}
.dropdown {
  display: none;
  opacity: 0;
  pointer-events: none;
  position: absolute;
  right: 0;
  top: 110%;
  min-width: 160px;
  background: linear-gradient(135deg, #fff 60%, #b07b4a 100%);
  color: #7c4a1e;
  box-shadow: 0 8px 24px 0 #b8355633;
  margin-top: 8px;
  border-radius: 14px;
  overflow: hidden;
  z-index: 20;
  border: 1.5px solid #b07b4a;
  transform: translateY(-10px) scale(0.98);
  transition: opacity 0.25s, transform 0.25s;
}
.dropdown.open {
  display: block;
  opacity: 1;
  pointer-events: auto;
  transform: none;
  animation: dropdownFade 0.3s cubic-bezier(.68,-0.55,.27,1.55);
}
@keyframes dropdownFade {
  0% { opacity: 0; transform: translateY(-10px) scale(0.98); }
  100% { opacity: 1; transform: none; }
}
.dropdown a {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 18px;
  text-decoration: none;
  color: #B83556;
  font-weight: 600;
  font-size: 1rem;
  border-bottom: 1px solid #f3e6e6;
  transition: background 0.2s, color 0.2s;
}
  .dropdown a:hover {
    background: #a97c50;
    color: #fff;
}
@media screen and (max-width: 900px) {
  header {
    padding: 1rem 1rem 1rem 1.5rem;
    height: 70px;
    background: linear-gradient(135deg, #7c4a1e 0%, #b07b4a 100%);
  }
}
@media screen and (max-width: 600px) {
}

html, body {
  min-height: 100vh;
  height: 100%;
  background: linear-gradient(135deg, #835c3aff 0%, #aa7e55ff 100%);
}
.main-content, .ortu-main-content {
  min-height: 100vh;
  background: transparent;
}
</style>
<header>
  <div style="flex:1 1 auto;"></div>
  <div class="profile-menu">
    <button class="profile-button" onclick="toggleDropdown()">
      <img src="../img/profile.png" alt="Avatar" class="profile-avatar" />
      <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left:2px;"><path d="M5 8L10 13L15 8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
    <div class="dropdown" id="dropdown">
      <a href="ortu_edit_profile.php"><svg width="18" height="18" fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>Edit Profil</a>
      <a href="../logout.php"><svg width="18" height="18" fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>Logout</a>
    </div>
  </div>
</header>
<script>
function toggleDropdown() {
  const dropdown = document.getElementById("dropdown");
  dropdown.classList.toggle("open");
}
document.addEventListener('click', function(e) {
  const dropdown = document.getElementById("dropdown");
  const profileBtn = document.querySelector('.profile-button');
  if (dropdown && profileBtn && !profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
    dropdown.classList.remove("open");
  }
});
function toggleSidebar(){}
</script>
