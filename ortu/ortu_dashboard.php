<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'ortu') {
  header("Location: ../index.html");
  exit();
}
// Set mapping ortu ke siswa (dummy: aryaadiww)
if (!isset($_SESSION['siswa'])) {
    // Mapping ortu ke siswa, misal dari database atau array
    // Contoh dummy: semua ortu ke siswa 'aryaadiww'
    $_SESSION['siswa'] = 'aryaadiww';
}
$siswa = $_SESSION['siswa'];

// Ambil nama lengkap siswa
$nama_lengkap = '-';
$profile_path = __DIR__ . '/../siswa/profile_data/' . $siswa . '.json';
if (file_exists($profile_path)) {
    $profile_data = json_decode(file_get_contents($profile_path), true);
    if (isset($profile_data['nama_lengkap'])) {
        $nama_lengkap = htmlspecialchars($profile_data['nama_lengkap']);
    }
}

// 1. Rata-rata Nilai
$nilai_path = __DIR__ . '/../api/nilai_data.json';
$nilai_data = file_exists($nilai_path) ? json_decode(file_get_contents($nilai_path), true) : [];
if (!is_array($nilai_data)) $nilai_data = [];
$nilai_siswa = array_filter($nilai_data, function($n) use ($siswa) {
    return isset($n['siswa_id']) && $n['siswa_id'] === $siswa && is_numeric($n['nilai']);
});
$rata2_nilai = count($nilai_siswa) > 0 ? round(array_sum(array_column($nilai_siswa, 'nilai')) / count($nilai_siswa)) : 0;

// 2. Tugas & Evaluasi
$tugas_path = __DIR__ . '/../tugas.json';
$evaluasi_path = __DIR__ . '/../evaluasi.json';
$tugas_data = file_exists($tugas_path) ? json_decode(file_get_contents($tugas_path), true) : [];
if (!is_array($tugas_data)) $tugas_data = [];
$evaluasi_data = file_exists($evaluasi_path) ? json_decode(file_get_contents($evaluasi_path), true) : [];
if (!is_array($evaluasi_data)) $evaluasi_data = [];
$total_tugas = count($tugas_data);
$total_evaluasi = count($evaluasi_data);
$total_semua = $total_tugas + $total_evaluasi;

$tugas_submissions_path = __DIR__ . '/../tugas_submissions.json';
$evaluasi_submissions_path = __DIR__ . '/../evaluasi_submissions.json';
$tugas_submissions = file_exists($tugas_submissions_path) ? json_decode(file_get_contents($tugas_submissions_path), true) : [];
if (!is_array($tugas_submissions)) $tugas_submissions = [];
$evaluasi_submissions = file_exists($evaluasi_submissions_path) ? json_decode(file_get_contents($evaluasi_submissions_path), true) : [];
if (!is_array($evaluasi_submissions)) $evaluasi_submissions = [];
$tugas_selesai = 0;
$evaluasi_selesai = 0;
foreach ($tugas_submissions as $s) {
    if (isset($s['siswa_id']) && $s['siswa_id'] === $siswa) $tugas_selesai++;
}
foreach ($evaluasi_submissions as $s) {
    if (isset($s['siswa_id']) && $s['siswa_id'] === $siswa) $evaluasi_selesai++;
}
$total_selesai = $tugas_selesai + $evaluasi_selesai;

// 3. Prestasi (dummy)
$jumlah_prestasi = 3;

