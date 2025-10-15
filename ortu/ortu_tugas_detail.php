<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'ortu') {
  header("Location: ../index.html");
  exit();
}

$siswa = isset($_SESSION['siswa']) ? $_SESSION['siswa'] : 'aryaadiww';
$tugas_path = __DIR__ . '/../api/tugas.json';
$tugas_data = file_exists($tugas_path) ? json_decode(file_get_contents($tugas_path), true) : [];
if (!is_array($tugas_data)) $tugas_data = [];

$submissions_path = __DIR__ . '/../uploads/tugas_submissions/tugas_submissions.json';
$submissions = file_exists($submissions_path) ? json_decode(file_get_contents($submissions_path), true) : [];
if (!is_array($submissions)) $submissions = [];

$tugas_selesai = 0;
$total_tugas = count($tugas_data);

// Ambil data tugas dan evaluasi dari API
$tugas = json_decode(@file_get_contents('http://localhost/CUAN/api/tugas_api.php'), true);
if (!is_array($tugas)) $tugas = [];
$evaluasi = json_decode(@file_get_contents('http://localhost/CUAN/api/evaluasi_api.php'), true);
if (!is_array($evaluasi)) $evaluasi = [];

// Ambil data submissions tugas dan evaluasi
$tugas_submissions = json_decode(@file_get_contents('../tugas_submissions.json'), true);
if (!is_array($tugas_submissions)) $tugas_submissions = [];
$evaluasi_submissions = json_decode(@file_get_contents('../evaluasi_submissions.json'), true);
if (!is_array($evaluasi_submissions)) $evaluasi_submissions = [];

// Ambil data siswa untuk mapping username -> nama
$siswa_list = json_decode(@file_get_contents('http://localhost/CUAN/api/daftar_siswa_api.php'), true);
$siswa_map = [];
if (is_array($siswa_list) && isset($siswa_list['status']) && $siswa_list['status'] === 'success' && isset($siswa_list['data'])) {
  foreach ($siswa_list['data'] as $s) {
    $siswa_map[$s['username']] = $s['nama'];
  }
}

function get_tugas_status($tugas_id, $siswa_id, $submissions) {
  foreach ($submissions as $sub) {
    if ($sub['tugas_id'] == $tugas_id && $sub['siswa_id'] == $siswa_id) {
      return $sub;
    }
  }
  return null;
}

function get_evaluasi_status($evaluasi_id, $siswa_id, $submissions) {
  foreach ($submissions as $sub) {
    if ($sub['evaluasi_id'] == $evaluasi_id && $sub['siswa_id'] == $siswa_id) {
      return $sub;
    }
  }
  return null;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detail Tugas Anak - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous"/>
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #43cea2 0%, #185a9d 100%);
      --secondary-gradient: linear-gradient(135deg, #185a9d 0%, #43cea2 100%);
      --accent-color: #43cea2;
      --dark-accent: #185a9d;
      --light-color: #f8fafc;
      --card-shadow: 0 10px 30px rgba(24, 90, 157, 0.15);
      --hover-shadow: 0 15px 35px rgba(24, 90, 157, 0.25);
    }
    
    body { 
      background-image: var(--primary-gradient);
      background-attachment: fixed;
      font-family: 'Poppins', sans-serif; 
      margin: 0; 
      padding: 0; 
      min-height: 100vh;
      color: #333;
    }
    
    .main-content {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1.5rem;
    }
    
    .page-title {
      color: white;
      text-align: center;
      font-size: 2.2rem;
      font-weight: 700;
      margin: 2rem 0;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      position: relative;
    }
    
    .page-title::after {
      content: '';
      display: block;
      width: 80px;
      height: 4px;
      background: white;
      margin: 0.5rem auto;
      border-radius: 2px;
    }
    
    .task-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      box-shadow: var(--card-shadow);
      padding: 2rem;
      margin-bottom: 1.5rem;
      transition: all 0.3s ease;
      animation: fadeIn 0.8s ease-out;
      overflow: hidden;
      position: relative;
    }
    
    .task-card:hover {
      box-shadow: var(--hover-shadow);
      transform: translateY(-5px);
    }
    
    .task-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 8px;
      height: 100%;
      background: var(--primary-gradient);
    }
    
    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      border-bottom: 2px solid rgba(67, 206, 162, 0.2);
      padding-bottom: 1rem;
    }
    
    .card-title {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--dark-accent);
      margin: 0;
      display: flex;
      align-items: center;
    }
    
    .card-title i {
      margin-right: 0.8rem;
      font-size: 1.5rem;
      background: var(--primary-gradient);
      -webkit-text-fill-color: transparent;
    }
    
    .task-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      margin-top: 1rem;
    }
    
    .task-table th {
      background: var(--primary-gradient);
      color: white;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.85rem;
      letter-spacing: 0.5px;
      padding: 1rem 0.8rem;
      text-align: center;
    }
    
    .task-table td {
      padding: 1rem 0.8rem;
      text-align: center;
      border-bottom: 1px solid rgba(67, 206, 162, 0.1);
      font-size: 0.95rem;
      transition: all 0.2s;
    }
    
    .task-table tr:last-child td {
      border-bottom: none;
    }
    
    .task-table tr:hover td {
      background-color: rgba(67, 206, 162, 0.05);
    }
    
    .status-done {
      color: #43cea2;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      background: rgba(67, 206, 162, 0.1);
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
    }
    
    .status-done i {
      margin-right: 0.4rem;
    }
    
    .status-pending {
      color: #e74c3c;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      background: rgba(231, 76, 60, 0.1);
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
    }
    
    .status-pending i {
      margin-right: 0.4rem;
    }
    
    .nilai-badge {
      background: var(--primary-gradient);
      color: white;
      font-weight: 700;
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
      display: inline-block;
      min-width: 2.5rem;
    }
    
    .nilai-none {
      background: #f1f1f1;
      color: #777;
    }
    
    .file-link {
      color: var(--dark-accent);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
    }
    
    .file-link:hover {
      color: var(--accent-color);
    }
    
    .file-link i {
      margin-right: 0.4rem;
    }
    
    .summary-info {
      display: flex;
      justify-content: space-between;
      margin-top: 1.5rem;
      padding: 1rem;
      background: rgba(67, 206, 162, 0.1);
      border-radius: 12px;
      font-weight: 600;
    }
    
    .summary-item {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    
    .summary-value {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--dark-accent);
    }
    
    .summary-label {
      font-size: 0.85rem;
      color: #777;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    @media (max-width: 768px) {
      .task-table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
      }
      
      .card-header {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .page-title {
        font-size: 1.8rem;
      }
    }
  </style>
