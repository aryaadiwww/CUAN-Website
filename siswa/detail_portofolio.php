<?php
session_start();
$level = $_SESSION['level'] ?? '';
$username = $_SESSION['username'] ?? '';
$siswa_id = ($level === 'siswa' && $username) ? $username : ($_SESSION['siswa_id'] ?? 'guest');

// Ambil data submissions tugas dan evaluasi
$tugas_submissions = json_decode(@file_get_contents('../tugas_submissions.json'), true);
if (!is_array($tugas_submissions)) $tugas_submissions = [];
$evaluasi_submissions = json_decode(@file_get_contents('../evaluasi_submissions.json'), true);
if (!is_array($evaluasi_submissions)) $evaluasi_submissions = [];

// Ambil data tugas dan evaluasi dari API
$tugas_list = json_decode(@file_get_contents('http://localhost/CUAN/api/tugas_api.php'), true);
if (!is_array($tugas_list)) $tugas_list = [];
$evaluasi_list = json_decode(@file_get_contents('http://localhost/CUAN/api/evaluasi_api.php'), true);
if (!is_array($evaluasi_list)) $evaluasi_list = [];

// Buat mapping tugas dan evaluasi berdasarkan id
$tugas_map = [];
foreach ($tugas_list as $t) {
	$tugas_map[$t['id']] = $t;
}
$evaluasi_map = [];
foreach ($evaluasi_list as $e) {
	$evaluasi_map[$e['id']] = $e;
}

