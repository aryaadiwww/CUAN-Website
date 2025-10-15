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
  <title>Detail Kehadiran - Siswa</title>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="../responsive.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
    body { background: linear-gradient(120deg, #B83556 0%, #DC97A5 60%, #fff0f5 100%); min-height:100vh; display:flex; }
  .sidebar { width:70px; background: linear-gradient(135deg, #1a2933ff 0%, #405d6fff 100%); color:#fff; transition:width .4s cubic-bezier(.68,-0.55,.27,1.55); overflow:hidden; position:fixed; left:0; top:0; height:100vh; z-index:1000; display:flex; flex-direction:column; box-shadow: 0 4px 24px #1a293355; }
  .sidebar.open { width:180px; box-shadow: none; }
    .sidebar .logo-section { display:flex; align-items:center; justify-content:center; padding:1rem 0; height:80px; opacity:0; visibility:hidden; transition:opacity .4s, visibility .4s; }
    .sidebar.open .logo-section { opacity:1; visibility:visible; }
    .sidebar .logo-section img { width:120px; height:60px; }
    .sidebar ul { list-style:none; padding:0; margin-top:10px; }
    .sidebar ul li { display:flex; align-items:center; padding:14px 18px; cursor:pointer; border-radius:18px; margin:8px; background: linear-gradient(135deg, #634338ff 0%, #ffb296ff 100%); transition: background .3s, transform .2s; }
    .sidebar ul li:hover { background:#e4aa95ff; transform: scale(1.06) translateX(4px) rotate(-2deg); }
    .menu-icon img { width:18px; height:18px; object-fit:contain; }
    .menu-text { display:none; color:#fff; font-weight:600; }
    .sidebar.open .menu-text { display:inline; margin-left:6px; }
  .main-content { flex:1; display:flex; flex-direction:column; background: linear-gradient(135deg, #B83556 0%, #DC97A5 100%); min-height:100vh; margin-left:70px; transition: background 0.4s, margin-left 0.4s; width:100vw; overflow-x:hidden; overflow-y:auto; }
  .sidebar.open ~ .main-content { margin-left:180px; transition: margin-left 0.4s; }
    @media screen and (max-width: 900px) {
      .main-content { margin-left: 0 !important; }
      .sidebar { width: 56px; }
      .sidebar.open { width: 140px; }
    }
    @media screen and (max-width: 600px) {
      .main-content { margin-left: 0 !important; padding: 0.5rem; }
      .sidebar { width: 44px; }
      .sidebar.open { width: 110px; }
      .sidebar .logo-section img { width: 80px; height: 40px; }
    }
    header { color:#fff; display:flex; justify-content:space-between; align-items:center; padding:0.5rem 2rem; background-color:#a82747ff; }
    .hamburger { cursor:pointer; background:#fff6; color:#B83556; border:none; border-radius:50%; width:40px; height:40px; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px #ffb34733; }
    .hamburger svg { width:28px; height:28px; }
    .profile-button { background: linear-gradient(90deg, #dc97a5b2 0%, #dc97a5b2 100%); color:#fff; border:none; border-radius:50px; padding:5px 16px 5px 10px; display:flex; align-items:center; font-weight:700; }
    .profile-avatar { width:32px; height:32px; border-radius:50%; background:#fff; border:2px solid #B83556; margin-right:4px; }
    .profile-menu {
      position: relative;
      display: flex;
      align-items: center;
    }
    .dropdown {
      display: none;
      position: absolute;
      right: 0;
      top: 110%;
      min-width: 160px;
      background: linear-gradient(135deg, #fff 60%, #DC97A5 100%);
      color: #B83556;
      border: 1.5px solid #DC97A5;
      border-radius: 14px;
      box-shadow: 0 4px 16px rgba(184,53,86,0.12);
      z-index: 9999;
      overflow: visible;
    }
    .dropdown.open {
      display: block;
      animation: dropdownFade 0.3s;
    }
    @keyframes dropdownFade {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .dropdown a {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 12px 18px;
      color: #B83556;
      text-decoration: none;
      font-weight: 600;
      border-bottom: 1px solid #f3e6e6;
      background: none;
      transition: background 0.2s;
    }
    .dropdown a:last-child {
      border-bottom: none;
    }
    .dropdown a:hover {
      background: #f9e6ef;
      color: #a82747;
    }
    .content-wrap { padding: 1.5rem; }
    .title { font-size:1.8rem; font-weight:800; color:#fff; text-align:center; margin-bottom:1rem; text-shadow:0 2px 8px rgba(0,0,0,.1); }
    .month-selector { display:flex; justify-content:center; gap:0.6rem; flex-wrap:wrap; margin-bottom:1rem; }
    .month-button { background: rgba(255,255,255,0.2); color:#fff; border:none; padding:0.6rem 1.1rem; border-radius:50px; font-weight:600; cursor:pointer; transition:all .3s; }
    .month-button.active { background:#fff; color:#B83556; box-shadow:0 4px 12px rgba(184,53,86,0.3); }
    .table-container { background:#ffffff; border-radius:16px; padding:1rem; box-shadow:0 4px 20px rgba(0,0,0,0.1); overflow-x:auto; }
    table { width:100%; border-collapse:collapse; min-width:800px; }
    th { background:#B83556; color:#fff; padding:0.8rem; text-align:center; position:sticky; top:0; }
    th:first-child { text-align:left; border-top-left-radius:8px; border-bottom-left-radius:8px; }
    th:last-child { border-top-right-radius:8px; border-bottom-right-radius:8px; }
    td { padding:0.7rem 0.9rem; text-align:center; border-bottom:1px solid #eee; }
    td:first-child { text-align:left; font-weight:600; color:#333; }
    .status-icon { width:22px; height:22px; display:inline-block; }
    .hadir { color:#38B000; }
    .izin { color:#F9A826; }
    .alpa { color:#B83556; }
  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
    <div class="logo-section"><img src="../img/cuan.png" alt="Logo CUAN"></div>
    <ul>
      <li onclick="location.href='siswa_dashboard.php'"><span class="menu-icon"><img src="../img/home.png" alt="Beranda"></span><span class="menu-text">Beranda</span></li>
      <li onclick="location.href='siswa_matapelajaran.php'"><span class="menu-icon"><img src="../img/book.png" alt="Mata Pelajaran"></span><span class="menu-text">Mata Pelajaran</span></li>
      <li onclick="location.href='siswa_jadwal.php'"><span class="menu-icon"><img src="../img/calendar.png" alt="Jadwal"></span><span class="menu-text">Jadwal</span></li>
      <li onclick="location.href='siswa_games.php'"><span class="menu-icon"><img src="../img/games.png" alt="Games"></span><span class="menu-text">Games</span></li>
    </ul>
  </div>
  <div class="main-content">
    <header>
      <button class="hamburger" id="sidebarToggleBtn" onclick="toggleSidebar()">
        <svg id="sidebarArrow" viewBox="0 0 24 24" fill="none"><path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
      <div class="profile-menu">
        <button class="profile-button" onclick="toggleDropdown()">
          <img src="../img/profile.png" alt="Avatar" class="profile-avatar" />
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left:2px;"><path d="M5 8L10 13L15 8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="dropdown" id="dropdown">
          <a href="siswa_edit_profile.php">Edit Profil</a>
          <a href="../logout.php">Logout</a>
        </div>
      </div>
    </header>
    <div class="content-wrap">
      <div class="title">Detail Kehadiran Siswa</div>
      <div class="month-selector">
        <button class="month-button active" data-month="1">Januari</button>
        <button class="month-button" data-month="2">Februari</button>
        <button class="month-button" data-month="3">Maret</button>
        <button class="month-button" data-month="4">April</button>
        <button class="month-button" data-month="5">Mei</button>
        <button class="month-button" data-month="6">Juni</button>
      </div>
      <div class="table-container">
        <table id="kehadiran-table">
          <thead>
            <tr>
              <th>Nama Siswa</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
  <script>
    const currentUser = <?php echo json_encode(isset($_SESSION['username']) ? $_SESSION['username'] : ''); ?>;
    function toggleSidebar(){ const s=document.getElementById('sidebar'); s.classList.toggle('open'); updateSidebarArrow(); }
    function updateSidebarArrow(){ const s=document.getElementById('sidebar'); const a=document.getElementById('sidebarArrow'); a.innerHTML = s.classList.contains('open') ? '<path d="M16 5l-8 7 8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' : '<path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'; }
    function toggleDropdown() {
      const d = document.getElementById('dropdown');
      d.classList.toggle('open');
    }
    document.addEventListener('click', function(e) {
      const d = document.getElementById('dropdown');
      const p = document.querySelector('.profile-button');
      if (p && d && !p.contains(e.target) && !d.contains(e.target)) {
        d.classList.remove('open');
      }
    });
    const statusIcons = {
      1: '<svg class="status-icon hadir" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
      2: '<svg class="status-icon izin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v4"></path><path d="M12 16h.01"></path></svg>',
      3: '<svg class="status-icon alpa" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>'
    };
    let attendanceDataAPI = [];
    function displayAttendance(month){
      const table = document.getElementById('kehadiran-table');
      const thead = table.querySelector('thead tr');
      const tbody = table.querySelector('tbody');
      thead.innerHTML = '<th>Nama Siswa</th>';
      tbody.innerHTML = '';
      const daysInMonth = 26;
      for (let i=1;i<=daysInMonth;i++){ const th=document.createElement('th'); th.textContent=i; thead.appendChild(th); }
      const thTotal=document.createElement('th'); thTotal.textContent='Total Hadir'; thead.appendChild(thTotal);
      fetch('../api/daftar_siswa_api.php').then(r=>r.json()).then(js=>{
        let siswaList = js.status==='success' ? js.data : [];
        // Tampilkan hanya siswa yang sedang login
        if (currentUser) {
          siswaList = siswaList.filter(s => s.username === currentUser);
        }
        if (!siswaList.length) {
          tbody.innerHTML = '<tr><td colspan="28">Data siswa tidak ditemukan.</td></tr>';
          return;
        }
        siswaList.forEach(student=>{
          const tr=document.createElement('tr');
          const tdName=document.createElement('td'); tdName.textContent=student.nama; tr.appendChild(tdName);
          let totalPresent=0;
          for(let i=0;i<daysInMonth;i++){
            const td=document.createElement('td');
            const found = attendanceDataAPI.find(row => row.username === student.username && row.hari == (i+1));
            if(found){ td.innerHTML = statusIcons[found.status] || ''; if(found.status==1) totalPresent++; }
            tr.appendChild(td);
          }
          const tdTotal=document.createElement('td'); tdTotal.textContent = totalPresent>0 ? totalPresent : ''; tr.appendChild(tdTotal);
          tbody.appendChild(tr);
        });
      }).catch(()=>{ tbody.innerHTML = '<tr><td colspan="28">Gagal memuat data siswa.</td></tr>'; });
    }
  function loadAttendanceFromAPI(month, cb){ fetch('../api/kehadiran_api.php?bulan=' + month).then(r=>r.json()).then(d=>{ attendanceDataAPI = Array.isArray(d.data)? d.data : []; cb && cb(); }).catch(()=>{ attendanceDataAPI=[]; cb && cb(); }); }
    document.querySelectorAll('.month-button').forEach(btn=>{ btn.addEventListener('click', function(){ document.querySelectorAll('.month-button').forEach(b=>b.classList.remove('active')); this.classList.add('active'); const m=parseInt(this.dataset.month); loadAttendanceFromAPI(m, ()=>displayAttendance(m)); });});
    document.addEventListener('DOMContentLoaded', function(){ updateSidebarArrow(); loadAttendanceFromAPI(1, ()=>displayAttendance(1)); });
  </script>
  <script src="../music-player.js"></script>
</body>
</html>

