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
  <title>Bahan Ajar IPAS - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
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
    .bahanajar-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
      display: flex;
      flex-direction: column;
      align-items: stretch;
    }

    .bahanajar-header {
      display: flex;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }

    .bahanajar-icon {
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

    .bahanajar-icon svg {
      width: 36px;
      height: 36px;
    }

    .bahanajar-title {
      font-size: 2.2rem;
      font-weight: 700;
      color: #fff;
      margin: 0;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .bahanajar-subtitle {
      font-size: 1.1rem;
      color: rgba(255, 255, 255, 0.8);
      margin-top: 0.5rem;
    }

    .bahanajar-content {
      display: flex;
      flex-direction: column;
      gap: 2rem;
    }

    .bahanajar-empty {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 3rem 2rem;
      text-align: center;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.8s ease-out;
    }

    .bahanajar-empty svg {
      width: 80px;
      height: 80px;
      margin-bottom: 1.5rem;
      opacity: 0.7;
    }

    .bahanajar-empty-text {
      font-size: 1.4rem;
      font-weight: 600;
      color: #fff;
      margin-bottom: 0.5rem;
    }

    .bahanajar-empty-subtext {
      font-size: 1rem;
      color: rgba(255, 255, 255, 0.7);
      max-width: 500px;
      margin: 0 auto;
    }

    .bahanajar-card {
      background: rgba(81, 177, 255, 1);
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: 0 4px 20px rgba(33, 150, 243, 0.13);
      display: flex;
      flex-direction: column;
      gap: 1rem;
      transition: transform 0.3s, box-shadow 0.3s;
      animation: fadeIn 0.8s ease-out;
      border-left: 4px solid #1976D2;
    }

    .bahanajar-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .bahanajar-card-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    .bahanajar-card-title {
      font-size: 1.4rem;
      font-weight: 700;
      color: #1976D2;
      margin: 0;
    }

    .bahanajar-card-date {
      font-size: 0.9rem;
      color: #1976D2;
      background: rgba(33, 150, 243, 0.13);
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
    }

    .bahanajar-card-desc {
      font-size: 1rem;
      color: #222;
      line-height: 1.5;
    }

    .bahanajar-card-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 0.5rem;
    }

    .bahanajar-card-info {
      display: flex;
      flex-direction: column;
      gap: 0.3rem;
    }

    .bahanajar-card-stats {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.9rem;
      color: #1976D2;
      font-weight: 600;
    }

    .bahanajar-card-stats svg {
      width: 16px;
      height: 16px;
    }

    .bahanajar-card-time {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.9rem;
      color: #1976D2;
    }

    .bahanajar-card-time svg {
      width: 16px;
      height: 16px;
    }

    .bahanajar-card-actions {
      display: flex;
      gap: 0.8rem;
    }

    .bahanajar-card-button {
      background: #1976D2;
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

    .bahanajar-card-button:hover {
      background: #1565C0;
      transform: scale(1.05);
    }

    .bahanajar-card-button svg {
      width: 16px;
      height: 16px;
    }

    .bahanajar-add-button {
      background: #2196F3;
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

    .bahanajar-add-button:hover {
      background: #1976D2;
      transform: translateY(-3px);
    }

    .bahanajar-add-button svg {
      width: 20px;
      height: 20px;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media screen and (max-width: 768px) {
      .bahanajar-container {
        padding: 1.5rem;
      }

      .bahanajar-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }

      .bahanajar-title {
        font-size: 1.8rem;
      }

      .bahanajar-card-header {
        flex-direction: column;
        gap: 0.5rem;
      }

      .bahanajar-card-footer {
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
          <a href="guru_edit_profile.php"><svg width="18" height="18" fill="none" stroke="#1976D2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>Edit Profil</a>
          <a href="../logout.php"><svg width="18" height="18" fill="none" stroke="#1976D2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>Logout</a>
        </div>
      </div>
    </header>
    
    <div class="bahanajar-container">
      <div class="bahanajar-header">
        <div class="bahanajar-icon">
          <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
          </svg>
        </div>
        <div>
          <h1 class="bahanajar-title">Bahan Ajar IPAS</h1>
          <p class="bahanajar-subtitle">Kelola materi pembelajaran untuk siswa</p>
        </div>
      </div>
      
      <div class="bahanajar-content">
        <!-- Tampilan ketika belum ada bahan ajar -->
        
        <!-- Tombol tambah bahan ajar dan modal upload -->
        <button class="bahanajar-add-button" onclick="showUploadModal()">
          <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="16"/>
            <line x1="8" y1="12" x2="16" y2="12"/>
          </svg>
          Tambah Bahan Ajar Baru
        </button>
        <!-- Modal Upload File/Link Gabung -->
        <div id="uploadModal" style="display:none;position:fixed;z-index:2000;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
          <div style="background:#fff;padding:2rem 2rem 1rem 2rem;border-radius:16px;max-width:400px;width:90vw;position:relative;">
            <h2 style="margin-top:0;color:#6A4C93;">Upload Bahan Ajar</h2>
            <form id="uploadForm">
              <label>Judul:</label><br>
              <input type="text" name="title" required style="width:100%;margin-bottom:10px;"><br>
              <label>Deskripsi:</label><br>
              <textarea name="desc" required style="width:100%;margin-bottom:10px;"></textarea><br>
              <input type="file" name="file" style="margin-bottom:10px;"><br>
              <input type="text" name="link" placeholder="https:// atau format bebas" style="width:100%;margin-bottom:10px;"><br>
              <button type="submit" style="background:#6A4C93;color:#fff;padding:0.5rem 1.5rem;border:none;border-radius:8px;font-weight:600;">Upload</button>
              <button type="button" onclick="hideUploadModal()" style="margin-left:10px;background:#ccc;color:#333;padding:0.5rem 1.5rem;border:none;border-radius:8px;">Batal</button>
            </form>
            <div id="uploadStatus" style="margin-top:10px;color:#c00;"></div>
          </div>
        </div>
        <!-- Tempat render bahan ajar -->
        <div id="bahanajar-list"></div>
      </div>
    </div>
  </div>

  <script>
    // Modal logic
    function showUploadModal() {
      document.getElementById('uploadModal').style.display = 'flex';
    }
    function hideUploadModal() {
      document.getElementById('uploadModal').style.display = 'none';
      document.getElementById('uploadStatus').innerText = '';
      document.getElementById('uploadForm').reset();
    }
    // Upload logic
    document.getElementById('uploadForm').onsubmit = function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      var status = document.getElementById('uploadStatus');
      status.innerText = 'Uploading...';
      fetch('upload_bahan_ajar.php', {
        method: 'POST',
        body: formData
      })
      
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          status.style.color = '#090';
          status.innerText = 'Upload berhasil!';
          hideUploadModal();
          loadBahanAjar();
        } else {
          status.style.color = '#c00';
          status.innerText = data.error || 'Upload gagal';
        }
      })
      .catch(() => {
        status.style.color = '#c00';
        status.innerText = 'Upload gagal';
      });
    };
    // Render bahan ajar
    function bahanAjarCard(entry) {
      // Konversi waktu ke WIB (Asia/Jakarta)
      let dateWIB = new Date(entry.date + ' UTC');
      let wibString = dateWIB.toLocaleString('id-ID', { timeZone: 'Asia/Jakarta' });
      return `<div class="bahanajar-card">
        <div class="bahanajar-card-header" style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
          <h3 class="bahanajar-card-title">${entry.title}</h3>
          <div style="display:flex;align-items:center;gap:6px;">
            <span class="bahanajar-card-date">${wibString} WIB</span>
                      </div>
        </div>
        <p class="bahanajar-card-desc">${entry.desc}</p>
        <div class="bahanajar-card-footer">
          <div class="bahanajar-card-info">
            <div class="bahanajar-card-stats" style="display:flex;flex-direction:column;align-items:flex-start;gap:2px;">
              <svg fill="none" stroke="#4ecca3" stroke-width="2" viewBox="0 0 24 24" style="margin-bottom:2px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              ${entry.filename ? `<span style="margin-bottom:2px;">File: <a href="../uploads/bahan_ajar/${entry.filename}" target="_blank" style="color:#fff;text-decoration:underline;word-break:break-all;">${entry.original}</a></span>` : ''}
              ${entry.link ? `<span style="margin-bottom:2px;">Link: <a href="${entry.link}" target="_blank" style="color:#4ecca3;text-decoration:underline;word-break:break-all;">${entry.link}</a></span>` : ''}
            </div>
            <div class="bahanajar-card-time">
              <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
          </div>
          <div class="bahanajar-card-actions" style="position:relative;">
            ${entry.filename ? `<a class="bahanajar-card-button" href="../uploads/bahan_ajar/${entry.filename}" download>
              <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              Unduh
            </a>
            <a class="bahanajar-card-button" href="../uploads/bahan_ajar/${entry.filename}" target="_blank">
              <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              Lihat
            </a>` : ''}
            ${entry.link ? `<a class="bahanajar-card-button" href="${entry.link}" target="_blank">
                <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Kunjungi Link
              </a>` : ''}
            <button class="bahanajar-card-button" style="background:#c00;color:#fff;" onclick="hapusBahanAjar(this, '${entry.filename || ''}', '${entry.link || ''}', '${entry.title.replace(/'/g, "\'")}')">
              <svg fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6v12a4 4 0 0 0 8 0V6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
              Hapus
            </button>
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
            list.innerHTML = `<div class='bahanajar-empty'><svg fill='none' stroke='#fff' stroke-width='1.5' viewBox='0 0 24 24'><path d='M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z'/><path d='M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z'/></svg><h2 class='bahanajar-empty-text'>Belum Ada Bahan Ajar</h2><p class='bahanajar-empty-subtext'>Anda belum menambahkan bahan ajar untuk mata pelajaran ini. Klik tombol di atas untuk menambahkan bahan ajar baru.</p></div>`;
          } else {
            list.innerHTML = data.map(bahanAjarCard).join('');
          }
        })
        .catch(() => {
          document.getElementById('bahanajar-list').innerHTML = `<div class='bahanajar-empty'><svg fill='none' stroke='#fff' stroke-width='1.5' viewBox='0 0 24 24'><path d='M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z'/><path d='M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z'/></svg><h2 class='bahanajar-empty-text'>Belum Ada Bahan Ajar</h2><p class='bahanajar-empty-subtext'>Gagal memuat data bahan ajar.</p></div>`;
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
      loadBahanAjar();
    });
    // Fungsi hapus bahan ajar
    function hapusBahanAjar(btn, filename, link, title) {
      if (!confirm('Yakin ingin menghapus bahan ajar ini?')) return;
      btn.disabled = true;
      btn.innerText = 'Menghapus...';
      // Kirim request ke backend
      fetch('delete_bahan_ajar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ filename, link, title })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          loadBahanAjar();
        } else {
          alert(data.error || 'Gagal menghapus bahan ajar');
          btn.disabled = false;
          btn.innerText = 'Hapus';
        }
      })
      .catch(() => {
        alert('Gagal menghapus bahan ajar');
        btn.disabled = false;
        btn.innerText = 'Hapus';
      });
    }
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
        arrow.innerHTML = '<path d="M16 5l-8 7 8 7" stroke="#1976D2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      } else {
        arrow.innerHTML = '<path d="M8 5l8 7-8 7" stroke="#1976D2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
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
</body>
</html>