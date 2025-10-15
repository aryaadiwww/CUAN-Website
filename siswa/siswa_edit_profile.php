<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'siswa') {
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
function loadProfile($file) {
  if (file_exists($file)) {
    return json_decode(file_get_contents($file), true);
  } else {
    // Default profile setup saat pertama kali
    return [
      "nama_lengkap" => "",
      "tempat_lahir" => "",
      "tanggal_lahir" => "",
      "jenis_kelamin" => "",
      "agama" => "",
      "hobi" => "",
      "cita_cita" => ""
    ];
  }
}

$profile = loadProfile($profile_file);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profil - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="../responsive.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer"/>
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
      <li onclick="location.href='siswa_dashboard.php'">
        <span class="menu-icon"><img src="../img/home.png" alt="Beranda" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Beranda</span>
      </li>
      <li onclick="location.href='siswa_matapelajaran.php'">
        <span class="menu-icon"><img src="../img/book.png" alt="Mata Pelajaran" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Mata Pelajaran</span>
      </li>
      <li onclick="location.href='siswa_jadwal.php'">
        <span class="menu-icon"><img src="../img/calendar.png" alt="Jadwal" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Jadwal</span>
      </li>
      <li onclick="location.href='siswa_games.php'">
        <span class="menu-icon"><img src="../img/games.png" alt="Games" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Games</span>
      </li>
    </ul>
  </div>

  <div class="main-content">
    <header>
      <div class="hamburger-logo">
        <button class="hamburger" id="sidebarToggleBtn" onclick="toggleSidebar()">
          <!-- Panah kanan default, akan diganti JS -->
          <svg id="sidebarArrow" viewBox="0 0 24 24" fill="none"><path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>
      <div class="profile-menu">
        <button class="profile-button" onclick="toggleDropdown()">
          <img src="../img/profile.png" alt="Avatar" class="profile-avatar" />
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left:2px;"><path d="M5 8L10 13L15 8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="dropdown" id="dropdown">
          <a href="siswa_edit_profile.php"><svg width="18" height="18" fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>Edit Profil</a>
          <a href="../logout.php"><svg width="18" height="18" fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>Logout</a>
        </div>
      </div>
    </header>

    <div class="profile-container">
      <div class="profile-card">
        <div class="profile-decoration decoration-1"></div>
        <div class="profile-decoration decoration-2"></div>
        
        <div class="profile-header">
          <div style="display:flex;justify-content:center;align-items:center;margin-bottom:0.3rem;">
            <img src="../img/profile.png" alt="Profile" style="width:80px;height:80px;border-radius:50%;background:#eee;border:1.5px solid #bbb;object-fit:cover;box-shadow:none;" />
          </div>
          <h1 class="profile-title">Profil Siswa</h1>
        </div>
        
        <div class="profile-body">
          <div class="profile-field">
            <div class="field-label">
              <svg fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              Nama Lengkap
            </div>
            <div class="field-value"><?php echo isset($profile['nama_lengkap']) ? htmlspecialchars($profile['nama_lengkap']) : ''; ?></div>
            <input type="text" class="field-input" id="nama_lengkap" value="<?php echo isset($profile['nama_lengkap']) ? htmlspecialchars($profile['nama_lengkap']) : ''; ?>">
          </div>
          
          <div class="profile-field">
            <div class="field-label">
              <svg fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              Tempat Lahir
            </div>
            <div class="field-value"><?php echo isset($profile['tempat_lahir']) ? htmlspecialchars($profile['tempat_lahir']) : ''; ?></div>
            <input type="text" class="field-input" id="tempat_lahir" value="<?php echo isset($profile['tempat_lahir']) ? htmlspecialchars($profile['tempat_lahir']) : ''; ?>">
          </div>
          
          <div class="profile-field">
            <div class="field-label">
              <svg fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              Tanggal Lahir
            </div>
            <div class="field-value">
              <?php
                // Tampilkan tanggal lahir hanya jika format valid
                $tgl = isset($profile['tanggal_lahir']) ? $profile['tanggal_lahir'] : '';
                echo preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl) ? htmlspecialchars($tgl) : '';
              ?>
            </div>
            <input type="date" class="field-input" id="tanggal_lahir" value="<?php echo (isset($profile['tanggal_lahir']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $profile['tanggal_lahir']) ? htmlspecialchars($profile['tanggal_lahir']) : ''); ?>">
          </div>
          
          <div class="profile-field">
            <div class="field-label">
              <svg fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path d="M20 8v6"/><path d="M23 11h-6"/></svg>
              Jenis Kelamin
            </div>
            <div class="field-value"><?php echo isset($profile['jenis_kelamin']) ? htmlspecialchars($profile['jenis_kelamin']) : ''; ?></div>
            <select class="field-input" id="jenis_kelamin">
              <option value="" <?php echo (isset($profile['jenis_kelamin']) && $profile['jenis_kelamin'] == '') ? 'selected' : ''; ?>>Pilih Jenis Kelamin</option>
              <option value="Laki-laki" <?php echo (isset($profile['jenis_kelamin']) && $profile['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
              <option value="Perempuan" <?php echo (isset($profile['jenis_kelamin']) && $profile['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
            </select>
          </div>
          
          <div class="profile-field">
            <div class="field-label">
              <svg fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              Agama
            </div>
            <div class="field-value"><?php echo isset($profile['agama']) ? htmlspecialchars($profile['agama']) : ''; ?></div>
            <select class="field-input" id="agama">
              <option value="" <?php echo (isset($profile['agama']) && $profile['agama'] == '') ? 'selected' : ''; ?>>Pilih Agama</option>
              <option value="Islam" <?php echo (isset($profile['agama']) && $profile['agama'] == 'Islam') ? 'selected' : ''; ?>>Islam</option>
              <option value="Kristen" <?php echo (isset($profile['agama']) && $profile['agama'] == 'Kristen') ? 'selected' : ''; ?>>Kristen</option>
              <option value="Katolik" <?php echo (isset($profile['agama']) && $profile['agama'] == 'Katolik') ? 'selected' : ''; ?>>Katolik</option>
              <option value="Hindu" <?php echo (isset($profile['agama']) && $profile['agama'] == 'Hindu') ? 'selected' : ''; ?>>Hindu</option>
              <option value="Buddha" <?php echo (isset($profile['agama']) && $profile['agama'] == 'Buddha') ? 'selected' : ''; ?>>Buddha</option>
              <option value="Konghucu" <?php echo (isset($profile['agama']) && $profile['agama'] == 'Konghucu') ? 'selected' : ''; ?>>Konghucu</option>
            </select>
          </div>
          
          <div class="profile-field">
            <div class="field-label">
              <svg fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14.5 3.5a2.5 2.5 0 0 0-5 0v8.5l-4.5 4.5a2.121 2.121 0 0 0 3 3L12 16l4 4a2.121 2.121 0 0 0 3-3l-4.5-4.5V3.5z"/></svg>
              Hobi
            </div>
            <div class="field-value"><?php echo isset($profile['hobi']) ? htmlspecialchars($profile['hobi']) : ''; ?></div>
            <input type="text" class="field-input" id="hobi" value="<?php echo isset($profile['hobi']) ? htmlspecialchars($profile['hobi']) : ''; ?>">
          </div>
          
          <div class="profile-field">
            <div class="field-label">
              <svg fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              Cita-cita
            </div>
            <div class="field-value"><?php echo isset($profile['cita_cita']) ? htmlspecialchars($profile['cita_cita']) : ''; ?></div>
            <input type="text" class="field-input" id="cita_cita" value="<?php echo isset($profile['cita_cita']) ? htmlspecialchars($profile['cita_cita']) : ''; ?>">
          </div>
        </div>
        
        <div class="profile-actions">
          <button class="profile-btn edit-btn" onclick="editProfile()">
            <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>
            Edit Profil
          </button>
          <button class="profile-btn save-btn" onclick="saveProfile()">
            <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Simpan
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('open');
      updateSidebarArrow();
    }
    
    function updateSidebarArrow() {
      const sidebar = document.getElementById('sidebar');
      const arrow = document.getElementById('sidebarArrow');
      if (sidebar.classList.contains('open')) {
        // Panah kiri (masuk)
        arrow.innerHTML = '<path d="M16 5l-8 7 8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      } else {
        // Panah kanan (keluar)
        arrow.innerHTML = '<path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      }
    }
    
    // Inisialisasi panah saat halaman dimuat
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
    
    // Profile Edit Functions
    function editProfile() {
      // Hide values, show inputs
      document.querySelectorAll('.field-value').forEach(el => {
        el.style.display = 'none';
      });
      
      document.querySelectorAll('.field-input').forEach(el => {
        el.style.display = 'block';
      });
      
      // Hide edit button, show save button
      document.querySelector('.edit-btn').style.display = 'none';
      document.querySelector('.save-btn').style.display = 'flex';
      
      // Add animation to profile card
      document.querySelector('.profile-card').style.animation = 'pulse 2s infinite';
    }
    
    function saveProfile() {
      // Get all input values
      const nama_lengkap = document.getElementById('nama_lengkap').value;
      const tempat_lahir = document.getElementById('tempat_lahir').value;
      const tanggal_lahir = document.getElementById('tanggal_lahir').value;
      const jenis_kelamin = document.getElementById('jenis_kelamin').value;
      const agama = document.getElementById('agama').value;
      const hobi = document.getElementById('hobi').value;
      const cita_cita = document.getElementById('cita_cita').value;
      
      // Send data to API
      fetch('profile_api.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          nama_lengkap,
          tempat_lahir,
          tanggal_lahir,
          jenis_kelamin,
          agama,
          hobi,
          cita_cita
        }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update displayed values
          document.querySelectorAll('.field-value').forEach((el, index) => {
            const inputId = el.nextElementSibling.id;
            el.textContent = document.getElementById(inputId).value;
          });
          
          // Show values, hide inputs
          document.querySelectorAll('.field-value').forEach(el => {
            el.style.display = 'block';
          });
          
          document.querySelectorAll('.field-input').forEach(el => {
            el.style.display = 'none';
          });
          
          // Show edit button, hide save button
          document.querySelector('.edit-btn').style.display = 'flex';
          document.querySelector('.save-btn').style.display = 'none';
          
          // No animation to remove
          
          // Show success message
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
      // Create notification element
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
      
      // Add styles
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
      
      // Style for content
      const content = notification.querySelector('.notification-content');
      content.style.display = 'flex';
      content.style.alignItems = 'center';
      content.style.gap = '10px';
      
      // Style for icon
      const icon = notification.querySelector('svg');
      icon.style.width = '20px';
      icon.style.height = '20px';
      
      // Add to DOM
      document.body.appendChild(notification);
      
      // Show notification
      setTimeout(() => {
        notification.style.opacity = '1';
      }, 10);
      
      // Remove after 3 seconds
      setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
          document.body.removeChild(notification);
        }, 300);
      }, 3000);
    }
    
    // No animations for profile fields
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize without animations
    });
  </script>
<script src="../music-player.js"></script>
</body>
</html>