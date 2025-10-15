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
    
    /* Animasi untuk elemen */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
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
      background: linear-gradient(135deg, #335165ff 0%, #547991ff  100%);
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
      background: linear-gradient(135deg, #1a2933ff 0%, #405d6fff  100%);
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
    .dropdown a:last-child {
      border-bottom: none;
    }
    .dropdown a:hover {
      background: #DC97A5;
      color: #fff;
    }
    .main {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: center;
      padding: 2.5rem 2.5rem 2.5rem 2.5rem;
      animation: fadeInMain 0.7s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .main-illustration {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 360px;
      min-width: 360px;
      max-width: 460px;
      margin-right: 0.5rem;
      padding: 1.2rem 1.2rem 1.2rem 1.2rem;
      animation: bounceIn 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .main-illustration img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      border-radius: 12px;
      box-shadow: none;
    }
    .main-content-text {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: center;
      min-width: 260px;
      max-width: 650px;
      background: rgba(255,255,255,0.10);
      border-radius: 16px;
      padding: 2rem 2.2rem 2rem 2.2rem;
      box-shadow: 0 2px 12px #b8355633;
    }
    .main-content-text h1 {
      font-size: 2.2rem;
      color: #fff;
      margin-bottom: 0.7rem;
      font-weight: 700;
      letter-spacing: 1px;
      text-shadow: 0 2px 8px #b8355633;
      word-break: break-word;
    }
    .main-content-text p {
      font-size: 1.15rem;
      font-style: italic;
      color: #fff;
      font-weight: 500;
      background: linear-gradient(90deg, #dc97a586 0%, #dc97a586 100%);
      padding: 0.9rem 1.4rem;
      border-radius: 10px;
      box-shadow: 0 2px 8px #b8355633;
      margin: 0;
      text-shadow: 0 1px 4px #b8355633;
      line-height: 1.6;
    }
    @keyframes bounceIn {
      0% { opacity: 0; transform: scale(0.7) translateY(60px); }
      60% { opacity: 1; transform: scale(1.1) translateY(-10px); }
      100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes fadeInMain {
      0% { opacity: 0; transform: translateY(30px) scale(0.98); }
      100% { opacity: 1; transform: none; }
    }
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
        transition: margin-left 0.4s;
      }
      .sidebar.open ~ .main-content {
        margin-left: 120px;
      }
      header {
        padding: 1rem 1rem 1rem 1.5rem;
      }
    }
    @media screen and (max-width: 600px) {
      .main {
        flex-direction: column;
        gap: 1.2rem;
        padding: 1.2rem 0.7rem 1rem 0.7rem;
        align-items: stretch;
      }
      .main-illustration {
        margin: 0 auto 1rem auto;
        padding: 0.7rem;
        max-width: 180px;
        min-width: 120px;
        height: 160px;
      }
      .main-content-text {
        padding: 1.2rem 1rem;
        min-width: unset;
        max-width: unset;
      }
      .sidebar.open {
        width: 120px;
      }
      .sidebar .logo-section img {
        width: 70px;
        height: 36px;
      }
      .main-content {
        margin-left: 54px;
      }
      .sidebar.open ~ .main-content {
        margin-left: 120px;
      }
      .main {
        padding: 1.2rem 0.7rem 1rem 0.7rem;
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
          <a href="siswa_edit_profile.php"><svg width="18" height="18" fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>Edit Profil</a>
          <a href="../logout.php"><svg width="18" height="18" fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>Logout</a>
        </div>
      </div>
    </header>
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
  </script>
<script src="../music-player.js"></script>
</body>
</html>
<style>
  .ortu-container {
    width: 100vw;
    max-width: 100vw;
    margin: 0;
    padding: 1.2rem 0 1.2rem 0;
    background: none;
    border-radius: 0;
    box-shadow: none;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    animation: fadeInOrtu 1.1s cubic-bezier(.68,-0.55,.27,1.55);
  }
  @keyframes fadeInOrtu {
    0% { opacity: 0; transform: scale(0.97) translateY(40px); }
    100% { opacity: 1; transform: scale(1) translateY(0); }
  }
  .ortu-title {
    font-size: 2.3rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 2.2rem;
    letter-spacing: 1px;
    text-shadow: 0 2px 8px #b8355633;
    text-align: center;
    animation: pulseOrtuTitle 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    margin-right: 6rem;
  }
  @keyframes pulseOrtuTitle {
    0% { transform: scale(0.95); }
    60% { transform: scale(1.05); }
    100% { transform: scale(1); }
  }
  .ortu-list {
    display: flex;
    flex-direction: column;
    gap: 0.7rem;
    width: 100vw;
    margin: 0;
    padding: 0 2vw;
    box-sizing: border-box;
  }
  .ortu-card {
    background: linear-gradient(135deg, #e4f0fa 60%, #f7e6e9 100%);
    border-radius: 12px;
    box-shadow: 0 1px 6px #33516533;
    display: flex;
    align-items: center;
    gap: 0.7rem;
    padding: 0.5rem 1.2rem 0.5rem 1.2rem;
    margin: 0 auto;
    min-height: 54px;
    max-width: 1100px;
    width: 100%;
    transition: transform 0.18s, box-shadow 0.18s, background 0.18s;
    animation: ortuCardPop 0.8s cubic-bezier(.68,-0.55,.27,1.55);
    position: relative;
    overflow: hidden;
    margin-right: 6.5rem;
  }
  .ortu-card:hover {
    transform: scale(1.04) translateY(-4px) rotate(-1deg);
    box-shadow: 0 8px 32px #33516555;
    background: linear-gradient(135deg, #d6eaff 60%, #ffe6f0 100%);
  }
  @keyframes ortuCardPop {
    0% { opacity: 0; transform: scale(0.85) translateY(30px); }
    60% { opacity: 1; transform: scale(1.08) translateY(-6px); }
    100% { opacity: 1; transform: scale(1) translateY(0); }
  }
  .ortu-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #335165 40%, #b83556 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: #fff;
    box-shadow: 0 1px 4px #33516533;
    border: 2px solid #fff;
    animation: ortuIconBounce 1.1s cubic-bezier(.68,-0.55,.27,1.55);
    flex-shrink: 0;
  }
  @keyframes ortuIconBounce {
    0% { transform: scale(0.7); }
    60% { transform: scale(1.15); }
    100% { transform: scale(1); }
  }
  .ortu-name {
    font-size: 1rem;
    font-weight: 600;
    color: #335165;
    letter-spacing: 0.5px;
    flex: 1;
    text-shadow: 0 1px 4px #33516533;
    word-break: break-word;
    transition: color 0.2s;
    padding-left: 0.2rem;
  }
  .ortu-card:hover .ortu-name {
    color: #b83556;
  }
  .diskusi-btn {
    background: linear-gradient(90deg, #335165 0%, #b83556 100%);
    color: #fff;
    border: none;
    border-radius: 50px;
    padding: 0.3rem 0.9rem;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    box-shadow: 0 1px 4px #33516533;
    transition: background 0.18s, color 0.18s, transform 0.13s;
    outline: none;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    animation: ortuBtnFadeIn 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    flex-shrink: 0;
  }
  .diskusi-btn:hover {
    background: linear-gradient(90deg, #b83556 0%, #335165 100%);
    color: #fff;
    transform: scale(1.04) rotate(-2deg);
    box-shadow: 0 2px 8px #33516555;
  }
  @keyframes ortuBtnFadeIn {
    0% { opacity: 0; transform: scale(0.9); }
    100% { opacity: 1; transform: scale(1); }
  }
  @media screen and (max-width: 900px) {
    .ortu-container {
      padding: 0.7rem 0.2rem 0.7rem 0.2rem;
    }
    .ortu-title {
      font-size: 1.7rem;
    }
    .ortu-list {
      padding: 0 1vw;
    }
    .ortu-card {
      padding: 0.5rem 0.5rem 0.5rem 0.5rem;
      max-width: 98vw;
    }
  }
  @media screen and (max-width: 600px) {
    .ortu-container {
      padding: 0.3rem 0.1rem 0.3rem 0.1rem;
    }
    .ortu-title {
      font-size: 1.2rem;
    }
    .ortu-list {
      padding: 0 0.5vw;
    }
    .ortu-card {
      padding: 0.4rem 0.3rem 0.4rem 0.3rem;
      max-width: 99vw;
    }
    .ortu-icon {
      width: 30px;
      height: 30px;
      font-size: 1rem;
    }
    .diskusi-btn {
      font-size: 0.8rem;
      padding: 0.3rem 0.6rem;
    }
  }
</style>

<div class="ortu-container">
  <div class="ortu-title">Data Orang Tua Siswa</div>
  <div class="ortu-list" id="ortu-list"></div>
</div>

<script>
  // Ambil data ortu dari API
  function renderOrtu() {
    fetch('api_guru_ortu.php')
      .then(response => response.json())
      .then(ortuList => {
        const list = document.getElementById('ortu-list');
        list.innerHTML = '';
        const ortuProfileIcon = '<img src="../img/profile.png" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
        ortuList.forEach((ortu, i) => {
          const card = document.createElement('div');
          card.className = 'ortu-card';
          card.innerHTML = `
            <div class="ortu-icon" style="background: linear-gradient(135deg, #${(i%2===0)?'335165':'b83556'} 40%, #${(i%2===0)?'b83556':'335165'} 100%); animation-delay: ${i*0.04}s;">
              ${ortuProfileIcon}
            </div>
            <div class="ortu-name"> ${ortu.nama}(Orang Tua Siswa)</div>
            <div style="display:flex;gap:0.5rem;justify-content:center;margin-top:0.7rem;">
              <button class="diskusi-btn" type="button" data-username="${encodeURIComponent(ortu.username)}">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2z"/></svg>
                Detail
              </button>
              <button class="diskusi-btn chat-btn" type="button" data-username="${encodeURIComponent(ortu.username)}" data-nama="${encodeURIComponent(ortu.nama)}" style="background:linear-gradient(135deg,#36b9cc 0%,#5e72e4 100%);color:#fff;">
                <i class="fas fa-comments"></i> Diskusi
              </button>
            </div>
          `;
          card.style.animationDelay = (i*0.04) + 's';
          list.appendChild(card);
        });
        // Event tombol detail
        list.querySelectorAll('.diskusi-btn:not(.chat-btn)').forEach(btn => {
          btn.addEventListener('click', function() {
            const username = decodeURIComponent(this.getAttribute('data-username'));
            showDetail(username);
          });
        });
        // Event tombol diskusi
        list.querySelectorAll('.chat-btn').forEach(btn => {
          btn.addEventListener('click', function() {
            const username = decodeURIComponent(this.getAttribute('data-username'));
            const nama = decodeURIComponent(this.getAttribute('data-nama'));
            chatPopup.show(username, nama);
          });
        });
      });
  }

  // Fungsi detail ortu
  function showDetail(username) {
    window.location.href = 'detailortu.php?username=' + encodeURIComponent(username);
  }

  // Render saat halaman dimuat
  document.addEventListener('DOMContentLoaded', renderOrtu);
</script>
<script>
  // Kirim username guru ke JS agar chat_popup.js bisa tahu mana pesan dari guru
  window.GURU_USERNAME = <?php echo json_encode($_SESSION['username']); ?>;
</script>
<script src="chat_popup.js"></script>
</script>