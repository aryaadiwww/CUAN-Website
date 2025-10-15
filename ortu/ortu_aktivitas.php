<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'ortu') {
  header("Location: ../index.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Aktivitas Anak - Orang Tua</title>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="../responsive.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #B83556 0%, #DC97A5 100%);
      --secondary-gradient: linear-gradient(135deg, #DC97A5 0%, #B83556 100%);
      --accent-color: #B83556;
      --dark-accent: #a82747;
      --light-color: #f8fafc;
      --card-shadow: 0 10px 30px rgba(184, 53, 86, 0.15);
      --hover-shadow: 0 15px 35px rgba(184, 53, 86, 0.25);
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

    .title {
      font-size: 2rem;
      font-weight: 800;
      color: #fff;
      text-align: center;
      margin-bottom: 1.5rem;
      text-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .activity-container {
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 15px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
      animation: fadeInMain 0.5s ease-in-out;
      overflow: visible;
    }

    @keyframes fadeInMain {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .activity-toolbar {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-bottom: 10px;
    }

    .refresh-button {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      background-color: var(--accent-color);
      border: none;
      border-radius: 30px;
      color: #fff;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .refresh-button:hover {
      background-color: var(--dark-accent);
      transform: translateY(-2px);
    }

    .filter-container {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 20px;
    }

    .filter-button {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      background-color: #f5f5f5;
      border: none;
      border-radius: 30px;
      color: #555;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .filter-button.active {
      background-color: var(--accent-color);
      color: #fff;
    }

    .activity-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
      max-height: none;
      overflow: visible;
    }

    .activity-item {
      display: flex;
      gap: 15px;
      padding: 15px;
      background: #f9f9f9;
      border-radius: 12px;
      transition: all 0.3s ease;
      border-left: 4px solid #ddd;
      animation: slideIn 0.5s ease-out forwards;
      opacity: 0;
      transform: translateX(-20px);
    }

    .activity-item.tugas { border-left-color: #4CAF50; }
    .activity-item.evaluasi { border-left-color: #2196F3; }
    .activity-item.diskusi { border-left-color: #FF9800; }
    .activity-item.bahanajar { border-left-color: #673AB7; }

    .activity-avatar img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .activity-content {
      flex: 1;
    }

    .activity-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 5px;
    }

    .activity-item:hover {
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      transform: translateY(-3px);
    }

    .activity-name {
      font-weight: 600;
      color: #333;
    }

    .activity-time {
      font-size: 0.85rem;
      color: #888;
    }

    .activity-description {
      margin-bottom: 10px;
      color: #555;
      line-height: 1.4;
    }

    .activity-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .activity-category {
      font-size: 0.8rem;
      padding: 3px 10px;
      border-radius: 20px;
      font-weight: 500;
    }

    .category-tugas { background-color: rgba(76, 175, 80, 0.1); color: #4CAF50; }
    .category-evaluasi { background-color: rgba(33, 150, 243, 0.1); color: #2196F3; }
    .category-diskusi { background-color: rgba(255, 152, 0, 0.1); color: #FF9800; }
    .category-bahanajar { background-color: rgba(103, 58, 183, 0.1); color: #673AB7; }

    .activity-action {
      display: flex;
      gap: 10px;
    }

    .action-button {
      display: flex;
      align-items: center;
      gap: 5px;
      padding: 5px 10px;
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 20px;
      font-size: 0.8rem;
      color: #555;
      cursor: pointer;
      transition: all 0.2s;
    }

    .action-button:hover {
      background-color: #f0f0f0;
      transform: translateY(-2px);
    }

    .action-button.danger {
      border-color: #e57373;
      color: #c62828;
    }

    .action-button.danger:hover {
      background-color: #ffebee;
    }

    @keyframes slideIn {
      to { opacity: 1; transform: translateX(0); }
    }

    @media (max-width: 768px) {
      .activity-item { padding: 12px; }
      .activity-avatar img { width: 44px; height: 44px; }
    }

    @media (max-width: 480px) {
      .activity-item { padding: 10px; }
      .activity-name { font-size: 0.95rem; }
      .activity-time { font-size: 0.8rem; }
      .action-button { padding: 4px 8px; font-size: 0.75rem; }
    }
  </style>
</head>
<body>
  <?php include 'ortu_navbar.php'; ?>
  
  <div class="main-content">
    <h1 class="title">Aktivitas Anak</h1>
    <div class="activity-container">
      <div class="activity-toolbar">
        <button id="refresh-activities" class="refresh-button">Muat Ulang</button>
      </div>
      <div class="filter-container">
        <button class="filter-button active" data-filter="all">Semua</button>
        <button class="filter-button" data-filter="bahanajar">Bahan Ajar</button>
        <button class="filter-button" data-filter="tugas">Tugas</button>
        <button class="filter-button" data-filter="evaluasi">Evaluasi</button>
        <button class="filter-button" data-filter="diskusi">Diskusi</button>
      </div>
      <div class="activity-list" id="activity-list"></div>
    </div>
  </div>

  <script>
    // Ambil data siswa dari session
    const studentUsername = <?php echo json_encode(isset($_SESSION['siswa_username']) ? $_SESSION['siswa_username'] : ''); ?>;
    
    let activities = [];
    let activitiesLoaded = false;
    
    function fetchActivities(cb) {
      fetch('../api/aktivitas_terbaru.php?_ts=' + Date.now(), { cache: 'no-store' })
        .then(r => r.json())
        .then(data => {
          if (data.status === 'success') {
            let raw = Array.isArray(data.data) ? data.data : [];
            // Tampilkan aktivitas siswa (dari session) + seluruh aktivitas guru (buat tugas/evaluasi & bahan ajar)
            raw = raw.filter(it => (it.username === studentUsername) || (it.peran === 'guru') || (it.aksi === 'buat') || (it.kategori === 'bahanajar'));
            const hiddenKeys = loadHiddenActivities();
            activities = raw.map(item => {
              let description = '';
              if (item.kategori === 'tugas') {
                description = item.peran === 'guru' || item.aksi === 'buat' ? 
                  `Membuat tugas: <b>${item.judul}</b>` : 
                  `Mengumpulkan tugas: <b>${item.judul}</b>${item.nilai !== null ? ` (Nilai: <b>${item.nilai}</b>)` : ''}`;
                if (item.file) { description += `<br>File: <a href='../uploads/bahan_ajar/${item.file}' target='_blank'>${item.file}</a>`; }
                if (item.link) { description += `<br>Link: <a href='${item.link}' target='_blank'>${item.link}</a>`; }
              } else if (item.kategori === 'evaluasi') {
                description = item.peran === 'guru' || item.aksi === 'buat' ? 
                  `Membuat evaluasi: <b>${item.judul}</b>` : 
                  `Menyelesaikan evaluasi: <b>${item.judul}</b>${item.nilai !== null ? ` (Nilai: <b>${item.nilai}</b>)` : ''}`;
                if (item.file) { description += `<br>File: <a href='../uploads/bahan_ajar/${item.file}' target='_blank'>${item.file}</a>`; }
                if (item.link) { description += `<br>Link: <a href='${item.link}' target='_blank'>${item.link}</a>`; }
              } else if (item.kategori === 'bahanajar') {
                description = `Upload bahan ajar: <b>${item.judul}</b>`;
                if (item.file) { description += `<br>File: <a href='../uploads/bahan_ajar/${item.file}' target='_blank'>${item.file}</a>`; }
                if (item.link) { description += `<br>Link: <a href='${item.link}' target='_blank'>${item.link}</a>`; }
              } else if (item.kategori === 'diskusi') {
                description = `Diskusi: <b>${item.judul}</b>`;
              }
              // Gunakan key yang lebih stabil, misal jika ada id, gunakan id, jika tidak gunakan kombinasi yang tidak berubah
              let stableKey = item.id ? String(item.id) : `${item.kategori}|${item.username}|${item.judul}`;
              return {
                name: item.nama,
                username: item.username,
                category: item.kategori,
                title: item.judul || '',
                rawTime: item.waktu || '',
                description: description,
                time: formatWaktu(item.waktu),
                link: item.link || null,
                key: stableKey
              };
            }).filter(a => !hiddenKeys.has(a.key));
            activitiesLoaded = true;
            if (typeof cb === 'function') cb();
          }
        })
        .catch(() => {
          activities = [];
          activitiesLoaded = true;
          if (typeof cb === 'function') cb();
        });
    }
    
    function formatWaktu(waktu) {
      const date = new Date(waktu ? waktu.replace(' ', 'T') : Date.now());
      if (isNaN(date.getTime())) return waktu || '';
      const now = new Date();
      const diff = (now - date) / 1000;
      if (diff < 86400 && now.getDate() === date.getDate()) {
        return `Hari ini, ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
      } else {
        return `${String(date.getDate()).padStart(2, '0')}-${String(date.getMonth() + 1).padStart(2, '0')}-${date.getFullYear()}, ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
      }
    }
    
    function loadHiddenActivities() {
      try {
        const raw = localStorage.getItem('hidden_activities_ortu');
        const arr = raw ? JSON.parse(raw) : [];
        return new Set(Array.isArray(arr) ? arr : []);
      } catch (e) {
        return new Set();
      }
    }
    
    function saveHiddenActivities(set) {
      try {
        localStorage.setItem('hidden_activities_ortu', JSON.stringify(Array.from(set)));
      } catch (e) {}
    }
    
    function deleteActivity(key) {
      try {
        if (!key) return;
        if (!confirm('Hapus riwayat aktivitas ini dari tampilan?')) return;
        // Simpan ke localStorage (persisten di browser)
        const s = loadHiddenActivities();
        s.add(key);
        saveHiddenActivities(s);
        // Hapus dari array activities saat ini agar langsung hilang tanpa reload
        activities = activities.filter(a => a.key !== key);
        const activeBtn = document.querySelector('.filter-button.active');
        const filterType = activeBtn ? activeBtn.getAttribute('data-filter') : 'all';
        renderActivities(filterType);
      } catch (_) {}
    }

    function renderActivities(filterType = 'all') {
      const list = document.getElementById('activity-list');
      list.innerHTML = '';
      if (!activitiesLoaded) {
        list.innerHTML = '<div style="padding:20px;color:#888;">Memuat data aktivitas...</div>';
        return;
      }
      const hiddenKeys = loadHiddenActivities();
      let filtered = filterType === 'all' ? activities : activities.filter(a => a.category === filterType);
      filtered = filtered.filter(a => !hiddenKeys.has(a.key));
      if (filtered.length === 0) {
        list.innerHTML = '<div style="padding:20px;color:#888;">Tidak ada aktivitas.</div>';
        return;
      }
      filtered.forEach((act, index) => {
        const item = document.createElement('div');
        item.className = `activity-item ${act.category}`;
        item.style.animationDelay = `${index * 0.1}s`;
        item.innerHTML = `
          <div class="activity-avatar"><img src="../img/profile.png" alt="${act.name}"></div>
          <div class="activity-content">
            <div class="activity-header"><div class="activity-name">${act.name}</div><div class="activity-time">${act.time}</div></div>
            <div class="activity-description">${act.description}</div>
            <div class="activity-meta">
              <span class="activity-category category-${act.category}">${getCategoryName(act.category)}</span>
              <div class="activity-action">
                <button class="action-button danger" onclick='deleteActivity(${JSON.stringify(act.key)})'>Hapus</button>
              </div>
            </div>
          </div>`;
        list.appendChild(item);
      });
    }
    
    function getCategoryName(c) {
      switch (c) {
        case 'tugas': return 'Tugas';
        case 'evaluasi': return 'Evaluasi';
        case 'diskusi': return 'Diskusi';
        case 'bahanajar': return 'Bahan Ajar';
        default: return c;
      }
    }
    
    function setupFilters() {
      const btns = document.querySelectorAll('.filter-button');
      btns.forEach(b => {
        b.addEventListener('click', function() {
          btns.forEach(x => x.classList.remove('active'));
          this.classList.add('active');
          renderActivities(this.getAttribute('data-filter'));
        });
      });
    }
    
    document.getElementById('refresh-activities').addEventListener('click', function() {
      const activeBtn = document.querySelector('.filter-button.active');
      const f = activeBtn ? activeBtn.getAttribute('data-filter') : 'all';
      fetchActivities(() => renderActivities(f));
    });
    
    document.addEventListener('DOMContentLoaded', function() {
      fetchActivities(() => {
        renderActivities('all');
      });
      setupFilters();
    });
  </script>
</body>
</html>