</head>
<body>
  <?php include 'ortu_navbar.php'; ?>
  
  <div class="main-content">
    <h1 class="page-title">Detail Tugas & Evaluasi Siswa</h1>
    
    <div class="task-card">
      <div class="card-header">
        <h2 class="card-title"><i class="fas fa-tasks"></i>Daftar Tugas Siswa</h2>
      </div>
      
      <div class="table-responsive">
        <table class="task-table">
    <tr>
            <th>Nama Siswa</th>
            <th>Judul Tugas</th>
            <th>Deadline</th>
            <th>Status</th>
            <th>File/Link</th>
            <th>Nilai</th>
            <th>Waktu Upload</th>
          </tr>
          <?php
          $completed_tasks = 0;
          $total_tasks = 0;
          
          foreach ($siswa_map as $username => $nama) {
            foreach ($tugas as $t) {
              $total_tasks++;
              $sub = get_tugas_status($t['id'] ?? $t['judul'] ?? $t['title'], $username, $tugas_submissions);
              echo '<tr>';
              echo '<td>' . htmlspecialchars($nama) . '</td>';
              echo '<td>' . htmlspecialchars($t['judul'] ?? $t['title'] ?? '-') . '</td>';
              echo '<td>' . htmlspecialchars($t['deadline'] ?? '-') . '</td>';
              if ($sub) {
                $completed_tasks++;
                echo '<td><span class="status-done"><i class="fas fa-check-circle"></i>Sudah Upload</span></td>';
                echo '<td><a href="#" class="file-link"><i class="fas fa-file-alt"></i>' . ($sub['file'] ? basename($sub['file']) : $sub['link']) . '</a></td>';
                echo '<td>' . ($sub['nilai'] !== null ? '<span class="nilai-badge">' . $sub['nilai'] . '</span>' : '<span class="nilai-badge nilai-none">-</span>') . '</td>';
                echo '<td>' . htmlspecialchars($sub['upload_time']) . '</td>';
              } else {
                echo '<td><span class="status-pending"><i class="fas fa-clock"></i>Belum Upload</span></td>';
                echo '<td>-</td>';
                echo '<td><span class="nilai-badge nilai-none">-</span></td>';
                echo '<td>-</td>';
              }
              echo '</tr>';
            }
          }
          ?>
        </table>
        
        <?php if ($total_tasks > 0): ?>
        <div class="summary-info">
          <div class="summary-item">
            <div class="summary-value"><?php echo $completed_tasks; ?></div>
            <div class="summary-label">Tugas Selesai</div>
          </div>
          <div class="summary-item">
            <div class="summary-value"><?php echo $total_tasks; ?></div>
            <div class="summary-label">Total Tugas</div>
          </div>
          <div class="summary-item">
            <div class="summary-value"><?php echo round(($completed_tasks / $total_tasks) * 100); ?>%</div>
            <div class="summary-label">Persentase Selesai</div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
    
    <div class="task-card">
      <div class="card-header">
        <h2 class="card-title"><i class="fas fa-clipboard-check"></i>Daftar Evaluasi Siswa</h2>
      </div>
      
      <div class="table-responsive">
        <table class="task-table">
          <tr>
            <th>Nama Siswa</th>
            <th>Judul Evaluasi</th>
            <th>Deadline</th>
            <th>Status</th>
            <th>File/Link</th>
            <th>Nilai</th>
            <th>Waktu Upload</th>
          </tr>
          <?php
          $completed_evaluasi = 0;
          $total_evaluasi = 0;
          
          foreach ($siswa_map as $username => $nama) {
            foreach ($evaluasi as $e) {
              $total_evaluasi++;
              $sub = get_evaluasi_status($e['id'] ?? $e['judul'] ?? $e['title'], $username, $evaluasi_submissions);
              echo '<tr>';
              echo '<td>' . htmlspecialchars($nama) . '</td>';
              echo '<td>' . htmlspecialchars($e['judul'] ?? $e['title'] ?? '-') . '</td>';
              echo '<td>' . htmlspecialchars($e['deadline'] ?? '-') . '</td>';
              if ($sub) {
                $completed_evaluasi++;
                echo '<td><span class="status-done"><i class="fas fa-check-circle"></i>Sudah Upload</span></td>';
                echo '<td><a href="#" class="file-link"><i class="fas fa-file-alt"></i>' . ($sub['file'] ? basename($sub['file']) : $sub['link']) . '</a></td>';
                echo '<td>' . ($sub['nilai'] !== null ? '<span class="nilai-badge">' . $sub['nilai'] . '</span>' : '<span class="nilai-badge nilai-none">-</span>') . '</td>';
                echo '<td>' . htmlspecialchars($sub['upload_time']) . '</td>';
              } else {
                echo '<td><span class="status-pending"><i class="fas fa-clock"></i>Belum Upload</span></td>';
                echo '<td>-</td>';
                echo '<td><span class="nilai-badge nilai-none">-</span></td>';
                echo '<td>-</td>';
              }
              echo '</tr>';
            }
          }
          ?>
        </table>
        
        <?php if ($total_evaluasi > 0): ?>
        <div class="summary-info">
          <div class="summary-item">
            <div class="summary-value"><?php echo $completed_evaluasi; ?></div>
            <div class="summary-label">Evaluasi Selesai</div>
          </div>
          <div class="summary-item">
            <div class="summary-value"><?php echo $total_evaluasi; ?></div>
            <div class="summary-label">Total Evaluasi</div>
          </div>
          <div class="summary-item">
            <div class="summary-value"><?php echo round(($completed_evaluasi / $total_evaluasi) * 100); ?>%</div>
            <div class="summary-label">Persentase Selesai</div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
    
    <div class="task-card">
      <div class="card-header">
        <h2 class="card-title"><i class="fas fa-chart-line"></i>Ringkasan Progres</h2>
      </div>
      
      <div class="summary-info" style="background: rgba(24, 90, 157, 0.1);">
        <div class="summary-item">
          <div class="summary-value"><?php echo $completed_tasks + $completed_evaluasi; ?></div>
          <div class="summary-label">Total Selesai</div>
        </div>
        <div class="summary-item">
          <div class="summary-value"><?php echo $total_tasks + $total_evaluasi; ?></div>
          <div class="summary-label">Total Keseluruhan</div>
        </div>
        <div class="summary-item">
          <div class="summary-value">
            <?php 
            $total_all = $total_tasks + $total_evaluasi;
            $completed_all = $completed_tasks + $completed_evaluasi;
            echo $total_all > 0 ? round(($completed_all / $total_all) * 100) : 0; 
            ?>%
          </div>
          <div class="summary-label">Persentase Total</div>
        </div>
      </div>
    </div>
  </div>
  
  <script>
  // Animasi untuk card saat scroll
  document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.task-card');
    
    function checkScroll() {
      cards.forEach(card => {
        const cardTop = card.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;
        
        if (cardTop < windowHeight * 0.9) {
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }
      });
    }
    
    // Set initial state
    cards.forEach((card, index) => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(30px)';
      card.style.transitionDelay = `${index * 0.1}s`;
    });
    
    // Check on load and scroll
    window.addEventListener('scroll', checkScroll);
    checkScroll();
  });
  </script>
</body>
</html>