// Filter submissions milik siswa yang sedang login dan sudah dinilai
$tugas_saya = array_filter($tugas_submissions, function($row) use ($siswa_id) {
	return $row['siswa_id'] == $siswa_id && isset($row['nilai']) && $row['nilai'] !== null;
});
$evaluasi_saya = array_filter($evaluasi_submissions, function($row) use ($siswa_id) {
	return $row['siswa_id'] == $siswa_id && isset($row['nilai']) && $row['nilai'] !== null;
});

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Siswa - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="../responsive.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    * {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      box-sizing: border-box;
    }

	 body {
      background: linear-gradient(120deg, #B83556 0%, #DC97A5 60%, #fff0f5 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: row;
      width: 100vw;
      overflow-x: hidden;
      transition: background 0.7s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .sidebar {
      width: 70px;
      background: linear-gradient(135deg, #1a2933ff 0%, #405d6fff  100%);
      color: white;
      transition: width 0.4s cubic-bezier(.68,-0.55,.27,1.55);
      overflow: hidden;
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
      box-shadow: 0 4px 24px #1a293355;
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
      transition: margin-right 0.4s cubic-bezier(.68,-0.55,.27,1.55), font-size 0.3s, padding-left 0.3s;

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
      font-size: 2.1rem;
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
      color: #ff5e62;
      box-shadow: 0 4px 16px #ffb34755;
      transform: scale(1.1) rotate(-8deg);
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
      transition: background 0.3s, color 0.3s, box-shadow 0.3s, transform 0.2s;
      position: relative;
    }
    .profile-button:hover {
      background: linear-gradient(90deg, #DC97A5 0%, #DC97A5 100%);
      color: #fff;
      box-shadow: 0 4px 16px #b8355655;
      transform: scale(1.06) rotate(-2deg);
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
    .container {
      max-width: 1200px;
      margin: 40px auto;
      padding: 32px 32px;
      background: #ffffff6e;
      border-radius: 24px;
      box-shadow: 0 8px 32px rgba(94,60,134,0.10);
    }
    h2 {
      margin-top: 0;
      color: #6c3cff;
      font-size: 2.1rem;
      font-weight: 700;
      letter-spacing: 1px;
      text-shadow: 0 2px 8px #e0c3fc44;
      margin-bottom: 32px;
    }
    .portfolio-columns {
      display: flex;
      gap: 32px;
      width: 100%;
      justify-content: space-between;
    }
    .portfolio-column {
      flex: 1;
      background: none;
      min-width: 0;
      display: flex;
      flex-direction: column;
      height: auto;
      min-height: 0;
      overflow-y: auto;
      scrollbar-width: thin;
      scrollbar-color: #B83556 #ffe6e6;
      padding-right: 8px;
      max-height: none;
    }
    .portfolio-column::-webkit-scrollbar {
      width: 8px;
      background: #ffe6e6;
    }
    .portfolio-column::-webkit-scrollbar-thumb {
      background: #B83556;
      border-radius: 8px;
    }
    .portfolio-column h3 {
      margin-bottom: 18px;
      font-size: 1.8rem;
      color: #B83556;
      font-weight: 700;
      text-align: center;
    }
    .card-list { display: none; }
    .card {
      border-radius: 18px;
      box-shadow: 0 4px 24px rgba(94,60,134,0.12);
      padding: 28px 24px 22px 24px;
      position: relative;
      transition: transform 0.2s, box-shadow 0.2s;
      overflow: hidden;
      border: none;
      min-width: 0;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
  /* Animasi dihilangkan */
    .card:hover {
      transform: scale(1) translateY(-2px);
      box-shadow: 0 8px 32px rgba(94,60,134,0.18);
      background: linear-gradient(120deg, #e0c3fc 0%, #ffe6e6 100%);
    }
    .card.tugas {
      border-left: 10px solid #33337dff;
      background: linear-gradient(120deg, #009FFD 0%, #2A2A72 100%);
      color: #fff;
    }
    .card.evaluasi {
      border-left: 10px solid #b87b17ff;
      background: linear-gradient(120deg, #F9A826 0%, #FEE440 100%);
      color: #fff;
    }
    .card .judul {
      font-size: 1.35rem;
      font-weight: 700;
      margin-bottom: 8px;
      letter-spacing: 0.5px;
      text-shadow: none;
      color: #fff;
    }
    .card .desc {
      font-size: 1.05rem;
      margin-bottom: 10px;
      font-weight: 500;
      color: #fff;
    }
    .card .deadline {
      font-size: 0.98rem;
      margin-bottom: 10px;
      font-weight: 500;
      padding: 3px 10px;
      border-radius: 8px;
      display: inline-block;
      color: #fff;
      background: rgba(255,255,255,0.18);
    }
    .card .nilai {
      font-size: 1.15rem;
      font-weight: bold;
      margin-bottom: 10px;
      padding: 6px 18px;
      border-radius: 12px;
      display: inline-block;
      box-shadow: 0 2px 8px #e0c3fc33;
      letter-spacing: 1px;
      color: #fff;
      background: rgba(255,255,255,0.18);
    }
    .card .upload-time {
      font-size: 0.80rem;
      color: #ffffffff;
      margin-bottom: 10px;
      font-style: italic;
    }
    .card .lampiran {
      margin-top: 12px;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
    }
    .card .lampiran-file {
      color: #ffffffff;
      font-size: 1rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .card .lampiran a.button-link {
      text-decoration: none;
      margin-right: 8px;
    }
    .card .lampiran a button {
      font-size: 1rem;
      font-weight: 600;
      border: none;
      padding: 7px 18px;
      border-radius: 24px;
      cursor: pointer;
      transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.2s;
      box-shadow: 0 2px 8px #e0c3fc33;
      margin-right: 2px;
      margin-bottom: 2px;
      outline: none;
      display: flex;
      align-items: center;
      gap: 8px;
      background: linear-gradient(90deg, #1976d2 0%, #64b5f6 100%);
      color: #fff;
      letter-spacing: 0.5px;
      border: 2px solid #fff;
      font-family: 'Poppins', sans-serif;
      box-shadow: 0 4px 16px #90caf944;
    }
    .card.evaluasi .lampiran a button {
      background: linear-gradient(90deg, #ffd600 0%, #ffe082 100%);
      color: #333;
      border: 2px solid #fffde7;
      box-shadow: 0 4px 16px #ffe08244;
    }
    .card .lampiran a button:hover {
      background: linear-gradient(90deg, #1565c0 0%, #1976d2 100%);
      color: #fff;
      transform: scale(1.07);
      box-shadow: 0 8px 24px #1976d244;
    }
    .card.evaluasi .lampiran a button:hover {
      background: linear-gradient(90deg, #ffe082 0%, #ffd600 100%);
      color: #333;
      transform: scale(1.07);
      box-shadow: 0 8px 24px #ffd60044;
    }
    @media (max-width: 900px) {
      .container { padding: 18px 4px; }
      .portfolio-columns { gap: 18px; }
      h2 { font-size: 1.5rem; }
      .portfolio-column { max-height: 340px; }
    }
    @media (max-width: 600px) {
      .container { padding: 8px 0; }
      .portfolio-columns { flex-direction: column; gap: 12px; }
      .portfolio-column { max-height: 260px; padding-right: 0; }
      .card { padding: 16px 8px; }
      h2 { font-size: 1.1rem; }
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
          <!-- Panah kanan default, akan diganti JS -->
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
    
    <div class="container">
      <div class="portfolio-columns">
        <div class="portfolio-column" id="tugas-column">
          <h3>Tugas</h3>
          <?php foreach ($tugas_saya as $row):
            $tugas = $tugas_map[$row['tugas_id']] ?? null;
            if (!$tugas) continue;
          ?>
          <div class="card tugas">
            <div class="judul">Tugas: <?= htmlspecialchars($tugas['judul'] ?? '-') ?></div>
            <div class="desc">Deskripsi: <?= htmlspecialchars($tugas['deskripsi'] ?? '-') ?></div>
            <div class="deadline">Deadline: <?= htmlspecialchars($tugas['deadline'] ?? '-') ?></div>
            <div class="nilai">Nilai: <?= htmlspecialchars($row['nilai']) ?></div>
            <div class="upload-time">Waktu Upload: <?= htmlspecialchars($row['upload_time']) ?></div>
            <div class="lampiran">
              <?php if (!empty($row['file'])): ?>
                <span class="lampiran-file">File:
                  <a href="../uploads/bahan_ajar/<?= urlencode($row['file']) ?>" target="_blank" class="button-link">
                    <button><i class="fa fa-eye"></i> Lihat</button>
                  </a>
                  <a href="../uploads/bahan_ajar/<?= urlencode($row['file']) ?>" download class="button-link">
                    <button><i class="fa fa-download"></i> Unduh</button>
                  </a>
                </span>
              <?php elseif (!empty($row['link'])): ?>
                <span class="lampiran-file">Link:
                  <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank" class="button-link">
                    <button><i class="fa fa-link"></i> Buka</button>
                  </a>
                </span>
              <?php elseif (!empty($tugas['lampiran_file'])): ?>
                <span class="lampiran-file">File:
                  <a href="../uploads/bahan_ajar/<?= urlencode($tugas['lampiran_file']) ?>" target="_blank" class="button-link">
                    <button><i class="fa fa-eye"></i> Lihat</button>
                  </a>
                  <a href="../uploads/bahan_ajar/<?= urlencode($tugas['lampiran_file']) ?>" download class="button-link">
                    <button><i class="fa fa-download"></i> Unduh</button>
                  </a>
                </span>
              <?php elseif (!empty($tugas['lampiran_link'])): ?>
                <span class="lampiran-file">Link:
                  <a href="<?= htmlspecialchars($tugas['lampiran_link']) ?>" target="_blank" class="button-link">
                    <button><i class="fa fa-link"></i> Buka</button>
                  </a>
                </span>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="portfolio-column" id="evaluasi-column">
          <h3>Evaluasi</h3>
          <?php foreach ($evaluasi_saya as $row):
            $evaluasi = $evaluasi_map[$row['evaluasi_id']] ?? null;
            if (!$evaluasi) continue;
          ?>
          <div class="card evaluasi">
            <div class="judul">Evaluasi: <?= htmlspecialchars($evaluasi['judul'] ?? '-') ?></div>
            <div class="desc">Deskripsi: <?= htmlspecialchars($evaluasi['deskripsi'] ?? '-') ?></div>
            <div class="deadline">Deadline: <?= htmlspecialchars($evaluasi['deadline'] ?? '-') ?></div>
            <div class="nilai">Nilai: <?= htmlspecialchars($row['nilai']) ?></div>
            <div class="upload-time">Waktu Upload: <?= htmlspecialchars($row['upload_time']) ?></div>
            <div class="lampiran">
              <?php if (!empty($row['file'])): ?>
                <span class="lampiran-file">File:
                  <a href="../uploads/bahan_ajar/<?= urlencode($row['file']) ?>" target="_blank" class="button-link">
                    <button><i class="fa fa-eye"></i> Lihat</button>
                  </a>
                  <a href="../uploads/bahan_ajar/<?= urlencode($row['file']) ?>" download class="button-link">
                    <button><i class="fa fa-download"></i> Unduh</button>
                  </a>
                </span>
              <?php elseif (!empty($row['link'])): ?>
                <span class="lampiran-file">Link:
                  <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank" class="button-link">
                    <button><i class="fa fa-link"></i> Buka</button>
                  </a>
                </span>
              <?php elseif (!empty($evaluasi['lampiran_file'])): ?>
                <span class="lampiran-file">File:
                  <a href="../uploads/bahan_ajar/<?= urlencode($evaluasi['lampiran_file']) ?>" target="_blank" class="button-link">
                    <button><i class="fa fa-eye"></i> Lihat</button>
                  </a>
                  <a href="../uploads/bahan_ajar/<?= urlencode($evaluasi['lampiran_file']) ?>" download class="button-link">
                    <button><i class="fa fa-download"></i> Unduh</button>
                  </a>
                </span>
              <?php elseif (!empty($evaluasi['lampiran_link'])): ?>
                <span class="lampiran-file">Link:
                  <a href="<?= htmlspecialchars($evaluasi['lampiran_link']) ?>" target="_blank" class="button-link">
                    <button><i class="fa fa-link"></i> Buka</button>
                  </a>
                </span>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php if (empty($tugas_saya) && empty($evaluasi_saya)): ?>
        <p style="margin-top:32px;color:#888;font-size:1.1rem;">Belum ada hasil tugas atau evaluasi yang sudah dinilai oleh guru.</p>
      <?php endif; ?>
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
