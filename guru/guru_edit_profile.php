<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'guru') {
  header("Location: ../index.html");
  exit();
}

$username = $_SESSION['username'];
$profile_file = __DIR__ . "/profile_data/{$username}.json";

// Pastikan direktori profile_data ada
if (!file_exists(__DIR__ . "/profile_data")) {
  mkdir(__DIR__ . "/profile_data", 0777, true);
}

// Load profile data
$default_profile = [
  "nama_lengkap" => "",
  "nip" => "",
  "tempat_lahir" => "",
  "tanggal_lahir" => "",
  "jenis_kelamin" => "",
  "agama" => "",
  "mata_pelajaran" => "",
  "pendidikan_terakhir" => ""
];
function loadProfile($file) {
  global $default_profile;
  if (file_exists($file)) {
    $data = json_decode(file_get_contents($file), true);
    return array_merge($default_profile, $data);
  } else {
    return $default_profile;
  }
}

$profile = loadProfile($profile_file);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profil Guru - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous"/>
  <style>
    * {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      box-sizing: border-box;
    }
    body {
      background-color: #634338ff;
      min-height: 100vh;
      display: flex;
      flex-direction: row;
      width: 100vw;
      overflow-x: hidden;
    }
    .sidebar {
      width: 70px;
      background: linear-gradient(135deg, #1a2933ff 0%, #405d6fff  100%);
      color: white;
      overflow: hidden;
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
      box-shadow: none;
      position: fixed;
      left: 0;
      top: 0;
      height: 100vh;
      z-index: 1000;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      transition: width 0.4s cubic-bezier(.68,-0.55,.27,1.55), box-shadow 0.4s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .sidebar.open {
      width: 180px;
      box-shadow: none;
    }
    .sidebar .logo-section {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem 0;
      height: 80px;
      opacity: 0;
      visibility: hidden;
    }
    .sidebar.open .logo-section {
      opacity: 1;
      visibility: visible;
    }
    .sidebar .logo-section img {
      width: 120px;
      height: 60px;
      margin-right: 10px;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
      margin-top: 10px;
    }
    .sidebar ul li {
      display: flex;
      align-items: center;
      padding: 14px 18px;
      cursor: pointer;
      border-radius: 18px;
      margin: 8px 8px;
      background: linear-gradient(135deg, #634338ff 0%, #ffb296ff 100%);
      box-shadow: 0 2px 8px 0 rgba(255,179,71,0.04);
      position: relative;
      overflow: hidden;
    }
    .sidebar ul li:hover {
      background:  #e4aa95ff;
      box-shadow: 0 4px 16px 0 rgba(255,94,98,0.12);
    }
    .sidebar ul li .menu-icon {
      font-size: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .sidebar.open ul li .menu-icon {
      margin-right: 15px;
      font-size: 28px;
      padding-left: 0;
    }
    .sidebar span.menu-text {
      display: none;
      font-size: 1rem;
      letter-spacing: 0.5px;
      opacity: 0;
      margin-left: 0;
      color: #ffff;
    }
    .sidebar.open span.menu-text {
      display: inline;
      opacity: 1;
      margin-left: 1px;
      color: #ffffffff;
      font-weight: 600;
    }
    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      background: linear-gradient(135deg, #845747 0%, #cb8e78ff 100%);
      min-height: 100vh;
      margin-left: 70px;
      width: 100vw;
      overflow-x: hidden;
      overflow-y: auto;
    }
    .sidebar.open ~ .main-content {
      margin-left: 180px;
    }
    header {
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 2rem;
      border-bottom-left-radius: 0;
      box-shadow: bottom 2px 8px rgba(0, 0, 0, 1);
      background-color: #845747;
    }
    .hamburger-logo {
      display: flex;
      align-items: center;
    }
    .hamburger {
      font-size: 2.1rem;
      cursor: pointer;
      background: #fff6;
      border: none;
      color: #B83556;
      margin-right: 1rem;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 8px #ffb34733;
      /* Remove default font-size for svg */
      font-size: unset;
      padding: 0;
    }
    .hamburger svg {
      width: 28px;
      height: 28px;
      display: block;
      transition: transform 0.3s;
    }
    .hamburger:hover {
      background: #fff;
      color: #ff5e62;
      box-shadow: 0 4px 16px #ffb34755;
    }
    .profile-menu {
      position: relative;
      display: flex;
      align-items: center;
    }
    .profile-button {
      background: linear-gradient(90deg, #cb8e78ff 0%, #cb8e78ff 100%);
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
      position: relative;
    }
    .profile-button:hover {
      background: linear-gradient(90deg, #DC97A5 0%, #DC97A5 100%);
      color: #fff;
      box-shadow: 0 4px 16px #b8355655;
    }
    .profile-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #fff;
      border: 2px solid #B83556;
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
      background: linear-gradient(135deg, #fff 60%, #DC97A5 100%);
      color: #B83556;
      box-shadow: 0 8px 24px 0 #b8355633;
      margin-top: 8px;
      border-radius: 14px;
      overflow: hidden;
      z-index: 20;
      border: 1.5px solid #DC97A5;
    }
    .dropdown.open {
      display: block;
      opacity: 1;
      pointer-events: auto;
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
    }
    .dropdown a:last-child {
      border-bottom: none;
    }
    .dropdown a:hover {
      background: #DC97A5;
      color: #fff;
    }
    
    /* Profile Card Styles */
    .profile-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }
    
    .profile-card {
      background: #fff;
      color: #222;
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.07);
      width: 100%;
      max-width: 370px;
      padding: 1.1rem 1.2rem 1.2rem 1.2rem;
      margin: 1.5rem auto 0 auto;
      position: relative;
      overflow: hidden;
      border: 1px solid #eee;
      min-width: 0;
      font-size: 1rem;
      transition: box-shadow 0.2s;
    }
    .profile-card:hover {
      box-shadow: 0 4px 18px rgba(0,0,0,0.10);
    }
    .profile-card::before,
    .profile-card:hover::before,
    .profile-decoration,
    .profile-card:hover .profile-avatar-large {
      display: none !important;
    }
    
    .profile-header {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 1.1rem;
      position: relative;
      z-index: 2;
    }
    .profile-avatar-large {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      background: #eee;
      border: 2px solid #bbb;
      object-fit: cover;
      margin-bottom: 0.7rem;
      box-shadow: none;
    }
    .profile-title {
      font-size: 1.2rem;
      font-weight: 700;
      color: #222;
      text-align: center;
      margin-bottom: 0.2rem;
      text-shadow: none;
      background: none;
      -webkit-background-clip: unset;
      -webkit-text-fill-color: unset;
      background-clip: unset;
    }
    .profile-subtitle {
      font-size: 0.75rem;
      font-style: italic;
      color: #444;
      text-align: center;
      margin-bottom: 0.7rem;
      opacity: 0.8;
    }
    
    .profile-body {
      display: flex;
      flex-direction: column;
      gap: 0.6rem;
      position: relative;
      z-index: 2;
    }
    .profile-field {
      display: flex;
      flex-direction: column;
      background: #f7f7f7;
      border-radius: 10px;
      padding: 0.5rem 0.7rem;
      border: 1px solid #eee;
      margin-bottom: 0.1rem;
      transition: background 0.2s;
    }
    .profile-field:hover {
      background: #ededed;
    }
    .field-label {
      font-size: 0.85rem;
      font-weight: 600;
      color: #222;
      margin-bottom: 0.2rem;
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }
    .field-label svg {
      width: 15px;
      height: 15px;
    }
    .field-value {
      font-size: 1rem;
      color: #222;
      font-weight: 500;
      min-height: 1.2rem;
    }
    .field-input {
      font-size: 1rem;
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 0.3rem 0.5rem;
      color: #222;
      width: 100%;
      display: none;
    }
    .field-input:focus {
      outline: none;
      background: #f3f3f3;
      border: 1px solid #b83556;
    }
    
    .profile-actions {
      display: flex;
      justify-content: center;
      gap: 0.7rem;
      margin-top: 1.1rem;
    }
    .profile-btn {
      background: linear-gradient(135deg, #1a2933ff 0%, #405d6fff  100%);
      color: #ffff;
      border: 1px solid #bbb;
      border-radius: 20px;
      padding: 0.5rem 1.2rem;
      font-size: 0.98rem;
      font-weight: 600;
      cursor: pointer;
      box-shadow: none;
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }
    .profile-btn:hover {
      background: linear-gradient(135deg, #1a2933ff 0%, #141e25ff  100%);
      color: #ffffffff;
    }
    .profile-btn svg {
      width: 16px;
      height: 16px;
    }
    .save-btn {
      display: none;
    }
    
    /* Responsive Styles */
    @media screen and (max-width: 900px) {
      .sidebar {
        width: 54px;
        border-radius: 0 30px 30px 0;
      }
      .sidebar.open {
        width: 120px;
      }
      .main-content {
        margin-left: 54px;
      }
      .sidebar.open ~ .main-content {
        margin-left: 120px;
      }
      header {
        padding: 1rem 1rem 1rem 1.5rem;
      }
      .profile-container {
        padding: 1.1rem 0.7rem;
      }
      .profile-card {
        padding: 1rem 0.7rem;
        border-radius: 13px;
      }
    }
    @media screen and (max-width: 600px) {
      .profile-container {
        padding: 0.7rem 0.3rem;
      }
      .profile-card {
        padding: 0.7rem 0.3rem;
        border-radius: 10px;
      }
      .profile-avatar-large {
        width: 50px;
        height: 50px;
      }
      .profile-title {
        font-size: 1rem;
      }
      .profile-subtitle {
        font-size: 0.8rem;
      }
      .profile-field {
        padding: 0.4rem 0.5rem;
      }
      .field-label {
        font-size: 0.7rem;
      }
      .field-value {
        font-size: 0.9rem;
      }
      .profile-btn {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
      }
    }
    @media screen and (max-width: 400px) {
      .profile-avatar-large {
        width: 36px;
        height: 36px;
      }
      .profile-title {
        font-size: 0.8rem;
      }
      .profile-subtitle {
        font-size: 0.7rem;
      }
      .profile-actions {
        flex-direction: column;
        gap: 0.5rem;
      }
      .profile-btn {
        width: 100%;
        justify-content: center;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
    <div class="logo-section">
      <img src="../img/cuan.png" alt="Logo CUAN">
    </div>
    <ul>
      <li onclick="location.href='guru_dashboard.php'">
        <span class="menu-icon"><img src="../img/home.png" alt="Beranda" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Beranda</span>
      </li>
      <li onclick="location.href='guru_jadwal.php'">
        <span class="menu-icon"><img src="../img/calendar.png" alt="Jadwal" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Jadwal</span>
      </li>
      <li onclick="location.href='guru_datasiswa.php'">
        <span class="menu-icon"><img src="../img/siswa.png" alt="Data Siswa" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Data Siswa</span>
      </li>
      <li onclick="location.href='guru_orangtuasiswa.php'">
        <span class="menu-icon"><img src="../img/ortu.png" alt="Orang Tua Siswa" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Orang Tua Siswa</span>
      </li>
      <li onclick="location.href='guru_aktivitasterbaru.php'">
        <span class="menu-icon"><img src="../img/aktivitas.png" alt="Aktivitas Terbaru" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Aktivitas Terbaru</span>
      </li>
      <li onclick="location.href='guru_siswaberprestasi.php'">
        <span class="menu-icon"><img src="../img/piala.png" alt="Siswa Berprestasi" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Siswa Berprestasi</span>
      </li>
    </ul>
  </div>

  <div class="main-content">
    <header>
      <div class="hamburger-logo">
        <button class="hamburger" id="sidebarToggleBtn" onclick="toggleSidebar()">
          <svg id="sidebarArrow" viewBox="0 0 24 24" fill="none"><path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>
      <div class="profile-menu">
        <button class="profile-button" onclick="toggleDropdown()">
          <img src="../img/profile.png" alt="Avatar" class="profile-avatar" />
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left:2px;"><path d="M5 8L10 13L15 8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="dropdown" id="dropdown">
          <a href="guru_edit_profile.php"><svg width="18" height="18" fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>Edit Profil</a>
          <a href="../logout.php"><svg width="18" height="18" fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>Logout</a>
        </div>
      </div>
    </header>

    <div class="profile-container">
      <div class="profile-card">
        <div class="profile-header">
          <div style="display:flex;justify-content:center;align-items:center;margin-bottom:0.3rem;">
            <img src="../img/profile.png" alt="Profile" style="width:80px;height:80px;border-radius:50%;background:#eee;border:1.5px solid #bbb;object-fit:cover;box-shadow:none;" />
          </div>
          <h1 class="profile-title">Profil Guru</h1>
        </div>
        <div class="profile-body">
          <div class="profile-field">
            <div class="field-label">Nama Lengkap</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['nama_lengkap']); ?></div>
            <input type="text" class="field-input" id="nama_lengkap" value="<?php echo htmlspecialchars($profile['nama_lengkap']); ?>">
          </div>
          <div class="profile-field">
            <div class="field-label">NIP</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['nip']); ?></div>
            <input type="text" class="field-input" id="nip" value="<?php echo htmlspecialchars($profile['nip']); ?>">
          </div>
          <div class="profile-field">
            <div class="field-label">Tempat Lahir</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['tempat_lahir']); ?></div>
            <input type="text" class="field-input" id="tempat_lahir" value="<?php echo htmlspecialchars($profile['tempat_lahir']); ?>">
          </div>
          <div class="profile-field">
            <div class="field-label">Tanggal Lahir</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['tanggal_lahir']); ?></div>
            <input type="date" class="field-input" id="tanggal_lahir" value="<?php echo htmlspecialchars($profile['tanggal_lahir']); ?>">
          </div>
          <div class="profile-field">
            <div class="field-label">Jenis Kelamin</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['jenis_kelamin']); ?></div>
            <select class="field-input" id="jenis_kelamin">
              <option value="" <?php echo $profile['jenis_kelamin'] == '' ? 'selected' : ''; ?>>Pilih Jenis Kelamin</option>
              <option value="Laki-laki" <?php echo $profile['jenis_kelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
              <option value="Perempuan" <?php echo $profile['jenis_kelamin'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
            </select>
          </div>
          <div class="profile-field">
            <div class="field-label">Agama</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['agama']); ?></div>
            <select class="field-input" id="agama">
              <option value="" <?php echo $profile['agama'] == '' ? 'selected' : ''; ?>>Pilih Agama</option>
              <option value="Islam" <?php echo $profile['agama'] == 'Islam' ? 'selected' : ''; ?>>Islam</option>
              <option value="Kristen" <?php echo $profile['agama'] == 'Kristen' ? 'selected' : ''; ?>>Kristen</option>
              <option value="Katolik" <?php echo $profile['agama'] == 'Katolik' ? 'selected' : ''; ?>>Katolik</option>
              <option value="Hindu" <?php echo $profile['agama'] == 'Hindu' ? 'selected' : ''; ?>>Hindu</option>
              <option value="Buddha" <?php echo $profile['agama'] == 'Buddha' ? 'selected' : ''; ?>>Buddha</option>
              <option value="Konghucu" <?php echo $profile['agama'] == 'Konghucu' ? 'selected' : ''; ?>>Konghucu</option>
            </select>
          </div>
          <div class="profile-field">
            <div class="field-label">Mata Pelajaran</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['mata_pelajaran']); ?></div>
            <input type="text" class="field-input" id="mata_pelajaran" value="<?php echo htmlspecialchars($profile['mata_pelajaran']); ?>">
          </div>
          <div class="profile-field">
            <div class="field-label">Pendidikan Terakhir</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['pendidikan_terakhir']); ?></div>
            <input type="text" class="field-input" id="pendidikan_terakhir" value="<?php echo htmlspecialchars($profile['pendidikan_terakhir']); ?>">
          </div>
        </div>
        <div class="profile-actions">
          <button class="profile-btn edit-btn" onclick="editProfile()">Edit Profil</button>
          <button class="profile-btn save-btn" onclick="saveProfile()">Simpan</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Copy all JS dari siswa_edit_profile.php, ganti endpoint fetch ke 'profile_api.php' di folder guru
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('open');
      updateSidebarArrow();
    }
    function updateSidebarArrow() {
      const sidebar = document.getElementById('sidebar');
      const arrow = document.getElementById('sidebarArrow');
      if (sidebar.classList.contains('open')) {
        arrow.innerHTML = '<path d="M16 5l-8 7 8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      } else {
        arrow.innerHTML = '<path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      }
    }
    document.addEventListener('DOMContentLoaded', updateSidebarArrow);
    function toggleDropdown() {
      const dropdown = document.getElementById("dropdown");
      dropdown.classList.toggle("open");
    }
    document.addEventListener('click', function(e) {
      const dropdown = document.getElementById("dropdown");
      const profileBtn = document.querySelector('.profile-button');
      if (!profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove("open");
      }
    });
    function editProfile() {
      document.querySelectorAll('.field-value').forEach(el => {
        el.style.display = 'none';
      });
      document.querySelectorAll('.field-input').forEach(el => {
        el.style.display = 'block';
      });
      document.querySelector('.edit-btn').style.display = 'none';
      document.querySelector('.save-btn').style.display = 'flex';
      document.querySelector('.profile-card').style.animation = 'pulse 2s infinite';
    }
    function saveProfile() {
      const nama_lengkap = document.getElementById('nama_lengkap').value;
      const nip = document.getElementById('nip').value;
      const tempat_lahir = document.getElementById('tempat_lahir').value;
      const tanggal_lahir = document.getElementById('tanggal_lahir').value;
      const jenis_kelamin = document.getElementById('jenis_kelamin').value;
      const agama = document.getElementById('agama').value;
      const mata_pelajaran = document.getElementById('mata_pelajaran').value;
      const pendidikan_terakhir = document.getElementById('pendidikan_terakhir').value;
      fetch('profile_api.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          nama_lengkap,
          nip,
          tempat_lahir,
          tanggal_lahir,
          jenis_kelamin,
          agama,
          mata_pelajaran,
          pendidikan_terakhir
        }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          document.querySelectorAll('.field-value').forEach((el, index) => {
            const inputId = el.nextElementSibling.id;
            el.textContent = document.getElementById(inputId).value;
          });
          document.querySelectorAll('.field-value').forEach(el => {
            el.style.display = 'block';
          });
          document.querySelectorAll('.field-input').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelector('.edit-btn').style.display = 'flex';
          document.querySelector('.save-btn').style.display = 'none';
          showNotification('Profil berhasil disimpan!', 'success');
        } else {
          showNotification('Gagal menyimpan profil. Silakan coba lagi.', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
      });
    }
    function showNotification(message, type) {
      const notification = document.createElement('div');
      notification.className = `notification ${type}`;
      notification.innerHTML = `
        <div class="notification-content">
          <svg class="${type}-icon" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            ${type === 'success' ? '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>' : '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12" y2="16"/>'}
          </svg>
          <span>${message}</span>
        </div>
      `;
      notification.style.position = 'fixed';
      notification.style.bottom = '20px';
      notification.style.right = '20px';
      notification.style.padding = '12px 20px';
      notification.style.borderRadius = '8px';
      notification.style.backgroundColor = type === 'success' ? 'rgba(46, 213, 115, 0.9)' : 'rgba(255, 71, 87, 0.9)';
      notification.style.color = '#fff';
      notification.style.boxShadow = '0 4px 16px rgba(0,0,0,0.2)';
      notification.style.zIndex = '1000';
      notification.style.opacity = '0';
      const content = notification.querySelector('.notification-content');
      content.style.display = 'flex';
      content.style.alignItems = 'center';
      content.style.gap = '10px';
      const icon = notification.querySelector('svg');
      icon.style.width = '20px';
      icon.style.height = '20px';
      document.body.appendChild(notification);
      setTimeout(() => {
        notification.style.opacity = '1';
      }, 10);
      setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
          document.body.removeChild(notification);
        }, 300);
      }, 3000);
    }
  </script>
</body>
</html>
