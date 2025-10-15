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
    .jadwal-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2.5rem 1rem 2rem 1rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .jadwal-title {
      font-size: 2.3rem;
      font-weight: 700;
      color: #ffffffff;
      margin-bottom: 1.2rem;
      letter-spacing: 1px;
      text-shadow: 0 2px 12px #b8355633;
      text-align: center;
    }
    .jadwal-wrapper {
      background: #304a5bff;
      border-radius: 18px;
      box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
      padding: 2rem 2rem;
      margin: 4rem 10;
      width: 100%;
      overflow-x: auto;
    }
    .jadwal-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      background: rgba(255,255,255,0.12);
      border-radius: 18px;
      box-shadow: 0 2px 12px #b8355633;
      overflow: hidden;
      font-size: 1.1rem;
      animation: fadeInCard 0.8s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .jadwal-table th, .jadwal-table td {
      padding: 1.1rem 2.7rem;
      text-align: center;
      border-bottom: 1px solid #DC97A5;
      color: #fff;
      font-weight: 600;
      background: none;
      transition: background 0.3s, color 0.3s;
    }
    .jadwal-table th {
      background: linear-gradient(135deg, #B83556 60%, #DC97A5 100%);
      color: #fff;
      font-size: 1.2rem;
      letter-spacing: 1px;
      border-top: none;
      border-bottom: 2px solid #fff;
    }
    .jadwal-table tr {
      transition: background 0.3s;
    }
    .jadwal-table tr:hover {
      background: #DC97A5;
      color: #fff;
    }
    /* Mata pelajaran card style */
    .jadwal-table td.interaktif {
      position: relative;
      background: none;
      border-radius: 18px;
      overflow: visible;
      padding: 0.7rem 0.2rem;
      transition: box-shadow 0.3s, transform 0.2s, background 0.3s;
    }
    .mapel-card {
      display: flex;
      align-items: center;
      gap: 0.7rem;
      background: linear-gradient(120deg, #fffbe7 60%, #ffe0ec 100%);
      border-radius: 16px;
      box-shadow: 0 4px 16px 0 rgba(184,53,86,0.10);
      padding: 0.5rem 1.1rem 0.5rem 0.5rem;
      font-size: 1.08rem;
      font-weight: 600;
      color: #264653;
      min-width: 120px;
      min-height: 48px;
      cursor: pointer;
      border: 2.5px solid transparent;
      transition: background 0.3s, box-shadow 0.3s, border 0.3s, transform 0.2s;
      position: relative;
      z-index: 1;
    }
    .mapel-card .mapel-icon {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: linear-gradient(135deg, #ffe0ec, #caf0f8);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 8px #b8355633;
      margin-right: 0.2rem;
      transition: background 0.3s;
    }
    /* Variasi warna per mapel */
    .mapel-card.pai { background: linear-gradient(120deg, #fffbe7 60%, #ffe0ec 100%); }
    .mapel-card.mtk { background: linear-gradient(120deg, #caf0f8 60%, #ffd6e0 100%); }
    .mapel-card.indonesia { background: linear-gradient(120deg, #d8f3dc 60%, #f1f7b5 100%); }
    .mapel-card.ipa { background: linear-gradient(120deg, #f8edeb 60%, #fec89a 100%); }
    .mapel-card.ppkn { background: linear-gradient(120deg, #e0c3fc 60%, #b5ead7 100%); }
    .mapel-card.seni { background: linear-gradient(120deg, #f1f7b5 60%, #caf0f8 100%); }
    .mapel-card.ips { background: linear-gradient(120deg, #ffd6e0 60%, #e0c3fc 100%); }
    .mapel-card.olahraga { background: linear-gradient(120deg, #caf0f8 60%, #f8edeb 100%); }
    .mapel-card.games { background: linear-gradient(120deg, #e0c3fc 60%, #ffe0ec 100%); }

    .mapel-card:hover, .jadwal-table td.interaktif.selected .mapel-card {
      box-shadow: 0 8px 24px 0 rgba(184,53,86,0.18);
      border: 2.5px solid #B83556;
      transform: scale(1.06) rotate(-2deg);
      background: linear-gradient(120deg, #ff698eff 40%, #B83556 100%);
      color: #fff;
    }
    .mapel-card .mapel-icon img {
      width: 28px;
      height: 28px;
      object-fit: contain;
      border-radius: 50%;
      background: none;
      box-shadow: none;
    }
    @media screen and (max-width: 900px) {
      .jadwal-table th, .jadwal-table td {
        padding: 0.7rem 0.3rem;
        font-size: 0.95rem;
      }
      .jadwal-title {
        font-size: 1.5rem;
      }
      .jadwal-wrapper {
        padding: 1rem;
      }
    }
    @media screen and (max-width: 600px) {
      .jadwal-table th, .jadwal-table td {
        padding: 0.5rem 0.1rem;
        font-size: 0.8rem;
      }
      .jadwal-title {
        font-size: 1.1rem;
      }
      .jadwal-wrapper {
        padding: 0.5rem;
      }
    }
    @keyframes fadeInCard {
      0% { opacity: 0; transform: translateY(30px) scale(0.98); }
      100% { opacity: 1; transform: none; }
    }
    .jadwal-table td.interaktif {
      cursor: pointer;
      transition: background 0.2s, color 0.2s, transform 0.2s;
    }
    .jadwal-table td.interaktif:hover {
      background: #fff;
      color: #B83556;
      transform: scale(1.08) rotate(-2deg);
      font-weight: 700;
      box-shadow: 0 2px 12px #b8355633;
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
    <div class="jadwal-container">
      <div class="jadwal-title">Jadwal Mata Pelajaran</div>
      <div class="jadwal-wrapper">
        <table class="jadwal-table">
          <thead>
            <tr>
              <th>Hari</th>
              <th>07.00 - 09.30</th>
              <th>09.30 - 11.30</th>
              <th>13.00 - 15.30</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Senin</td>
              <td class="interaktif"><div class="mapel-card pai"><span class="mapel-icon"><img src="../img/pai.png" alt="PAI"></span>PAI</div></td>
              <td class="interaktif"><div class="mapel-card mtk"><span class="mapel-icon"><img src="../img/mtk.png" alt="Matematika"></span>Matematika</div></td>
              <td class="interaktif"><div class="mapel-card indonesia"><span class="mapel-icon"><img src="../img/indonesia.png" alt="B.Indonesia"></span>B. Indonesia</div></td>
            </tr>
            <tr>
              <td>Selasa</td>
              <td class="interaktif"><div class="mapel-card ipa"><span class="mapel-icon"><img src="../img/ipa.png" alt="IPA"></span>IPA</div></td>
              <td class="interaktif"><div class="mapel-card ppkn"><span class="mapel-icon"><img src="../img/ppkn.png" alt="PPKN"></span>PPKN</div></td>
              <td class="interaktif"><div class="mapel-card seni"><span class="mapel-icon"><img src="../img/seni.png" alt="Seni Budaya"></span>Seni Budaya</div></td>
            </tr>
            <tr>
              <td>Rabu</td>
              <td class="interaktif"><div class="mapel-card mtk"><span class="mapel-icon"><img src="../img/mtk.png" alt="Matematika"></span>Matematika</div></td>
              <td class="interaktif"><div class="mapel-card indonesia"><span class="mapel-icon"><img src="../img/indonesia.png" alt="B.Indonesia"></span>B. Indonesia</div></td>
              <td class="interaktif"><div class="mapel-card ipa"><span class="mapel-icon"><img src="../img/ipa.png" alt="IPA"></span>IPA</div></td>
            </tr>
            <tr>
              <td>Kamis</td>
              <td class="interaktif"><div class="mapel-card pai"><span class="mapel-icon"><img src="../img/pai.png" alt="PAI"></span>PAI</div></td>
              <td class="interaktif"><div class="mapel-card ppkn"><span class="mapel-icon"><img src="../img/ppkn.png" alt="PPKN"></span>PPKN</div></td>
              <td class="interaktif"><div class="mapel-card ips"><span class="mapel-icon"><img src="../img/ips.png" alt="IPS"></span>IPS</div></td>
            </tr>
            <tr>
              <td>Jumat</td>
              <td class="interaktif"><div class="mapel-card indonesia"><span class="mapel-icon"><img src="../img/indonesia.png" alt="B.Indonesia"></span>B. Indonesia</div></td>
              <td class="interaktif"><div class="mapel-card mtk"><span class="mapel-icon"><img src="../img/mtk.png" alt="Matematika"></span>Matematika</div></td>
              <td class="interaktif"><div class="mapel-card olahraga"><span class="mapel-icon"><img src="../img/olahraga.png" alt="PJOK"></span>PJOK</div></td>
            </tr>
            <tr>
              <td>Sabtu</td>
              <td class="interaktif"><div class="mapel-card games"><span class="mapel-icon"><img src="../img/games.png" alt="Games"></span>Games Edukatif</div></td>
              <td class="interaktif" colspan="2" style="background:rgba(255,255,255,0.08);color:#fff;font-style:italic;">Libur / Kegiatan Ekstrakurikuler</td>
            </tr>
          </tbody>
        </table>
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
    // Interaktif: highlight cell saat diklik
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.jadwal-table td.interaktif').forEach(function(cell) {
        cell.addEventListener('click', function() {
          cell.classList.toggle('selected');
        });
      });
    });
  </script>
<script src="../music-player.js"></script>
</body>
</html>
