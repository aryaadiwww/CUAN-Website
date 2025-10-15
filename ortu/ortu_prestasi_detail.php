<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'ortu') {
  header("Location: ../index.html");
  exit();
}
$siswa = isset($_SESSION['siswa']) ? $_SESSION['siswa'] : 'aryaadiww';
// Dummy data prestasi, bisa dihubungkan ke file JSON jika sudah ada
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detail Prestasi Anak - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous"/>
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #ffaf7b 0%, #d76d77 100%);
      --secondary-gradient: linear-gradient(135deg, #d76d77 0%, #ffaf7b 100%);
      --accent-color: #ffaf7b;
      --dark-accent: #d76d77;
      --light-color: #f8fafc;
      --card-shadow: 0 10px 30px rgba(215, 109, 119, 0.15);
      --hover-shadow: 0 15px 35px rgba(215, 109, 119, 0.25);
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
      margin-bottom: 2.5rem;
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
      border-bottom: 2px solid rgba(255, 175, 123, 0.2);
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
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    
    .prestasi-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      margin-top: 1rem;
    }
    
    .prestasi-table th {
      background: var(--primary-gradient);
      color: white;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.85rem;
      letter-spacing: 0.5px;
      padding: 1rem 0.8rem;
      text-align: center;
    }
    
    .prestasi-table td {
      padding: 1rem 0.8rem;
      text-align: center;
      border-bottom: 1px solid rgba(255, 175, 123, 0.1);
      font-size: 0.95rem;
      transition: all 0.2s;
    }
    
    .prestasi-table tr:last-child td {
      border-bottom: none;
    }
    
    .prestasi-table tr:hover td {
      background-color: rgba(255, 175, 123, 0.05);
    }
    
    .juara {
      color: #ffaf7b;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      background: rgba(255, 175, 123, 0.1);
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
    }
    
    .juara i {
      margin-right: 0.4rem;
    }
    
    .partisipasi {
      color: #20bf6b;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      background: rgba(32, 191, 107, 0.1);
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
    }
    
    .partisipasi i {
      margin-right: 0.4rem;
    }
    
    .summary-info {
      display: flex;
      justify-content: space-between;
      margin-top: 1.5rem;
      padding: 1rem;
      background: rgba(255, 175, 123, 0.1);
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
      .prestasi-table {
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
    <h1 class="page-title">Detail Prestasi Siswa</h1>
    
    <div class="task-card">
      <div class="card-header">
        <h2 class="card-title"><i class="fas fa-trophy"></i>Daftar Prestasi Siswa</h2>
      </div>
      
      <div class="table-responsive">
        <table class="prestasi-table">
          <tr>
            <th>Nama Siswa</th>
            <th>Tahun</th>
            <th>Prestasi</th>
            <th>Keterangan</th>
          </tr>
          <tr>
            <td><?php echo htmlspecialchars($siswa); ?></td>
            <td>2024</td>
            <td>Juara 2 Lomba Sains</td>
            <td><span class="juara"><i class="fas fa-medal"></i>Juara</span></td>
          </tr>
          <tr>
            <td><?php echo htmlspecialchars($siswa); ?></td>
            <td>2023</td>
            <td>Juara 1 Lomba Cerdas Cermat</td>
            <td><span class="juara"><i class="fas fa-medal"></i>Juara</span></td>
          </tr>
          <tr>
            <td><?php echo htmlspecialchars($siswa); ?></td>
            <td>2023</td>
            <td>Peserta Lomba Futsal</td>
            <td><span class="partisipasi"><i class="fas fa-certificate"></i>Partisipasi</span></td>
          </tr>
        </table>
        
        <div class="summary-info">
          <div class="summary-item">
            <div class="summary-value">2</div>
            <div class="summary-label">Juara</div>
          </div>
          <div class="summary-item">
            <div class="summary-value">1</div>
            <div class="summary-label">Partisipasi</div>
          </div>
          <div class="summary-item">
            <div class="summary-value">3</div>
            <div class="summary-label">Total Prestasi</div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="task-card">
      <div class="card-header">
        <h2 class="card-title"><i class="fas fa-chart-line"></i>Ringkasan Prestasi</h2>
      </div>
      
      <div class="summary-info" style="background: rgba(215, 109, 119, 0.1);">
        <div class="summary-item">
          <div class="summary-value">2</div>
          <div class="summary-label">Total Juara</div>
        </div>
        <div class="summary-item">
          <div class="summary-value">3</div>
          <div class="summary-label">Total Prestasi</div>
        </div>
        <div class="summary-item">
          <div class="summary-value">67%</div>
          <div class="summary-label">Persentase Juara</div>
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
