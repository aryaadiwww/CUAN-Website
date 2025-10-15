
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
  <title>Detail Nilai - Orang Tua</title>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="../responsive.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #5e72e4 0%, #3b5998 100%);
      --secondary-gradient: linear-gradient(135deg, #3b5998 0%, #5e72e4 100%);
      --accent-color: #5e72e4;
      --dark-accent: #3b5998;
      --light-color: #f8fafc;
      --card-shadow: 0 10px 30px rgba(59, 89, 152, 0.15);
      --hover-shadow: 0 15px 35px rgba(59, 89, 152, 0.25);
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
    
    .nilai-card {
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
    
    .nilai-card:hover {
      box-shadow: var(--hover-shadow);
      transform: translateY(-5px);
    }
    
    .nilai-card::before {
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
      border-bottom: 2px solid rgba(94, 114, 228, 0.2);
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
    
    .table-container {
      background: transparent;
      border-radius: 12px;
      overflow: hidden;
      margin-top: 1rem;
      transition: box-shadow 0.3s;
    }
    
    #nilai-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    
    #nilai-table th {
      background: var(--primary-gradient);
      color: white;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.85rem;
      letter-spacing: 0.5px;
      padding: 1rem 0.8rem;
      text-align: center;
    }
    
    #nilai-table td {
      padding: 1rem 0.8rem;
      text-align: center;
      border-bottom: 1px solid rgba(94, 114, 228, 0.1);
      font-size: 0.95rem;
      transition: all 0.2s;
      background: white;
      color: #333;
    }
    
    #nilai-table tr:last-child td {
      border-bottom: none;
    }
    
    #nilai-table tr:hover td {
      background-color: rgba(94, 114, 228, 0.05);
    }
    
    #nilai-table tr.summary-row td {
      background: var(--primary-gradient);
      color: white;
      font-weight: 700;
      font-size: 1.1rem;
    }
    
    .subject-selector {
      display: flex;
      gap: 0.7rem;
      margin: 1.2rem 0 1.5rem 0;
      flex-wrap: wrap;
      justify-content: center;
    }
    
    .subject-button {
      background: linear-gradient(135deg, #414e9cff 0%, #24355cff 100%);
      color: #fff;
      border: none;
      border-radius: 30px;
      padding: 0.6rem 1.4rem;
      font-size: 1rem;
      font-weight: 600;
      box-shadow: 0 2px 8px rgba(94, 114, 228, 0.2);
      cursor: pointer;
      transition: all 0.3s ease;
      outline: none;
    }
    
    .subject-button.active, .subject-button:hover {
      background: linear-gradient(135deg, #3b5998 0%, #5e72e4 100%);
      color: #dcac96ff;
      transform: scale(1.07);
      box-shadow: 0 4px 16px rgba(94, 114, 228, 0.3);
    }
    
    .grade-a, .grade-b, .grade-c, .grade-d {
      font-weight: bold;
      font-size: 1.1em;
      border-radius: 20px;
      padding: 0.4rem 0.8rem;
      display: inline-block;
      min-width: 2.5rem;
    }
    
    .grade-a { 
      background: #5e72e4; 
      color: #ffd700; 
    }
    
    .grade-b { 
      background: #3b5998; 
      color: #fff; 
    }
    
    .grade-c { 
      background: #5e72e4; 
      color: #fff; 
    }
    
    .grade-d { 
      background: #3b5998; 
      color: #ff5e62; 
    }
    
    .summary-info {
      display: flex;
      justify-content: space-between;
      margin-top: 1.5rem;
      padding: 1rem;
      background: rgba(94, 114, 228, 0.1);
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
      #nilai-table {
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
      
      .subject-button {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
      }
    }
    
    @media (max-width: 500px) {
      .nilai-card {
        padding: 1.5rem 1rem;
      }
      
      #nilai-table th, #nilai-table td {
        padding: 0.7rem 0.5rem;
        font-size: 0.85rem;
      }
      
      .grade-a, .grade-b, .grade-c, .grade-d {
        font-size: 0.9em;
        padding: 0.3rem 0.6rem;
      }
    }
  </style>
</head>
<body>
  <?php include 'ortu_navbar.php'; ?>
  
  <div class="main-content">
    <h1 class="page-title">Detail Nilai Siswa</h1>
    
    <div class="nilai-card">
      <div class="card-header">
        <h2 class="card-title"><i class="fas fa-chart-bar"></i>Nilai Mata Pelajaran</h2>
      </div>
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
          <thead></thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
  <script>
    function toggleSidebar(){ const s=document.getElementById('sidebar'); s.classList.toggle('open'); updateSidebarArrow(); }
    function updateSidebarArrow(){ const s=document.getElementById('sidebar'); const a=document.getElementById('sidebarArrow'); a.innerHTML = s.classList.contains('open') ? '<path d="M16 5l-8 7 8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' : '<path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'; }
    function toggleDropdown(){ document.getElementById('dropdown').classList.toggle('open'); }
    document.addEventListener('click', function(e){ const d=document.getElementById('dropdown'); const p=document.querySelector('.profile-button'); if(p && d && !p.contains(e.target) && !d.contains(e.target)){ d.classList.remove('open'); }});
    function getGrade(avg){ if(avg < 60) return '<span class="grade-d">D</span>'; if(avg >= 61 && avg <= 70) return '<span class="grade-c">C</span>'; if(avg >= 71 && avg <= 85) return '<span class="grade-b">B</span>'; if(avg > 86) return '<span class="grade-a">A</span>'; return '-'; }
    // Pastikan tombol IPAS aktif secara default dan tampilkan nilai IPAS
    window.onload = function() {
      document.querySelector('.subject-button[data-subject="ipas"]').classList.add('active');
      displayScores('ipas');
    }
    async function displayScores(subject){
      const table=document.getElementById('nilai-table');
      const thead=table.querySelector('thead');
      const tbody=table.querySelector('tbody');
      tbody.innerHTML='<tr><td colspan="100">Memuat data...</td></tr>';
      try{
        const siswaRes=await fetch('../api/daftar_siswa_api.php');
        const siswaJson=await siswaRes.json();
        let siswaList=siswaJson.status==='success'? siswaJson.data : [];
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
    // Panggil displayScores('ipas') segera setelah halaman dimuat
    document.addEventListener('DOMContentLoaded', function(){ 
      updateSidebarArrow(); 
      // Pastikan tombol IPAS aktif
      document.querySelector('.subject-button[data-subject="ipas"]').classList.add('active');
      // Tampilkan nilai IPAS secara default
      displayScores('ipas'); 
    });
  </script>
</body>
</html>
