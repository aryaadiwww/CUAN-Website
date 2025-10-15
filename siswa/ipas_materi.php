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
  <title>Jadwal Mata Pelajaran - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="../responsive.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    body {
      background-color: #B83556;
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
      font-size: 1rem;
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
      background: linear-gradient(135deg, #B83556 0%, #DC97A5 100%);
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
      background-color: #a82747ff;
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
      color: #B83556;
      margin-right: 1rem;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 8px #ffb34733;
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
      color: #ff5e62;
      box-shadow: 0 4px 16px #ffb34755;
    }
    .profile-menu {
      position: relative;
      display: flex;
      align-items: center;
    }
    .profile-button {
      background: linear-gradient(90deg, #dc97a5b2 0%, #dc97a5b2 100%);
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
    .ipas-materi-container {
      max-width: 100vw;
      margin: 0;
      padding: 2.5rem 0.5vw 2rem 0.5vw;
      display: flex;
      flex-direction: column;
      align-items: stretch;
    }
    .ipas-title {
      font-size: 2.3rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 1rem;
      letter-spacing: 1px;
      text-shadow: 0 2px 12px #b8355633;
      text-align: center;
    }
    .ipas-materi-grid {
      display: flex;
      flex-direction: column;
      gap: 2.2rem;
      width: 100%;
      margin-top: 1.2rem;
      margin-bottom: 1.2rem;
    }
    .ipas-materi-card {
      background: linear-gradient(135deg, var(--color1), var(--color2));
      border-radius: 22px;
      box-shadow: 0 6px 32px #2222a033, 0 2px 12px #2222a033;
      display: flex;
      flex-direction: row;
      align-items: center;
      padding: 1.5rem 3vw 1.5rem 3vw;
      transition: box-shadow 0.3s, transform 0.2s;
      cursor: pointer;
      min-height: 140px;
      min-width: 0;
      position: relative;
      overflow: hidden;
      animation: fadeInSmooth 1.1s cubic-bezier(.68,-0.55,.27,1.55);
      width: 99vw;
      max-width: 99vw;
      margin: 0 auto;
    }
    .ipas-materi-card:hover {
      box-shadow: 0 12px 36px #2222a055, 0 4px 16px #2222a033;
    }
    .ipas-materi-icon {
      flex-shrink: 0;
      width: 100px;
      height: 100px;
      margin-right: 2.2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(255,255,255,0.18);
      border-radius: 16px;
      box-shadow: 0 2px 8px #fff3;
    }
    .ipas-materi-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      min-width: 0;
    }
    .ipas-materi-title {
      font-size: 1.6rem;
      font-weight: 800;
      color: #fff;
      margin-bottom: 0.4rem;
      letter-spacing: 0.5px;
      text-shadow: 0 2px 8px #2222a033;
      line-height: 1.1;
    }
    .ipas-materi-desc {
      font-size: 1.08rem;
      color: #fff;
      font-weight: 500;
      margin-bottom: 1.1rem;
      opacity: 0.93;
    }
    .ipas-materi-actions {
      display: flex;
      gap: 0.7rem;
      margin-top: auto;
      width: 100%;
      justify-content: flex-start;
      flex-wrap: wrap;
      position: relative;
      min-height: 48px;
    }
    .ipas-action-card.diskusi {
      position: absolute;
      right: 6rem;
      margin: 0;
      z-index: 2;
      background: linear-gradient(135deg, #ffffffff 60%, #ffffffff 100%) !important;
      box-shadow: 0 2px 12px #43e97b55;
      border: #B83556 2px solid;
    }
    .ipas-action-card {
      background: linear-gradient(135deg, #fffbe7 60%, #ffe0ec 100%);
      color: #B83556;
      border-radius: 12px;
      box-shadow: 0 2px 8px #b8355633;
      padding: 0.5rem 1.1rem 0.5rem 0.7rem;
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 0.3rem;
      margin-right: 0.3rem;
      cursor: pointer;
      transition: background 0.2s, color 0.2s, transform 0.2s;
      border: none;
      outline: none;
      display: flex;
      align-items: center;
      min-width: 110px;
      text-align: left;
      animation: fadeInSmooth 1.2s cubic-bezier(.68,-0.55,.27,1.55);
      gap: 0.6rem;
      text-decoration: underline;
    }
    .ipas-action-card.disabled {
      pointer-events: none;
      opacity: 0.5;
      color: #888;
      background: #eee;
      text-decoration: none;
      cursor: default;
    }
    .ipas-action-card svg {
      width: 20px;
      height: 20px;
      margin-right: 0.2rem;
      flex-shrink: 0;
    }
    .ipas-action-card:hover {
      background: linear-gradient(135deg, #ffe0ec 60%, #fffbe7 100%);
      color: #a82747;
      transform: scale(1.07);
    }
    @keyframes fadeInSmooth {
      0% { opacity: 0; transform: translateY(30px) scale(0.98); }
      100% { opacity: 1; transform: none; }
    }
    @media screen and (max-width: 900px) {
      .ipa-materi-grid { grid-template-columns: 1fr 1fr; gap: 1.2rem; }
      .ipa-materi-card { min-height: 120px; padding: 1rem 0.7rem; }
      .ipa-materi-title { font-size: 1.1rem; }
      .ipa-materi-desc { font-size: 0.9rem; }
    }
    @media screen and (max-width: 600px) {
      .ipa-materi-grid { grid-template-columns: 1fr; gap: 1rem; }
      .ipa-materi-card { min-height: 90px; padding: 0.7rem 0.5rem; }
      .ipa-materi-title { font-size: 1rem; }
      .ipa-materi-desc { font-size: 0.8rem; }
      .ipa-action-card { font-size: 0.9rem; min-width: 70px; }
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
    <div class="ipas-materi-container">
      <div class="ipas-title">Materi IPAS</div>
      <div class="ipas-materi-grid">
        <div class="ipas-materi-card" style="--color1:#6A4C93;--color2:#B983FF;">
          <div class="ipas-materi-icon">
            <!-- Bone icon -->
            <svg width="44" height="44" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 5a3.001 3.001 0 0 0-5.197-2.118l-7.68 7.68A3.001 3.001 0 1 0 7.44 15.88l7.68-7.68A3.001 3.001 0 1 0 19 5z"/></svg>
          </div>
          <div class="ipas-materi-content">
            <div class="ipas-materi-title">Rangka</div>
            <div class="ipas-materi-desc">Mengenal struktur dan fungsi rangka pada manusia dan hewan.</div>
            <div class="ipas-materi-actions">
              <span class="ipas-action-card disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 8h8v8H8z"/></svg>Bahan Ajar</span>
              <span class="ipas-action-card disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h10v10H7z"/><path d="M7 7l10 10"/></svg>Media Pembelajaran</span>
              <span class="ipas-action-card disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>Tugas</span>
              <span class="ipas-action-card disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>Evaluasi</span>
              <span class="ipas-action-card diskusi disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2z"/></svg>Diskusi</span>
            </div>
          </div>
        </div>
        <div class="ipas-materi-card" style="--color1:#43E97B;--color2:#38F9D7;">
          <div class="ipas-materi-icon">
            <!-- Muscle/arm icon -->
            <svg width="44" height="44" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 8c0-2.21-1.79-4-4-4s-4 1.79-4 4c0 2.21 1.79 4 4 4s4-1.79 4-4z"/><path d="M7 8v8a4 4 0 0 0 8 0V8"/></svg>
          </div>
          <div class="ipas-materi-content">
            <div class="ipas-materi-title">Sendi & Otot</div>
            <div class="ipas-materi-desc">Belajar tentang sendi, otot, dan pergerakan tubuh.</div>
            <div class="ipas-materi-actions">
              <a class="ipas-action-card" href="ipas_bahan_ajar.php"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 8h8v8H8z"/></svg>Bahan Ajar</a>
              <a class="ipas-action-card" href="ipas_mediapembelajaran.php"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h10v10H7z"/><path d="M7 7l10 10"/></svg>Media Pembelajaran</a>
              <a class="ipas-action-card" href="ipas_tugas.php"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>Tugas</a>
              <a class="ipas-action-card" href="ipas_evaluasi.php"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>Evaluasi</a>
              <a class="ipas-action-card diskusi" href="ipas_diskusi.php"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2z"/></svg>Diskusi</a>
            </div>
          </div>
        </div>
        <div class="ipas-materi-card" style="--color1:#F9A826;--color2:#FEE440;">
          <div class="ipas-materi-icon">
            <!-- Shield/defense icon -->
            <svg width="44" height="44" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 3l7 4v5c0 5.25-3.5 10-7 10S5 17.25 5 12V7l7-4z"/></svg>
          </div>
          <div class="ipas-materi-content">
            <div class="ipas-materi-title">Macam-macam Perlawanan</div>
            <div class="ipas-materi-desc">Memahami berbagai bentuk perlawanan tubuh terhadap penyakit.</div>
            <div class="ipas-materi-actions">
              <span class="ipas-action-card disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 8h8v8H8z"/></svg>Bahan Ajar</span>
              <span class="ipas-action-card disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h10v10H7z"/><path d="M7 7l10 10"/></svg>Media Pembelajaran</span>
              <span class="ipas-action-card disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>Tugas</span>
              <span class="ipas-action-card disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>Evaluasi</span>
              <span class="ipas-action-card diskusi disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2z"/></svg>Diskusi</span>
            </div>
          </div>
        </div>
        <div class="ipas-materi-card" style="--color1:#009FFD;--color2:#2A2A72;">
          <div class="ipas-materi-icon">
            <!-- Globe/world icon -->
            <svg width="44" height="44" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 0 20M12 2a15.3 15.3 0 0 0 0 20"/></svg>
          </div>
          <div class="ipas-materi-content">
            <div class="ipas-materi-title">Benua-benua di Dunia</div>
            <div class="ipas-materi-desc">Menjelajahi benua-benua di dunia beserta ciri khasnya.</div>
            <div class="ipas-materi-actions">
              <span class="ipas-action-card disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 8h8v8H8z"/></svg>Bahan Ajar</span>
              <span class="ipas-action-card disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h10v10H7z"/><path d="M7 7l10 10"/></svg>Media Pembelajaran</span>
              <span class="ipas-action-card disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>Tugas</span>
              <span class="ipas-action-card disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>Evaluasi</span>
              <span class="ipas-action-card diskusi disabled"><svg fill="none" stroke="#B83556" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2z"/></svg>Diskusi</span>
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
