<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'ortu') {
  header("Location: ../index.html");
  exit();
}
$siswa = isset($_SESSION['siswa']) ? $_SESSION['siswa'] : 'aryaadiww';
$nama_lengkap = $siswa;
// Ambil nama lengkap dari API daftar_siswa_api.php
$siswa_list = [];
$api_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../api/daftar_siswa_api.php';
$api_data = @json_decode(file_get_contents($api_url), true);
if (isset($api_data['status']) && $api_data['status'] === 'success' && isset($api_data['data'])) {
  $siswa_list = $api_data['data'];
  foreach ($siswa_list as $s) {
    if (isset($s['username']) && $s['username'] === $siswa && isset($s['nama'])) {
      $nama_lengkap = $s['nama'];
      break;
    }
  }
}
$kehadiran_path = __DIR__ . '/../api/kehadiran_data.json';
$kehadiran_data = file_exists($kehadiran_path) ? json_decode(file_get_contents($kehadiran_path), true) : [];
if (!is_array($kehadiran_data)) $kehadiran_data = [];
$kehadiran_siswa = array_filter($kehadiran_data, function($k) use ($siswa) {
    return isset($k['username']) && $k['username'] === $siswa;
});
$hadir = $izin = $alpa = 0;
// Pastikan status kehadiran mengikuti format backend siswa (string atau int)
foreach ($kehadiran_siswa as $k) {
    $status_val = isset($k['status']) ? $k['status'] : null;
    if ($status_val === 1 || strtolower($status_val) === 'hadir') $hadir++;
    elseif ($status_val === 2 || strtolower($status_val) === 'izin') $izin++;
    elseif ($status_val === 3 || strtolower($status_val) === 'alpa') $alpa++;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detail Kehadiran Anak - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous"/>
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
      --secondary-gradient: linear-gradient(135deg, #ffd200 0%, #f7971e 100%);
      --accent-color: #f7971e;
      --dark-accent: #e67e00;
      --light-color: #f8fafc;
      --card-shadow: 0 10px 30px rgba(247, 151, 30, 0.15);
      --hover-shadow: 0 15px 35px rgba(247, 151, 30, 0.25);
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
    
    .container { 
      max-width: 1200px; 
      margin: 2rem auto; 
      padding: 0 1.5rem; 
    }
    
    .detail-card { 
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
    
    .detail-card:hover {
      box-shadow: var(--hover-shadow);
      transform: translateY(-5px);
    }
    
    .detail-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 8px;
      height: 100%;
      background: var(--primary-gradient);
    }
    
    .detail-title { 
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--dark-accent);
      margin-bottom: 1.5rem;
      border-bottom: 2px solid rgba(247, 151, 30, 0.2);
      padding-bottom: 1rem;
      display: flex;
      align-items: center;
    }
    
    .detail-title i {
      margin-right: 0.8rem;
      font-size: 1.5rem;
      background: var(--primary-gradient);
      -webkit-text-fill-color: transparent;
    }
    
    .kehadiran-table { 
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      margin-top: 1rem;
    }
    
    .kehadiran-table th { 
      background: var(--primary-gradient);
      color: white;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.85rem;
      letter-spacing: 0.5px;
      padding: 1rem 0.8rem;
      text-align: center;
    }
    
    .kehadiran-table td { 
      padding: 1rem 0.8rem;
      text-align: center;
      border-bottom: 1px solid rgba(247, 151, 30, 0.1);
      font-size: 0.95rem;
      transition: all 0.2s;
    }
    
    .kehadiran-table tr:last-child td { 
      border-bottom: none; 
    }
    
    .hadir { color: #20bf6b; font-weight: 700; }
    .izin { color: #f7b731; font-weight: 700; }
    .alpa { color: #e74c3c; font-weight: 700; }
    
    .summary { 
      font-size: 1.1rem; 
      font-weight: 600; 
      color: var(--dark-accent); 
      margin-top: 1.5rem; 
      padding: 1rem;
      background: rgba(255, 255, 255, 0.7);
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(247, 151, 30, 0.1);
    }
    
    @keyframes fadeIn { 
      0% { opacity: 0; transform: translateY(20px); } 
      100% { opacity: 1; transform: translateY(0); } 
    }
    
    .back-btn { 
      margin-top: 1.5rem; 
      background: var(--primary-gradient); 
      color: #fff; 
      border: none; 
      border-radius: 8px; 
      padding: 0.8rem 1.8rem; 
      font-weight: 600; 
      cursor: pointer; 
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(247, 151, 30, 0.3);
    }
    
    .back-btn:hover { 
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(247, 151, 30, 0.4);
    }
    
    select {
      padding: 0.5rem 1rem;
      border-radius: 8px;
      border: 1px solid rgba(247, 151, 30, 0.3);
      background-color: white;
      font-family: 'Poppins', sans-serif;
      font-size: 0.9rem;
      color: #333;
      transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
    
    select:focus {
      outline: none;
      border-color: var(--accent-color);
      box-shadow: 0 0 0 3px rgba(247, 151, 30, 0.2);
    }
  </style>
</head>
<body>
  <?php include 'ortu_navbar.php'; ?>
  <div class="container">
    <div class="detail-card">
      <div class="detail-title"><i class="fa-solid fa-user-check"></i> Detail Kehadiran Anak</div>
      <div style="margin-bottom:1.5rem;">
        <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
          <div>
            <label for="siswaSelect" style="font-weight: 600; color: var(--dark-accent); margin-right: 0.5rem;">Nama Siswa:</label>
            <select id="siswaSelect">
              <option value="">-- Pilih Siswa --</option>
              <?php foreach ($siswa_list as $s): ?>
                <option value="<?php echo htmlspecialchars($s['username']); ?>" <?php if ($s['username'] === $siswa) echo 'selected'; ?>><?php echo htmlspecialchars($s['nama']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="bulanSelect" style="font-weight: 600; color: var(--dark-accent); margin-right: 0.5rem;">Bulan:</label>
            <select id="bulanSelect">
              <option value="1">Januari</option>
              <option value="2">Februari</option>
              <option value="3">Maret</option>
              <option value="4">April</option>
              <option value="5">Mei</option>
              <option value="6">Juni</option>
            </select>
          </div>
        </div>
      </div>
      <table class="kehadiran-table" id="kehadiranTable">
        <thead>
          <tr><th>Pertemuan</th><th>Status</th></tr>
        </thead>
        <tbody id="kehadiranBody">
        <!-- Data kehadiran akan dimuat di sini -->
        </tbody>
      </table>
      <div class="summary" id="kehadiranSummary"></div>
      <button class="back-btn" onclick="window.location.href='ortu_dashboard.php'">Kembali</button>
      <script>
      function loadKehadiran(username, bulan) {
        if (!username) {
          document.getElementById('kehadiranBody').innerHTML = '<tr><td colspan="2">Silakan pilih siswa.</td></tr>';
          document.getElementById('kehadiranSummary').innerHTML = '';
          return;
        }
        fetch(`../api/kehadiran_api.php?username=${encodeURIComponent(username)}&bulan=${encodeURIComponent(bulan)}`)
          .then(res => res.json())
          .then(data => {
            let rows = '';
            let hadir=0, izin=0, alpa=0;
            if (data.status === 'success' && Array.isArray(data.data) && data.data.length > 0) {
              // Urutkan berdasarkan hari
              data.data.sort((a,b)=>parseInt(a.hari)-parseInt(b.hari));
              data.data.forEach(k => {
                let status = '-';
                let className = '';
                if (k.status === 1 || String(k.status).toLowerCase() === 'hadir') { status = 'Hadir'; className = 'hadir'; hadir++; }
                else if (k.status === 2 || String(k.status).toLowerCase() === 'izin') { status = 'Izin'; className = 'izin'; izin++; }
                else if (k.status === 3 || String(k.status).toLowerCase() === 'alpa') { status = 'Alpa'; className = 'alpa'; alpa++; }
                rows += `<tr><td>${k.hari}</td><td class="${className}">${status}</td></tr>`;
              });
            } else {
              rows = '<tr><td colspan="2">Belum ada data kehadiran.</td></tr>';
            }
            document.getElementById('kehadiranBody').innerHTML = rows;
            document.getElementById('kehadiranSummary').innerHTML = `
              <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 1rem;">
                <div style="text-align: center; padding: 0.8rem; background: rgba(32, 191, 107, 0.1); border-radius: 10px; min-width: 100px;">
                  <div class="hadir" style="font-size: 1.5rem;">${hadir}</div>
                  <div style="font-size: 0.9rem; color: #555;">Hadir</div>
                </div>
                <div style="text-align: center; padding: 0.8rem; background: rgba(247, 183, 49, 0.1); border-radius: 10px; min-width: 100px;">
                  <div class="izin" style="font-size: 1.5rem;">${izin}</div>
                  <div style="font-size: 0.9rem; color: #555;">Izin</div>
                </div>
                <div style="text-align: center; padding: 0.8rem; background: rgba(231, 76, 60, 0.1); border-radius: 10px; min-width: 100px;">
                  <div class="alpa" style="font-size: 1.5rem;">${alpa}</div>
                  <div style="font-size: 0.9rem; color: #555;">Alpa</div>
                </div>
              </div>
            `;
          });
      }
      document.getElementById('siswaSelect').addEventListener('change', function() {
        loadKehadiran(this.value, document.getElementById('bulanSelect').value);
      });
      document.getElementById('bulanSelect').addEventListener('change', function() {
        loadKehadiran(document.getElementById('siswaSelect').value, this.value);
      });
      // Load default saat halaman dibuka
      window.addEventListener('DOMContentLoaded', function() {
        loadKehadiran(document.getElementById('siswaSelect').value, document.getElementById('bulanSelect').value);
      });
      </script>
    </div>
  </div>
</body>
</html>