// 4. Kehadiran
$kehadiran_path = __DIR__ . '/../api/kehadiran_data.json';
$kehadiran_data = file_exists($kehadiran_path) ? json_decode(file_get_contents($kehadiran_path), true) : [];
if (!is_array($kehadiran_data)) $kehadiran_data = [];
$kehadiran_siswa = array_filter($kehadiran_data, function($k) use ($siswa) {
    return isset($k['username']) && $k['username'] === $siswa;
});
$hadir = 0; $total_hari = 0;
foreach ($kehadiran_siswa as $k) {
    if (isset($k['status']) && strtolower($k['status']) == 'hadir') $hadir++;
    $total_hari++;
}
$persen_hadir = $total_hari > 0 ? round($hadir / $total_hari * 100) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Orang Tua - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="../responsive.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous"/>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      color: #222;
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem 1rem 2rem 1rem;
    }
    .dashboard-cards {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1rem;
      margin-bottom: 2rem;
      justify-items: center;
    }
    .dashboard-card {
      width: 100%;
      min-width: 0;
      max-width: 100%;
      height: 120px;
      border-radius: 18px;
      box-shadow: 0 2px 12px 0 rgba(80,40,20,0.08);
      padding: 0.7rem 1.1rem;
      display: flex;
      flex-direction: row;
      align-items: stretch;
      justify-content: flex-start;
      transition: box-shadow 0.25s, background 0.25s, transform 0.18s;
      position: relative;
      overflow: hidden;
      background: var(--card-gradient, linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%));
    }
    .dashboard-card:nth-child(1) { --card-gradient: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%); }
    .dashboard-card:nth-child(2) { --card-gradient: linear-gradient(135deg, #43cea2 0%, #185a9d 100%); }
    .dashboard-card:nth-child(3) { --card-gradient: linear-gradient(135deg, #ffaf7b 0%, #d76d77 100%); }
    .dashboard-card:nth-child(4) { --card-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); }
    .dashboard-card:hover {
      box-shadow: 0 4px 18px #8f94fb55;
      transform: scale(1.03);
      filter: brightness(1.04);
    }
    .dashboard-card .icon {
      font-size: 2.2rem;
      color: #fff;
      background: var(--icon-bg, #4e54c8);
      border-radius: 50% / 40%; /* oval shape */
      padding: 0.7rem 1.1rem 0.5rem 1.1rem;
      box-shadow: 0 2px 8px #8f94fb33;
      transition: background 0.3s, color 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 56px;
      width: 56px;
      margin-right: 1.1rem;
      margin-top: auto;
      margin-bottom: auto;
    }
    .dashboard-card:nth-child(1) .icon { background: #4e54c8; }
    .dashboard-card:nth-child(2) .icon { background: #43cea2; }
    .dashboard-card:nth-child(3) .icon { background: #d76d77; }
    .dashboard-card:nth-child(4) .icon { background: #f7971e; }
    .dashboard-card-content {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: flex-start;
      width: 100%;
      height: 100%;
      padding: 0.2rem 0 0.2rem 0;
    }
    .dashboard-card .title {
      font-size: 1.08rem;
      font-weight: 700;
      color: #fff;
      letter-spacing: 0.7px;
      text-align: left;
      text-shadow: 0 1px 4px #2226;
      margin-bottom: 0.15rem;
    }
    .dashboard-card .desc {
      font-size: 0.98rem;
      color: #fff;
      text-align: left;
      font-style: italic;
      margin-bottom: 0.15rem;
      letter-spacing: 0.1px;
      opacity: 0.93;
      text-shadow: 0 1px 4px #2226;
    }
    .dashboard-card .value {
      font-size: 1.35rem;
      font-weight: 800;
      color: #fff;
      text-shadow: 0 2px 8px #2226;
      text-align: left;
      letter-spacing: 1px;
      margin-top: auto;
    }

    /* Mapel Cards */
    .mapel-cards {
      display: flex;
      gap: 1.2rem;
      margin-bottom: 2.5rem;
      flex-wrap: wrap;
      justify-content: center;
    }
    .mapel-card {
      flex: 1 1 160px;
      min-width: 160px;
      max-width: 200px;
      border-radius: 18px;
      box-shadow: 0 6px 24px #b8355633, 0 1.5px 8px #5e72e433;
      padding: 1.3rem 0.8rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s, box-shadow 0.3s, background 0.3s;
      animation: fadeInUp 0.7s cubic-bezier(.68,-0.55,.27,1.55);
      position: relative;
      overflow: hidden;
      cursor: pointer;
      background: var(--mapel-gradient, linear-gradient(135deg, #e4aa95ff 0%, #b83556 100%));
      color: #fff;
    }
    .mapel-card:nth-child(1) { --mapel-gradient: linear-gradient(135deg, #b83556 0%, #f7b731 100%); }
    .mapel-card:nth-child(2) { --mapel-gradient: linear-gradient(135deg, #5e72e4 0%, #3b5998 100%); }
    .mapel-card:nth-child(3) { --mapel-gradient: linear-gradient(135deg, #f7b731 0%, #f96d00 100%); }
    .mapel-card:nth-child(4) { --mapel-gradient: linear-gradient(135deg, #20bf6b 0%, #38ada9 100%); }
    .mapel-card:nth-child(5) { --mapel-gradient: linear-gradient(135deg, #8854d0 0%, #a55eea 100%); }
    .mapel-card:nth-child(6) { --mapel-gradient: linear-gradient(135deg, #eb3b5a 0%, #f76d6d 100%); }
    .mapel-card:hover {
      transform: scale(1.09) rotate(-2deg);
      box-shadow: 0 12px 40px #b8355655, 0 2px 12px #5e72e455;
      background: var(--mapel-gradient-hover, linear-gradient(135deg, #fff 0%, #b83556 100%));
      color: #fff;
      filter: brightness(1.08) saturate(1.12);
    }
    .mapel-card .icon {
      font-size: 2.3rem;
      margin-bottom: 0.5rem;
      color: #fff;
      background: rgba(255,255,255,0.13);
      border-radius: 50%;
      padding: 0.7rem;
      box-shadow: 0 2px 8px #b8355633;
      animation: popIn 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .mapel-card .title {
      font-size: 1.08rem;
      font-weight: 700;
      color: #fff;
      letter-spacing: 0.6px;
      text-align: center;
      text-shadow: 0 1.5px 6px #2226;
    }

    /* Feature Cards */
    .flex-row-cards {
      display: flex;
      gap: 1.5rem;
      margin-bottom: 2.5rem;
      flex-wrap: wrap;
      justify-content: center;
    }
    .feature-card {
      flex: 1 1 320px;
      min-width: 320px;
      max-width: 420px;
      border-radius: 26px;
      box-shadow: 0 12px 40px #b8355633, 0 4px 18px #5e72e433;
      padding: 2.1rem 1.7rem 1.7rem 1.7rem;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: flex-start;
      position: relative;
      overflow: hidden;
      background: var(--feature-gradient, linear-gradient(135deg, #b8355655 0%, #b8355655 100%));
      color: #fff;
      animation: fadeInUpFeature 0.7s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .feature-card:nth-child(1) { --feature-gradient: linear-gradient(135deg, #f7b731 0%, #b83556 100%); }
    .feature-card:nth-child(2) { --feature-gradient: linear-gradient(135deg, #5e72e4 0%, #3b5998 100%); }
    .feature-card:nth-child(3) { --feature-gradient: linear-gradient(135deg, #20bf6b 0%, #38ada9 100%); }
    .feature-card:hover {
      transform: translateY(-4px) scale(1);
      box-shadow: 0 16px 48px #b8355655, 0 4px 18px #5e72e455;
      filter: brightness(1.09) saturate(1.18);
    }
    .feature-card .icon {
      font-size: 2.3rem;
      margin-bottom: 0.7rem;
      color: #fff;
      background: rgba(255,255,255,0.13);
      border-radius: 50%;
      padding: 0.7rem;
      box-shadow: 0 2px 8px #b8355633;
      animation: popIn 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .feature-card .title {
      font-size: 1.13rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 0.5rem;
      letter-spacing: 0.6px;
      text-shadow: 0 1.5px 6px #2226;
    }
    .feature-card .desc {
      font-size: 1.01rem;
      color: #fff;
      margin-bottom: 0.5rem;
      opacity: 0.97;
      text-shadow: 0 1.5px 6px #2226;
    }
    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(40px) scale(0.95); }
      100% { opacity: 1; transform: none; }
    }
    @keyframes popIn {
      0% { opacity: 0; transform: scale(0.7); }
      60% { opacity: 1; transform: scale(1.1); }
      100% { opacity: 1; transform: scale(1); }
    }
    @keyframes fadeInUpFeature {
      0% { opacity: 0; transform: translateY(40px) scale(0.95); }
      100% { opacity: 1; transform: none; }
    }
    /* Jadwal - Lebih Colorful dan Modern */
    .jadwal-section {
      background: linear-gradient(135deg, #4d3528a2 0%, #4d3528a2 100%);
      border-radius: 18px;
      box-shadow: 0 4px 16px #b97a5633;
      padding: 1.2rem 1rem 1.2rem 1rem;
      margin-bottom: 2rem;
      color: #fff;
    }
    .jadwal-section h2 {
      color: #fff;
      font-size: 1.18rem;
      font-weight: 700;
      margin-bottom: 0.8rem;
      text-align: center;
      letter-spacing: 1px;
      text-shadow: 0 1px 4px #b97a5633;
    }
    .jadwal-table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 8px #b97a5633;
      font-size: 0.98rem;
      margin: 0 auto;
    }
    .jadwal-table th, .jadwal-table td {
      padding: 0.55rem 0.4rem;
      text-align: center;
      border-bottom: 1px solid #e4aa95;
    }
    .jadwal-table th {
      background: linear-gradient(90deg, #b97a56 0%, #b97a56 100%);
      color: #fff;
      font-weight: 700;
      letter-spacing: 0.6px;
      font-size: 1rem;
      border-bottom: 2px solid #e4aa95;
      text-shadow: 0 1px 4px #b97a5633;
    }
    .jadwal-table th:first-child,
    .jadwal-table td:first-child {
      background: #b97a56 !important;
      color: #fff !important;
      font-weight: 700;
      letter-spacing: 0.8px;
      text-shadow: 0 1px 4px #b97a5633;
    }
    .jadwal-table tr:last-child td {
      border-bottom: none;
    }
    .jadwal-table td.mapel {
      font-weight: 600;
      color: #fff;
      background: #a86b3c;
      /* no border-radius */
      box-shadow: none;
      cursor: default;
      transition: none;
    }
    .jadwal-table td.mapel:hover {
      background: #a86b3c;
      color: #fff;
      filter: none;
    }
    /* Card Mapel - Lebih Colorful, Variatif, Modern */
    .mapel-cards {
      display: flex;
      gap: 1.2rem;
      margin-bottom: 2.5rem;
      flex-wrap: wrap;
      justify-content: center;
    }
    .mapel-card {
      flex: 1 1 160px;
      min-width: 160px;
      max-width: 200px;
      border-radius: 18px;
      box-shadow: none;
      padding: 1.4rem 0.9rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      transition: transform 0.25s, background 0.25s;
      animation: fadeInUp 0.7s cubic-bezier(.68,-0.55,.27,1.55);
      position: relative;
      overflow: hidden;
      cursor: pointer;
      background: #4d3528a2;
      color: #fff;
    }
    .mapel-card:hover {
      transform: scale(1.07) rotate(-2deg);
      background: #4d3528a2;
      color: #fff;
      filter: brightness(1.07) saturate(1.08);
    }
    .mapel-card .icon {
      font-size: 2.5rem;
      margin-bottom: 0.7rem;
      color: #fff;
      background: rgba(255,255,255,0.13);
      border-radius: 50%;
      padding: 0.8rem;
      box-shadow: 0 2px 12px #b8355633;
      animation: popIn 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .mapel-card .title {
      font-size: 1.13rem;
      font-weight: 800;
      color: #fff;
      letter-spacing: 0.7px;
      text-align: center;
      text-shadow: 0 2px 8px #2226, 0 1.5px 6px #b8355633;
    }
    /* Card Aktivitas, Komunikasi, Galeri - Lebih Colorful, Modern, Playful */
    .flex-row-cards {
      display: flex;
      gap: 1.5rem;
      margin-bottom: 2.5rem;
      flex-wrap: wrap;
      justify-content: center;
    }
    .feature-card {
      flex: 1 1 320px;
      min-width: 320px;
      max-width: 420px;
      border-radius: 26px;
      box-shadow: 0 12px 40px #b8355633, 0 4px 18px #5e72e433;
      padding: 2.1rem 1.7rem 1.7rem 1.7rem;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: flex-start;
      position: relative;
      overflow: hidden;
      background: var(--feature-gradient, linear-gradient(135deg, #b8355655 0%, #b8355655 100%));
      color: #fff;
      animation: fadeInUpFeature 0.7s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .feature-card:nth-child(1) { --feature-gradient: linear-gradient(135deg, #f7b731 0%, #daa32dff  100%); }
    .feature-card:nth-child(2) { --feature-gradient: linear-gradient(135deg, #5e72e4 0%, #5264c7ff 100%); }
    .feature-card:nth-child(3) { --feature-gradient: linear-gradient(135deg, #20bf6b 0%, #1a9d58ff 100%); }
    .feature-card:hover {
      box-shadow: 0 16px 48px #b8355655, 0 4px 18px #5e72e455;
      filter: brightness(1.09) saturate(1.18);
    }
    .feature-card .icon {
      font-size: 2.3rem;
      margin-bottom: 0.7rem;
      color: #fff;
      background: rgba(255,255,255,0.13);
      border-radius: 50%;
      padding: 0.7rem;
      box-shadow: 0 2px 8px #b8355633;
      animation: popIn 1.2s;
    }
    .feature-card .title {
      font-size: 1.13rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 0.5rem;
      letter-spacing: 0.6px;
      text-shadow: 0 1.5px 6px #2226;
    }
    .feature-card .desc {
      font-size: 1.01rem;
      color: #fff;
      margin-bottom: 0.5rem;
      opacity: 0.97;
      text-shadow: 0 1.5px 6px #2226;
    }
    /* Responsive */
    @media (max-width: 900px) {
      .dashboard-cards, .mapel-cards, .flex-row-cards {
        flex-direction: column;
        gap: 1.2rem;
        align-items: center;
      }
      .dashboard-card, .mapel-card, .feature-card {
        max-width: 98vw;
        width: 98vw;
      }
      .jadwal-section {
        padding: 1.2rem 0.5rem 1.2rem 0.5rem;
      }
    }
    @media (max-width: 600px) {
      .container {
        padding: 1rem 0.2rem 1rem 0.2rem;
      }
      .dashboard-card, .mapel-card, .feature-card {
        padding: 1rem 0.5rem;
      }
      .jadwal-section {
        padding: 0.7rem 0.2rem 0.7rem 0.2rem;
      }
      .jadwal-table th, .jadwal-table td {
        padding: 0.4rem 0.2rem;
        font-size: 0.95rem;
      }
    }
    
  </style>
</head>
<body>
  <?php include 'ortu_navbar.php'; ?>
  <!-- Logo CUAN di pojok kiri navbar -->
  <div style="position: absolute; top: 0.1rem; left: 1.2rem; z-index: 1000;">
    <img src="../img/cuan.png" alt="Logo CUAN" style="height:55px;width:auto;object-fit:contain;"/>
  </div>
  <!-- Navbar background -->
  <div class="container">
    <!-- 4 Card Tracking Progress -->
    <div class="dashboard-cards">
      <div class="dashboard-card" onclick="window.location.href='ortu_nilai_detail.php'" style="cursor:pointer;">
        <div class="icon"><i class="fa-solid fa-chart-line"></i></div>
        <div class="dashboard-card-content">
          <div class="title">Rata-rata Nilai</div>
          <div class="desc">Nilai rata-rata siswa</div>
          <div class="value"><?php echo $rata2_nilai; ?></div>
        </div>
      </div>
      <div class="dashboard-card" onclick="window.location.href='ortu_tugas_detail.php'" style="cursor:pointer;">
        <div class="icon"><i class="fa-solid fa-clipboard-list"></i></div>
        <div class="dashboard-card-content">
          <div class="title">Tugas & Evaluasi</div>
          <div class="desc">Selesai / Total</div>
          <div class="value"><?php echo $total_selesai . '/' . $total_semua; ?></div>
        </div>
      </div>
      <div class="dashboard-card" onclick="window.location.href='ortu_prestasi_detail.php'" style="cursor:pointer;">
        <div class="icon"><i class="fa-solid fa-trophy"></i></div>
        <div class="dashboard-card-content">
          <div class="title">Prestasi</div>
          <div class="desc">Prestasi yang diraih</div>
          <div class="value"><?php echo $jumlah_prestasi; ?></div>
        </div>
      </div>
      <div class="dashboard-card" onclick="window.location.href='ortu_kehadiran_detail.php'" style="cursor:pointer;">
        <div class="icon"><i class="fa-solid fa-user-check"></i></div>
        <div class="dashboard-card-content">
          <div class="title">Kehadiran</div>
          <div class="desc">Kehadiran siswa</div>
          <div class="value"><?php echo $persen_hadir; ?>%</div>
        </div>
      </div>
    </div>
    <!-- Jadwal Anak -->
    <div class="jadwal-section">
      <h2>Jadwal Anak</h2>
      <table class="jadwal-table">
        <thead>
          <tr>
            <th>Hari</th>
            <th>07.00 - 09.30</th>
            <th>09.30 - 11.30</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Senin</td><td class="mapel">IPAS</td><td class="mapel">Matematika</td></tr>
          <tr><td>Selasa</td><td class="mapel">B.Indonesia</td><td class="mapel">PPKN</td></tr>
          <tr><td>Rabu</td><td class="mapel">PAI</td><td class="mapel">PJOK</td></tr>
          <tr><td>Kamis</td><td class="mapel">Matematika</td><td class="mapel">IPAS</td></tr>
          <tr><td>Jumat</td><td class="mapel">PPKN</td><td class="mapel">B.Indonesia</td></tr>
        </tbody>
      </table>
    </div>
    <!-- 6 Card Mata Pelajaran -->
    <div class="mapel-cards">
      <div class="mapel-card" onclick="window.location.href='ortu_ipas_detail.php'" style="cursor:pointer;"><div class="icon" style="background:#b83556;"><i class="fa-solid fa-flask"></i></div><div class="title">IPAS</div></div>
      <div class="mapel-card"><div class="icon" style="background:#5e72e4;"><i class="fa-solid fa-square-root-variable"></i></div><div class="title">Matematika</div></div>
      <div class="mapel-card"><div class="icon" style="background:#f7b731;"><i class="fa-solid fa-language"></i></div><div class="title">B.Indonesia</div></div>
      <div class="mapel-card"><div class="icon" style="background:#20bf6b;"><i class="fa-solid fa-scale-balanced"></i></div><div class="title">PPKN</div></div>
      <div class="mapel-card"><div class="icon" style="background:#8854d0;"><i class="fa-solid fa-mosque"></i></div><div class="title">PAI</div></div>
      <div class="mapel-card"><div class="icon" style="background:#eb3b5a;"><i class="fa-solid fa-futbol"></i></div><div class="title">PJOK</div></div>
    </div>
    
    <!-- Card Aktivitas, Komunikasi, Galeri -->
    <div class="flex-row-cards">
      <div class="feature-card" onclick="window.location.href='ortu_aktivitas.php'" style="cursor:pointer;">
        <div class="icon"><i class="fa-solid fa-bolt"></i></div>
        <div class="title">Aktivitas Anak</div>
        <div class="desc">Sudah mengumpulkan tugas, evaluasi, dan mendapat prestasi.</div>
        <ul style="margin:0 0 0 1.2rem;padding:0;font-size:0.98rem;color:#335165;">
          <li>Mengumpulkan tugas Matematika</li>
          <li>Evaluasi IPAS selesai</li>
          <li>Juara 2 lomba sains</li>
        </ul>
      </div>
      <div class="feature-card" onclick="window.location.href='ortu_diskusi.php'" style="cursor:pointer;">
        <div class="icon"><i class="fa-solid fa-comments"></i></div>
        <div class="title">Komunikasi dengan Guru</div>
        <div class="desc">Diskusi, konsultasi, dan pesan dengan guru kelas dan mapel.</div>
        <button style="margin-top:0.7rem;padding:0.5rem 1.2rem;background:#b83556;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;transition:background 0.3s;">Buka Pesan</button>
      </div>
      <div class="feature-card" onclick="window.location.href='ortu_galeri.php'" style="cursor:pointer;">
        <div class="icon"><i class="fa-solid fa-image"></i></div>
        <div class="title">Galeri Karya Anak</div>
        <div class="desc">Kumpulan karya, tugas kreatif, dan dokumentasi prestasi anak.</div>
        <div style="display:flex;gap:1.1rem;margin-top:1.1rem;justify-content:center;width:100%;">
          <img src="../img/beo.jpg" alt="Karya Beo" style="width:90px;height:90px;object-fit:cover;border-radius:14px;box-shadow:0 4px 16px #b8355633;">
          <img src="../img/kaligrafi.jpg" alt="Karya Kaligrafi" style="width:90px;height:90px;object-fit:cover;border-radius:14px;box-shadow:0 4px 16px #b8355633;">
          <img src="../img/rumah.jpg" alt="Karya Rumah" style="width:90px;height:90px;object-fit:cover;border-radius:14px;box-shadow:0 4px 16px #b8355633;">
        </div>
      </div>
    </div>
  </div>
  <script>
    // Navbar toggle (ambil dari ortu_navbar.php jika perlu)
    function toggleSidebar(){}
  </script>
</body>
</html>
