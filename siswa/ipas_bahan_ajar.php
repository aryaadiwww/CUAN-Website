<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'siswa') {
  header("Location: ../index.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Bahan Ajar IPAS - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="../responsive.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
       body {
      background-color: #0777d3ff;
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
      background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
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
      background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
      box-shadow: 0 2px 8px 0 rgba(255,179,71,0.04);
      transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
      position: relative;
      overflow: hidden;
    }
    .sidebar ul li:hover {
      background:  #1565C0;
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
      background: linear-gradient(135deg, #0777d3ff 0%, #1aa0ffff 100%);
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
      background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
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
      background: linear-gradient(90deg, #2196F3 0%, #2196F3 100%);
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
      background: linear-gradient(90deg, #1976D2 0%, #1976D2 100%);
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

    /* Konten Bahan Ajar */
    .bahan-ajar-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
      display: flex;
      flex-direction: column;
      align-items: stretch;
    }

    .bahan-ajar-header {
      display: flex;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }

    .bahan-ajar-icon {
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

    .bahan-ajar-icon svg {
      width: 36px;
      height: 36px;
    }

    .bahan-ajar-title {
      font-size: 2.2rem;
      font-weight: 700;
      color: #fff;
      margin: 0;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .bahan-ajar-subtitle {
      font-size: 1.1rem;
      color: rgba(255, 255, 255, 0.8);
      margin-top: 0.5rem;
    }

    .bahan-ajar-content {
      display: flex;
      flex-direction: column;
      gap: 2rem;
    }

    .bahan-ajar-empty {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 3rem 2rem;
      text-align: center;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.8s ease-out;
    }

    .bahan-ajar-empty svg {
      width: 80px;
      height: 80px;
      margin-bottom: 1.5rem;
      opacity: 0.7;
    }

    .bahan-ajar-empty-text {
      font-size: 1.4rem;
      font-weight: 600;
      color: #fff;
      margin-bottom: 0.5rem;
    }

    .bahan-ajar-empty-subtext {
      font-size: 1rem;
      color: rgba(255, 255, 255, 0.7);
      max-width: 500px;
      margin: 0 auto;
    }

    .bahan-ajar-card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      gap: 1rem;
      transition: transform 0.3s, box-shadow 0.3s;
      animation: fadeIn 0.8s ease-out;
      border-left: 4px solid #fff;
      margin-bottom: 1.5rem;
    }

    .bahan-ajar-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .bahan-ajar-card-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    .bahan-ajar-card-title {
      font-size: 1.4rem;
      font-weight: 700;
      color: #fff;
      margin: 0;
    }

    .bahan-ajar-card-date {
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.7);
      background: rgba(255, 255, 255, 0.1);
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
    }

    .bahan-ajar-card-desc {
      font-size: 1rem;
      color: rgba(255, 255, 255, 0.9);
      line-height: 1.5;
    }

    .bahan-ajar-card-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 0.5rem;
    }

    .bahan-ajar-card-teacher {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.8);
    }

    .bahan-ajar-card-teacher img {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .bahan-ajar-card-actions {
      display: flex;
      gap: 0.8rem;
    }

    .bahan-ajar-card-button {
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

    .bahan-ajar-card-button:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: scale(1.05);
    }

    .bahan-ajar-card-button svg {
      width: 16px;
      height: 16px;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media screen and (max-width: 768px) {
      .bahan-ajar-container {
        padding: 1.5rem;
      }

      .bahan-ajar-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }

      .bahan-ajar-title {
        font-size: 1.8rem;
      }

      .bahan-ajar-card-header {
        flex-direction: column;
        gap: 0.5rem;
      }

      .bahan-ajar-card-footer {
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
    
    <div class="bahan-ajar-container">
      <div class="bahan-ajar-header">
        <div class="bahan-ajar-icon">
          <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 8h8v8H8z"/></svg>
        </div>
        <div>
          <h1 class="bahan-ajar-title">Bahan Ajar IPAS</h1>
          <p class="bahan-ajar-subtitle">Materi pembelajaran yang diberikan oleh guru</p>
        </div>
      </div>
      
      <div class="bahan-ajar-content">
        <!-- Tempat render bahan ajar -->
        <div id="bahanajar-list"></div>
      </div>
    </div>
  </div>
  
  <script>
    // Render bahan ajar
    function bahanAjarCard(entry) {
      // Gunakan entry.link jika ada, fallback ke entry.filename
      let fileUrl = '';
      let isExternal = false;
      if (entry.link) {
        fileUrl = entry.link;
        isExternal = /^https?:\/\//.test(fileUrl);
      } else if (entry.filename) {
        fileUrl = '../uploads/bahan_ajar/' + entry.filename;
        isExternal = false;
      } else {
        fileUrl = '#';
        isExternal = false;
      }
      // Tombol unduh: jika eksternal, buka tab baru tanpa download, jika lokal pakai download
      let unduhBtn = isExternal
        ? `<a class="bahan-ajar-card-button" href="${fileUrl}" target="_blank" title="File akan dibuka di tab baru, silakan unduh manual jika perlu.">
            <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Unduh
          </a>`
        : `<a class="bahan-ajar-card-button" href="${fileUrl}" download>
            <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Unduh
          </a>`;
      return `<div class="bahan-ajar-card">
        <div class="bahan-ajar-card-header">
          <h3 class="bahan-ajar-card-title">${entry.title}</h3>
          <span class="bahan-ajar-card-date">${new Date(entry.date).toLocaleString('id-ID')}</span>
        </div>
        <p class="bahan-ajar-card-desc">${entry.desc}</p>
        <div class="bahan-ajar-card-footer">
          <div class="bahan-ajar-card-teacher">
            <img src="../img/profile.png" alt="Guru">
            <span>${entry.teacher}</span>
          </div>
          <div class="bahan-ajar-card-actions">
            ${unduhBtn}
            <a class="bahan-ajar-card-button" href="${fileUrl}" target="_blank">
              <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              Lihat
            </a>
          </div>
        </div>
      </div>`;
    }
    function loadBahanAjar() {
      fetch('../uploads/bahan_ajar/bahan_ajar.json?' + Date.now())
        .then(r => r.json())
        .then(data => {
          const list = document.getElementById('bahanajar-list');
          if (!data || !data.length) {
            list.innerHTML = `<div class='bahan-ajar-empty'><svg fill='none' stroke='#fff' stroke-width='1.5' viewBox='0 0 24 24'><path d='M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'/></svg><h2 class='bahan-ajar-empty-text'>Belum Ada Bahan Ajar</h2><p class='bahan-ajar-empty-subtext'>Guru belum mengunggah bahan ajar untuk mata pelajaran ini. Silakan periksa kembali nanti.</p></div>`;
          } else {
            list.innerHTML = data.map(bahanAjarCard).join('');
          }
        })
        .catch(() => {
          document.getElementById('bahanajar-list').innerHTML = `<div class='bahan-ajar-empty'><svg fill='none' stroke='#fff' stroke-width='1.5' viewBox='0 0 24 24'><path d='M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'/></svg><h2 class='bahan-ajar-empty-text'>Belum Ada Bahan Ajar</h2><p class='bahan-ajar-empty-subtext'>Gagal memuat data bahan ajar.</p></div>`;
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
      loadBahanAjar();
    });
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