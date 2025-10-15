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
  <title>Detail Nilai - Siswa</title>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="../responsive.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
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
    .subject-selector { display:flex; justify-content:center; gap:.6rem; flex-wrap:wrap; margin-bottom:1rem; }
    .subject-button { background: rgba(255,255,255,0.2); color:#fff; border:none; padding:0.6rem 1.1rem; border-radius:50px; font-weight:600; cursor:pointer; transition:all .3s; }
    .subject-button.active { background:#fff; color:#B83556; box-shadow:0 4px 12px rgba(184,53,86,0.3); }
    .table-container { background:#fff; border-radius:16px; padding:1rem; box-shadow:0 4px 20px rgba(0,0,0,0.1); overflow-x:auto; }
    table { width:100%; border-collapse:collapse; }
    th { background:#B83556; color:#fff; padding:.8rem; text-align:center; position:sticky; top:0; }
    th:first-child { text-align:left; border-top-left-radius:8px; border-bottom-left-radius:8px; }
    th:last-child { border-top-right-radius:8px; border-bottom-right-radius:8px; }
    td { padding:.7rem .9rem; text-align:center; border-bottom:1px solid #eee; }
    td:first-child { text-align:left; font-weight:600; color:#333; }
    .grade-a { color:#38B000; font-weight:700; }
    .grade-b { color:#2D7DD2; font-weight:700; }
    .grade-c { color:#F9A826; font-weight:700; }
    .grade-d { color:#FF5E5B; font-weight:700; }
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
      <div class="title">Detail Nilai Siswa</div>
      <div class="subject-selector">
        <button class="subject-button active" data-subject="ipas">IPAS</button>
        <button class="subject-button" data-subject="matematika">Matematika</button>
        <button class="subject-button" data-subject="bindonesia">B. Indonesia</button>
        <button class="subject-button" data-subject="pai">PAI</button>
        <button class="subject-button" data-subject="ppkn">PPKN</button>
        <button class="subject-button" data-subject="olahraga">Olahraga</button>
      </div>
      <div class="table-container">
        <table id="nilai-table">
          <thead>
            <tr>
              <th>Nama Siswa</th>
              <th>Tugas</th>
              <th>Evaluasi</th>
              <th>UTS</th>
              <th>UAS</th>
              <th>Rata-rata</th>
              <th>Grade</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
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
    function getGrade(avg){ if(avg < 60) return '<span class="grade-d">D</span>'; if(avg >= 61 && avg <= 70) return '<span class="grade-c">C</span>'; if(avg >= 71 && avg <= 85) return '<span class="grade-b">B</span>'; if(avg > 86) return '<span class="grade-a">A</span>'; return '-'; }
    async function displayScores(subject){
      const table=document.getElementById('nilai-table');
      const thead=table.querySelector('thead');
      const tbody=table.querySelector('tbody');
      tbody.innerHTML='<tr><td colspan="100">Memuat data...</td></tr>';
      try{
        const siswaRes=await fetch('../api/daftar_siswa_api.php');
        const siswaJson=await siswaRes.json();
        let siswaList=siswaJson.status==='success'? siswaJson.data : [];
        // Tampilkan hanya siswa yang sedang login
        if (currentUser) {
          siswaList = siswaList.filter(s => s.username === currentUser);
        }
        if (!siswaList.length) {
          tbody.innerHTML = '<tr><td colspan="100">Data siswa tidak ditemukan.</td></tr>';
          return;
        }
        const tugasMasterRes=await fetch('../api/tugas_api.php');
        const tugasMaster=await tugasMasterRes.json();
        let tugasList=[]; let tugasKeys=[];
        if(subject==='ipas'){ tugasList = Array.isArray(tugasMaster)? tugasMaster.filter(t=>!t.mapel || t.mapel==='ipas') : []; tugasKeys = tugasList.map(t=>t.id); }
        const evalMasterRes=await fetch('../api/evaluasi_api.php');
        const evalMaster=await evalMasterRes.json();
        let evalList=[]; let evalKeys=[];
        if(subject==='ipas'){ evalList = Array.isArray(evalMaster)? evalMaster.filter(e=>!e.mapel || e.mapel==='ipas') : []; evalKeys = evalList.map(e=>e.id); }
        const tugasSubRes=await fetch('../tugas_submissions.json');
        const tugasSubs=tugasSubRes.ok? await tugasSubRes.json():[];
        const evalSubRes=await fetch('../evaluasi_submissions.json');
        const evalSubs=evalSubRes.ok? await evalSubRes.json():[];
        let headerHtml='<tr><th>Nama Siswa</th>';
        tugasKeys.forEach((k,i)=>{ headerHtml += `<th>Tugas ${i+1}</th>`; });
        headerHtml += '<th>UTS</th><th>UAS</th>';
        evalKeys.forEach((k,i)=>{ headerHtml += `<th>Evaluasi ${i+1}</th>`; });
        headerHtml += '<th>Rata-rata</th><th>Grade</th></tr>';
        thead.innerHTML=headerHtml;
        const siswaMap={};
        siswaList.forEach(s=>{ siswaMap[s.username] = { nama:s.nama, tugas:{}, evaluasi:{}, uts:null, uas:null }; });
        if(subject==='ipas' && Array.isArray(tugasSubs)){
          tugasSubs.forEach(s=>{ const sid=s.siswa_id; const tid=s.tugas_id; if(sid && tid && siswaMap[sid]){ if(s.nilai!==undefined && s.nilai!==null && s.nilai!==''){ siswaMap[sid].tugas[tid]=Number(s.nilai); } } });
        }
        if(subject==='ipas' && Array.isArray(evalSubs)){
          evalSubs.forEach(s=>{ const sid=s.siswa_id; const eid=s.evaluasi_id; if(sid && eid && siswaMap[sid]){ if(s.nilai!==undefined && s.nilai!==null && s.nilai!==''){ siswaMap[sid].evaluasi[eid]=Number(s.nilai); } } });
        }
        tbody.innerHTML='';
        let totalTugasArr=Array(tugasKeys.length).fill(0);
        let totalEvalArr=Array(evalKeys.length).fill(0);
        let totalUTS=0, totalUAS=0, totalAverage=0, count=0;
        siswaList.forEach(siswa=>{
          const dataSiswa=siswaMap[siswa.username] || { nama:siswa.nama, tugas:{}, evaluasi:{}, uts:null, uas:null };
          const tr=document.createElement('tr');
          const tdName=document.createElement('td'); tdName.textContent=siswa.nama; tr.appendChild(tdName);
          let tugasSum=0, tugasCount=0;
          tugasKeys.forEach((k,idx)=>{ const td=document.createElement('td'); const v=dataSiswa.tugas[k]!==undefined? Number(dataSiswa.tugas[k]):null; td.textContent= v!==null? v : '-'; tr.appendChild(td); if(v!==null){ tugasSum+=v; tugasCount++; totalTugasArr[idx]+=v; }});
          const uts=Number(dataSiswa.uts)||0; const tdUTS=document.createElement('td'); tdUTS.textContent = dataSiswa.uts!==null? dataSiswa.uts : '-'; tr.appendChild(tdUTS);
          const uas=Number(dataSiswa.uas)||0; const tdUAS=document.createElement('td'); tdUAS.textContent = dataSiswa.uas!==null? dataSiswa.uas : '-'; tr.appendChild(tdUAS);
          let evalSum=0, evalCount=0;
          evalKeys.forEach((k,idx)=>{ const td=document.createElement('td'); const v=dataSiswa.evaluasi[k]!==undefined? Number(dataSiswa.evaluasi[k]):null; td.textContent= v!==null? v : '-'; tr.appendChild(td); if(v!==null){ evalSum+=v; evalCount++; totalEvalArr[idx]+=v; }});
          let totalNilai=tugasSum + evalSum + uts + uas;
          let totalKomponen=tugasCount + evalCount + (dataSiswa.uts!==null?1:0) + (dataSiswa.uas!==null?1:0);
          let avg= totalKomponen>0 ? Math.round(totalNilai/totalKomponen) : 0;
          const tdAvg=document.createElement('td'); tdAvg.textContent = totalKomponen>0? avg : '-'; tr.appendChild(tdAvg);
          const tdGrade=document.createElement('td'); tdGrade.innerHTML = totalKomponen>0? getGrade(avg) : '-'; tr.appendChild(tdGrade);
          tbody.appendChild(tr);
          if(tugasCount>0 || evalCount>0 || uts || uas){ totalUTS+=uts; totalUAS+=uas; totalAverage+=avg; count++; }
        });
        // Tampilkan baris ringkasan hanya jika menampilkan lebih dari satu siswa
        if(count>1){
          const tr=document.createElement('tr'); tr.className='summary-row';
          const td=document.createElement('td'); td.textContent='Rata-rata Kelas'; tr.appendChild(td);
          tugasKeys.forEach((k,idx)=>{ const t=document.createElement('td'); t.textContent=Math.round(totalTugasArr[idx]/count); tr.appendChild(t); });
          const tUTS=document.createElement('td'); tUTS.textContent=Math.round(totalUTS/count); tr.appendChild(tUTS);
          const tUAS=document.createElement('td'); tUAS.textContent=Math.round(totalUAS/count); tr.appendChild(tUAS);
          evalKeys.forEach((k,idx)=>{ const e=document.createElement('td'); e.textContent=Math.round(totalEvalArr[idx]/count); tr.appendChild(e); });
          const avgClass=Math.round(totalAverage/count); const tAvg=document.createElement('td'); tAvg.textContent=avgClass; tr.appendChild(tAvg);
          const tGrade=document.createElement('td'); tGrade.innerHTML=getGrade(avgClass); tr.appendChild(tGrade);
          tbody.appendChild(tr);
        }
        if(siswaList.length===0){ tbody.innerHTML = `<tr><td colspan="${2 + tugasKeys.length + evalKeys.length + 3}">Belum ada data nilai untuk mapel ini.</td></tr>`; }
      }catch(err){ tbody.innerHTML='<tr><td colspan="100">Gagal memuat data nilai.</td></tr>'; }
    }
    document.querySelectorAll('.subject-button').forEach(btn=>{ btn.addEventListener('click', function(){ document.querySelectorAll('.subject-button').forEach(b=>b.classList.remove('active')); this.classList.add('active'); const s=this.dataset.subject; displayScores(s); }); });
    document.addEventListener('DOMContentLoaded', function(){ updateSidebarArrow(); displayScores('ipas'); });
  </script>
  <script src="../music-player.js"></script>
</body>
</html>

