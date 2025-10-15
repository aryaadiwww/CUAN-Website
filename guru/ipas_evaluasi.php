<?php
// Halaman guru untuk upload evaluasi dan melihat hasil upload siswa
// Handler hapus evaluasi (POST ke halaman ini)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {
    $hapus_id = trim($_POST['hapus_id']);
    // Ambil data evaluasi saat ini (dari file JSON, bukan PHP)
    $evalPath = realpath(__DIR__ . '/../api/evaluasi_api.php');
    if ($evalPath === false) { $evalPath = __DIR__ . '/../api/evaluasi_api.php'; }
    $evaluasi_now = [];
    if (file_exists($evalPath)) {
        $evaluasi_now = json_decode(file_get_contents($evalPath), true);
        if (!is_array($evaluasi_now)) { $evaluasi_now = []; }
    }
    // Filter keluar yang dihapus dan hanya proses jika ditemukan
    $filtered = [];
    $found = false;
    foreach ($evaluasi_now as $row) {
        if ((string)$row['id'] === (string)$hapus_id) { $found = true; continue; }
        $filtered[] = $row;
    }
    if ($found) {
        // Simpan ulang data evaluasi dalam format JSON
        file_put_contents($evalPath, json_encode($filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // Bersihkan submissions terkait evaluasi yang dihapus
        $evalSubPath = realpath(__DIR__ . '/../evaluasi_submissions.json');
        if ($evalSubPath === false) { $evalSubPath = __DIR__ . '/../evaluasi_submissions.json'; }
        if (file_exists($evalSubPath)) {
            $subs = json_decode(file_get_contents($evalSubPath), true);
            if (!is_array($subs)) { $subs = []; }
            $subs_new = [];
            foreach ($subs as $s) { if ((string)$s['evaluasi_id'] !== (string)$hapus_id) { $subs_new[] = $s; } }
            file_put_contents($evalSubPath, json_encode($subs_new, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    }
    header('Location: ipas_evaluasi.php');
    exit;
}

$evaluasi = [];
$evalPath = realpath(__DIR__ . '/../api/evaluasi_api.php');
if ($evalPath === false) { $evalPath = __DIR__ . '/../api/evaluasi_api.php'; }
if (file_exists($evalPath)) {
    $evaluasi = json_decode(file_get_contents($evalPath), true);
    if (!is_array($evaluasi)) $evaluasi = [];
}
$submissions = [];
if (file_exists('../evaluasi_submissions.json')) {
    $submissions = json_decode(file_get_contents('../evaluasi_submissions.json'), true);
    if (!is_array($submissions)) $submissions = [];
}

// Ambil data siswa untuk mapping id ke username
$siswa_list = json_decode(@file_get_contents('http://localhost/CUAN/api/daftar_siswa_api.php'), true);
$siswa_map = [];
if (is_array($siswa_list) && isset($siswa_list['status']) && $siswa_list['status'] === 'success' && isset($siswa_list['data'])) {
    foreach ($siswa_list['data'] as $s) {
        // Map username to nama for display, or just use username
        $siswa_map[$s['username']] = $s['nama'];
    }
}
// Set local timezone for consistent local date-time formatting
@date_default_timezone_set('Asia/Jakarta');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Evaluasi IPAS - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --eval-primary: #8B5E3C; /* coklat utama */
      --eval-primary-dark: #6f4b30;
      --eval-accent: #C49A6C; /* coklat terang */
      --eval-bg1: #5a3b25; /* gradien background */
      --eval-bg2: #d7b899;
      --eval-card-bg: #f5e8d868; /* latar card krem */
      --eval-chip-bg: rgba(139, 94, 60, 0.12);
      --eval-shadow: rgba(139, 94, 60, 0.18);
    }

    body {
      background: linear-gradient(135deg, var(--eval-bg1) 0%, var(--eval-bg2) 100%);
      min-height: 100vh;
      margin: 0;
      font-family: 'Poppins', sans-serif;
      color: #222;
      overflow-x: hidden;
      display: flex;
      flex-direction: row;
      width: 100vw;
    }
    /* Sidebar biru seperti bahan ajar agar konsisten */
    .sidebar {
      width: 70px;
      background: linear-gradient(135deg, #6f4b30 0%, #8B5E3C 100%);
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
    .sidebar.open { width: 180px; }
    .sidebar .logo-section {
      display: flex; align-items: center; justify-content: center; padding: 1rem 0; height: 80px;
      opacity: 0; visibility: hidden; transition: opacity 0.4s, visibility 0.4s;
    }
    .sidebar.open .logo-section { opacity: 1; visibility: visible; transition-delay: 0.1s; }
    .sidebar .logo-section img { width: 120px; height: 60px; margin-right: 10px; transition: width 0.4s, height 0.4s, filter 0.4s; }
    .sidebar ul { list-style: none; padding: 0; margin-top: 10px; }
    .sidebar ul li { display: flex; align-items: center; padding: 14px 18px; cursor: pointer; border-radius: 18px; margin: 8px 8px; background: linear-gradient(135deg, #7e5637ff 0%, #a7724aff 100%); box-shadow: 0 2px 8px 0 rgba(255,179,71,0.04); transition: background 0.3s, transform 0.2s, box-shadow 0.3s; position: relative; overflow: hidden; }
    .sidebar ul li:hover { background: #1565C0; transform: scale(1.06) translateX(4px) rotate(-2deg); box-shadow: 0 4px 16px 0 rgba(255,94,98,0.12); }
    .sidebar ul li .menu-icon { font-size: 16px; display: flex; align-items: center; justify-content: center; transition: margin-right 0.4s, font-size 0.3s, padding-left 0.3s; }
    .sidebar.open ul li .menu-icon { margin-right: 15px; font-size: 28px; padding-left: 0; }
    .sidebar span.menu-text { display: none; font-size: 0.9rem; letter-spacing: 0.5px; transition: opacity 0.3s, margin-left 0.3s; opacity: 0; margin-left: 0; color: #fff; }
    .sidebar.open span.menu-text { display: inline; opacity: 1; margin-left: 1px; color: #fff; font-weight: 600; }

    .main-content { flex: 1; display: flex; flex-direction: column; min-height: 100vh; margin-left: 70px; transition: margin-left 0.4s; width: 100vw; overflow-x: hidden; overflow-y: auto; }
    .sidebar.open ~ .main-content { margin-left: 180px; transition: margin-left 0.4s; }

    header { color: white; display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 2rem; background: linear-gradient(135deg, var(--eval-primary) 0%, var(--eval-primary-dark) 100%); }
    .hamburger { cursor: pointer; background: #fff6; border: none; color: #ffffffd0; margin-right: 1rem; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08); font-size: unset; padding: 0; }
    .hamburger svg { width: 28px; height: 28px; display: block; }
    .hamburger:hover { background: #fff; color: var(--eval-primary); box-shadow: 0 4px 16px rgba(0,0,0,0.12); }
    .profile-menu { position: relative; display: flex; align-items: center; }
    .profile-button { background: linear-gradient(90deg, var(--eval-primary) 0%, var(--eval-primary) 100%); border: none; color: #fff; cursor: pointer; font-weight: bold; border-radius: 50px; padding: 5px 16px 5px 10px; display: flex; align-items: center; box-shadow: 0 2px 8px var(--eval-shadow); font-size: 1rem; position: relative; }
    .profile-button:hover { background: linear-gradient(90deg, var(--eval-primary-dark) 0%, var(--eval-primary-dark) 100%); color: #fff; box-shadow: 0 4px 16px var(--eval-shadow); }
    .profile-avatar { width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); object-fit: cover; }
    .dropdown { position: absolute; top: 100%; right: 0; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); width: 200px; opacity: 0; pointer-events: none; transform: translateY(10px); transition: opacity 0.3s, transform 0.3s; overflow: hidden; z-index: 20; border: 1.5px solid var(--eval-accent); }
    .dropdown.open { display: block; opacity: 1; pointer-events: auto; }
    .dropdown a { display: flex; align-items: center; gap: 8px; padding: 12px 18px; text-decoration: none; color: var(--eval-primary); font-weight: 600; font-size: 1rem; border-bottom: 1px solid #f3e6e6; }
    .dropdown a:last-child { border-bottom: none; }
    .dropdown a:hover { background: var(--eval-accent); color: #fff; }

    /* Konten Evaluasi */
    .eval-container { max-width: 1200px; margin: 0 auto; padding: 2rem; display: flex; flex-direction: column; align-items: stretch; }
    .eval-header { display: flex; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid rgba(255, 255, 255, 0.2); }
    .eval-icon { width: 64px; height: 64px; background: rgba(255, 255, 255, 0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-right: 1.5rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
    .eval-title { font-size: 2.2rem; font-weight: 700; color: #fff; margin: 0; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); }
    .eval-subtitle { font-size: 1.1rem; color: rgba(255, 255, 255, 0.85); margin-top: 0.5rem; }

    .eval-section-title { color: #fff; font-weight: 700; font-size: 1.3rem; margin: 1rem 0 0.75rem; }

    .eval-card {
      background: linear-gradient(135deg, #fffbe6 60%, #f5e8d8 100%);
      border-radius: 22px;
      padding: 1.6rem 1.4rem 1.2rem 1.4rem;
      box-shadow: 0 6px 32px 0 rgba(139,94,60,0.13), 0 1.5px 8px 0 rgba(139,94,60,0.08);
      display: flex;
      flex-direction: column;
      gap: 1.1rem;
      transition: transform 0.32s cubic-bezier(.68,-0.55,.27,1.55), box-shadow 0.32s;
      animation: fadeIn 0.6s ease-out;
      border-left: 7px solid var(--eval-primary);
      position: relative;
      overflow: hidden;
      margin-bottom: 2.2rem;
    }
    .eval-card::before {
      content: "";
      position: absolute;
      top: -40px; right: -40px;
      width: 110px; height: 110px;
      background: radial-gradient(circle, var(--eval-accent) 0%, transparent 70%);
      opacity: 0.18;
      z-index: 0;
      pointer-events: none;
    }
    .eval-card:hover {
      transform: translateY(-7px) scale(1.025) rotate(-0.5deg);
      box-shadow: 0 12px 40px 0 rgba(139,94,60,0.18), 0 2px 12px 0 rgba(139,94,60,0.10);
    }
    .eval-card-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 12px;
      z-index: 1;
      position: relative;
    }
    .eval-card-title {
      font-size: 1.45rem;
      font-weight: 800;
      color: var(--eval-primary);
      margin: 0;
      letter-spacing: 0.5px;
      text-shadow: 0 1.5px 8px rgba(139,94,60,0.07);
    }
    .eval-card-date {
      font-size: 1rem;
      color: #fff;
      background: linear-gradient(90deg, var(--eval-primary) 60%, var(--eval-accent) 100%);
      padding: 0.32rem 1.1rem;
      border-radius: 18px;
      font-weight: 600;
      box-shadow: 0 1.5px 8px rgba(139,94,60,0.08);
      border: none;
    }
    .eval-card-desc {
      font-size: 1.08rem;
      color: #3a2b1a;
      line-height: 1.7;
      margin-bottom: 0.2rem;
      z-index: 1;
      position: relative;
    }
    .eval-card-submissions {
      background: linear-gradient(90deg, #fff 60%, #f5e8d8 100%);
      border-radius: 14px;
      padding: 1.1rem 1rem 0.7rem 1rem;
      border: 1.5px dashed var(--eval-accent);
      box-shadow: 0 1.5px 8px rgba(139,94,60,0.04);
      z-index: 1;
      position: relative;
    }
    .eval-submissions-title {
      font-weight: 800;
      color: var(--eval-primary);
      margin: 0 0 0.7rem 0;
      font-size: 1.08rem;
      letter-spacing: 0.2px;
    }
    .eval-submissions {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }
    .eval-submissions li {
      background: linear-gradient(90deg, #f9e7d1 60%, #fff 100%);
      border: 1.5px solid var(--eval-accent);
      border-radius: 13px;
      padding: 13px 16px;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 12px;
      justify-content: space-between;
      box-shadow: 0 1.5px 8px rgba(139,94,60,0.06);
      position: relative;
      transition: box-shadow 0.2s, transform 0.2s;
    }
    .eval-submissions li:hover {
      box-shadow: 0 6px 24px rgba(139,94,60,0.13);
      transform: scale(1.015) translateY(-2px);
    }
    .eval-sub-left {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }
    .eval-sub-meta {
      font-size: 1.01rem;
      color: #4b3a2a;
      font-weight: 500;
      letter-spacing: 0.1px;
    }
    .eval-sub-actions form {
      display: inline-flex;
      gap: 10px;
      align-items: center;
    }
    .eval-grade-input {
      width: 90px;
      padding: 7px 10px;
      border-radius: 8px;
      border: 1.5px solid var(--eval-primary);
      font-size: 1rem;
      background: #fffbe6;
      font-weight: 600;
      color: var(--eval-primary);
      outline: none;
      transition: border 0.2s;
    }
    .eval-grade-input:focus {
      border: 1.5px solid var(--eval-primary-dark);
      background: #fff9e0;
    }
    .eval-btn {
      background: linear-gradient(90deg, var(--eval-primary) 60%, var(--eval-accent) 100%);
      border: none;
      color: #fff;
      padding: 0.5rem 1.1rem;
      border-radius: 9px;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      transition: background 0.3s, transform 0.2s, box-shadow 0.2s;
      box-shadow: 0 1.5px 8px rgba(139,94,60,0.08);
      letter-spacing: 0.2px;
    }
    .eval-btn:hover {
      background: linear-gradient(90deg, var(--eval-primary-dark) 60%, var(--eval-accent) 100%);
      transform: scale(1.06);
      box-shadow: 0 4px 16px rgba(139,94,60,0.13);
    }

    .eval-form-card {
      background: linear-gradient(135deg, #fffbe6 60%, #f5e8d8 100%);
      border-radius: 22px;
      padding: 1.6rem 1.4rem 1.2rem 1.4rem;
      box-shadow: 0 6px 32px 0 rgba(139,94,60,0.13), 0 1.5px 8px 0 rgba(139,94,60,0.08);
      border-left: 7px solid var(--eval-primary);
      animation: fadeIn 0.6s ease-out;
      margin-bottom: 2.2rem;
      position: relative;
      overflow: hidden;
    }
    .eval-form-row {
      display: flex;
      flex-direction: column;
      margin-bottom: 13px;
    }
    .eval-form-row input[type="text"], .eval-form-row input[type="date"], .eval-form-row textarea {
      border: 1.5px solid var(--eval-primary);
      border-radius: 11px;
      padding: 12px 13px;
      font-family: inherit;
      font-size: 1.05rem;
      background: #fffbe6;
      color: var(--eval-primary);
      font-weight: 600;
      outline: none;
      transition: border 0.2s;
    }
    .eval-form-row input[type="text"]:focus, .eval-form-row input[type="date"]:focus, .eval-form-row textarea:focus {
      border: 1.5px solid var(--eval-primary-dark);
      background: #fff9e0;
    }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }

    @media screen and (max-width: 1024px) {
      .eval-container { padding: 1.1rem; }
      .eval-card { padding: 1.1rem 0.7rem 0.7rem 0.7rem; }
      .eval-form-card { padding: 1.1rem 0.7rem 0.7rem 0.7rem; }
    }
    @media screen and (max-width: 768px) {
      .eval-container { padding: 0.7rem; }
      .eval-card-header { flex-direction: column; align-items: flex-start; gap: 6px; }
      .eval-submissions li { flex-direction: column; align-items: flex-start; gap: 7px; }
      .eval-sub-actions { width: 100%; display: flex; justify-content: flex-start; }
      .eval-card, .eval-form-card { padding: 0.7rem 0.5rem 0.5rem 0.5rem; }
    }
    @media screen and (max-width: 480px) {
      .eval-card, .eval-form-card { border-radius: 12px; padding: 0.4rem 0.2rem 0.2rem 0.2rem; }
      .eval-card-title { font-size: 1.1rem; }
      .eval-card-date { font-size: 0.85rem; padding: 0.2rem 0.7rem; }
      .eval-card-desc { font-size: 0.95rem; }
      .eval-sub-meta { font-size: 0.92rem; }
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
          <svg id="sidebarArrow" viewBox="0 0 24 24" fill="none"><path d="M8 5l8 7-8 7" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>
      <div class="profile-menu">
        <button class="profile-button" onclick="toggleDropdown()">
          <img src="../img/profile.png" alt="Avatar" class="profile-avatar" />
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left:2px;"><path d="M5 8L10 13L15 8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="dropdown" id="dropdown">
          <a href="guru_edit_profile.php"><svg width="18" height="18" fill="none" stroke="var(--eval-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>Edit Profil</a>
          <a href="../logout.php"><svg width="18" height="18" fill="none" stroke="var(--eval-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>Logout</a>
        </div>
      </div>
    </header>

    <div class="eval-container">
      <div class="eval-header">
        <div class="eval-icon">
          <!-- Icon evaluasi: kertas dan pensil (assessment) -->
          <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="8" width="28" height="36" rx="6" fill="#fff" stroke="#8B5E3C" stroke-width="2.5"/>
            <path d="M16 18h16M16 26h10M16 34h8" stroke="#8B5E3C" stroke-width="2.5" stroke-linecap="round"/>
            <rect x="32" y="32" width="7" height="7" rx="1.5" fill="#C49A6C" stroke="#8B5E3C" stroke-width="2"/>
            <path d="M35.5 35.5l2.5 2.5" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
            <rect x="18" y="4" width="12" height="8" rx="3" fill="#C49A6C" stroke="#8B5E3C" stroke-width="2"/>
            <path d="M38 38l-3-3" stroke="#8B5E3C" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </div>
        <div>
          <h1 class="eval-title">Evaluasi IPAS</h1>
          <p class="eval-subtitle">Buat evaluasi dan nilai pengumpulan siswa</p>
        </div>
      </div>

      <div class="eval-section-title">Buat Evaluasi Baru</div>
      <div class="eval-form-card">
        <form action="upload_evaluasi_guru.php" method="post" enctype="multipart/form-data">
          <div class="eval-form-row">
            <label>Judul Evaluasi</label>
            <input type="text" name="judul" required>
          </div>
          <div class="eval-form-row">
            <label>Deskripsi</label>
            <textarea name="deskripsi" required></textarea>
          </div>
          <div class="eval-form-row">
            <label>Deadline</label>
            <input type="datetime-local" name="deadline" required>
          </div>
          <div class="eval-form-row">
            <label>Lampiran File (opsional)</label>
            <input type="file" name="guru_file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.png,.zip,.rar">
          </div>
          <div class="eval-form-row">
            <label>atau Lampiran Link (opsional)</label>
            <input type="text" name="guru_link" placeholder="https://...">
          </div>
          <button type="submit" class="eval-btn">Upload Evaluasi</button>
        </form>
      </div>

      <div class="eval-section-title">Daftar Evaluasi & Penilaian Siswa</div>
      <?php foreach ($evaluasi as $e): ?>
        <div class="eval-card">
          <form action="ipas_evaluasi.php" method="post" class="form-hapus-evaluasi" style="position:absolute;top:10px;right:10px;z-index:3;">
            <input type="hidden" name="hapus_id" value="<?php echo htmlspecialchars($e['id']); ?>">
            <button type="submit" title="Hapus" aria-label="Hapus" style="background:#c0392b;color:#fff;border:none;border-radius:10px;padding:6px 10px;font-weight:800;cursor:pointer;box-shadow:0 2px 8px rgba(192,57,43,.3);">×</button>
          </form>
          <div class="eval-card-header">
            <h3 class="eval-card-title"><?php echo htmlspecialchars($e['judul']); ?></h3>
          </div>
          <p class="eval-card-desc"><?php echo nl2br(htmlspecialchars($e['deskripsi'])); ?></p>
        <?php
          // Format deadline: pisahkan tanggal dan jam
          $dl = $e['deadline'];
          $dl_tgl = $dl_jam = '';
          if ($dl) {
            $ts = strtotime($dl);
            if ($ts) {
              $dl_tgl = date('d-m-Y', $ts);
              $dl_jam = date('H:i', $ts);
            } else {
              $dl_tgl = $dl;
            }
          }
        ?>
        <span class="eval-card-date" style="margin-left:0.2rem;margin-top:0.2rem;margin-bottom:0.5rem;display:inline-block;">
          Deadline:
          <span style="font-weight:600;letter-spacing:0.5px;"> <?php echo htmlspecialchars($dl_tgl); ?> </span>
          <span style="color:#8B5E3C;font-weight:700;font-size:1.08em;"> | </span>
          <span style="font-weight:600;letter-spacing:0.5px;"> <?php echo htmlspecialchars($dl_jam); ?> </span>
        </span>
          <?php if (!empty($e['lampiran_file']) || !empty($e['lampiran_link'])): ?>
            <div class="eval-attachments" style="margin:-2px 0 8px;padding:10px 12px;border:1.5px dashed var(--eval-accent);border-radius:12px;background:#fffaf0;">
              <strong style="color:var(--eval-primary);display:block;margin-bottom:4px;">Lampiran dari Guru</strong>
              <?php if (!empty($e['lampiran_file'])): ?>
                File:
                <a href="../uploads/bahan_ajar/<?php echo htmlspecialchars($e['lampiran_file']); ?>" target="_blank" style="color:var(--eval-primary);text-decoration:underline;">Lihat</a>
                &nbsp;|
                <a href="../uploads/bahan_ajar/<?php echo htmlspecialchars($e['lampiran_file']); ?>" download style="color:var(--eval-primary);text-decoration:underline;">Unduh</a><br>
              <?php endif; ?>
              <?php if (!empty($e['lampiran_link'])): ?>
                Link: <a href="<?php echo htmlspecialchars($e['lampiran_link']); ?>" target="_blank" style="color:var(--eval-primary);text-decoration:underline;word-break:break-all;">Kunjungi</a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <div class="eval-card-submissions">
            <div class="eval-submissions-title">Pengumpulan Siswa</div>
            <?php
              $ada = false;
              foreach ($submissions as $sub) {
                if ($sub['evaluasi_id'] == $e['id']) { $ada = true; break; }
              }
            ?>
            <?php if (!$ada): ?>
              <div style="color:#555;">Belum ada pengumpulan.</div>
            <?php else: ?>
              <ul class="eval-submissions">
                <?php foreach ($submissions as $sub): if ($sub['evaluasi_id'] == $e['id']): ?>
                  <li>
                    <div class="eval-sub-left">
                      <div class="eval-sub-meta">
                        Siswa: <?php
                          $sid = $sub['siswa_id'];
                          // Show username only, like greeting style
                          echo htmlspecialchars($sid);
                        ?>
                      </div>
                      <div class="eval-sub-meta">
                        Waktu Upload: <?php
                          // Prefer stored upload_time or waktu_upload; fallback to parse from filename timestamp
                          $upload_time_str = null;
                          if (!empty($sub['upload_time'])) {
                            $upload_time_str = $sub['upload_time'];
                          } elseif (!empty($sub['waktu_upload'])) {
                            $upload_time_str = $sub['waktu_upload'];
                          } elseif (!empty($sub['file']) && preg_match('/_(\d+)\.[a-zA-Z0-9]+$/', $sub['file'], $m)) {
                            $upload_time_str = date('Y-m-d H:i:s', (int)$m[1]);
                          }
                          $upload_time_local = $upload_time_str ? date('d-m-Y H:i:s', strtotime($upload_time_str)) : '-';
                          echo $upload_time_local;
                        ?>
                      </div>
                      <div class="eval-sub-meta">
                        <?php if (!empty($sub['file'])): ?>
                          File: <a href="../uploads/bahan_ajar/<?php echo htmlspecialchars($sub['file']); ?>" target="_blank" style="color:var(--eval-primary);text-decoration:underline;">Lihat File</a>
                        <?php endif; ?>
                        <?php if (!empty($sub['link'])): ?>
                          <?php if (!empty($sub['file'])): ?> | <?php endif; ?>
                          Link: <a href="<?php echo htmlspecialchars($sub['link']); ?>" target="_blank" style="color:var(--eval-primary);text-decoration:underline;">Lihat Link</a>
                        <?php endif; ?>
                      </div>
                      <div class="eval-sub-meta">
                        Nilai: <?php echo ($sub['nilai'] !== null && $sub['nilai'] !== '') ? htmlspecialchars($sub['nilai']) : 'Belum dinilai'; ?>
                      </div>
                    </div>
                    <div class="eval-sub-actions">
                      <?php if (!$sub['nilai']): ?>
                        <form action="../api/guru_nilai_evaluasi.php" method="post">
                          <input type="hidden" name="evaluasi_id" value="<?php echo $e['id']; ?>">
                          <input type="hidden" name="siswa_id" value="<?php echo $sub['siswa_id']; ?>">
                          <input class="eval-grade-input" type="number" name="nilai" min="0" max="100" required placeholder="0-100">
                          <button type="submit" class="eval-btn">Nilai</button>
                        </form>
                      <?php endif; ?>
                    </div>
                  </li>
                <?php endif; endforeach; ?>
              </ul>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('open');
    }
    function toggleDropdown() {
      const dropdown = document.getElementById('dropdown');
      dropdown.classList.toggle('open');
    }
    document.addEventListener('click', function(e) {
      const dropdown = document.getElementById('dropdown');
      const profileBtn = document.querySelector('.profile-button');
      if (!profileBtn || !dropdown) return;
      if (!profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('open');
      }
    });

    // Custom popup untuk validasi dan konfirmasi
    function showCustomPopupEval(message, options = { confirm: false, onConfirm: null, onCancel: null }) {
      let popup = document.createElement('div');
      popup.className = 'custom-popup-eval';
      popup.innerHTML = `
        <div class=\"custom-popup-eval-content\">
          <div class=\"custom-popup-eval-icon\">📝</div>
          <div class=\"custom-popup-eval-message\">${message}</div>
          <div class=\"custom-popup-eval-actions\"></div>
        </div>
      `;
      document.body.appendChild(popup);
      const actions = popup.querySelector('.custom-popup-eval-actions');
      if (options.confirm) {
        const yesBtn = document.createElement('button');
        yesBtn.textContent = 'Ya';
        yesBtn.className = 'custom-popup-eval-btn yes';
        yesBtn.onclick = function() {
          document.body.removeChild(popup);
          if (options.onConfirm) options.onConfirm();
        };
        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = 'Batal';
        cancelBtn.className = 'custom-popup-eval-btn cancel';
        cancelBtn.onclick = function() {
          document.body.removeChild(popup);
          if (options.onCancel) options.onCancel();
        };
        actions.appendChild(yesBtn);
        actions.appendChild(cancelBtn);
      } else {
        const okBtn = document.createElement('button');
        okBtn.textContent = 'Oke!';
        okBtn.className = 'custom-popup-eval-btn ok';
        okBtn.onclick = function() {
          document.body.removeChild(popup);
        };
        actions.appendChild(okBtn);
      }
    }

    window.addEventListener('DOMContentLoaded', function() {
      // Validasi form upload evaluasi: wajib isi file atau link
      var form = document.querySelector('.eval-form-card form');
      if (form) {
        form.addEventListener('submit', function(e) {
          var fileInput = form.querySelector('input[name="guru_file"]');
          var linkInput = form.querySelector('input[name="guru_link"]');
          var fileFilled = fileInput && fileInput.files && fileInput.files.length > 0;
          var linkFilled = linkInput && linkInput.value.trim() !== '';
          if (!fileFilled && !linkFilled) {
            e.preventDefault();
            showCustomPopupEval('Harap unggah file atau isi link (salah satu wajib diisi)!', { confirm: false });
            return false;
          }
          // Konfirmasi sebelum upload
          e.preventDefault();
          showCustomPopupEval('Yakin ingin mengupload evaluasi ini?', {
            confirm: true,
            onConfirm: function() {
              form.submit();
              sessionStorage.setItem('popup_upload_success_eval', '1');
            },
            onCancel: function() { /* batal, tidak submit */ }
          });
        });
      }

      // Konfirmasi hapus evaluasi dengan popup
      document.querySelectorAll('.form-hapus-evaluasi').forEach(function(hapusForm) {
        hapusForm.addEventListener('submit', function(e) {
          e.preventDefault();
          showCustomPopupEval('Yakin ingin menghapus evaluasi ini? Tindakan ini akan menghapus juga di halaman siswa.', {
            confirm: true,
            onConfirm: function() {
              hapusForm.submit();
              sessionStorage.setItem('popup_hapus_success_eval', '1');
            },
            onCancel: function() { /* batal hapus */ }
          });
        });
      });

      // Prevent form undo on back, force real back navigation
      if (!history.state || !history.state.preventFormUndo) {
        history.replaceState({preventFormUndo:true, first:true}, '');
        history.pushState({preventFormUndo:true, first:false}, '');
      }

      // Tampilkan popup sukses upload jika baru saja upload
      if (sessionStorage.getItem('popup_upload_success_eval')) {
        showCustomPopupEval('Evaluasi berhasil diupload! 🎉', { confirm: false });
        sessionStorage.removeItem('popup_upload_success_eval');
      }
      // Tampilkan popup sukses hapus jika baru saja hapus
      if (sessionStorage.getItem('popup_hapus_success_eval')) {
        showCustomPopupEval('Evaluasi berhasil dihapus! 🗑️', { confirm: false });
        sessionStorage.removeItem('popup_hapus_success_eval');
      }
    });
    // Tambahkan style untuk custom popup evaluasi
    const popupStyleEval = document.createElement('style');
    popupStyleEval.innerHTML = `
      .custom-popup-eval {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(139,94,60,0.18); z-index: 9999; display: flex; align-items: center; justify-content: center;
        animation: fadeIn 0.3s;
      }
      .custom-popup-eval-content {
        background: linear-gradient(135deg, #fffbe6 60%, #f5e8d8 100%);
        border-radius: 22px; box-shadow: 0 6px 32px 0 rgba(139,94,60,0.13);
        padding: 2.2rem 2rem 1.5rem 2rem; min-width: 320px; max-width: 90vw;
        display: flex; flex-direction: column; align-items: center; gap: 1.1rem;
        border-left: 7px solid var(--eval-primary);
        position: relative;
      }
      .custom-popup-eval-icon {
        font-size: 2.8rem; margin-bottom: 0.5rem;
        animation: bounce 0.7s infinite alternate;
      }
      @keyframes bounce { from { transform: translateY(0); } to { transform: translateY(-10px); } }
      .custom-popup-eval-message {
        font-size: 1.15rem; color: var(--eval-primary); font-weight: 700; text-align: center;
      }
      .custom-popup-eval-actions {
        display: flex; gap: 18px; margin-top: 1.1rem;
      }
      .custom-popup-eval-btn {
        background: linear-gradient(90deg, var(--eval-primary) 60%, var(--eval-accent) 100%);
        border: none; color: #fff; padding: 0.7rem 1.5rem; border-radius: 9px; font-size: 1.08rem; font-weight: 700;
        cursor: pointer; box-shadow: 0 1.5px 8px rgba(139,94,60,0.08); letter-spacing: 0.2px; transition: background 0.3s, transform 0.2s;
      }
      .custom-popup-eval-btn:hover {
        background: linear-gradient(90deg, var(--eval-primary-dark) 60%, var(--eval-accent) 100%);
        transform: scale(1.06);
        box-shadow: 0 4px 16px rgba(139,94,60,0.13);
      }
      .custom-popup-eval-btn.cancel { background: #c0392b; }
      .custom-popup-eval-btn.cancel:hover { background: #e74c3c; }
      .custom-popup-eval-btn.yes { background: #8B5E3C; }
      .custom-popup-eval-btn.ok { background: #C49A6C; }
    `;
    document.head.appendChild(popupStyleEval);
    window.addEventListener('popstate', function(e) {
      if (e.state && e.state.preventFormUndo) {
        if (e.state.first) {
          history.back();
        }
      }
    });
  </script>
    <script src="../music-player.js"></script>
</body>
</html>