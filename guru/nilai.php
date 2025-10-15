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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer"/>
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
        transition: margin-left 0.4s cubic-bezier(.68,-0.55,.27,1.55);
      }
      .sidebar.open ~ .main-content {
        margin-left: 120px;
      }
      header {
        padding: 1rem 1rem 1rem 1.5rem;
        height: 70px;
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
        transition: margin-left 0.4s cubic-bezier(.68,-0.55,.27,1.55);
      }
      .sidebar.open ~ .main-content {
        margin-left: 120px;
      }
      header {
        height: 60px;
      }
    }
    .dashboard-chart {
      padding: 1.5rem 0.5rem 0.5rem 0.5rem; /* tambah padding atas */
      position: relative;
      overflow: visible; /* biar label keluar card tetap terlihat */
    }
    
    
    /* Nilai Container Styles */
    .nilai-container {
      padding: 2rem;
      max-width: 1200px;
      margin: 0 auto;
      width: 100%;
    }
    
    .nilai-title {
      font-size: 2.2rem;
      font-weight: 800;
      color: #fff;
      margin-bottom: 1.5rem;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    
    .subject-selector {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-bottom: 2rem;
      flex-wrap: wrap;
    }
    
    .subject-button {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      color: #fff;
      padding: 0.7rem 1.5rem;
      border-radius: 50px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .subject-button:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: translateY(-3px);
    }
    
    .subject-button.active {
      background: #fff;
      color: #B83556;
      box-shadow: 0 4px 12px rgba(184, 53, 86, 0.3);
    }
    
    .nilai-table-container {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      overflow-x: auto;
    }
    
    .nilai-table {
      width: 100%;
      border-collapse: collapse;
    }
    
    .nilai-table th {
      background: #B83556;
      color: #fff;
      padding: 1rem;
      text-align: center;
      position: sticky;
      top: 0;
      z-index: 10;
    }
    
    .nilai-table th:first-child {
      text-align: left;
      border-top-left-radius: 8px;
      border-bottom-left-radius: 8px;
    }
    
    .nilai-table th:last-child {
      border-top-right-radius: 8px;
      border-bottom-right-radius: 8px;
    }
    
    .nilai-table td {
      padding: 0.8rem 1rem;
      text-align: center;
      border-bottom: 1px solid #eee;
    }
    
    .nilai-table td:first-child {
      text-align: left;
      font-weight: 600;
      color: #333;
    }
    
    .nilai-table tr:hover {
      background: rgba(220, 151, 165, 0.1);
    }
    
    .nilai-table tr:last-child td {
      border-bottom: none;
    }
    
    .grade-a {
      color: #38B000;
      font-weight: 700;
    }
    
    .grade-b {
      color: #2D7DD2;
      font-weight: 700;
    }
    
    .grade-c {
      color: #F9A826;
      font-weight: 700;
    }
    
    .grade-d {
      color: #FF5E5B;
      font-weight: 700;
    }
    
    .summary-row {
      background: rgba(184, 53, 86, 0.05);
      font-weight: 600;
    }
    
    .summary-row td {
      border-top: 2px solid #B83556;
    }
    
    @media screen and (max-width: 900px) {
      .nilai-container {
        padding: 1.5rem;
      }
      
      .nilai-title {
        font-size: 1.8rem;
      }
      
      .subject-button {
        padding: 0.6rem 1.2rem;
        font-size: 0.9rem;
      }
      
      header {
        height: 70px;
      }
    }
    
    @media screen and (max-width: 600px) {
      .nilai-container {
        padding: 1rem;
      }
      
      .nilai-title {
        font-size: 1.5rem;
      }
      
      .subject-button {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
      }
      
      .nilai-table-container {
        padding: 1rem;
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
    
    <div class="nilai-container">
      <div class="nilai-title">Data Nilai Siswa</div>
      
      <div class="subject-selector">
        <button class="subject-button active" data-subject="ipas">IPAS</button>
        <button class="subject-button" data-subject="matematika">Matematika</button>
        <button class="subject-button" data-subject="bindonesia">B. Indonesia</button>
        <button class="subject-button" data-subject="pai">PAI</button>
        <button class="subject-button" data-subject="ppkn">PPKN</button>
        <button class="subject-button" data-subject="olahraga">Olahraga</button>
      </div>
      
      <div class="nilai-table-container">
        <table class="nilai-table" id="nilai-table">
          <thead>
            <tr>
              <th>Nama Siswa</th>
              <th>Tugas</th>
              <th>Evaluasi</th>
              <th>UTS</th>
              <th>UAS</th>
              <th>Rata-rata</th>
              <th>Grade</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data nilai akan diisi oleh JavaScript -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    // Toggle sidebar
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('open');
      
      const sidebarArrow = document.getElementById('sidebarArrow');
      if (sidebar.classList.contains('open')) {
        sidebarArrow.innerHTML = '<path d="M16 5l-8 7 8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      } else {
        sidebarArrow.innerHTML = '<path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      }
    }
    
    // Toggle dropdown
    function toggleDropdown() {
      const dropdown = document.getElementById('dropdown');
      dropdown.classList.toggle('open');
    }
    // Close dropdown when clicking outside
    window.addEventListener('click', function(event) {
      const dropdown = document.getElementById('dropdown');
      const profileButton = document.querySelector('.profile-button');
      if (!profileButton.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.remove('open');
      }
    });
    
    // Fungsi untuk menentukan grade berdasarkan nilai rata-rata
    function getGrade(average) {
      if (average < 60) return '<span class="grade-d">D</span>';
      if (average >= 61 && average <= 70) return '<span class="grade-c">C</span>';
      if (average >= 71 && average <= 85) return '<span class="grade-b">B</span>';
      if (average > 86) return '<span class="grade-a">A</span>';
      return '-';
    }

    // Ambil data nilai dari API dan tampilkan di tabel
    async function displayScores(subject) {
      const table = document.getElementById('nilai-table');
      const thead = table.querySelector('thead');
      const tbody = table.querySelector('tbody');
      tbody.innerHTML = '<tr><td colspan="100">Memuat data...</td></tr>';
      try {
        // Ambil seluruh data siswa
        const siswaRes = await fetch('../api/daftar_siswa_api.php');
        const siswaJson = await siswaRes.json();
        const siswaList = siswaJson.status === 'success' ? siswaJson.data : [];

        // Ambil master tugas dan evaluasi (mengacu pada sumber data yang digunakan halaman guru)
        const tugasMasterRes = await fetch('../api/tugas_api.php');
        const tugasMaster = await tugasMasterRes.json();
        let tugasList = [];
        let tugasKeys = [];
        if (subject === 'ipas') {
          tugasList = Array.isArray(tugasMaster) ? tugasMaster.filter(t => !t.mapel || t.mapel === 'ipas') : [];
          tugasKeys = tugasList.map(t => t.id);
        }

        const evalMasterRes = await fetch('../api/evaluasi_api.php');
        const evalMaster = await evalMasterRes.json();
        let evalList = [];
        let evalKeys = [];
        if (subject === 'ipas') {
          evalList = Array.isArray(evalMaster) ? evalMaster.filter(e => !e.mapel || e.mapel === 'ipas') : [];
          evalKeys = evalList.map(e => e.id);
        }

        // Ambil nilai dari data submissions tugas & evaluasi
        const tugasSubRes = await fetch('../tugas_submissions.json');
        const tugasSubs = tugasSubRes.ok ? await tugasSubRes.json() : [];
        const evalSubRes = await fetch('../evaluasi_submissions.json');
        const evalSubs = evalSubRes.ok ? await evalSubRes.json() : [];

        // Buat header dinamis
        let headerHtml = '<tr><th>Nama Siswa</th>';
        tugasKeys.forEach((k, i) => {
          headerHtml += `<th>Tugas ${i+1}</th>`;
        });
        headerHtml += '<th>UTS</th><th>UAS</th>';
        evalKeys.forEach((k, i) => {
          headerHtml += `<th>Evaluasi ${i+1}</th>`;
        });
        headerHtml += '<th>Rata-rata</th><th>Grade</th></tr>';
        thead.innerHTML = headerHtml;

        // Gabungkan data nilai untuk seluruh siswa dari submissions
        const siswaMap = {};
        siswaList.forEach(siswa => {
          siswaMap[siswa.username] = { nama: siswa.nama, tugas: {}, evaluasi: {}, uts: null, uas: null };
        });

        // Map nilai tugas berdasarkan tugas_id dan siswa_id (hanya untuk IPAS)
        if (subject === 'ipas' && Array.isArray(tugasSubs)) {
          tugasSubs.forEach(s => {
            const sid = s.siswa_id;
            const tid = s.tugas_id;
            if (sid && tid && siswaMap[sid]) {
              if (s.nilai !== undefined && s.nilai !== null && s.nilai !== '') {
                siswaMap[sid].tugas[tid] = Number(s.nilai);
              }
            }
          });
        }

        // Map nilai evaluasi berdasarkan evaluasi_id dan siswa_id (hanya untuk IPAS)
        if (subject === 'ipas' && Array.isArray(evalSubs)) {
          evalSubs.forEach(s => {
            const sid = s.siswa_id;
            const eid = s.evaluasi_id;
            if (sid && eid && siswaMap[sid]) {
              if (s.nilai !== undefined && s.nilai !== null && s.nilai !== '') {
                siswaMap[sid].evaluasi[eid] = Number(s.nilai);
              }
            }
          });
        }

        // Tampilkan data siswa
        tbody.innerHTML = '';
        let totalTugasArr = Array(tugasKeys.length).fill(0);
        let totalEvalArr = Array(evalKeys.length).fill(0);
        let totalUTS = 0, totalUAS = 0, totalAverage = 0, count = 0;
        siswaList.forEach(siswa => {
          const dataSiswa = siswaMap[siswa.username] || { nama: siswa.nama, tugas: {}, evaluasi: {}, uts: null, uas: null };
          const tr = document.createElement('tr');
          // Nama
          const tdName = document.createElement('td');
          tdName.textContent = siswa.nama;
          tr.appendChild(tdName);
          // Tugas dinamis
          let tugasSum = 0, tugasCount = 0;
          tugasKeys.forEach((k, idx) => {
            const tdTugas = document.createElement('td');
            const nilaiTugas = dataSiswa.tugas[k] ? Number(dataSiswa.tugas[k]) : 0;
            tdTugas.textContent = dataSiswa.tugas[k] !== undefined ? dataSiswa.tugas[k] : '-';
            tr.appendChild(tdTugas);
            if (dataSiswa.tugas[k] !== undefined) {
              tugasSum += nilaiTugas;
              tugasCount++;
              totalTugasArr[idx] += nilaiTugas;
            }
          });
          // UTS
          const uts = Number(dataSiswa.uts) || 0;
          const tdUTS = document.createElement('td');
          tdUTS.textContent = dataSiswa.uts !== null ? dataSiswa.uts : '-';
          tr.appendChild(tdUTS);
          // UAS
          const uas = Number(dataSiswa.uas) || 0;
          const tdUAS = document.createElement('td');
          tdUAS.textContent = dataSiswa.uas !== null ? dataSiswa.uas : '-';
          tr.appendChild(tdUAS);
          // Evaluasi dinamis
          let evalSum = 0, evalCount = 0;
          evalKeys.forEach((k, idx) => {
            const tdEval = document.createElement('td');
            const nilaiEval = dataSiswa.evaluasi[k] ? Number(dataSiswa.evaluasi[k]) : 0;
            tdEval.textContent = dataSiswa.evaluasi[k] !== undefined ? dataSiswa.evaluasi[k] : '-';
            tr.appendChild(tdEval);
            if (dataSiswa.evaluasi[k] !== undefined) {
              evalSum += nilaiEval;
              evalCount++;
              totalEvalArr[idx] += nilaiEval;
            }
          });
          // Rata-rata
          let totalNilai = tugasSum + evalSum + uts + uas;
          let totalKomponen = tugasCount + evalCount + (dataSiswa.uts !== null ? 1 : 0) + (dataSiswa.uas !== null ? 1 : 0);
          let average = totalKomponen > 0 ? Math.round(totalNilai / totalKomponen) : 0;
          const tdAverage = document.createElement('td');
          tdAverage.textContent = totalKomponen > 0 ? average : '-';
          tr.appendChild(tdAverage);
          // Grade
          const tdGrade = document.createElement('td');
          tdGrade.innerHTML = totalKomponen > 0 ? getGrade(average) : '-';
          tr.appendChild(tdGrade);
          tbody.appendChild(tr);
          if (tugasCount > 0 || evalCount > 0 || uts || uas) {
            totalUTS += uts;
            totalUAS += uas;
            totalAverage += average;
            count++;
          }
        });
        // Baris ringkasan rata-rata kelas
        if (count > 0) {
          const trSummary = document.createElement('tr');
          trSummary.className = 'summary-row';
          const tdSummary = document.createElement('td');
          tdSummary.textContent = 'Rata-rata Kelas';
          trSummary.appendChild(tdSummary);
          // Rata-rata tugas per kolom
          tugasKeys.forEach((k, idx) => {
            const tdAvgTugas = document.createElement('td');
            tdAvgTugas.textContent = Math.round(totalTugasArr[idx] / count);
            trSummary.appendChild(tdAvgTugas);
          });
          // Rata-rata UTS
          const tdAvgUTS = document.createElement('td');
          tdAvgUTS.textContent = Math.round(totalUTS / count);
          trSummary.appendChild(tdAvgUTS);
          // Rata-rata UAS
          const tdAvgUAS = document.createElement('td');
          tdAvgUAS.textContent = Math.round(totalUAS / count);
          trSummary.appendChild(tdAvgUAS);
          // Rata-rata evaluasi per kolom
          evalKeys.forEach((k, idx) => {
            const tdAvgEval = document.createElement('td');
            tdAvgEval.textContent = Math.round(totalEvalArr[idx] / count);
            trSummary.appendChild(tdAvgEval);
          });
          // Rata-rata kelas keseluruhan
          const avgClass = Math.round(totalAverage / count);
          const tdAvgClass = document.createElement('td');
          tdAvgClass.textContent = avgClass;
          trSummary.appendChild(tdAvgClass);
          // Grade rata-rata kelas
          const tdGradeClass = document.createElement('td');
          tdGradeClass.innerHTML = getGrade(avgClass);
          trSummary.appendChild(tdGradeClass);
          tbody.appendChild(trSummary);
        }
        if (siswaList.length === 0) {
          tbody.innerHTML = `<tr><td colspan="${2 + tugasKeys.length + evalKeys.length + 3}">Belum ada data nilai untuk mapel ini.</td></tr>`;
        }
      } catch (err) {
        tbody.innerHTML = '<tr><td colspan="100">Gagal memuat data nilai.</td></tr>';
      }
    }

    // Event listener untuk tombol mata pelajaran
    document.querySelectorAll('.subject-button').forEach(button => {
      button.addEventListener('click', function() {
        document.querySelectorAll('.subject-button').forEach(btn => {
          btn.classList.remove('active');
        });
        this.classList.add('active');
        const subject = this.dataset.subject;
        displayScores(subject);
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      displayScores('ipas');
    });
  </script>
    <script src="../music-player.js"></script>
</body>
</html>