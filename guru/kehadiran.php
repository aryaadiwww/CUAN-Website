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
    
    
    /* Kehadiran Container Styles */
    .kehadiran-container {
      padding: 2rem;
      max-width: 1200px;
      margin: 0 auto;
      width: 100%;
    }
    
    .kehadiran-title {
      font-size: 2.2rem;
      font-weight: 800;
      color: #fff;
      margin-bottom: 1.5rem;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    
    .month-selector {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-bottom: 2rem;
      flex-wrap: wrap;
    }
    
    .month-button {
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
    
    .month-button:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: translateY(-3px);
    }
    
    .month-button.active {
      background: #fff;
      color: #B83556;
      box-shadow: 0 4px 12px rgba(184, 53, 86, 0.3);
    }
    
    .kehadiran-table-container {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      overflow-x: auto;
    }
    
    .kehadiran-table {
      width: 100%;
      border-collapse: collapse;
      min-width: 800px;
    }
    
    .kehadiran-table th {
      background: #B83556;
      color: #fff;
      padding: 1rem;
      text-align: center;
      position: sticky;
      top: 0;
      z-index: 10;
    }
    
    .kehadiran-table th:first-child {
      text-align: left;
      border-top-left-radius: 8px;
      border-bottom-left-radius: 8px;
    }
    
    .kehadiran-table th:last-child {
      border-top-right-radius: 8px;
      border-bottom-right-radius: 8px;
    }
    
    .kehadiran-table td {
      padding: 0.8rem 1rem;
      text-align: center;
      border-bottom: 1px solid #eee;
      border-right: 1px solid #B83556;
      transition: background 0.2s, box-shadow 0.2s;
    }
    .kehadiran-table td:last-child {
      border-right: none;
    }
    .kehadiran-table td.meeting-cell:hover {
      background: #ffe6ef;
      box-shadow: 0 0 0 2px #B83556 inset;
      cursor: pointer;
      z-index: 2;
      position: relative;
    }
    
    .kehadiran-table td:first-child {
      text-align: left;
      font-weight: 600;
      color: #333;
    }
    
    .kehadiran-table tr:hover {
      background: rgba(220, 151, 165, 0.1);
    }
    
    .kehadiran-table tr:last-child td {
      border-bottom: none;
    }
    
    .status-icon {
      width: 24px;
      height: 24px;
      display: inline-block;
    }
    
    .hadir {
      color: #38B000;
    }
    
    .izin {
      color: #F9A826;
    }
    
    .alpa {
      color: #B83556;
    }
    
    .summary-row {
      background: rgba(184, 53, 86, 0.05);
      font-weight: 600;
    }
    
    .summary-row td {
      border-top: 2px solid #B83556;
    }
    
    @media screen and (max-width: 900px) {
      .kehadiran-container {
        padding: 1.5rem;
      }
      
      .kehadiran-title {
        font-size: 1.8rem;
      }
      
      .month-button {
        padding: 0.6rem 1.2rem;
        font-size: 0.9rem;
      }
      
      header {
        height: 70px;
      }
    }
    
    @media screen and (max-width: 600px) {
      .kehadiran-container {
        padding: 1rem;
      }
      
      .kehadiran-title {
        font-size: 1.5rem;
      }
      
      .month-button {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
      }
      
      .kehadiran-table-container {
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
<?php
// Ambil data username siswa dari database
include '../koneksi.php';
$siswa = [];
$sql = "SELECT username, nama FROM users WHERE level='siswa' ORDER BY username ASC";
$result = mysqli_query($koneksi, $sql);
if (!$result) {
  echo '<div style="color:red;background:#fff;padding:10px;">Query error: '.mysqli_error($koneksi).'</div>';
}
while ($row = mysqli_fetch_assoc($result)) {
  $siswa[] = ['username' => $row['username'], 'nama' => $row['nama']];
}
if (count($siswa) === 0) {
  echo '<div style="color:red;background:#fff;padding:10px;">Tidak ada siswa ditemukan di database!</div>';
}
?>
<script>
window.studentsFromPHP = <?php echo json_encode($siswa); ?>;
</script>
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
    <div class="kehadiran-container">
      <div class="kehadiran-title">Data Kehadiran Siswa</div>
      
      <div class="month-selector">
        <button class="month-button active" data-month="1">Januari</button>
        <button class="month-button" data-month="2">Februari</button>
        <button class="month-button" data-month="3">Maret</button>
        <button class="month-button" data-month="4">April</button>
        <button class="month-button" data-month="5">Mei</button>
        <button class="month-button" data-month="6">Juni</button>
      </div>
      
      <div class="kehadiran-table-container">
        <table class="kehadiran-table" id="kehadiran-table">
          <thead>
            <tr>
              <th>Nama Siswa</th>
              <!-- Hari akan diisi oleh JavaScript -->
            </tr>
          </thead>
          <tbody>
            <!-- Data kehadiran akan diisi oleh JavaScript -->
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
    
    // Data siswa dari PHP (username)
    const students = window.studentsFromPHP || [];
    if (students.length === 0) {
      document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.querySelector('#kehadiran-table tbody');
        if (tbody) tbody.innerHTML = '<tr><td colspan="28" style="color:red;text-align:center;">Tidak ada siswa ditemukan di database!</td></tr>';
      });
    }
    
    // Status kehadiran (1: hadir, 2: izin/sakit, 3: alpa)
    const statusIcons = {
      1: '<svg class="status-icon hadir" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
      2: '<svg class="status-icon izin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v4"></path><path d="M12 16h.01"></path></svg>',
      3: '<svg class="status-icon alpa" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>'
    };
    
    // Data kehadiran yang sudah di-load dari API
    let attendanceDataAPI = [];

    // Fungsi untuk menampilkan data kehadiran
    function displayAttendance(month) {
      const table = document.getElementById('kehadiran-table');
      const thead = table.querySelector('thead tr');
      const tbody = table.querySelector('tbody');
      
      // Reset header dan body
      thead.innerHTML = '<th>Nama Siswa</th>';
      tbody.innerHTML = '';
      
      // Tambahkan header untuk setiap hari
      const daysInMonth = 26; // Semua bulan memiliki 26 hari pertemuan
      for (let i = 1; i <= daysInMonth; i++) {
        const th = document.createElement('th');
        th.textContent = i;
        thead.appendChild(th);
      }
      
      // Tambahkan header untuk total
      const thTotal = document.createElement('th');
      thTotal.textContent = 'Total Hadir';
      thead.appendChild(thTotal);
      
      // Tambahkan baris untuk setiap siswa
      students.forEach(student => {
        const tr = document.createElement('tr');
        
        // Tambahkan nama siswa
        const tdName = document.createElement('td');
        tdName.textContent = student.nama;
        tr.appendChild(tdName);
        
        // Tambahkan status kehadiran untuk setiap hari (isi dari API jika ada)
        let totalPresent = 0;
        for (let i = 0; i < daysInMonth; i++) {
          const td = document.createElement('td');
          td.className = 'meeting-cell';
          td.innerHTML = '';
          td.style.cursor = 'pointer';
          td.dataset.username = student.username;
          td.dataset.hari = (i+1);
          td.dataset.bulan = month;
          td.onclick = function() { showAttendanceModal(td); };

          // Cek data API, jika ada tampilkan icon
          const found = attendanceDataAPI.find(row => row.username === student.username && row.bulan == month && row.hari == (i+1));
          if (found) {
            td.innerHTML = statusIcons[found.status];
            if (found.status == 1) totalPresent++;
          }
          tr.appendChild(td);
        }
        // Tambahkan total kehadiran
        const tdTotal = document.createElement('td');
        tdTotal.textContent = totalPresent > 0 ? totalPresent : '';
        tr.appendChild(tdTotal);
        tbody.appendChild(tr);
      });
      // Baris ringkasan tidak perlu jika data kehadiran belum diisi
    }

    // Ambil data kehadiran dari API
    function loadAttendanceFromAPI(month, callback) {
      fetch('../api/kehadiran_api.php?bulan=' + month)
        .then(res => res.json())
        .then(data => {
          attendanceDataAPI = Array.isArray(data.data) ? data.data : [];
          if (callback) callback();
        })
        .catch(() => {
          attendanceDataAPI = [];
          if (callback) callback();
        });
    }
    
    // Event listener untuk tombol bulan
    document.querySelectorAll('.month-button').forEach(button => {
      button.addEventListener('click', function() {
        // Hapus kelas active dari semua tombol
        document.querySelectorAll('.month-button').forEach(btn => {
          btn.classList.remove('active');
        });
        // Tambahkan kelas active ke tombol yang diklik
        this.classList.add('active');
        // Tampilkan data kehadiran untuk bulan yang dipilih
        const month = parseInt(this.dataset.month);
        loadAttendanceFromAPI(month, function() {
          displayAttendance(month);
        });
      });
    });
    // Tampilkan data kehadiran untuk bulan pertama saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
      loadAttendanceFromAPI(1, function() {
        displayAttendance(1);
      });
    });

    // Pop up modal logic
    let selectedCell = null;
    function showAttendanceModal(cell) {
      selectedCell = cell;
      document.getElementById('attendanceModal').style.display = 'flex';
    }
    function closeAttendanceModal() {
      document.getElementById('attendanceModal').style.display = 'none';
      selectedCell = null;
    }
    // Pop up konfirmasi custom sebelum simpan status
    let pendingStatus = null;
    function setAttendanceStatus(status) {
      if (selectedCell) {
        pendingStatus = status;
        showConfirmStatusModal(status);
      }
    }

    function showConfirmStatusModal(status) {
      const confirmModal = document.getElementById('confirmStatusModal');
      const statusText = status === 1 ? 'Hadir' : (status === 2 ? 'Izin' : 'Alpha');
      const statusColor = status === 1 ? '#38B000' : (status === 2 ? '#F9A826' : '#B83556');
      confirmModal.querySelector('.confirm-status-title').textContent = `Konfirmasi Simpan Status: ${statusText}`;
      confirmModal.querySelector('.confirm-status-title').style.color = statusColor;
      confirmModal.querySelector('.confirm-status-icon').innerHTML = statusIcons[status];
      confirmModal.style.display = 'flex';
      confirmModal.style.animation = 'modalPop 0.4s';
    }

    function closeConfirmStatusModal() {
      document.getElementById('confirmStatusModal').style.display = 'none';
      pendingStatus = null;
    }

    function saveConfirmedStatus() {
      if (selectedCell && pendingStatus) {
        const status = pendingStatus;
        const statusIconsLocal = {
          1: '<svg class="status-icon hadir" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
          2: '<svg class="status-icon izin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v4"></path><path d="M12 16h.01"></path></svg>',
          3: '<svg class="status-icon alpa" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>'
        };
        selectedCell.innerHTML = statusIconsLocal[status];
        const username = selectedCell.dataset.username;
        const hari = selectedCell.dataset.hari;
        const bulan = selectedCell.dataset.bulan;
        fetch('../api/kehadiran_api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username, hari, bulan, status })
        }).then(res => res.json()).then(() => {
          loadAttendanceFromAPI(bulan, function() {
            displayAttendance(parseInt(bulan));
          });
        });
        closeAttendanceModal();
        closeConfirmStatusModal();
      }
    }
  </script>

  <!-- Modal Pop Up Kehadiran -->
  <div id="attendanceModal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div style="background:#fff;padding:2rem 2.5rem;border-radius:18px;box-shadow:0 4px 24px #b8355633;min-width:260px;max-width:90vw;text-align:center;animation:modalPop 0.4s;">
      <h3 style="margin-bottom:1.5rem;color:#B83556;font-size:1.3rem;">Pilih Status Kehadiran</h3>
      <div style="display:flex;gap:1.2rem;justify-content:center;margin-bottom:1.5rem;">
        <button onclick="setAttendanceStatus(1)" style="background:#38B000;color:#fff;padding:0.7rem 1.2rem;border:none;border-radius:8px;font-weight:600;font-size:1rem;cursor:pointer;">Hadir</button>
        <button onclick="setAttendanceStatus(2)" style="background:#F9A826;color:#fff;padding:0.7rem 1.2rem;border:none;border-radius:8px;font-weight:600;font-size:1rem;cursor:pointer;">Izin</button>
        <button onclick="setAttendanceStatus(3)" style="background:#B83556;color:#fff;padding:0.7rem 1.2rem;border:none;border-radius:8px;font-weight:600;font-size:1rem;cursor:pointer;">Alpha</button>
      </div>
      <button onclick="closeAttendanceModal()" style="background:#eee;color:#B83556;padding:0.5rem 1.2rem;border:none;border-radius:8px;font-weight:600;font-size:1rem;cursor:pointer;">Batal</button>
    </div>
  </div>

  <!-- Modal Konfirmasi Simpan Status -->
  <div id="confirmStatusModal" style="display:none;position:fixed;z-index:10000;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.25);align-items:center;justify-content:center;">
    <div style="background:#fff;padding:2rem 2.5rem;border-radius:22px;box-shadow:0 6px 32px #b8355633;min-width:260px;max-width:90vw;text-align:center;animation:modalPop 0.4s;">
      <div class="confirm-status-icon" style="margin-bottom:1.2rem;font-size:2.2rem;"></div>
      <h3 class="confirm-status-title" style="margin-bottom:1.2rem;font-size:1.25rem;font-weight:700;">Konfirmasi Simpan Status</h3>
      <div style="display:flex;gap:1.2rem;justify-content:center;margin-bottom:1.2rem;">
        <button onclick="saveConfirmedStatus()" style="background:#38B000;color:#fff;padding:0.7rem 1.2rem;border:none;border-radius:8px;font-weight:600;font-size:1rem;cursor:pointer;transition:background 0.2s;">Ya, Simpan</button>
        <button onclick="closeConfirmStatusModal()" style="background:#eee;color:#B83556;padding:0.7rem 1.2rem;border:none;border-radius:8px;font-weight:600;font-size:1rem;cursor:pointer;transition:background 0.2s;">Batal</button>
      </div>
      <div style="font-size:0.95rem;color:#888;">Pastikan status kehadiran sudah benar sebelum disimpan.</div>
    </div>
  </div>

  <style>
    @keyframes modalPop {
      0% { opacity: 0; transform: scale(0.8); }
      60% { opacity: 1; transform: scale(1.05); }
      100% { opacity: 1; transform: scale(1); }
    }
  </style>

  <script src="../music-player.js"></script>
</body>
</html>