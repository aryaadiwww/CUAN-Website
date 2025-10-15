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
    
    <div class="activity-container">
       <h1 class="activity-title">Aktivitas Terbaru</h1>
       
       <div class="activity-toolbar">
         <button id="refresh-activities" class="refresh-button" title="Muat ulang aktivitas">
           <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
             <path d="M23 4v6h-6"/>
             <path d="M20.49 15a9 9 0 1 1-2.12-9.36"/>
           </svg>
           Muat Ulang
         </button>
       </div>
       <div class="filter-container">
         <button class="filter-button active" data-filter="all">
           <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
             <path d="M3 6h18M3 12h18M3 18h18"/>
           </svg>
           Semua
         </button>
         <button class="filter-button" data-filter="bahanajar">
           <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
             <rect x="3" y="4" width="18" height="16" rx="2"/>
             <path d="M8 2v4M16 2v4"/>
           </svg>
           Bahan Ajar
         </button>
         <button class="filter-button" data-filter="tugas">
           <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
             <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
             <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
           </svg>
           Tugas
         </button>
         <button class="filter-button" data-filter="evaluasi">
           <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
             <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
             <path d="M22 4L12 14.01l-3-3"/>
           </svg>
           Evaluasi
         </button>
         <button class="filter-button" data-filter="diskusi">
           <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
             <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
           </svg>
           Diskusi
         </button>
         <button class="filter-button" data-filter="login">
           <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
             <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/>
           </svg>
           Login
         </button>
               </div>
       
       <div class="activity-list" id="activity-list">
          <!-- Aktivitas akan dirender oleh JavaScript -->
        </div>
     </div>
     
     <style>
  .activity-container {
    padding: 20px;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    margin: 20px;
    animation: fadeInMain 0.5s ease-in-out;
    overflow: visible;
  }
  
  .activity-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
    position: relative;
    display: inline-block;
  }
  
  .activity-title::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 40%;
    height: 4px;
    background: linear-gradient(90deg, #B83556, #e27b9d);
    border-radius: 2px;
    transition: width 0.3s ease;
  }
  
  .activity-title:hover::after {
    width: 100%;
  }
  
  .filter-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
  }
  
  .filter-button {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: #f5f5f5;
    border: none;
    border-radius: 30px;
    color: #555;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
  }
  
  .filter-button:hover {
    background-color: #eeeeee;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  .activity-toolbar {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-bottom: 10px;
  }
  .refresh-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: #B83556;
    border: none;
    border-radius: 30px;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
  }
  .refresh-button:hover {
    background-color: #a02e4b;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  
  .filter-button.active {
    background-color: #B83556;
    color: white;
  }
  
  .filter-button svg {
    transition: transform 0.3s ease;
  }
  
  .filter-button:hover svg {
    transform: scale(1.2);
  }
  
  .activity-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    max-height: none;
    overflow: visible;
    scrollbar-width: thin;
    scrollbar-color: #B83556 #f5f5f5;
  }
  .activity-list::-webkit-scrollbar {
    width: 8px;
    background: #f5f5f5;
    border-radius: 8px;
  }
  .activity-list::-webkit-scrollbar-thumb {
    background: #B83556;
    border-radius: 8px;
  }
  
  .activity-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 12px;
    transition: all 0.3s ease;
    border-left: 4px solid #ddd;
    animation: slideIn 0.5s ease-out forwards;
    opacity: 0;
    transform: translateX(-20px);
  }
  
  @keyframes slideIn {
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  .activity-item:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    transform: translateY(-3px);
  }
  
  .activity-item.tugas {
    border-left-color: #4CAF50;
  }
  
  .activity-item.evaluasi {
    border-left-color: #2196F3;
  }
  
  .activity-item.diskusi {
    border-left-color: #FF9800;
  }
  
  .activity-item.login {
    border-left-color: #9C27B0;
  }
  .activity-item.bahanajar {
    border-left-color: #673AB7;
  }
  
  .activity-avatar {
    position: relative;
  }
  
  .activity-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
  }
  
  .activity-item:hover .activity-avatar img {
    transform: scale(1.1);
  }
  
  .notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 20px;
    height: 20px;
    background-color: #B83556;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
    animation: pulse 1.5s infinite;
  }
  
  @keyframes pulse {
    0% {
      transform: scale(1);
      box-shadow: 0 0 0 0 rgba(184, 53, 86, 0.7);
    }
    70% {
      transform: scale(1.1);
      box-shadow: 0 0 0 10px rgba(184, 53, 86, 0);
    }
    100% {
      transform: scale(1);
      box-shadow: 0 0 0 0 rgba(184, 53, 86, 0);
    }
  }
  
  .activity-content {
    flex: 1;
  }
  
  .activity-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
  }
  
  .activity-name {
    font-weight: 600;
    color: #333;
  }
  
  .activity-time {
    font-size: 0.85rem;
    color: #888;
  }
  
  .activity-description {
    margin-bottom: 10px;
    color: #555;
    line-height: 1.4;
  }
  
  .activity-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .activity-category {
    font-size: 0.8rem;
    padding: 3px 10px;
    border-radius: 20px;
    font-weight: 500;
  }
  
  .category-tugas {
    background-color: rgba(76, 175, 80, 0.1);
    color: #4CAF50;
  }
  
  .category-evaluasi {
    background-color: rgba(33, 150, 243, 0.1);
    color: #2196F3;
  }
  
  .category-diskusi {
    background-color: rgba(255, 152, 0, 0.1);
    color: #FF9800;
  }
  
  .category-login {
    background-color: rgba(156, 39, 176, 0.1);
    color: #9C27B0;
  }
  .category-bahanajar {
    background-color: rgba(103, 58, 183, 0.1);
    color: #673AB7;
  }
  
  .activity-action {
    display: flex;
    gap: 10px;
  }
  
  .action-button {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 20px;
    font-size: 0.8rem;
    color: #555;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  
  .action-button:hover {
    background-color: #f0f0f0;
    transform: translateY(-2px);
  }
  .action-button.danger { border-color: #e57373; color: #c62828; }
  .action-button.danger:hover { background-color: #ffebee; }
  
  .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 0;
    color: #888;
    text-align: center;
  }
  
  .empty-state svg {
    width: 60px;
    height: 60px;
    margin-bottom: 15px;
    color: #ccc;
  }
  
  /* Responsive adjustments */
  @media (max-width: 768px) {
    .activity-list {
      max-height: none;
    }
    .activity-container {
      margin: 10px;
      padding: 15px;
    }
    
    .filter-container {
      overflow-x: auto;
      padding-bottom: 10px;
      flex-wrap: nowrap;
    }
    
    .activity-item {
      padding: 12px;
    }
    
    .activity-avatar img {
      width: 40px;
      height: 40px;
    }
  }
  
  @media (max-width: 480px) {
    .activity-list {
      max-height: none;
    }
    .activity-header {
      flex-direction: column;
    }
    
    .activity-time {
      font-size: 0.75rem;
    }
    
    .activity-meta {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
    }
    
    .activity-action {
      width: 100%;
      justify-content: flex-start;
      margin-top: 5px;
    }
  }
    </style>
  <script>
    // Ambil data aktivitas terbaru dari API
    let activities = [];
    let activitiesLoaded = false;

    function fetchActivities(callback) {
      fetch('../api/aktivitas_terbaru.php?_ts=' + Date.now(), { cache: 'no-store' })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            activities = data.data.map(item => {
              let description = '';
              if (item.kategori === 'tugas') {
                if (item.peran === 'guru' || item.aksi === 'buat') {
                  description = `Membuat tugas: <b>${item.judul}</b>`;
                } else {
                  description = `Mengumpulkan tugas: <b>${item.judul}</b>${item.nilai !== null ? ` (Nilai: <b>${item.nilai}</b>)` : ''}`;
                }
                if (item.file) {
                  description += `<br>File: <a href='../uploads/bahan_ajar/${item.file}' target='_blank'>${item.file}</a>`;
                }
                if (item.link) {
                  description += `<br>Link: <a href='${item.link}' target='_blank'>${item.link}</a>`;
                }
              } else if (item.kategori === 'evaluasi') {
                if (item.peran === 'guru' || item.aksi === 'buat') {
                  description = `Membuat evaluasi: <b>${item.judul}</b>`;
                } else {
                  description = `Menyelesaikan evaluasi: <b>${item.judul}</b>${item.nilai !== null ? ` (Nilai: <b>${item.nilai}</b>)` : ''}`;
                }
                if (item.file) {
                  description += `<br>File: <a href='../uploads/bahan_ajar/${item.file}' target='_blank'>${item.file}</a>`;
                }
                if (item.link) {
                  description += `<br>Link: <a href='${item.link}' target='_blank'>${item.link}</a>`;
                }
              } else if (item.kategori === 'diskusi') {
                description = `Diskusi: <b>${item.judul}</b>`;
              } else if (item.kategori === 'bahanajar') {
                description = `Upload bahan ajar: <b>${item.judul}</b>`;
                if (item.file) {
                  description += `<br>File: <a href='../uploads/bahan_ajar/${item.file}' target='_blank'>${item.file}</a>`;
                }
                if (item.link) {
                  description += `<br>Link: <a href='${item.link}' target='_blank'>${item.link}</a>`;
                }
              } else if (item.kategori === 'login') {
                description = `Login oleh: <b>${item.nama}</b>`;
              }
              return {
                name: item.nama,
                username: item.username,
                category: item.kategori,
                title: item.judul || '',
                rawTime: item.waktu || '',
                description: description,
                time: formatWaktu(item.waktu),
                link: item.link || null,
                isNew: false,
                key: `${item.kategori}|${item.username}|${item.waktu}|${item.judul}`
              };
            });
            activitiesLoaded = true;
            if (typeof callback === 'function') callback();
          }
        })
        .catch(() => {
          activities = [];
          activitiesLoaded = true;
          if (typeof callback === 'function') callback();
        });
    }

    // Format waktu ke tampilan lokal
    function formatWaktu(waktu) {
      const date = new Date(waktu.replace(' ', 'T'));
      if (isNaN(date.getTime())) return waktu;
      const now = new Date();
      const diff = (now - date) / 1000;
      if (diff < 60 * 60 * 24 && now.getDate() === date.getDate()) {
        return `Hari ini, ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
      } else if (diff < 60 * 60 * 48 && now.getDate() - date.getDate() === 1) {
        return `Kemarin, ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
      } else {
        return `${date.getDate().toString().padStart(2, '0')}-${(date.getMonth()+1).toString().padStart(2, '0')}-${date.getFullYear()}, ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
      }
    }

    // Render aktivitas
    function renderActivities(filterType = 'all') {
      const activityList = document.getElementById('activity-list');
      activityList.innerHTML = '';
      if (!activitiesLoaded) {
        activityList.innerHTML = '<div class="empty-state"><p>Memuat data aktivitas...</p></div>';
        return;
      }
      const filteredActivities = filterType === 'all' 
        ? activities 
        : activities.filter(activity => activity.category === filterType);
      const hiddenKeys = loadHiddenActivities();
      const dataToRender = filteredActivities.filter(a => !hiddenKeys.has(a.key));
      if (dataToRender.length === 0) {
        activityList.innerHTML = `
          <div class="empty-state">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10"/>
              <path d="M8 15h8M9 9h.01M15 9h.01"/>
            </svg>
            <p>Tidak ada aktivitas ${filterType !== 'all' ? 'dalam kategori ini' : ''}</p>
          </div>
        `;
        return;
      }
      dataToRender.forEach((activity, index) => {
        const activityItem = document.createElement('div');
        activityItem.className = `activity-item ${activity.category}`;
        activityItem.style.animationDelay = `${index * 0.1}s`;
        let actionButtons = '';
        switch(activity.category) {
          case 'tugas':
            actionButtons = `
              <button class="action-button" onclick="location.href='ipas_tugas.php'">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <path d="M12 5v14M5 12h14"/>
                </svg>
                Detail
              </button>
              <button class="action-button danger" onclick="deleteActivity('${activity.key}')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  <path d="M10 11v6M14 11v6"/>
                  <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/>
                </svg>
                Hapus
              </button>
            `;
            break;
          case 'evaluasi':
            actionButtons = `
              <button class="action-button" onclick="location.href='ipas_evaluasi.php'">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
                Lihat Nilai
              </button>
              <button class="action-button danger" onclick="deleteActivity('${activity.key}')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  <path d="M10 11v6M14 11v6"/>
                  <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/>
                </svg>
                Hapus
              </button>
            `;
            break;
          case 'diskusi':
            actionButtons = `
              <button class="action-button" onclick="location.href='diskusi.php'">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                </svg>
                Balas
              </button>
              <button class="action-button danger" onclick="deleteActivity('${activity.key}')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  <path d="M10 11v6M14 11v6"/>
                  <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/>
                </svg>
                Hapus
              </button>
            `;
            break;
          case 'bahanajar':
            actionButtons = `
              <button class="action-button" onclick="location.href='ipas_bahan_ajar.php'">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <rect x="3" y="4" width="18" height="16" rx="2"/>
                  <path d="M8 2v4M16 2v4"/>
                </svg>
                Lihat Bahan
              </button>
              <button class="action-button danger" onclick="deleteActivity('${activity.key}')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  <path d="M10 11v6M14 11v6"/>
                  <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/>
                </svg>
                Hapus
              </button>
            `;
            break;
          case 'login':
            actionButtons = `
              <button class="action-button">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="10"/>
                  <path d="M12 16v-4M12 8h.01"/>
                </svg>
                Info
              </button>
              <button class="action-button danger" onclick="deleteActivity('${activity.key}')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  <path d="M10 11v6M14 11v6"/>
                  <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/>
                </svg>
                Hapus
              </button>
            `;
            break;
          default:
            actionButtons = `
              <button class="action-button">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="10"/>
                  <path d="M12 16v-4M12 8h.01"/>
                </svg>
                Info
              </button>
              <button class="action-button danger" onclick="deleteActivity('${activity.key}')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  <path d="M10 11v6M14 11v6"/>
                  <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/>
                </svg>
                Hapus
              </button>
            `;
        }
        activityItem.innerHTML = `
          <div class="activity-avatar">
            <img src="../img/profile.png" alt="${activity.name}">
            ${activity.isNew ? '<span class="notification-badge">!</span>' : ''}
          </div>
          <div class="activity-content">
            <div class="activity-header">
              <div class="activity-name">${activity.name}</div>
              <div class="activity-time">${activity.time}</div>
            </div>
            <div class="activity-description">${activity.description}</div>
            <div class="activity-meta">
              <span class="activity-category category-${activity.category}">${getCategoryName(activity.category)}</span>
              <div class="activity-action">
                ${actionButtons}
              </div>
            </div>
          </div>
        `;
        activityList.appendChild(activityItem);
      });
    }

    // Mendapatkan nama kategori dalam bahasa Indonesia
    function getCategoryName(category) {
      switch(category) {
        case 'tugas': return 'Tugas';
        case 'evaluasi': return 'Evaluasi';
        case 'diskusi': return 'Diskusi';
        case 'bahanajar': return 'Bahan Ajar';
        case 'login': return 'Login';
        default: return category;
      }
    }

    // Hidden activities helpers (local to this page only)
    function loadHiddenActivities() {
      try {
        const raw = localStorage.getItem('hidden_activities_guru');
        const arr = raw ? JSON.parse(raw) : [];
        return new Set(Array.isArray(arr) ? arr : []);
      } catch (e) { return new Set(); }
    }
    function saveHiddenActivities(set) {
      try { localStorage.setItem('hidden_activities_guru', JSON.stringify(Array.from(set))); } catch (e) {}
    }
    function deleteActivity(key) {
      if (!key) return;
      if (!confirm('Hapus riwayat aktivitas ini dari tampilan?')) return;
      const s = loadHiddenActivities();
      s.add(key);
      saveHiddenActivities(s);
      const activeBtn = document.querySelector('.filter-button.active');
      const filterType = activeBtn ? activeBtn.getAttribute('data-filter') : 'all';
      renderActivities(filterType);
    }

    // Filter aktivitas
    function setupFilters() {
      const filterButtons = document.querySelectorAll('.filter-button');
      filterButtons.forEach(button => {
        button.addEventListener('click', function() {
          filterButtons.forEach(btn => btn.classList.remove('active'));
          this.classList.add('active');
          const filterType = this.getAttribute('data-filter');
          renderActivities(filterType);
        });
      });
    }

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
      function loadActivitiesRealtime() {
        fetchActivities(() => {
          renderActivities();
        });
      }
      loadActivitiesRealtime();
      setupFilters();
      updateSidebarArrow();
      const refreshBtn = document.getElementById('refresh-activities');
      if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
          const activeBtn = document.querySelector('.filter-button.active');
          const filterType = activeBtn ? activeBtn.getAttribute('data-filter') : 'all';
          fetchActivities(() => {
            renderActivities(filterType);
          });
        });
      }
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
        // Panah kiri (masuk)
        arrow.innerHTML = '<path d="M16 5l-8 7 8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      } else {
        // Panah kanan (keluar)
        arrow.innerHTML = '<path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      }
    }

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