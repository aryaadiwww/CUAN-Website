<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'siswa') {
  header("Location: ../index.html");
  exit();
}
$jsonFile = '../uploads/media_pembelajaran/media_pembelajaran.json';
if (file_exists($jsonFile)) {
  $data = json_decode(file_get_contents($jsonFile), true);
} else {
  $data = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Media Pembelajaran IPAS - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="../responsive.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    body {
      background-color: #bfa800;
      min-height: 100vh;
      margin: 0;
      font-family: 'Poppins', sans-serif;
      color: #222;
      overflow-x: hidden;
      display: flex;
      flex-direction: row;
      width: 100vw;
    }
    .sidebar {
      width: 70px;
  background: linear-gradient(135deg, #bfa800 0%, #7c6f00 100%);
      color: white;
      transition: width 0.4s cubic-bezier(.68,-0.55,.27,1.55);
      overflow: hidden;
      position: fixed;
      left: 0;
      top: 0;
      height: 100vh;
      z-index: 1000;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .sidebar.open {
      width: 180px;
    }
    .sidebar .logo-section {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem 0;
      height: 80px;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.4s, visibility 0.4s;
    }
    .sidebar.open .logo-section {
      opacity: 1;
      visibility: visible;
      transition-delay: 0.1s;
    }
    .sidebar .logo-section img {
      width: 120px;
      height: 60px;
      margin-right: 10px;
      transition: width 0.4s, height 0.4s, filter 0.4s;
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
  background: linear-gradient(135deg, #bfa800 0%, #7c6f00 100%);
      box-shadow: 0 2px 8px 0 rgba(255,179,71,0.04);
      transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
      position: relative;
      overflow: hidden;
    }
    .sidebar ul li:hover {
      background:  #7c6f00;
      transform: scale(1.06) translateX(4px) rotate(-2deg);
      box-shadow: 0 4px 16px 0 rgba(255,94,98,0.12);
    }
    .sidebar ul li .menu-icon {
      font-size: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: margin-right 0.4s, font-size 0.3s, padding-left 0.3s;
    }
    .sidebar.open ul li .menu-icon {
      margin-right: 15px;
      font-size: 28px;
      padding-left: 0;
    }
    .sidebar span.menu-text {
      display: none;
      font-size: 0.9rem;
      letter-spacing: 0.5px;
      transition: opacity 0.3s, margin-left 0.3s;
      opacity: 0;
      margin-left: 0;
  color: #fff;
    }
    .sidebar.open span.menu-text {
      display: inline;
      opacity: 1;
      margin-left: 1px;
  color: #fff;
      font-weight: 600;
    }
    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      background: linear-gradient(135deg, #bfa800 0%, #7c6f00 100%);
      min-height: 100vh;
      margin-left: 70px;
      transition: background 0.4s, margin-left 0.4s;
      width: 100vw;
      overflow-x: hidden;
      overflow-y: auto;
    }
    .sidebar.open ~ .main-content {
      margin-left: 180px;
      transition: margin-left 0.4s;
    }
    header {
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 2rem;
      border-bottom-left-radius: 0;
      box-shadow: bottom 2px 8px rgba(0, 0, 0, 1);
      transition: background 0.4s;
  background: linear-gradient(135deg, #bfa800 0%, #7c6f00 100%);
    }
    .hamburger-logo {
      display: flex;
      align-items: center;
      transition: margin-left 0.3s;
    }
    .hamburger {
      cursor: pointer;
      background: #fff6;
      border: none;
      color: #ffffffd0;
      margin-right: 1rem;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 8px #b983ff33;
      font-size: unset;
      padding: 0;
    }
    .hamburger svg {
      width: 28px;
      height: 28px;
      display: block;
    }
    .hamburger:hover {
      background: #fff;
      color: #6A4C93;
      box-shadow: 0 4px 16px #b983ff55;
    }
    .profile-menu {
      position: relative;
      display: flex;
      align-items: center;
    }
    .profile-button {
  background: linear-gradient(90deg, #bfa800 0%, #7c6f00 100%);
      border: none;
      color: #fff;
      cursor: pointer;
      font-weight: bold;
      border-radius: 50px;
      padding: 5px 16px 5px 10px;
      display: flex;
      align-items: center;
      box-shadow: 0 2px 8px #1976D233;
      font-size: 1rem;
      position: relative;
    }
    .profile-button:hover {
  background: linear-gradient(90deg, #7c6f00 0%, #bfa800 100%);
      color: #fff;
      box-shadow: 0 4px 16px #1976D255;
    }
    .profile-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      margin-right: 8px;
      border: 2px solid #fff;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      object-fit: cover;
    }
    .dropdown {
      position: absolute;
      top: 100%;
      right: 0;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
      width: 200px;
      opacity: 0;
      pointer-events: none;
      transform: translateY(10px);
      transition: opacity 0.3s, transform 0.3s;
      overflow: hidden;
      z-index: 20;
  border: 1.5px solid #bfa800;
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
      color: #6A4C93;
      font-weight: 600;
      font-size: 1rem;
      border-bottom: 1px solid #f3e6e6;
    }
    .dropdown a:last-child {
      border-bottom: none;
    }
    .dropdown a:hover {
      background: #B983FF;
      color: #fff;
    }

    .media-list {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  align-items: center;
  justify-content: center;
  width: 100%;
  margin-top: 2.5rem;
}

.media-card {
  background: rgba(255, 230, 102, 0.18);
  border-radius: 14px;
  padding: 1rem 1.1rem 0.8rem 1.1rem;
  box-shadow: 0 2px 12px rgba(255, 230, 102, 0.10);
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
  transition: transform 0.3s, box-shadow 0.3s;
  animation: fadeIn 0.8s ease-out;
  border-left: 4px solid #ffe066;
  margin-bottom: 1.5rem;
  max-width: 400px;
  width: 100%;
  align-items: stretch;
  color: #fff;
}

.media-card:hover {
  box-shadow: 0 8px 32px rgba(255,111,145,0.18), 0 4px 16px rgba(255,224,102,0.12);
  transform: translateY(-4px) scale(1.01);
}

@media screen and (max-width: 600px) {
  .media-card {
    max-width: 10vw;
    padding: 0.7rem 0.2rem;
  }
}
    .elegant-btn {
      background: linear-gradient(90deg, #fff 0%, #ffe066 100%);
      color: #7c6f00 !important;
      border: none;
      border-radius: 30px;
      padding: 7px 18px 7px 12px;
      font-size: 1rem;
      font-weight: 600;
      box-shadow: 0 2px 8px #ffe06633;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin: 0 6px 6px 0;
      transition: background 0.3s, color 0.3s, box-shadow 0.3s, transform 0.2s;
      text-decoration: none !important;
      cursor: pointer;
    }
    .elegant-btn:hover {
      background: linear-gradient(90deg, #ffe066 0%, #fff 100%);
      color: #bfa800 !important;
      box-shadow: 0 4px 16px #ffe06655;
      transform: scale(1.05);
    }
    .elegant-btn svg {
      width: 16px !important;
      height: 16px !important;
      margin-right: 4px;
    }
    .btn-text {
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      color: inherit;
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
            <svg id="sidebarArrow" viewBox="0 0 24 24" fill="none"><path d="M8 5l8 7-8 7" stroke="#6A4C93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
        </div>
        <div class="profile-menu">
          <button class="profile-button" onclick="toggleDropdown()">
            <img src="../img/profile.png" alt="Avatar" class="profile-avatar" />
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left:2px;"><path d="M5 8L10 13L15 8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <div class="dropdown" id="dropdown">
            <a href="siswa_edit_profile.php"><svg width="18" height="18" fill="none" stroke="#6A4C93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>Edit Profil</a>
            <a href="../logout.php"><svg width="18" height="18" fill="none" stroke="#6A4C93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>Logout</a>
          </div>
        </div>
      </header>
      <div class="media-header" style="display:flex;flex-direction:column;align-items:center;justify-content:center;margin-top:2.5rem;">
        <div style="display:flex;flex-direction:row;align-items:center;justify-content:center;width:100%;">
          <div class="media-icon" style="margin-bottom:0;">
            <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24" width="60" height="60">
              <rect x="4" y="4" width="16" height="16" rx="4" fill="#ffffff05"/>
              <path d="M8 8h8v8H8z" stroke="#ffffffff" stroke-width="2" fill="none"/>
            </svg>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-start;justify-content:center;">
            <h1 class="media-title" style="font-size:2.2rem;font-weight:700;color:#fff;margin:0;text-shadow:0 2px 10px rgba(0,0,0,0.2);text-align:left;">Media Pembelajaran IPAS</h1>
            <p class="media-subtitle" style="font-size:1.1rem;color:rgba(255,255,255,0.8);margin-top:0.5rem;text-align:left;max-width:500px;">Media Pembelajaran yang diberikan oleh guru</p>
          </div>
        </div>
      </div>
      <div class="media-list">
        <?php if ($data && count($data)): ?>
          <?php foreach ($data as $entry): ?>
            <div class="media-card">
              <div class="media-card-header" style="display:flex;justify-content:space-between;align-items:flex-start;">
                <h3 class="media-card-title" style="margin:0;"><?= htmlspecialchars($entry['title']) ?></h3>
                <span class="media-card-date">
                  <?php 
                    $dateWIB = date_create($entry['date'], timezone_open('UTC'));
                    date_timezone_set($dateWIB, timezone_open('Asia/Jakarta'));
                    echo date_format($dateWIB, 'd-m-Y H:i') . ' WIB';
                  ?>
                </span>
              </div>
              <p class="media-card-desc" style="color:rgba(255,255,255,0.9);margin-bottom:0.2rem;"><?= htmlspecialchars($entry['desc']) ?></p>
              <div class="media-card-footer" style="display:flex;justify-content:space-between;align-items:center;">
                <div class="media-card-teacher" style="display:flex;align-items:center;gap:0.5rem;font-size:0.9rem;color:rgba(255,255,255,0.8);">
                  <img src="../img/profile.png" alt="Guru" style="width:24px;height:24px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.3);">
                  <span><?= isset($entry['teacher']) ? htmlspecialchars($entry['teacher']) : 'Guru' ?></span>
                </div>
                <div class="media-card-actions">
                  <?php if ($entry['filename']): ?>
                    <a class="media-card-button elegant-btn" href="../uploads/media_pembelajaran/<?= htmlspecialchars($entry['filename']) ?>" download>
                      <svg fill="none" stroke="#000000ff" stroke-width="2" viewBox="0 0 24 24" width="16" height="16" style="vertical-align:middle;margin-right:6px;"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                      <span class="btn-text">Unduh</span>
                    </a>
                    <a class="media-card-button elegant-btn" href="../uploads/media_pembelajaran/<?= htmlspecialchars($entry['filename']) ?>" target="_blank">
                      <svg fill="none" stroke="#000000ff" stroke-width="2" viewBox="0 0 24 24" width="16" height="16" style="vertical-align:middle;margin-right:6px;"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                      <span class="btn-text">Lihat</span>
                    </a>
                  <?php endif; ?>
                  <?php if ($entry['link']): ?>
                    <a class="media-card-button elegant-btn" href="<?= htmlspecialchars($entry['link']) ?>" target="_blank">
                      <svg fill="none" stroke="#000000ff" stroke-width="2" viewBox="0 0 24 24" width="16" height="16" style="vertical-align:middle;margin-right:6px;"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                      <span class="btn-text">Kunjungi Link</span>
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="media-card" style="background:rgba(255,255,255,0.1);text-align:center;">
            <h2 class="media-card-title" style="color:#fff;">Belum Ada Media Pembelajaran</h2>
            <p class="media-card-desc" style="color:rgba(255,255,255,0.7);">Media pembelajaran belum tersedia. Silakan hubungi guru untuk mengunggah media pembelajaran.</p>
          </div>
        <?php endif; ?>
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
        arrow.innerHTML = '<path d="M16 5l-8 7 8 7" stroke="#6A4C93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      } else {
        arrow.innerHTML = '<path d="M8 5l8 7-8 7" stroke="#6A4C93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
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
  </script>
  <script src="../music-player.js"></script>
</body>
</html>