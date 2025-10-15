<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'siswa') {
  header("Location: ../index.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Aktivitas Terbaru - Siswa</title>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="../responsive.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
    body { background: linear-gradient(120deg, #B83556 0%, #DC97A5 60%, #fff0f5 100%); min-height:100vh; display:flex; }
  .sidebar { width:70px; background: linear-gradient(135deg, #1a2933ff 0%, #405d6fff 100%); color:#fff; transition:width .4s cubic-bezier(.68,-0.55,.27,1.55); overflow:hidden; position:fixed; left:0; top:0; height:100vh; z-index:1000; display:flex; flex-direction:column; box-shadow: 0 4px 24px #1a293355; }
  .sidebar.open { width:180px; box-shadow: none; }
    .sidebar .logo-section { display:flex; align-items:center; justify-content:center; padding:1rem 0; height:80px; opacity:0; visibility:hidden; transition:opacity .4s, visibility .4s; }
    .sidebar.open .logo-section { opacity:1; visibility:visible; }
    .sidebar .logo-section img { width:120px; height:60px; }
    .sidebar ul { list-style:none; padding:0; margin-top:10px; }
    .sidebar ul li { display:flex; align-items:center; padding:14px 18px; cursor:pointer; border-radius:18px; margin:8px; background: linear-gradient(135deg, #634338ff 0%, #ffb296ff 100%); transition: background .3s, transform .2s; }
    .sidebar ul li:hover { background:#e4aa95ff; transform: scale(1.06) translateX(4px) rotate(-2deg); }
    .menu-icon img { width:18px; height:18px; object-fit:contain; }
    .menu-text { display:none; color:#fff; font-weight:600; }
    .sidebar.open .menu-text { display:inline; margin-left:6px; }
  .main-content { flex:1; display:flex; flex-direction:column; background: linear-gradient(135deg, #B83556 0%, #DC97A5 100%); min-height:100vh; margin-left:70px; transition: background 0.4s, margin-left 0.4s; width:100vw; overflow-x:hidden; overflow-y:auto; }
  .sidebar.open ~ .main-content { margin-left:180px; transition: margin-left 0.4s; }
    @media screen and (max-width: 900px) {
      .main-content { margin-left: 0 !important; }
      .sidebar { width: 56px; }
      .sidebar.open { width: 140px; }
    }
    @media screen and (max-width: 600px) {
      .main-content { margin-left: 0 !important; padding: 0.5rem; }
      .sidebar { width: 44px; }
      .sidebar.open { width: 110px; }
      .sidebar .logo-section img { width: 80px; height: 40px; }
    }
    header { color:#fff; display:flex; justify-content:space-between; align-items:center; padding:0.5rem 2rem; background-color:#a82747ff; }
    .hamburger { cursor:pointer; background:#fff6; color:#B83556; border:none; border-radius:50%; width:40px; height:40px; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px #ffb34733; }
    .hamburger svg { width:28px; height:28px; }
    .profile-button { background: linear-gradient(90deg, #dc97a5b2 0%, #dc97a5b2 100%); color:#fff; border:none; border-radius:50px; padding:5px 16px 5px 10px; display:flex; align-items:center; font-weight:700; }
    .profile-avatar { width:32px; height:32px; border-radius:50%; background:#fff; border:2px solid #B83556; margin-right:4px; }
    .profile-menu {
      position: relative;
      display: flex;
      align-items: center;
    }
    .dropdown {
      display: none;
      position: absolute;
      right: 0;
      top: 110%;
      min-width: 160px;
      background: linear-gradient(135deg, #fff 60%, #DC97A5 100%);
      color: #B83556;
      border: 1.5px solid #DC97A5;
      border-radius: 14px;
      box-shadow: 0 4px 16px rgba(184,53,86,0.12);
      z-index: 9999;
      overflow: visible;
    }
    .dropdown.open { display: block; animation: dropdownFade 0.3s; }
    @keyframes dropdownFade { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    .dropdown a { display: flex; align-items: center; gap: 8px; padding: 12px 18px; color: #B83556; text-decoration: none; font-weight: 600; border-bottom: 1px solid #f3e6e6; background: none; transition: background 0.2s; }
    .dropdown a:last-child { border-bottom: none; }
    .dropdown a:hover { background: #f9e6ef; color: #a82747; }
    .content-wrap { padding: 1.5rem; }
    .title { font-size:1.8rem; font-weight:800; color:#fff; text-align:center; margin-bottom:1rem; text-shadow:0 2px 8px rgba(0,0,0,.1); }
    .activity-container { padding: 20px; background-color: #ffffff98; border-radius: 15px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08); animation: fadeInMain 0.5s ease-in-out; overflow: visible; }
    .activity-toolbar { display:flex; justify-content:flex-end; gap:10px; margin-bottom:10px; }
    .refresh-button { display:inline-flex; align-items:center; gap:8px; padding:8px 16px; background-color:#B83556; border:none; border-radius:30px; color:#fff; font-weight:600; cursor:pointer; }
    .filter-container { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:20px; }
    .filter-button { display:flex; align-items:center; gap:8px; padding:8px 16px; background-color:#f5f5f5; border:none; border-radius:30px; color:#555; font-weight:500; cursor:pointer; }
    .filter-button.active { background-color:#B83556; color:#fff; }
    .activity-list { display:flex; flex-direction:column; gap:15px; max-height:none; overflow:visible; }
    .activity-item { display:flex; gap:15px; padding:15px; background:#f9f9f9; border-radius:12px; transition: all 0.3s ease; border-left:4px solid #ddd; animation: slideIn 0.5s ease-out forwards; opacity:0; transform: translateX(-20px); }
    .activity-item.tugas { border-left-color:#4CAF50; }
    .activity-item.evaluasi { border-left-color:#2196F3; }
    .activity-item.diskusi { border-left-color:#FF9800; }
    .activity-item.bahanajar { border-left-color:#673AB7; }
    .activity-avatar img { width:50px; height:50px; border-radius:50%; object-fit:cover; border:2px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
    .activity-header { display:flex; justify-content:space-between; margin-bottom:5px; }
    .activity-item:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.08); transform: translateY(-3px); }
    .activity-name { font-weight:600; color:#333; }
    .activity-time { font-size:.85rem; color:#888; }
    .activity-description { margin-bottom:10px; color:#555; line-height:1.4; }
    .activity-meta { display:flex; justify-content:space-between; align-items:center; }
    .activity-category { font-size:.8rem; padding:3px 10px; border-radius:20px; font-weight:500; }
    .category-tugas { background-color: rgba(76, 175, 80, 0.1); color:#4CAF50; }
    .category-evaluasi { background-color: rgba(33, 150, 243, 0.1); color:#2196F3; }
    .category-diskusi { background-color: rgba(255, 152, 0, 0.1); color:#FF9800; }
    .category-bahanajar { background-color: rgba(103, 58, 183, 0.1); color:#673AB7; }
    .activity-action { display:flex; gap:10px; }
    .action-button { display:flex; align-items:center; gap:5px; padding:5px 10px; background-color:#fff; border:1px solid #ddd; border-radius:20px; font-size:.8rem; color:#555; cursor:pointer; transition:all .2s; }
    .action-button:hover { background-color:#f0f0f0; transform: translateY(-2px); }
    .action-button.danger { border-color:#e57373; color:#c62828; }
    .action-button.danger:hover { background-color:#ffebee; }
    @keyframes slideIn { to { opacity:1; transform: translateX(0); } }
    @media (max-width: 768px) { .activity-item { padding:12px; } .activity-avatar img { width:44px; height:44px; } }
    @media (max-width: 480px) { .activity-item { padding:10px; } .activity-name { font-size:.95rem; } .activity-time { font-size:.8rem; } .action-button { padding:4px 8px; font-size:.75rem; } }
  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
    <div class="logo-section"><img src="../img/cuan.png" alt="Logo CUAN"></div>
    <ul>
      <li onclick="location.href='siswa_dashboard.php'"><span class="menu-icon"><img src="../img/home.png" alt="Beranda"></span><span class="menu-text">Beranda</span></li>
      <li onclick="location.href='siswa_matapelajaran.php'"><span class="menu-icon"><img src="../img/book.png" alt="Mata Pelajaran"></span><span class="menu-text">Mata Pelajaran</span></li>
      <li onclick="location.href='siswa_jadwal.php'"><span class="menu-icon"><img src="../img/calendar.png" alt="Jadwal"></span><span class="menu-text">Jadwal</span></li>
      <li onclick="location.href='siswa_games.php'"><span class="menu-icon"><img src="../img/games.png" alt="Games"></span><span class="menu-text">Games</span></li>
    </ul>
  </div>
  <div class="main-content">
    <header>
      <button class="hamburger" id="sidebarToggleBtn" onclick="toggleSidebar()">
        <svg id="sidebarArrow" viewBox="0 0 24 24" fill="none"><path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
      <div class="profile-menu">
        <button class="profile-button" onclick="toggleDropdown()">
          <img src="../img/profile.png" alt="Avatar" class="profile-avatar" />
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left:2px;"><path d="M5 8L10 13L15 8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="dropdown" id="dropdown">
          <a href="siswa_edit_profile.php">Edit Profil</a>
          <a href="../logout.php">Logout</a>
        </div>
      </div>
    </header>
    <div class="content-wrap">
      <div class="title">Aktivitas Terbaru</div>
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
  </div>
  <script>
    const currentUser = <?php echo json_encode(isset($_SESSION['username']) ? $_SESSION['username'] : ''); ?>;
    function toggleSidebar(){ const s=document.getElementById('sidebar'); s.classList.toggle('open'); updateSidebarArrow(); }
    function updateSidebarArrow(){ const s=document.getElementById('sidebar'); const a=document.getElementById('sidebarArrow'); a.innerHTML = s.classList.contains('open') ? '<path d="M16 5l-8 7 8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' : '<path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'; }
    function toggleDropdown(){
      const d=document.getElementById('dropdown');
      d.classList.toggle('open');
    }
    document.addEventListener('click', function(e){
      const d=document.getElementById('dropdown');
      const p=document.querySelector('.profile-button');
      if(p && d && !p.contains(e.target) && !d.contains(e.target)){
        d.classList.remove('open');
      }
    });
    let activities=[]; let activitiesLoaded=false;
    function fetchActivities(cb){
      fetch('../api/aktivitas_terbaru.php?_ts='+Date.now(), { cache:'no-store' })
        .then(r=>r.json())
        .then(data=>{
          if(data.status==='success'){
            let raw = Array.isArray(data.data)? data.data : [];
            // Tampilkan aktivitas siswa (username saat ini) + seluruh aktivitas guru (buat tugas/evaluasi & bahan ajar)
            raw = raw.filter(it => (it.username === currentUser) || (it.peran === 'guru') || (it.aksi === 'buat') || (it.kategori === 'bahanajar'));
            const hiddenKeys = loadHiddenActivities();
            activities = raw.map(item=>{
              let description='';
              if(item.kategori==='tugas'){
                description = item.peran==='guru' || item.aksi==='buat' ? `Membuat tugas: <b>${item.judul}</b>` : `Mengumpulkan tugas: <b>${item.judul}</b>${item.nilai!==null?` (Nilai: <b>${item.nilai}</b>)`:''}`;
                if(item.file){ description+=`<br>File: <a href='../uploads/bahan_ajar/${item.file}' target='_blank'>${item.file}</a>`; }
                if(item.link){ description+=`<br>Link: <a href='${item.link}' target='_blank'>${item.link}</a>`; }
              } else if(item.kategori==='evaluasi'){
                description = item.peran==='guru' || item.aksi==='buat' ? `Membuat evaluasi: <b>${item.judul}</b>` : `Menyelesaikan evaluasi: <b>${item.judul}</b>${item.nilai!==null?` (Nilai: <b>${item.nilai}</b>)`:''}`;
                if(item.file){ description+=`<br>File: <a href='../uploads/bahan_ajar/${item.file}' target='_blank'>${item.file}</a>`; }
                if(item.link){ description+=`<br>Link: <a href='${item.link}' target='_blank'>${item.link}</a>`; }
              } else if(item.kategori==='bahanajar'){
                description = `Upload bahan ajar: <b>${item.judul}</b>`;
                if(item.file){ description+=`<br>File: <a href='../uploads/bahan_ajar/${item.file}' target='_blank'>${item.file}</a>`; }
                if(item.link){ description+=`<br>Link: <a href='${item.link}' target='_blank'>${item.link}</a>`; }
              } else if(item.kategori==='diskusi'){
                description = `Diskusi: <b>${item.judul}</b>`;
              }
              // Gunakan key yang lebih stabil, misal jika ada id, gunakan id, jika tidak gunakan kombinasi yang tidak berubah
              let stableKey = item.id ? String(item.id) : `${item.kategori}|${item.username}|${item.judul}`;
              return { name:item.nama, username:item.username, category:item.kategori, title:item.judul||'', rawTime:item.waktu||'', description:description, time:formatWaktu(item.waktu), link:item.link||null, key: stableKey };
            }).filter(a => !hiddenKeys.has(a.key));
            activitiesLoaded=true; if(typeof cb==='function') cb();
          }
        })
        .catch(()=>{ activities=[]; activitiesLoaded=true; if(typeof cb==='function') cb(); });
    }
    function formatWaktu(waktu){ const date=new Date(waktu? waktu.replace(' ','T') : Date.now()); if(isNaN(date.getTime())) return waktu||''; const now=new Date(); const diff=(now-date)/1000; if(diff<86400 && now.getDate()===date.getDate()){ return `Hari ini, ${String(date.getHours()).padStart(2,'0')}:${String(date.getMinutes()).padStart(2,'0')}`; } else { return `${String(date.getDate()).padStart(2,'0')}-${String(date.getMonth()+1).padStart(2,'0')}-${date.getFullYear()}, ${String(date.getHours()).padStart(2,'0')}:${String(date.getMinutes()).padStart(2,'0')}`; } }
    function loadHiddenActivities(){
      try { const raw = localStorage.getItem('hidden_activities_siswa'); const arr = raw ? JSON.parse(raw) : []; return new Set(Array.isArray(arr)? arr : []); } catch(e){ return new Set(); }
    }
    function saveHiddenActivities(set){
      try { localStorage.setItem('hidden_activities_siswa', JSON.stringify(Array.from(set))); } catch(e) {}
    }
    function deleteActivity(key){
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

    function renderActivities(filterType='all'){
      const list=document.getElementById('activity-list'); list.innerHTML='';
      if(!activitiesLoaded){ list.innerHTML='<div style="padding:20px;color:#888;">Memuat data aktivitas...</div>'; return; }
      const hiddenKeys = loadHiddenActivities();
      let filtered = filterType==='all' ? activities : activities.filter(a=>a.category===filterType);
      filtered = filtered.filter(a => !hiddenKeys.has(a.key));
      if(filtered.length===0){ list.innerHTML='<div style="padding:20px;color:#888;">Tidak ada aktivitas.</div>'; return; }
      filtered.forEach((act, index)=>{
        const item=document.createElement('div'); item.className = `activity-item ${act.category}`; item.style.animationDelay = `${index * 0.1}s`;
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
    function getCategoryName(c){ switch(c){ case 'tugas': return 'Tugas'; case 'evaluasi': return 'Evaluasi'; case 'diskusi': return 'Diskusi'; case 'bahanajar': return 'Bahan Ajar'; default: return c; } }
    function setupFilters(){ const btns=document.querySelectorAll('.filter-button'); btns.forEach(b=>{ b.addEventListener('click', function(){ btns.forEach(x=>x.classList.remove('active')); this.classList.add('active'); renderActivities(this.getAttribute('data-filter')); }); }); }
    document.getElementById('refresh-activities').addEventListener('click', function(){ const activeBtn=document.querySelector('.filter-button.active'); const f= activeBtn? activeBtn.getAttribute('data-filter') : 'all'; fetchActivities(()=>renderActivities(f)); });
    document.addEventListener('DOMContentLoaded', function(){ updateSidebarArrow(); fetchActivities(()=>{ renderActivities('all'); }); setupFilters(); });
  </script>
  <script src="../music-player.js"></script>
</body>
</html>

