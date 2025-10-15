<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'ortu') {
  header("Location: ../index.html");
  exit();
}

$siswa = isset($_SESSION['siswa']) ? $_SESSION['siswa'] : 'aryaadiww';

// Ambil data siswa untuk mapping username -> nama
$siswa_list = json_decode(@file_get_contents('http://localhost/CUAN/api/daftar_siswa_api.php'), true);
$siswa_map = [];
if (is_array($siswa_list) && isset($siswa_list['status']) && $siswa_list['status'] === 'success' && isset($siswa_list['data'])) {
  foreach ($siswa_list['data'] as $s) {
    $siswa_map[$s['username']] = $s['nama'];
  }
}

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

// Fungsi untuk mendapatkan status tugas
function get_tugas_status($tugas_id, $siswa_id, $submissions) {
  foreach ($submissions as $sub) {
    if ($sub['tugas_id'] == $tugas_id && $sub['siswa_id'] == $siswa_id) {
      return $sub;
    }
  }
  return null;
}

// Fungsi untuk mendapatkan status evaluasi
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
  <title>Detail IPAS Anak - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous"/>
  <style>
    :root {
      --primary-color: #b83556;
      --secondary-color: #7d233a;
      --primary-gradient: linear-gradient(135deg, #b83556 0%, #7d233a 100%);
      --table-border-radius: 12px;
      --table-shadow: 0 4px 15px rgba(184, 53, 86, 0.12);
      --table-header-bg: rgba(184, 53, 86, 0.08);
      --table-row-hover: rgba(184, 53, 86, 0.04);
      --table-border-color: rgba(184, 53, 86, 0.15);
    }
    body { background: var(--primary-gradient); font-family: 'Poppins', sans-serif; margin: 0; padding: 0; min-height: 100vh; }
    .container { max-width: 1100px; margin: 0 auto; padding: 2rem 1rem; }
    .section { background: #fff; border-radius: 18px; box-shadow: 0 8px 30px rgba(125, 35, 58, 0.2); padding: 2rem 1.5rem; margin-bottom: 2.5rem; animation: fadeInUp 0.7s; position: relative; overflow: hidden; }
    .section::before { content: ''; position: absolute; top: 0; left: 0; width: 5px; height: 100%; background: var(--primary-gradient); }
    .section-title { font-size: 1.4rem; font-weight: 700; color: var(--primary-color); margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.7rem; }
    .section-title i { color: var(--primary-color); font-size: 1.3rem; }
    .bahan-ajar-list, .tugas-list, .evaluasi-list, .diskusi-list { margin: 0; padding: 0; list-style: none; }
    .bahan-ajar-item, .tugas-item, .evaluasi-item, .diskusi-item { background: #f8fafc; border-radius: 12px; box-shadow: 0 4px 12px rgba(184, 53, 86, 0.1); margin-bottom: 1.2rem; padding: 1.2rem 1rem; transition: all 0.3s ease; }
    .bahan-ajar-item:hover, .tugas-item:hover, .evaluasi-item:hover, .diskusi-item:hover { transform: translateY(-5px); box-shadow: 0 6px 16px rgba(184, 53, 86, 0.15); }
    .bahan-ajar-item:last-child, .tugas-item:last-child, .evaluasi-item:last-child, .diskusi-item:last-child { margin-bottom: 0; }
    .bahan-ajar-title { font-size: 1.1rem; font-weight: 600; color: var(--primary-color); margin-bottom: 0.3rem; }
    .bahan-ajar-desc { font-size: 0.98rem; color: #555; margin-bottom: 0.5rem; line-height: 1.5; }
    .bahan-ajar-meta { font-size: 0.92rem; color: #888; margin-bottom: 0.5rem; }
    .bahan-ajar-file { margin-top: 0.8rem; }
    .tugas-title, .evaluasi-title { font-size: 1.05rem; font-weight: 600; color: var(--primary-color); margin-bottom: 0.2rem; }
    .tugas-status, .evaluasi-status { font-size: 0.98rem; font-weight: 600; margin-bottom: 0.2rem; display: inline-block; padding: 0.3rem 0.8rem; border-radius: 50px; }
    .tugas-status.selesai, .evaluasi-status.selesai { color: #fff; background-color: #20bf6b; }
    .tugas-status.belum, .evaluasi-status.belum { color: #fff; background-color: var(--primary-color); }
    .tugas-nilai, .evaluasi-nilai { font-size: 0.98rem; color: #333; font-weight: 500; margin-top: 0.5rem; }
    .diskusi-thread { font-size: 1.05rem; font-weight: 600; color: var(--primary-color); margin-bottom: 0.2rem; }
    .diskusi-meta { font-size: 0.92rem; color: #888; margin-bottom: 0.5rem; }
    .diskusi-reply { margin-left: 1.2rem; background: rgba(184, 53, 86, 0.05); border-radius: 8px; padding: 0.7rem 0.8rem; margin-top: 0.5rem; font-size: 0.97rem; color: #222; border-left: 3px solid var(--primary-color); }
    .back-btn { margin-top: 1.5rem; background: var(--primary-gradient); color: #fff; border: none; border-radius: 50px; padding: 0.8rem 2rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 8px rgba(184, 53, 86, 0.3); }
    .back-btn:hover { background: var(--secondary-color); transform: translateY(-2px); box-shadow: 0 6px 12px rgba(184, 53, 86, 0.5); }
    
    /* Tabel Styling */
    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      margin: 1rem 0;
      border-radius: var(--table-border-radius);
      overflow: hidden;
      box-shadow: var(--table-shadow);
      background: #fff;
    }
    
    table thead {
      background: var(--table-header-bg);
    }
    
    table th {
      text-align: left;
      padding: 1rem;
      font-weight: 600;
      color: var(--primary-color);
      border-bottom: 2px solid var(--table-border-color);
      position: relative;
    }
    
    table td {
      padding: 0.9rem 1rem;
      border-bottom: 1px solid rgba(0,0,0,0.05);
      color: #444;
      transition: all 0.2s ease;
    }
    
    table tbody tr:last-child td {
      border-bottom: none;
    }
    
    table tbody tr:hover td {
      background-color: var(--table-row-hover);
    }
    
    .status-badge {
      display: inline-block;
      padding: 0.4rem 0.8rem;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 600;
      text-align: center;
    }
    
    .status-done {
      background-color: rgba(32, 191, 107, 0.15);
      color: #20bf6b;
    }
    
    .status-pending {
      background-color: rgba(184, 53, 86, 0.15);
      color: var(--primary-color);
    }
    
    .value-badge {
       display: inline-block;
       padding: 0.3rem 0.7rem;
       border-radius: 6px;
       font-weight: 600;
       background: rgba(184, 53, 86, 0.1);
       color: var(--primary-color);
     }
     
     .file-link, .link-badge {
       display: inline-flex;
       align-items: center;
       padding: 0.4rem 0.8rem;
       border-radius: 6px;
       font-size: 0.85rem;
       font-weight: 600;
       text-decoration: none;
       transition: all 0.2s ease;
     }
     
     .file-link {
       background-color: rgba(32, 191, 107, 0.15);
       color: #20bf6b;
     }
     
     .link-badge {
       background-color: rgba(45, 152, 218, 0.15);
       color: #2d98da;
     }
     
     .file-link:hover, .link-badge:hover {
       transform: translateY(-2px);
       box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
     }
     
     .file-link i, .link-badge i {
       margin-right: 5px;
     }
    
    /* Responsif untuk tabel */
    @media (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }
      
      table {
        box-shadow: none;
      }
      
      thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
      }
      
      tr {
        margin-bottom: 1rem;
        display: block;
        border-radius: var(--table-border-radius);
        box-shadow: var(--table-shadow);
        background: #fff;
      }
      
      td {
        border: none;
        position: relative;
        padding-left: 50%;
        text-align: right;
        border-bottom: 1px solid rgba(0,0,0,0.05);
      }
      
      td:before {
        position: absolute;
        top: 0.9rem;
        left: 1rem;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        text-align: left;
        font-weight: 600;
        color: var(--primary-color);
      }
      
      /* Label the data */
      #tugasTable td:nth-of-type(1):before { content: "Judul Tugas"; }
      #tugasTable td:nth-of-type(2):before { content: "Tanggal"; }
      #tugasTable td:nth-of-type(3):before { content: "Status"; }
      #tugasTable td:nth-of-type(4):before { content: "Nilai"; }
      #tugasTable td:nth-of-type(5):before { content: "File/Link"; }
      
      #evaluasiTable td:nth-of-type(1):before { content: "Judul Evaluasi"; }
      #evaluasiTable td:nth-of-type(2):before { content: "Tanggal"; }
      #evaluasiTable td:nth-of-type(3):before { content: "Status"; }
      #evaluasiTable td:nth-of-type(4):before { content: "Nilai"; }
      #evaluasiTable td:nth-of-type(5):before { content: "File/Link"; }
    }
    
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(40px) scale(0.95); } 100% { opacity: 1; transform: none; } }
    @media (max-width: 700px) { .container { padding: 1rem 0.2rem; } .section { padding: 1.2rem 0.5rem; } }
  </style>
</head>
<body>
  <?php include 'ortu_navbar.php'; ?>
  <div class="container">
    <!-- Pilih Siswa -->
    <div class="section">
      <div class="section-title"><i class="fa-solid fa-user-graduate"></i> Informasi Siswa</div>
      <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Pilih Siswa:</label>
        <select id="siswaSelect" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd; font-family: 'Poppins', sans-serif;">
          <?php
          if (!empty($siswa_map)) {
            foreach ($siswa_map as $username => $nama) {
              $selected = ($username == $siswa) ? 'selected' : '';
              echo "<option value=\"$username\" $selected>$nama</option>";
            }
          } else {
            echo "<option value=\"aryaadiww\">Arya Adi Wicaksono</option>";
          }
          ?>
        </select>
      </div>
      
      <div class="summary-info">
        <div class="summary-item">
          <div class="summary-value" id="totalBahanAjar">0</div>
          <div class="summary-label">Bahan Ajar</div>
        </div>
        <div class="summary-item">
          <div class="summary-value" id="totalTugas">0</div>
          <div class="summary-label">Tugas</div>
        </div>
        <div class="summary-item">
          <div class="summary-value" id="totalEvaluasi">0</div>
          <div class="summary-label">Evaluasi</div>
        </div>
        <div class="summary-item">
          <div class="summary-value" id="totalDiskusi">0</div>
          <div class="summary-label">Diskusi</div>
        </div>
      </div>
    </div>
    
    <div class="section" id="bahanAjarSection">
      <div class="section-title"><i class="fa-solid fa-book"></i> Bahan Ajar IPAS</div>
      <ul class="bahan-ajar-list" id="bahanAjarList"></ul>
    </div>
    
    <div class="section" id="tugasSection">
      <div class="section-title"><i class="fa-solid fa-clipboard-list"></i> Tugas IPAS</div>
      <table id="tugasTable">
        <thead>
          <tr>
            <th>Judul Tugas</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Nilai</th>
            <th>File/Link</th>
          </tr>
        </thead>
        <tbody id="tugasList"></tbody>
      </table>
    </div>
    
    <div class="section" id="evaluasiSection">
      <div class="section-title"><i class="fa-solid fa-pen-ruler"></i> Evaluasi IPAS</div>
      <table id="evaluasiTable">
        <thead>
          <tr>
            <th>Judul Evaluasi</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Nilai</th>
            <th>File/Link</th>
          </tr>
        </thead>
        <tbody id="evaluasiList"></tbody>
      </table>
    </div>
    
    <div class="section" id="diskusiSection">
      <div class="section-title"><i class="fa-solid fa-comments"></i> Forum Diskusi IPAS</div>
      <ul class="diskusi-list" id="diskusiList"></ul>
    </div>
    
    <button class="back-btn" onclick="window.location.href='ortu_dashboard.php'"><i class="fas fa-arrow-left"></i> Kembali</button>
  </div>
  <script>
    function renderBahanAjar(list) {
      if (!list.length) return '<li class="bahan-ajar-item">Belum ada bahan ajar.</li>';
      return list.map(item => {
        let fileLink = '';
        if (item.file) {
          fileLink = `<div class="bahan-ajar-file"><a href="${item.file}" class="file-link" target="_blank"><i class="fas fa-file-download"></i> Unduh Materi</a></div>`;
        } else if (item.link) {
          fileLink = `<div class="bahan-ajar-file"><a href="${item.link}" class="link-badge" target="_blank"><i class="fas fa-link"></i> Buka Link</a></div>`;
        }
        return `
        <li class="bahan-ajar-item">
          <div class="bahan-ajar-title">${item.title || item.judul || '-'}</div>
          <div class="bahan-ajar-desc">${item.desc || item.deskripsi || '-'}</div>
          <div class="bahan-ajar-meta">Dikirim oleh: <b>${item.teacher || 'Guru IPAS'}</b> | ${item.date || '-'}</div>
          ${fileLink}
        </li>
      `;
      }).join('');
    }
    
    function renderTugas(list) {
      if (!list.length) return '<tr><td colspan="5" style="text-align: center;">Belum ada tugas.</td></tr>';
      return list.map(item => {
        const status = item.status === 'Selesai' ? 'done' : 'pending';
        let fileLink = '-';
        if (item.file) {
          fileLink = `<a href="${item.file}" class="file-link" target="_blank"><i class="fas fa-file-download"></i> Unduh</a>`;
        } else if (item.link) {
          fileLink = `<a href="${item.link}" class="link-badge" target="_blank"><i class="fas fa-link"></i> Buka Link</a>`;
        }
        return `
          <tr class="tugas-row">
            <td class="tugas-title-cell">${item.judul || '-'}</td>
            <td class="tugas-date-cell">${item.tanggal || '-'}</td>
            <td class="tugas-status-cell"><span class="status-badge status-${status}">${item.status || 'Belum Dikerjakan'}</span></td>
            <td class="tugas-score-cell">${item.nilai !== null && item.nilai !== undefined ? `<span class="value-badge tugas-score">${item.nilai}</span>` : '-'}</td>
            <td class="tugas-file-cell">${fileLink}</td>
          </tr>
        `;
      }).join('');
    }
    
    function renderEvaluasi(list) {
      if (!list.length) return '<tr><td colspan="5" style="text-align: center;">Belum ada evaluasi.</td></tr>';
      return list.map(item => {
        const status = item.status === 'Selesai' ? 'done' : 'pending';
        let fileLink = '-';
        if (item.file) {
          fileLink = `<a href="${item.file}" class="file-link" target="_blank"><i class="fas fa-file-download"></i> Unduh</a>`;
        } else if (item.link) {
          fileLink = `<a href="${item.link}" class="link-badge" target="_blank"><i class="fas fa-link"></i> Buka Link</a>`;
        }
        return `
          <tr class="evaluasi-row">
            <td class="evaluasi-title-cell">${item.judul || '-'}</td>
            <td class="evaluasi-date-cell">${item.tanggal || '-'}</td>
            <td class="evaluasi-status-cell"><span class="status-badge status-${status}">${item.status || 'Belum Dikerjakan'}</span></td>
            <td class="evaluasi-score-cell">${item.nilai !== null && item.nilai !== undefined ? `<span class="value-badge evaluasi-score">${item.nilai}</span>` : '-'}</td>
            <td class="evaluasi-file-cell">${fileLink}</td>
          </tr>
        `;
      }).join('');
    }
    
    function renderDiskusi(list) {
      if (!list.length) return '<li class="diskusi-item">Belum ada diskusi.</li>';
      return list.map(item => `
        <li class="diskusi-item">
          <div class="diskusi-thread">${item.thread || '-'}</div>
          <div class="diskusi-meta">Oleh: ${item.oleh || '-'} | ${item.tanggal || '-'}</div>
          ${(item.balasan||[]).map(b => `<div class="diskusi-reply"><b>${b.oleh}:</b> ${b.isi}</div>`).join('')}
        </li>
      `).join('');
    }

    // Fungsi untuk memuat data
    function loadData(siswaId) {
      fetch('api_ortu_ipas.php?username=' + encodeURIComponent(siswaId))
        .then(r => r.json())
        .then(data => {
          // Update bahan ajar
          document.getElementById('bahanAjarList').innerHTML = renderBahanAjar(data.bahan_ajar || []);
          document.getElementById('totalBahanAjar').textContent = (data.bahan_ajar || []).length;
          
          // Update tugas
          document.getElementById('tugasList').innerHTML = renderTugas(data.tugas || []);
          document.getElementById('totalTugas').textContent = (data.tugas || []).length;
          
          // Update evaluasi
          document.getElementById('evaluasiList').innerHTML = renderEvaluasi(data.evaluasi || []);
          document.getElementById('totalEvaluasi').textContent = (data.evaluasi || []).length;
          
          // Update diskusi
          document.getElementById('diskusiList').innerHTML = renderDiskusi(data.diskusi || []);
          document.getElementById('totalDiskusi').textContent = (data.diskusi || []).length;
        })
        .catch(error => {
          console.error('Error loading data:', error);
          document.getElementById('bahanAjarList').innerHTML = '<li class="bahan-ajar-item">Gagal memuat data.</li>';
          document.getElementById('tugasList').innerHTML = '<tr><td colspan="4" style="text-align: center;">Gagal memuat data.</td></tr>';
          document.getElementById('evaluasiList').innerHTML = '<tr><td colspan="4" style="text-align: center;">Gagal memuat data.</td></tr>';
          document.getElementById('diskusiList').innerHTML = '<li class="diskusi-item">Gagal memuat data.</li>';
        });
    }

    // Event listener untuk perubahan siswa
    document.getElementById('siswaSelect').addEventListener('change', function() {
      loadData(this.value);
    });

    // Load data awal
    loadData(document.getElementById('siswaSelect').value);
  </script>
</body>
</html>
