<?php
// Ambil data tugas dari API
$tugas = json_decode(file_get_contents('http://localhost/CUAN/api/tugas_api.php'), true);
if (!is_array($tugas)) $tugas = [];
// Ambil data upload siswa dari JSON
$submissions = json_decode(file_get_contents('../tugas_submissions.json'), true);
if (!is_array($submissions)) $submissions = [];
session_start();
$level = $_SESSION['level'] ?? '';
$username = $_SESSION['username'] ?? '';
$siswa_id = ($level === 'siswa' && $username) ? $username : ($_SESSION['siswa_id'] ?? '1'); // gunakan username siswa login

function sudah_upload($tugas_id, $siswa_id, $submissions) {
    foreach ($submissions as $sub) {
        if ($sub['tugas_id'] == $tugas_id && $sub['siswa_id'] == $siswa_id) {
            return $sub;
        }
    }
    return false;
}

// Ambil data siswa untuk mapping username -> nama
$siswa_list = json_decode(@file_get_contents('http://localhost/CUAN/api/daftar_siswa_api.php'), true);
$siswa_map = [];
if (is_array($siswa_list) && isset($siswa_list['status']) && $siswa_list['status'] === 'success' && isset($siswa_list['data'])) {
    foreach ($siswa_list['data'] as $s) {
        $siswa_map[$s['username']] = $s['nama'] ?? $s['username'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas IPAS Siswa - CUAN</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../responsive.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
    <style>
    :root {
      --tugas-primary: #6A4C93;
      --tugas-primary-dark: #4b3570;
      --tugas-accent: #B983FF;
      --tugas-bg1: #4b3570;
      --tugas-bg2: #b983ff;
      --tugas-card-bg: #faf6ff;
      --tugas-chip-bg: rgba(106,76,147,0.12);
      --tugas-shadow: rgba(106,76,147,0.18);
    }
    body {
      background: linear-gradient(135deg, var(--tugas-bg1) 0%, var(--tugas-bg2) 100%);
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
      background: linear-gradient(135deg, #513474 0%, #5a3e82  100%);
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
    .sidebar ul li { display: flex; align-items: center; padding: 14px 18px; cursor: pointer; border-radius: 18px; margin: 8px 8px; background: linear-gradient(135deg, #5e3c86ff 0%, #8254bbff 100%); box-shadow: 0 2px 8px 0 rgba(255,179,71,0.04); transition: background 0.3s, transform 0.2s, box-shadow 0.3s; position: relative; overflow: hidden; }
    .sidebar ul li:hover { background: #1565C0; transform: scale(1.06) translateX(4px) rotate(-2deg); box-shadow: 0 4px 16px 0 rgba(255,94,98,0.12); }
    .sidebar ul li .menu-icon { font-size: 16px; display: flex; align-items: center; justify-content: center; transition: margin-right 0.4s, font-size 0.3s, padding-left 0.3s; }
    .sidebar.open ul li .menu-icon { margin-right: 15px; font-size: 28px; padding-left: 0; }
    .sidebar span.menu-text { display: none; font-size: 0.9rem; letter-spacing: 0.5px; transition: opacity 0.3s, margin-left 0.3s; opacity: 0; margin-left: 0; color: #fff; }
    .sidebar.open span.menu-text { display: inline; opacity: 1; margin-left: 1px; color: #fff; font-weight: 600; }
    .main-content { flex: 1; display: flex; flex-direction: column; min-height: 100vh; margin-left: 70px; transition: margin-left 0.4s; width: 100vw; overflow-x: hidden; overflow-y: auto; }
    .sidebar.open ~ .main-content { margin-left: 180px; transition: margin-left 0.4s; }
    header { color: white; display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 2rem; background: linear-gradient(135deg, var(--tugas-primary) 0%, var(--tugas-primary-dark) 100%); }
    .hamburger { cursor: pointer; background: #fff6; border: none; color: #ffffffd0; margin-right: 1rem; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08); font-size: unset; padding: 0; }
    .hamburger svg { width: 28px; height: 28px; display: block; }
    .hamburger:hover { background: #fff; color: var(--tugas-primary); box-shadow: 0 4px 16px rgba(0,0,0,0.12); }
    .profile-menu { position: relative; display: flex; align-items: center; }
    .profile-button { background: linear-gradient(90deg, var(--tugas-primary) 0%, var(--tugas-primary) 100%); border: none; color: #fff; cursor: pointer; font-weight: bold; border-radius: 50px; padding: 5px 16px 5px 10px; display: flex; align-items: center; box-shadow: 0 2px 8px var(--tugas-shadow); font-size: 1rem; position: relative; }
    .profile-button:hover { background: linear-gradient(90deg, var(--tugas-primary-dark) 0%, var(--tugas-primary-dark) 100%); color: #fff; box-shadow: 0 4px 16px var(--tugas-shadow); }
    .profile-avatar { width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); object-fit: cover; }
    .dropdown { position: absolute; top: 100%; right: 0; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); width: 200px; opacity: 0; pointer-events: none; transform: translateY(10px); transition: opacity 0.3s, transform 0.3s; overflow: hidden; z-index: 20; border: 1.5px solid var(--tugas-accent); }
    .dropdown.open { display: block; opacity: 1; pointer-events: auto; }
    .dropdown a { display: flex; align-items: center; gap: 8px; padding: 12px 18px; text-decoration: none; color: var(--tugas-primary); font-weight: 600; font-size: 1rem; border-bottom: 1px solid #f3e6e6; }
    .dropdown a:last-child { border-bottom: none; }
    .dropdown a:hover { background: var(--tugas-accent); color: #fff; }
    .tugas-container { max-width: 1200px; margin: 0 auto; padding: 2rem; display: flex; flex-direction: column; align-items: stretch; }
    .tugas-header { display: flex; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid rgba(255, 255, 255, 0.2); }
    .tugas-icon { width: 64px; height: 64px; background: rgba(255, 255, 255, 0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-right: 1.5rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
    .tugas-title { font-size: 2.2rem; font-weight: 700; color: #fff; margin: 0; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); }
    .tugas-subtitle { font-size: 1.1rem; color: rgba(255, 255, 255, 0.85); margin-top: 0.5rem; }
    .tugas-section-title { color: #fff; font-weight: 700; font-size: 1.3rem; margin: 1rem 0 0.75rem; }
    .tugas-card-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); gap: 2.2rem; margin-bottom: 2.2rem; align-items: stretch; }
    .tugas-card { background: linear-gradient(135deg, #faf6ff 60%, #e6e0fa 100%); border-radius: 22px; padding: 1.6rem 1.4rem 1.2rem 1.4rem; box-shadow: 0 6px 32px 0 rgba(106,76,147,0.13), 0 1.5px 8px 0 rgba(106,76,147,0.08); display: flex; flex-direction: column; gap: 1.1rem; transition: transform 0.32s cubic-bezier(.68,-0.55,.27,1.55), box-shadow 0.32s; animation: fadeIn 0.6s ease-out; border-left: 7px solid var(--tugas-primary); position: relative; overflow: hidden; min-width: 0; width: 100%; max-width: 100%; margin-bottom: 0; }
    .tugas-card-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; z-index: 1; position: relative; }
    .tugas-card-title { font-size: 1.45rem; font-weight: 800; color: var(--tugas-primary); margin: 0; letter-spacing: 0.5px; text-shadow: 0 1.5px 8px rgba(106,76,147,0.07); }
    .tugas-card-date { font-size: 1rem; color: #fff; background: linear-gradient(90deg, var(--tugas-primary) 60%, var(--tugas-accent) 100%); padding: 0.32rem 1.1rem; border-radius: 18px; font-weight: 600; box-shadow: 0 1.5px 8px rgba(106,76,147,0.08); border: none; }
    .tugas-card-desc { font-size: 1.08rem; color: #3a2b1a; line-height: 1.7; margin-bottom: 0.2rem; z-index: 1; position: relative; }
    .tugas-card-submissions { background: linear-gradient(90deg, #fff 60%, #faf6ff 100%); border-radius: 14px; padding: 1.1rem 1rem 0.7rem 1rem; border: 1.5px dashed var(--tugas-accent); box-shadow: 0 1.5px 8px rgba(106,76,147,0.04); z-index: 1; position: relative; }
    .tugas-submissions-title { font-weight: 800; color: var(--tugas-primary); margin: 0 0 0.7rem 0; font-size: 1.08rem; letter-spacing: 0.2px; }
    .tugas-submissions { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 14px; }
    .tugas-submissions li { background: linear-gradient(90deg, #e6e0fa 60%, #fff 100%); border: 1.5px solid var(--tugas-accent); border-radius: 13px; padding: 13px 16px; display: flex; flex-wrap: wrap; align-items: center; gap: 12px; justify-content: space-between; box-shadow: 0 1.5px 8px rgba(106,76,147,0.06); position: relative; transition: box-shadow 0.2s, transform 0.2s; }
    .tugas-submissions li:hover { box-shadow: 0 6px 24px rgba(106,76,147,0.13); transform: scale(1.015) translateY(-2px); }
    .tugas-sub-left { display: flex; flex-direction: column; gap: 5px; }
    .tugas-sub-meta { font-size: 1.01rem; color: #4b3a2a; font-weight: 500; letter-spacing: 0.1px; }
    .tugas-sub-actions form { display: inline-flex; gap: 10px; align-items: center; }
    .tugas-grade-input { width: 90px; padding: 7px 10px; border-radius: 8px; border: 1.5px solid var(--tugas-primary); font-size: 1rem; background: #faf6ff; font-weight: 600; color: var(--tugas-primary); outline: none; transition: border 0.2s; }
    .tugas-grade-input:focus { border: 1.5px solid var(--tugas-primary-dark); background: #f3e6ff; }
    .tugas-btn { background: linear-gradient(90deg, var(--tugas-primary) 60%, var(--tugas-accent) 100%); border: none; color: #fff; padding: 0.5rem 1.1rem; border-radius: 9px; font-size: 1rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; transition: background 0.3s, transform 0.2s, box-shadow 0.2s; box-shadow: 0 1.5px 8px rgba(106,76,147,0.08); letter-spacing: 0.2px; }
    .tugas-btn:hover { background: linear-gradient(90deg, var(--tugas-primary-dark) 60%, var(--tugas-accent) 100%); transform: scale(1.06); box-shadow: 0 4px 16px rgba(106,76,147,0.13); }
    .tugas-form-card { background: linear-gradient(135deg, #faf6ff 60%, #e6e0fa 100%); border-radius: 22px; padding: 1.6rem 1.4rem 1.2rem 1.4rem; box-shadow: 0 6px 32px 0 rgba(106,76,147,0.13), 0 1.5px 8px 0 rgba(106,76,147,0.08); border-left: 7px solid var(--tugas-primary); animation: fadeIn 0.6s ease-out; margin-bottom: 2.2rem; position: relative; overflow: hidden; }
    @media screen and (max-width: 1024px) {
      .tugas-container { padding: 1.1rem; }
      .tugas-card-list { grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.2rem; }
      .tugas-card { padding: 1.1rem 0.7rem 0.7rem 0.7rem; }
      .tugas-form-card { padding: 1.1rem 0.7rem 0.7rem 0.7rem; }
    }
    @media screen and (max-width: 768px) {
      .tugas-container { padding: 0.7rem; }
      .tugas-card-list { grid-template-columns: 1fr; gap: 0.7rem; }
      .tugas-card-header { flex-direction: column; align-items: flex-start; gap: 6px; }
      .tugas-submissions li { flex-direction: column; align-items: flex-start; gap: 7px; }
      .tugas-sub-actions { width: 100%; display: flex; justify-content: flex-start; }
      .tugas-card, .tugas-form-card { padding: 0.7rem 0.5rem 0.5rem 0.5rem; }
    }
    @media screen and (max-width: 480px) {
      .tugas-card-list { grid-template-columns: 1fr; gap: 0.3rem; }
      .tugas-card, .tugas-form-card { border-radius: 12px; padding: 0.4rem 0.2rem 0.2rem 0.2rem; }
      .tugas-card-title { font-size: 1.1rem; }
      .tugas-card-date { font-size: 0.85rem; padding: 0.2rem 0.7rem; }
      .tugas-card-desc { font-size: 0.95rem; }
      .tugas-sub-meta { font-size: 0.92rem; }
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
          <svg id="sidebarArrow" viewBox="0 0 24 24" fill="none"><path d="M8 5l8 7-8 7" stroke="#6A4C93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>
      <div class="profile-menu">
        <button class="profile-button" onclick="toggleDropdown()">
          <img src="../img/profile.png" alt="Avatar" class="profile-avatar" />
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left:2px;"><path d="M5 8L10 13L15 8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="dropdown" id="dropdown">
          <a href="siswa_edit_profile.php"><svg width="18" height="18" fill="none" stroke="#6A4C93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>Edit Profil</a>
          <a href="../logout.php"><svg width="18" height="18" fill="none" stroke="#6A4C93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>Logout</a>
        </div>
      </div>
    </header>
    
    <div class="tugas-container">
      <div class="tugas-header">
        <div class="tugas-icon">
          <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="8" width="28" height="36" rx="6" fill="#fff" stroke="#6A4C93" stroke-width="2.5"/>
            <path d="M16 18h16M16 26h10M16 34h8" stroke="#6A4C93" stroke-width="2.5" stroke-linecap="round"/>
            <rect x="32" y="32" width="7" height="7" rx="1.5" fill="#B983FF" stroke="#6A4C93" stroke-width="2"/>
            <path d="M35.5 35.5l2.5 2.5" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
            <rect x="18" y="4" width="12" height="8" rx="3" fill="#B983FF" stroke="#6A4C93" stroke-width="2"/>
            <path d="M38 38l-3-3" stroke="#6A4C93" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </div>
        <div>
          <h1 class="tugas-title">Tugas IPAS</h1>
          <p class="tugas-subtitle">Kumpulkan tugas yang diberikan guru dan lihat hasil penilaian</p>
        </div>
      </div>
      <div class="tugas-section-title">Daftar Tugas & Pengumpulan Siswa</div>
      <div class="tugas-card-list">
        <?php if (empty($tugas)): ?>
          <div class="tugas-card tugas-empty" style="background:rgba(255,255,255,0.1);border-radius:16px;padding:3rem 2rem;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,0.1);animation:fadeIn 0.8s ease-out;">
            <svg width="80" height="80" style="margin-bottom:1.5rem;opacity:0.7;" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="10" y="8" width="60" height="60" rx="12" fill="#faf6ff" stroke="#B983FF" stroke-width="3"/><path d="M30 38h28M30 50h18" stroke="#B983FF" stroke-width="3" stroke-linecap="round"/></svg>
            <div class="tugas-empty-text" style="font-size:1.4rem;font-weight:600;color:#6A4C93;margin-bottom:0.5rem;">Belum ada tugas IPAS dari guru</div>
            <div class="tugas-empty-subtext" style="font-size:1rem;color:rgba(106,76,147,0.7);max-width:500px;margin:0 auto;">Tugas akan muncul di sini jika guru sudah mengunggah tugas baru. Silakan cek secara berkala.</div>
          </div>
        <?php endif; ?>
        <?php foreach ($tugas as $t): ?>
          <div class="tugas-card">
            <div class="tugas-card-header">
              <h3 class="tugas-card-title"><?php echo htmlspecialchars($t['judul']); ?></h3>
            </div>
            <p class="tugas-card-desc"><?php echo nl2br(htmlspecialchars($t['deskripsi'])); ?></p>
            <?php
              $dl = $t['deadline'];
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
            <span class="tugas-card-date" style="margin-left:0.2rem;margin-top:0.2rem;margin-bottom:0.5rem;display:inline-block;">
              Deadline:
              <span style="font-weight:600;letter-spacing:0.5px;"> <?php echo htmlspecialchars($dl_tgl); ?> </span>
              <span style="color:#6A4C93;font-weight:700;font-size:1.08em;"> | </span>
              <span style="font-weight:600;letter-spacing:0.5px;"> <?php echo htmlspecialchars($dl_jam); ?> </span>
            </span>
            <?php if (!empty($t['lampiran_file']) || !empty($t['lampiran_link'])): ?>
              <div class="tugas-attachments" style="margin:-2px 0 8px;padding:10px 12px;border:1.5px dashed var(--tugas-accent);border-radius:12px;background:#faf6ff;">
                <strong style="color:var(--tugas-primary);display:block;margin-bottom:4px;">Lampiran dari Guru</strong>
                <?php if (!empty($t['lampiran_file'])): ?>
                File:
                <a href="../uploads/bahan_ajar/<?php echo htmlspecialchars($t['lampiran_file']); ?>" target="_blank" style="color:var(--tugas-primary);text-decoration:underline;">Lihat</a>
                &nbsp;|
                <a href="../uploads/bahan_ajar/<?php echo htmlspecialchars($t['lampiran_file']); ?>" download style="color:var(--tugas-primary);text-decoration:underline;">Unduh</a><br>
                <?php endif; ?>
                <?php if (!empty($t['lampiran_link'])): ?>
                  Link: <a href="<?php echo htmlspecialchars($t['lampiran_link']); ?>" target="_blank" style="color:var(--tugas-primary);text-decoration:underline;word-break:break-all;">Kunjungi</a>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <div class="tugas-card-submissions">
              <div class="tugas-submissions-title">Pengumpulan Siswa</div>
              <?php
              // Tampilkan semua pengumpulan siswa untuk tugas ini
              foreach ($submissions as $sub) {
                if ($sub['tugas_id'] == $t['id']) {
                  $sid = $sub['siswa_id'];
                  $username_disp = isset($siswa_map[$sid]) ? $siswa_map[$sid] : $sid;
                  $upload_time = isset($sub['upload_time']) ? $sub['upload_time'] : null;
                  $upload_time_local = $upload_time ? date('d-m-Y H:i:s', strtotime($upload_time)) : '-';
                  echo '<div class="tugas-sub-meta" style="margin-bottom:6px;">';
                  echo '<b style="color:var(--tugas-primary);font-size:1.08em;">'.$username_disp.'</b>';
                  echo '</div>';
                  if (!empty($sub['file'])) {
                    echo 'File: <a href="../uploads/bahan_ajar/'.htmlspecialchars($sub['file']).'" target="_blank">Lihat File</a><br>';
                  }
                  if (!empty($sub['link'])) {
                    echo 'Link: <a href="'.htmlspecialchars($sub['link']).'" target="_blank">Lihat Link</a><br>';
                  }
                  if (isset($sub['nilai'])) {
                    echo '<span>Nilai: <b>'.htmlspecialchars($sub['nilai']).'</b></span>';
                  } else {
                    echo '<span>Menunggu penilaian guru...</span>';
                  }
                  echo '<hr style="margin:10px 0;border:0;border-top:1px dashed #B983FF;">';
                }
              }
              ?>
              <?php
                $upload = sudah_upload($t['id'], $siswa_id, $submissions);
                if (!$upload) {
                  $legacy_id = null;
                  if (is_array($siswa_list) && isset($siswa_list['status']) && $siswa_list['status'] === 'success' && isset($siswa_list['data'])) {
                    foreach ($siswa_list['data'] as $row) {
                      if (isset($row['username']) && (string)$row['username'] === (string)$siswa_id) {
                        $legacy_id = (string)$row['id'];
                        break;
                      }
                    }
                  }
                  if ($legacy_id !== null) {
                    $upload = sudah_upload($t['id'], $legacy_id, $submissions);
                  }
                }
              ?>
              <?php if (!$upload): ?>
                <form class="form-upload-tugas" action="upload_tugas.php" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="tugas_id" value="<?php echo $t['id']; ?>">
                  <div class="tugas-form-row">
                    <label>Upload File:</label>
                    <input type="file" name="file_upload" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.png,.zip,.rar">
                  </div>
                  <div class="tugas-form-row">
                    <label>atau Link:</label>
                    <input type="text" name="link_upload" placeholder="https://...">
                  </div>
                  <button type="submit" class="tugas-btn">Kumpulkan</button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
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
    // Custom popup untuk validasi dan notifikasi
    function showCustomPopupTugas(message, options = { confirm: false, onConfirm: null, onCancel: null }) {
      let popup = document.createElement('div');
      popup.className = 'custom-popup-tugas';
      popup.innerHTML = `
        <div class=\"custom-popup-tugas-content\">
          <div class=\"custom-popup-tugas-icon\">📚</div>
          <div class=\"custom-popup-tugas-message\">${message}</div>
          <div class=\"custom-popup-tugas-actions\"></div>
        </div>
      `;
      document.body.appendChild(popup);
      const actions = popup.querySelector('.custom-popup-tugas-actions');
      if (options.confirm) {
        const yesBtn = document.createElement('button');
        yesBtn.textContent = 'Ya';
        yesBtn.className = 'custom-popup-tugas-btn yes';
        yesBtn.onclick = function() {
          document.body.removeChild(popup);
          if (options.onConfirm) options.onConfirm();
        };
        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = 'Batal';
        cancelBtn.className = 'custom-popup-tugas-btn cancel';
        cancelBtn.onclick = function() {
          document.body.removeChild(popup);
          if (options.onCancel) options.onCancel();
        };
        actions.appendChild(yesBtn);
        actions.appendChild(cancelBtn);
      } else {
        const okBtn = document.createElement('button');
        okBtn.textContent = 'Oke!';
        okBtn.className = 'custom-popup-tugas-btn ok';
        okBtn.onclick = function() {
          document.body.removeChild(popup);
        };
        actions.appendChild(okBtn);
      }
    }
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.form-upload-tugas').forEach(function(form) {
        form.addEventListener('submit', function(e) {
          var fileInput = form.querySelector('input[name="file_upload"]');
          var linkInput = form.querySelector('input[name="link_upload"]');
          var fileFilled = fileInput && fileInput.files && fileInput.files.length > 0;
          var linkFilled = linkInput && linkInput.value.trim() !== '';
          if (!fileFilled && !linkFilled) {
            e.preventDefault();
            showCustomPopupTugas('Harap unggah file atau isi link (salah satu wajib diisi)!', { confirm: false });
            return false;
          }
          // Konfirmasi sebelum upload
          e.preventDefault();
          showCustomPopupTugas('Yakin ingin mengupload tugas ini?', {
            confirm: true,
            onConfirm: function() {
              form.submit();
              sessionStorage.setItem('popup_upload_success_tugas', '1');
            },
            onCancel: function() { /* batal, tidak submit */ }
          });
        });
      });
      // Tampilkan popup sukses upload jika baru saja upload
      if (sessionStorage.getItem('popup_upload_success_tugas')) {
        showCustomPopupTugas('Tugas berhasil dikumpulkan! 🎉', { confirm: false });
        sessionStorage.removeItem('popup_upload_success_tugas');
      }
    });
    // Tambahkan style untuk custom popup tugas
    const popupStyleTugas = document.createElement('style');
    popupStyleTugas.innerHTML = `
      .custom-popup-tugas {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(106,76,147,0.18); z-index: 9999; display: flex; align-items: center; justify-content: center;
        animation: fadeIn 0.3s;
      }
      .custom-popup-tugas-content {
        background: linear-gradient(135deg, #faf6ff 60%, #e6e0fa 100%);
        border-radius: 22px; box-shadow: 0 6px 32px 0 rgba(106,76,147,0.13);
        padding: 2.2rem 2rem 1.5rem 2rem; min-width: 320px; max-width: 90vw;
        display: flex; flex-direction: column; align-items: center; gap: 1.1rem;
        border-left: 7px solid var(--tugas-primary);
        position: relative;
      }
      .custom-popup-tugas-icon {
        font-size: 2.8rem; margin-bottom: 0.5rem;
        animation: bounce 0.7s infinite alternate;
      }
      @keyframes bounce { from { transform: translateY(0); } to { transform: translateY(-10px); } }
      .custom-popup-tugas-message {
        font-size: 1.15rem; color: var(--tugas-primary); font-weight: 700; text-align: center;
      }
      .custom-popup-tugas-actions {
        display: flex; gap: 18px; margin-top: 1.1rem;
      }
      .custom-popup-tugas-btn {
        background: linear-gradient(90deg, var(--tugas-primary) 60%, var(--tugas-accent) 100%);
        border: none; color: #fff; padding: 0.7rem 1.5rem; border-radius: 9px; font-size: 1.08rem; font-weight: 700;
        cursor: pointer; box-shadow: 0 1.5px 8px rgba(106,76,147,0.08); letter-spacing: 0.2px; transition: background 0.3s, transform 0.2s;
      }
      .custom-popup-tugas-btn:hover {
        background: linear-gradient(90deg, var(--tugas-primary-dark) 60%, var(--tugas-accent) 100%);
        transform: scale(1.06);
        box-shadow: 0 4px 16px rgba(106,76,147,0.13);
      }
      .custom-popup-tugas-btn.cancel { background: #c0392b; }
      .custom-popup-tugas-btn.cancel:hover { background: #e74c3c; }
      .custom-popup-tugas-btn.yes { background: #6A4C93; }
      .custom-popup-tugas-btn.ok { background: #B983FF; }
    `;
    document.head.appendChild(popupStyleTugas);
  </script>
    <script src="../music-player.js"></script>

</body>
</html>
