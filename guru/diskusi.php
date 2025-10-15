<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'guru') {
  header("Location: ../index.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Diskusi IPAS - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    body {
      background-color: #6A4C93;
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
      background: linear-gradient(135deg, #1a2933ff 0%, #405d6fff  100%);
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
      background: linear-gradient(135deg, #634338ff 0%, #ffb296ff 100%);
      box-shadow: 0 2px 8px 0 rgba(255,179,71,0.04);
      transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
      position: relative;
      overflow: hidden;
    }
    .sidebar ul li:hover {
      background:  #e4aa95ff;
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
      background: linear-gradient(135deg, #6A4C93 0%, #B983FF 100%);
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
      background-color: #5a3f7dff;
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
      background: linear-gradient(90deg, #b983ffb2 0%, #b983ffb2 100%);
      border: none;
      color: #fff;
      cursor: pointer;
      font-weight: bold;
      border-radius: 50px;
      padding: 5px 16px 5px 10px;
      display: flex;
      align-items: center;
      box-shadow: 0 2px 8px #6a4c9333;
      font-size: 1rem;
      position: relative;
    }
    .profile-button:hover {
      background: linear-gradient(90deg, #B983FF 0%, #B983FF 100%);
      color: #fff;
      box-shadow: 0 4px 16px #6a4c9355;
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
      border: 1.5px solid #B983FF;
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

    /* Konten Diskusi */
    .diskusi-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
      display: flex;
      flex-direction: column;
      align-items: stretch;
    }

    .diskusi-header {
      display: flex;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }

    .diskusi-icon {
      width: 64px;
      height: 64px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1.5rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .diskusi-icon svg {
      width: 36px;
      height: 36px;
    }

    .diskusi-title {
      font-size: 2.2rem;
      font-weight: 700;
      color: #fff;
      margin: 0;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .diskusi-subtitle {
      font-size: 1.1rem;
      color: rgba(255, 255, 255, 0.8);
      margin-top: 0.5rem;
    }

    .diskusi-content {
      display: flex;
      flex-direction: column;
      gap: 2rem;
    }

    .diskusi-empty {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 3rem 2rem;
      text-align: center;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.8s ease-out;
    }

    .diskusi-empty svg {
      width: 80px;
      height: 80px;
      margin-bottom: 1.5rem;
      opacity: 0.7;
    }

    .diskusi-empty-text {
      font-size: 1.4rem;
      font-weight: 600;
      color: #fff;
      margin-bottom: 0.5rem;
    }

    .diskusi-empty-subtext {
      font-size: 1rem;
      color: rgba(255, 255, 255, 0.7);
      max-width: 500px;
      margin: 0 auto;
    }

    .diskusi-card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      gap: 1rem;
      transition: transform 0.3s, box-shadow 0.3s;
      animation: fadeIn 0.8s ease-out;
      border-left: 4px solid #4ecca3;
    }

    .diskusi-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .diskusi-card-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    .diskusi-card-title {
      font-size: 1.4rem;
      font-weight: 700;
      color: #fff;
      margin: 0;
    }

    .diskusi-card-date {
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.7);
      background: rgba(255, 255, 255, 0.1);
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
    }

    .diskusi-card-desc {
      font-size: 1rem;
      color: rgba(255, 255, 255, 0.9);
      line-height: 1.5;
    }

    .diskusi-card-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 0.5rem;
    }

    .diskusi-card-info {
      display: flex;
      flex-direction: column;
      gap: 0.3rem;
    }

    .diskusi-card-stats {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.9rem;
      color: #4ecca3;
      font-weight: 600;
    }

    .diskusi-card-stats svg {
      width: 16px;
      height: 16px;
    }

    .diskusi-card-time {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.8);
    }

    .diskusi-card-time svg {
      width: 16px;
      height: 16px;
    }

    .diskusi-card-actions {
      display: flex;
      gap: 0.8rem;
    }

    .diskusi-card-button {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      color: #fff;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      transition: background 0.3s, transform 0.2s;
    }

    .diskusi-card-button:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: scale(1.05);
    }

    .diskusi-card-button svg {
      width: 16px;
      height: 16px;
    }

    .diskusi-add-button {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      color: #fff;
      padding: 1rem;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.8rem;
      transition: background 0.3s, transform 0.2s;
      margin-top: 1rem;
    }

    .diskusi-add-button:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: translateY(-3px);
    }

    .diskusi-add-button svg {
      width: 20px;
      height: 20px;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media screen and (max-width: 768px) {
      .diskusi-container {
        padding: 1.5rem;
      }

      .diskusi-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }

      .diskusi-title {
        font-size: 1.8rem;
      }

      .diskusi-card-header {
        flex-direction: column;
        gap: 0.5rem;
      }

      .diskusi-card-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
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
        <span class="menu-icon"><img src="../img/aktivitas.png" alt="Orang Tua Siswa" style="width:18px;height:18px;object-fit:contain;"></span>
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
          <svg id="sidebarArrow" viewBox="0 0 24 24" fill="none"><path d="M8 5l8 7-8 7" stroke="#6A4C93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>
      <div class="profile-menu">
        <button class="profile-button" onclick="toggleDropdown()">
          <img src="../img/profile.png" alt="Avatar" class="profile-avatar" />
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left:2px;"><path d="M5 8L10 13L15 8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="dropdown" id="dropdown">
          <a href="guru_edit_profile.php"><svg width="18" height="18" fill="none" stroke="#6A4C93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>Edit Profil</a>
          <a href="../logout.php"><svg width="18" height="18" fill="none" stroke="#6A4C93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>Logout</a>
        </div>
      </div>
    </header>
    
    <div class="diskusi-container">
      <div class="diskusi-header">
        <div class="diskusi-icon">
          <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
          </svg>
        </div>
        <div>
          <h1 class="diskusi-title">Diskusi Guru & Orang Tua</h1>
          <p class="diskusi-subtitle">Forum komunikasi antara guru dan orang tua siswa</p>
        </div>
      </div>
      
      <div class="diskusi-content">
        <!-- Tampilan ketika belum ada diskusi -->
        <div class="diskusi-empty">
          <svg fill="none" stroke="#fff" stroke-width="1.5" viewBox="0 0 24 24">
            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
          </svg>
          <h2 class="diskusi-empty-text">Belum Ada Diskusi</h2>
          <p class="diskusi-empty-subtext">Belum ada diskusi antara guru dan orang tua untuk siswa ini. Klik tombol di bawah untuk memulai diskusi baru.</p>
        </div>
        
        <!-- Tombol tambah diskusi -->
        <button class="diskusi-add-button">
          <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="16"/>
            <line x1="8" y1="12" x2="16" y2="12"/>
          </svg>
          Mulai Diskusi Baru
        </button>
        
        <!-- Contoh diskusi (akan muncul ketika guru membuat diskusi) -->
        <!-- 
        <div class="diskusi-card">
          <div class="diskusi-card-header">
            <h3 class="diskusi-card-title">Diskusi: Pengaruh Polusi Terhadap Ekosistem</h3>
            <span class="diskusi-card-date">Dibuat: 20 Mei 2023</span>
          </div>
          <p class="diskusi-card-desc">Forum diskusi untuk membahas pengaruh polusi terhadap ekosistem air dan darat. Siswa diharapkan dapat berbagi pendapat dan solusi untuk mengatasi masalah polusi.</p>
          <div class="diskusi-card-footer">
            <div class="diskusi-card-info">
              <div class="diskusi-card-stats">
                <svg fill="none" stroke="#4ecca3" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                  <circle cx="9" cy="7" r="4"/>
                  <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                  <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span>15 siswa berpartisipasi</span>
              </div>
              <div class="diskusi-card-time">
                <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                </svg>
                <span>32 komentar</span>
              </div>
            </div>
            <div class="diskusi-card-actions">
              <button class="diskusi-card-button">
                <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
                Lihat Diskusi
              </button>
              <button class="diskusi-card-button">
                <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit
              </button>
              <button class="diskusi-card-button">
                <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                  <line x1="10" y1="11" x2="10" y2="17"/>
                  <line x1="14" y1="11" x2="14" y2="17"/>
                </svg>
                Hapus
              </button>
            </div>
          </div>
        </div>
        -->
      </div>
    </div>
  </div>

  <script>
    // Toggle sidebar
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
</body>
</html>