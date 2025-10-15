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
      background-color: #3c9a7bff;
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
      background: linear-gradient(135deg, #37ba63ff 0%, #2cc8abff 100%);
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
      background: linear-gradient(135deg, #3cd26eff 0%, #31dfbfff 100%);
      box-shadow: 0 2px 8px 0 rgba(255,179,71,0.04);
      transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
      position: relative;
      overflow: hidden;
    }
    .sidebar ul li:hover {
      background:  #43b18cff;
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
      color: #fff;
    }
    .sidebar.open span.menu-text {
      display: inline;
      opacity: 1;
      margin-left: 1px;
      color: #fff;
      font-weight: 600;
    }
    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      background: linear-gradient(135deg, #37bd63ff 0%, #2fd0b2ff 100%);
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
      background: linear-gradient(135deg, #288246ff 0%, #28af96ff 100%);
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
      box-shadow: 0 2px 8px #4ecca333;
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
      color: #4ecca3;
      box-shadow: 0 4px 16px #4ecca355;
    }
    .profile-menu {
      position: relative;
      display: flex;
      align-items: center;
    }
    .profile-button {
      background: linear-gradient(90deg, #3bc76aff 0%, #2dcbaeff 100%);
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
      background: linear-gradient(90deg, #4ecca3 0%, #43e97b 100%);
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
      border: 1.5px solid #4ecca3;
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
      color: #4ecca3;
      font-weight: 600;
      font-size: 1rem;
      border-bottom: 1px solid #f3e6e6;
    }
    .dropdown a:last-child {
      border-bottom: none;
    }
    .dropdown a:hover {
      background: #43e97b;
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
      color: #43e97b;
      margin: 0;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .diskusi-subtitle {
      font-size: 1.1rem;
      color: #38f9d7;
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
      background: rgba(255, 255, 255, 0.13);
      border-radius: 38px;
      padding: 5rem 4.5rem;
      box-shadow: 0 16px 64px rgba(0, 0, 0, 0.18);
      display: flex;
      flex-direction: column;
      gap: 3rem;
      transition: transform 0.3s, box-shadow 0.3s;
      animation: fadeIn 0.8s ease-out;
      border-left: 14px solid #4ecca3;
      max-width: 1400px;
      margin: 0 auto 4rem auto;
    }

    .diskusi-card:hover {
      transform: translateY(-8px) scale(1.03);
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.18);
    }

    .diskusi-card-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 1.5rem;
    }

    .diskusi-card-title {
      font-size: 3.2rem;
      font-weight: 900;
      color: #fff;
      margin: 0;
      letter-spacing: 1.5px;
    }

    .diskusi-card-date {
      font-size: 1.1rem;
      color: rgba(255, 255, 255, 0.8);
      background: rgba(255, 255, 255, 0.13);
      padding: 0.5rem 1.2rem;
      border-radius: 24px;
    }

    .diskusi-card-desc {
      font-size: 2rem;
      color: rgba(255, 255, 255, 0.97);
      line-height: 2.1;
      font-weight: 600;
      letter-spacing: 0.18px;
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

    @media screen and (max-width: 900px) {
      .diskusi-card {
        max-width: 99vw;
        padding: 3.2rem 1.5rem;
        border-radius: 28px;
      }
      .diskusi-card-title {
        font-size: 2.5rem;
      }
      .diskusi-card-desc {
        font-size: 1.5rem;
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

    <!-- CHAT BOX START -->
    <div class="chatbox-container" style="margin-top:2.5rem;">
      <div class="chatbox-tabs">
        <button class="chatbox-tab active" id="tabGrup">Chat Grup</button>
        <button class="chatbox-tab" id="tabPribadi">Chat Pribadi</button>
      </div>
      <div class="chatbox-panel" id="chatPanelGrup">
        <div class="chatbox-messages" id="chatMessagesGrup"></div>
        <form class="chatbox-form" id="chatFormGrup" autocomplete="off">
          <input type="text" class="chatbox-input" id="chatInputGrup" placeholder="Ketik pesan untuk grup..." maxlength="300" required />
          <button type="submit" class="chatbox-send">Kirim</button>
        </form>
      </div>
      <div class="chatbox-panel" id="chatPanelPribadi" style="display:none;">
        <div style="margin-bottom:0.7rem;">
          <input type="text" class="chatbox-user-input" id="chatTargetUser" placeholder="Username siswa..." maxlength="32" style="width:220px;" required />
        </div>
        <div class="chatbox-messages" id="chatMessagesPribadi"></div>
        <form class="chatbox-form" id="chatFormPribadi" autocomplete="off">
          <input type="text" class="chatbox-input" id="chatInputPribadi" placeholder="Ketik pesan pribadi ke siswa..." maxlength="300" required />
          <button type="submit" class="chatbox-send">Kirim</button>
        </form>
      </div>
    </div>
    <style>
      .chatbox-container {
        background: rgba(255,255,255,0.13);
        border-radius: 18px;
        box-shadow: 0 4px 24px #6a4c9340;
        max-width: 600px;
        margin: 0 auto 2.5rem auto;
        padding: 0 0 1.5rem 0;
        font-family: 'Poppins', sans-serif;
        animation: fadeIn 0.7s;
      }
      .chatbox-tabs {
        display: flex;
        border-bottom: 2px solid #b983ff55;
        margin-bottom: 0.5rem;
      }
      .chatbox-tab {
        flex: 1;
        background: none;
        border: none;
        color: #246851ff;
        font-weight: 700;
        font-size: 1.1rem;
        padding: 1rem 0;
        cursor: pointer;
        border-radius: 18px 18px 0 0;
        transition: background 0.2s, color 0.2s;
      }
      .chatbox-tab.active {
        background: #246851ff;
        color: #fff;
      }
      .chatbox-panel {
        min-height: 320px;
        display: flex;
        flex-direction: column;
      }
      .chatbox-messages {
        flex: 1;
        overflow-y: auto;
        max-height: 260px;
        padding: 1rem 1.2rem 0.5rem 1.2rem;
        margin-bottom: 0.5rem;
        background: rgba(255,255,255,0.08);
        border-radius: 12px;
        font-size: 1rem;
      }
      .chatbox-message {
        margin-bottom: 0.7rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        animation: fadeIn 0.4s;
      }
      .chatbox-message.me {
        align-items: flex-end;
      }
      .chatbox-message .chatbox-meta {
        font-size: 0.85rem;
        color: #fff;
        margin-bottom: 2px;
        font-weight: 600;
      }
      .chatbox-message .chatbox-meta.me {
        color: #4ecca3;
      }
      .chatbox-message .chatbox-text {
        background: #246851ff;
        color: #fff;
        padding: 0.6rem 1.1rem;
        border-radius: 14px 14px 14px 0;
        max-width: 80%;
        word-break: break-word;
        font-size: 1rem;
        box-shadow: 0 2px 8px #b983ff33;
      }
      .chatbox-message.me .chatbox-text {
        background: #4ecca3;
        color: #fff;
        border-radius: 14px 14px 0 14px;
        box-shadow: 0 2px 8px #4ecca355;
      }
      .chatbox-form {
        display: flex;
        gap: 0.7rem;
        margin-top: 0.5rem;
      }
      .chatbox-input {
        flex: 1;
        border-radius: 10px;
        border: 1.5px solid 246851ff;
        padding: 0.7rem 1rem;
        font-size: 1rem;
        background: #fff;
        color: #246851ff;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: border 0.2s;
      }
      .chatbox-input:focus {
        border: 2px solid #246851ff;
      }
      .chatbox-send {
        background: #246851ff;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 0.7rem 1.3rem;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
      }
      .chatbox-send:hover {
        background: #246851ff;
      }
      .chatbox-user-input {
        border-radius: 10px;
        border: 1.5px solid #246851ff;
        padding: 0.6rem 1rem;
        font-size: 1rem;
        background: #fff;
        color: #246851ff;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: border 0.2s;
      }
      .chatbox-user-input:focus {
        border: 2px solid #ffffffff;
      }
      @media (max-width: 700px) {
        .chatbox-container { max-width: 98vw; }
        .chatbox-messages { font-size: 0.95rem; }
      }
    </style>
    <!-- CHAT BOX END -->

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
        arrow.innerHTML = '<path d="M16 5l-8 7 8 7" stroke="#246851ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      } else {
        arrow.innerHTML = '<path d="M8 5l8 7-8 7" stroke="#246851ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
      updateSidebarArrow();
      // CHAT TAB SWITCH
      const tabGrup = document.getElementById('tabGrup');
      const tabPribadi = document.getElementById('tabPribadi');
      const panelGrup = document.getElementById('chatPanelGrup');
      const panelPribadi = document.getElementById('chatPanelPribadi');
      tabGrup.addEventListener('click', function() {
        tabGrup.classList.add('active');
        tabPribadi.classList.remove('active');
        panelGrup.style.display = '';
        panelPribadi.style.display = 'none';
      });
      tabPribadi.addEventListener('click', function() {
        tabPribadi.classList.add('active');
        tabGrup.classList.remove('active');
        panelPribadi.style.display = '';
        panelGrup.style.display = 'none';
      });
      // LOAD CHAT
      function renderMessages(msgs, container, myuser, autoScroll=true) {
        // Save scroll position
        const isAtBottom = container.scrollTop + container.clientHeight >= container.scrollHeight - 10;
        container.innerHTML = '';
        msgs.forEach(function(m) {
          const div = document.createElement('div');
          div.className = 'chatbox-message' + (m.from === myuser ? ' me' : '');
          const meta = document.createElement('div');
          meta.className = 'chatbox-meta' + (m.from === myuser ? ' me' : '');
          meta.textContent = m.from + ' • ' + m.time;
          // Tombol hapus jika pesan milik sendiri
          if (m.from === myuser) {
            const delBtn = document.createElement('button');
            delBtn.textContent = '🗑️';
            delBtn.title = 'Hapus/Tarik pesan';
            delBtn.style.cssText = 'margin-left:8px;background:none;border:none;color:#fff;cursor:pointer;font-size:1.1em;';
            delBtn.onclick = function(e) {
              e.stopPropagation();
              showDeleteModal(function() {
                fetch('../ipas_chat_api.php', {
                  method: 'POST',
                  headers: {'Content-Type':'application/json'},
                  body: JSON.stringify({action:'delete', from: m.from, time: m.time})
                }).then(r=>r.json()).then(res=>{
                  if (res.success) {
                    // Hapus dari tampilan segera
                    div.remove();
                  } else {
                    alert(res.error||'Gagal menghapus pesan');
                  }
                });
              });
            };
            meta.appendChild(delBtn);
          }
          const text = document.createElement('div');
          text.className = 'chatbox-text';
          text.textContent = m.msg;
          div.appendChild(meta);
          div.appendChild(text);
          container.appendChild(div);
        });
        // Only scroll if user was at bottom
        if (autoScroll && isAtBottom) {
          container.scrollTop = container.scrollHeight;
        }
      }
      // Get username from PHP session
      let myuser = '';
      <?php if (isset($_SESSION['username'])): ?>
        myuser = <?php echo json_encode($_SESSION['username']); ?>;
      <?php endif; ?>
      // Grup chat
      let lastMsgsGrup = [];
      function loadChatGrup(force=false) {
        // Only refresh if input not focused
        const input = document.getElementById('chatInputGrup');
        if (document.activeElement === input && !force) return;
        fetch('../ipas_chat_api.php?target=all').then(r=>r.json()).then(msgs=>{
          // Only update if changed
          if (JSON.stringify(msgs) !== JSON.stringify(lastMsgsGrup)) {
            renderMessages(msgs, document.getElementById('chatMessagesGrup'), myuser);
            lastMsgsGrup = msgs;
          }
        });
      }
      loadChatGrup(true);
      setInterval(loadChatGrup, 3500);
      document.getElementById('chatFormGrup').onsubmit = function(e) {
        e.preventDefault();
        const input = document.getElementById('chatInputGrup');
        const msg = input.value.trim();
        if (!msg) return;
        fetch('../ipas_chat_api.php', {
          method: 'POST',
          headers: {'Content-Type':'application/json'},
          body: JSON.stringify({msg: msg, target: 'all'})
        }).then(()=>{
          input.value = '';
          loadChatGrup(true);
        });
      };
      // Pribadi chat
      let lastMsgsPribadi = [];
      function loadChatPribadi(force=false) {
        const target = document.getElementById('chatTargetUser').value.trim();
        if (!target) { document.getElementById('chatMessagesPribadi').innerHTML = '<div style="color:#fff;padding:1.5rem 0;">Masukkan username siswa untuk mulai chat pribadi.</div>'; return; }
        const input = document.getElementById('chatInputPribadi');
        if (document.activeElement === input && !force) return;
        fetch('../ipas_chat_api.php?target='+encodeURIComponent(target)).then(r=>r.json()).then(msgs=>{
          if (JSON.stringify(msgs) !== JSON.stringify(lastMsgsPribadi)) {
            renderMessages(msgs, document.getElementById('chatMessagesPribadi'), myuser);
            lastMsgsPribadi = msgs;
          }
        });
      }
      document.getElementById('chatTargetUser').addEventListener('input', function() {
        loadChatPribadi(true);
      });
      setInterval(function() {
        if (document.getElementById('tabPribadi').classList.contains('active')) loadChatPribadi();
      }, 3500);
      document.getElementById('chatFormPribadi').onsubmit = function(e) {
        e.preventDefault();
        const input = document.getElementById('chatInputPribadi');
        const msg = input.value.trim();
        const target = document.getElementById('chatTargetUser').value.trim();
        if (!msg || !target) return;
        fetch('../ipas_chat_api.php', {
          method: 'POST',
          headers: {'Content-Type':'application/json'},
          body: JSON.stringify({msg: msg, target: target})
        }).then(()=>{
          input.value = '';
          loadChatPribadi(true);
        });
      };
    });
    
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
  <!-- Modal Hapus Pesan -->
  <div id="deleteModal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.25);align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:18px;box-shadow:0 8px 32px #6A4C9355;padding:2.2rem 2.5rem;max-width:90vw;min-width:320px;text-align:center;position:relative;">
      <div style="color:#222;font-size:1.1rem;margin:1.1rem 0 2.1rem 0;">Hapus aja nih?</div>
      <button id="deleteModalCancel" style="background:#eee;color:#6A4C93;font-weight:600;border:none;border-radius:8px;padding:0.7rem 1.5rem;font-size:1rem;cursor:pointer;margin-right:1.2rem;">Batal</button>
      <button id="deleteModalOk" style="background:#6A4C93;color:#fff;font-weight:700;border:none;border-radius:8px;padding:0.7rem 1.5rem;font-size:1rem;cursor:pointer;">Hapus</button>
    </div>
  </div>
  <script>
    // Modal hapus pesan
    function showDeleteModal(onOk) {
      const modal = document.getElementById('deleteModal');
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
      function close() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        document.getElementById('deleteModalOk').onclick = null;
        document.getElementById('deleteModalCancel').onclick = null;
      }
      document.getElementById('deleteModalCancel').onclick = close;
      document.getElementById('deleteModalOk').onclick = function() {
        close();
        if (onOk) onOk();
      };
      // Close modal on click outside
      modal.onclick = function(e) { if (e.target === modal) close(); };
    }
  </script>
    <script src="../music-player.js"></script>

</body>
</html>