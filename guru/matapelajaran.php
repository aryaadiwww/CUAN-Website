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
  <title>Dashboard Guru - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-dR4U0fFzFt+Spv4N3m4O+MJoCq1YvKZD2v7H8V++Xm+rRjUuZL+UV7KTLjmk0Kf9z79cGHVnsHlD45BzvL3ZXw==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
  <style>
    * {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      box-sizing: border-box;
    }
    body {
      background-color: #335165ff;
      min-height: 100vh;
      display: flex;
      flex-direction: row;
      width: 100vw;
      overflow-x: hidden;
    }

    
    @keyframes chartFadeIn {
      0% { opacity: 0; transform: scale(0.95) translateY(40px); }
      100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modern-chart-container canvas {
      background: #335165;
      border-radius: 18px;
      box-shadow: 0 2px 12px #b8355633;
      width: 100% !important;
      max-width: 1100px;
      height: 300px !important;
      max-height: 340px;
      display: block;
      margin: 0 auto;
      transition: box-shadow 0.3s;
      animation: chartPop 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes chartPop {
      0% { opacity: 0; transform: scale(0.8); }
      60% { opacity: 1; transform: scale(1.05); }
      100% { opacity: 1; transform: scale(1); }
    }
    @media screen and (max-width: 1100px) {
      .modern-chart-container {
        max-width: 98vw;
        padding: 1.2rem 0.5rem 1.2rem 0.5rem;
      }
      .modern-chart-container canvas {
        max-width: 98vw;
        height: 250px !important;
        max-height: 280px;
      }
    }
    @media screen and (max-width: 600px) {
      .modern-chart-container {
        max-width: 99vw;
        padding: 0.7rem 0.2rem 0.7rem 0.2rem;
      }
      .modern-chart-container canvas {
        max-width: 99vw;
        height: 200px !important;
        max-height: 220px;
      }
    }
    /* Remove old dashboard-chart, chart-title, and info-card styles */
    .sidebar {
      width: 70px;
      background: linear-gradient(135deg, #1a2933ff 0%, #405d6fff  100%);
      color: white;
      transition: width 0.4s cubic-bezier(.68,-0.55,.27,1.55);
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
      transition: opacity 0.4s cubic-bezier(.68,-0.55,.27,1.55), visibility 0.4s;
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
      background: linear-gradient(135deg, #4d7b99ff 0%, #335165ff 100%);
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
      transition: margin-right 0.4s cubic-bezier(.68,-0.55,.27,1.55), font-size 0.3s, padding-left 0.3s;

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
      background: linear-gradient(135deg, #395c74ff 0%, #77aacdff  100%);
      min-height: 100vh;
      margin-left: 70px;
      transition: background 0.4s, margin-left 0.4s;
      width: 100vw;
      overflow-x: hidden;
      overflow-y: auto;
    }
    .sidebar.open ~ .main-content {
      margin-left: 180px;
      transition: margin-left 0.4s cubic-bezier(.68,-0.55,.27,1.55);
    }
    header {
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 2rem;
      border-bottom-left-radius: 0;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
      transition: background 0.4s;
      background: linear-gradient(135deg, #1a2933ff 0%, #405d6fff  100%);
      height: 80px;
    }
    .hamburger-logo {
      display: flex;
      align-items: center;
      transition: margin-left 0.3s;
    }
    .hamburger {
      font-size: 2.1rem;
      cursor: pointer;
      background: #fff6;
      border: none;
      color: #335165ff;
      margin-right: 1rem;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 8px #ffb34733;
      transition: background 0.3s, color 0.3s, box-shadow 0.3s;
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
      color: #335165ff;
      box-shadow: 0 4px 16px #ffb34755;
      transform: scale(1.1) rotate(-8deg);
    }
    .profile-menu {
      position: relative;
      display: flex;
      align-items: center;
    }
    .profile-button {
      background: linear-gradient(90deg, #578aacff 0%, #5587a9ff 100%);
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
      background: linear-gradient(90deg, #335165ff 0%, #335165ff 100%);
      color: #fff;
      box-shadow: 0 4px 16px #b8355655;
      transform: scale(1.06) rotate(-2deg);
    }
    .profile-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #fff;
      border: 2px solid #335165ff;
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
    .main-content {
      flex: 1;
      margin-left: 70px;
      transition: margin-left 0.4s cubic-bezier(.68,-0.55,.27,1.55);
      overflow-x: hidden;
    }
    .sidebar.open ~ .main-content {
      margin-left: 180px;
    }

    .dropdown a:hover {
      background: #DC97A5;
      color: #fff;
    }


    .mapel-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2.5rem 1rem 2rem 1rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .mapel-title {
      font-size: 2.3rem;
      font-weight: 700;
      color: #ffffffff;
      margin-bottom: 1.2rem;
      letter-spacing: 1px;
      text-shadow: 0 2px 12px #b8355633;
      text-align: center;
    }
 
    .mapel-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2rem;
      width: 100%;
      margin-top: 1rem;
      margin-bottom: 1rem;
    }
    .mapel-card {
      background: linear-gradient(135deg, var(--color1), var(--color2));
      border-radius: 18px;
      box-shadow: 0 4px 24px #2222a033, 0 1.5px 8px #2222a033;
      display: flex;
      flex-direction: row;
      align-items: center;
      padding: 1.2rem 1.2rem;
      transition: box-shadow 0.3s, transform 0.2s;
      cursor: pointer;
      min-height: 110px;
      min-width: 0;
      position: relative;
      overflow: hidden;
      animation: fadeInSmooth 1.1s cubic-bezier(.68,-0.55,.27,1.55);
      max-width: 100%;
    }
    @keyframes fadeInSmooth {
      0% {
        opacity: 0;
        transform: translateY(30px) scale(0.98);
      }
      100% {
        opacity: 1;
        transform: none;
      }
    }
    .mapel-card:hover {
      box-shadow: 0 8px 32px #2222a033, 0 2px 12px #2222a033;
      transform: scale(1.03) rotate(-1deg);
    }
    .mapel-img {
      width: 72px;
      height: 72px;
      object-fit: contain;
      margin-right: 1.2rem;
      margin-bottom: 0;
      transition: transform 0.3s;
      animation: bounceInMapel 1.2s cubic-bezier(.68,-0.55,.27,1.55);
      border-radius: 12px;
      box-shadow: none;
      background: none;
      display: block;
    }
    .mapel-card:hover .mapel-img {
      transform: scale(1.18) rotate(-6deg);
    }
    .mapel-content {
      display: flex;
      flex-direction: column;
      justify-content: center;
      flex: 1;
      min-width: 0;
    }
    .mapel-title-card {
      font-size: 1.7rem;
      font-weight: 800;
      color: #fff;
      margin-bottom: 0.3rem;
      letter-spacing: 0.5px;
      text-align: left;
      text-shadow: 0 2px 8px #2222a033;
      line-height: 1.1;
    }
    .mapel-desc {
      font-size: 0.85rem;
      color: #fff;
      font-weight: 500;
      background: none;
      padding: 0;
      border-radius: 0;
      box-shadow: none;
      margin: 0;
      text-shadow: 0 1px 4px #2222a033;
      line-height: 1.3;
      text-align: left;
      opacity: 0.85;
      max-width: 90%;
    }
    @media screen and (max-width: 900px) {
      header {
        height: 70px;
      }
      .mapel-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.2rem;
      }
      .mapel-card {
        min-height: 90px;
        padding: 1rem 0.7rem;
      }
      .mapel-img {
        width: 56px;
        height: 56px;
      }
      .mapel-title-card {
        font-size: 1.2rem;
      }
      .mapel-desc {
        font-size: 0.8rem;
      }
    }
    @media screen and (max-width: 600px) {
      header {
        height: 60px;
        padding: 0.5rem 1rem;
      }
      .mapel-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
      }
      .mapel-card {
        min-height: 70px;
        padding: 0.7rem 0.5rem;
      }
      .mapel-img {
        width: 44px;
        height: 44px;
      }
      .mapel-title-card {
        font-size: 1rem;
      }
      .mapel-desc {
        font-size: 0.7rem;
      }
        header {
        height: 60px;
        padding: 0.5rem 1rem;
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
          <!-- Panah kanan default, akan diganti JS -->
          <svg id="sidebarArrow" viewBox="0 0 24 24" fill="none"><path d="M8 5l8 7-8 7" stroke="#335165ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
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
    <div class="mapel-container">
      <div class="mapel-title">Mata Pelajaran</div>
      <div class="mapel-wrapper">
        <div class="mapel-grid">
        <div class="mapel-card" style="--color1:#6A4C93;--color2:#B983FF;cursor:pointer;" onclick="window.location.href='ipas_materi.php'">
            <img src="../img/ips.png" alt="IPAS" class="mapel-img"/>
            <div class="mapel-content">
              <div class="mapel-title-card">IPAS</div>
              <div class="mapel-desc">Sains, makhluk hidup, lingkungan, masyarakat, sejarah, dan geografi.</div>
            </div>
          </div>
          <div class="mapel-card" style="--color1:#2D7DD2;--color2:#97DFFC;">
            <img src="../img/pai.png" alt="PAI" class="mapel-img"/>
            <div class="mapel-content">
              <div class="mapel-title-card">PAI</div>
              <div class="mapel-desc">Belajar agama Islam dan akhlak mulia.</div>
            </div>
          </div>
          <div class="mapel-card" style="--color1:#38B000;--color2:#90E39A;">
            <img src="../img/ppkn.png" alt="PPKN" class="mapel-img"/>
            <div class="mapel-content">
              <div class="mapel-title-card">PPKN</div>
              <div class="mapel-desc">Pancasila, kewarganegaraan, dan kebangsaan.</div>
            </div>
          </div>
          <div class="mapel-card" style="--color1:#F9A826;--color2:#FEE440;">
            <img src="../img/indonesia.png" alt="B.Indonesia" class="mapel-img"/>
            <div class="mapel-content">
              <div class="mapel-title-card">B. Indonesia</div>
              <div class="mapel-desc">Bahasa, sastra, dan budaya Indonesia.</div>
            </div>
          </div>
          <div class="mapel-card" style="--color1:#009FFD;--color2:#2A2A72;">
            <img src="../img/mtk.png" alt="Matematika" class="mapel-img"/>
            <div class="mapel-content">
              <div class="mapel-title-card">Matematika</div>
              <div class="mapel-desc">Angka, hitungan, dan logika.</div>
            </div>
          </div>
          <div class="mapel-card" style="--color1:#43E97B;--color2:#38F9D7;">
            <img src="../img/olahraga.png" alt="PJOK" class="mapel-img"/>
            <div class="mapel-content">
              <div class="mapel-title-card">PJOK</div>
              <div class="mapel-desc">Olahraga dan kesehatan jasmani.</div>
            </div>
          </div>
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
  </script>
<script src="../music-player.js"></script>
</body>
</html>
