// guru_dashboard_chart.js
// Script for Chart.js line chart on Guru Dashboard (real-time from nilai sources, bulanan)

// Register Chart.js plugins
Chart.register(ChartDataLabels);

document.addEventListener('DOMContentLoaded', function() {
  initDashboardChart();

  // Sidebar & Dropdown logic
  window.toggleSidebar = function() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('open');
    window.updateSidebarArrow();
  }
  window.updateSidebarArrow = function() {
    const sidebar = document.getElementById('sidebar');
    const arrow = document.getElementById('sidebarArrow');
    if (sidebar && arrow) {
      if (sidebar.classList.contains('open')) {
        // Panah kiri (masuk)
        arrow.innerHTML = '<path d="M16 5l-8 7 8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      } else {
        // Panah kanan (keluar)
        arrow.innerHTML = '<path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      }
    }
  }
  window.toggleDropdown = function() {
    const dropdown = document.getElementById("dropdown");
    if (dropdown) dropdown.classList.toggle("open");
  }
  document.addEventListener('click', function(e) {
    const dropdown = document.getElementById("dropdown");
    const profileBtn = document.querySelector('.profile-button');
    if (dropdown && profileBtn && !profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.remove("open");
    }
  });
  window.updateSidebarArrow();
});

function monthName(id) {
  const names = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  return names[id] || '';
}

function buildLastMonths(count = 7) {
  const now = new Date();
  const buckets = [];
  for (let i = count - 1; i >= 0; i--) {
    const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
    buckets.push({ year: d.getFullYear(), month: d.getMonth() + 1, label: monthName(d.getMonth()) + ' ' + String(d.getFullYear()).slice(-2) });
  }
  return buckets;
}

function parseUploadTime(rec) {
  // Coba gunakan upload_time atau waktu_upload, fallback parse dari nama file bila ada
  let t = null;
  if (rec.upload_time) t = rec.upload_time;
  else if (rec.waktu_upload) t = rec.waktu_upload;
  else if (rec.file) {
    const m = String(rec.file).match(/_(\d+)\.[a-zA-Z0-9]+$/);
    if (m) t = new Date(parseInt(m[1], 10) * 1000).toISOString().slice(0, 19).replace('T', ' ');
  }
  if (!t) return null;
  const dt = new Date(t.replace(' ', 'T'));
  if (isNaN(dt.getTime())) return null;
  return { year: dt.getFullYear(), month: dt.getMonth() + 1 };
}

async function initDashboardChart() {
  const subject = 'ipas'; // ambil data IPAS
  const months = buildLastMonths(7); // 7 bulan terakhir
  const labels = months.map(m => m.label);

  // default fallback
  let jumlahSiswaNow = 0;
  let kelasRata2Now = 0;

  let avgSeries = new Array(months.length).fill(0);
  let countSeries = new Array(months.length).fill(0);

  try {
    // Ambil data real-time
    const ts = Date.now();
    const fetchNoCache = (url) => fetch(url + (url.includes('?') ? '&' : '?') + '_ts=' + ts, { cache: 'no-store' });
    const [siswaRes, tugasMasterRes, evalMasterRes, tugasSubRes, evalSubRes] = await Promise.all([
      fetchNoCache('../api/daftar_siswa_api.php'),
      fetchNoCache('../api/tugas_api.php'),
      fetchNoCache('../api/evaluasi_api.php'),
      fetchNoCache('../tugas_submissions.json'),
      fetchNoCache('../evaluasi_submissions.json')
    ]);

    const siswaJson = await siswaRes.json();
    const siswaList = siswaJson && siswaJson.status === 'success' && Array.isArray(siswaJson.data) ? siswaJson.data : [];
    const siswaUsernames = siswaList.map(s => s.username);

    jumlahSiswaNow = siswaList.length; // total siswa, digunakan pada kartu jika mau

    const tugasMaster = await tugasMasterRes.json();
    const evalMaster = await evalMasterRes.json();
    const tugasKeys = (Array.isArray(tugasMaster) ? tugasMaster : []).filter(t => !t.mapel || t.mapel === subject).map(t => String(t.id));
    const evalKeys = (Array.isArray(evalMaster) ? evalMaster : []).filter(e => !e.mapel || e.mapel === subject).map(e => String(e.id));

    const tugasSubs = tugasSubRes.ok ? await tugasSubRes.json() : [];
    const evalSubs = evalSubRes.ok ? await evalSubRes.json() : [];

    // Kelompokkan nilai per bulan
    // Struktur: per index bulan -> map siswa -> array nilai
    const monthlyStudentScores = months.map(() => ({}));

    function pushScoreToMonth(rec, idKey, validIds) {
      if (!rec || !rec.siswa_id || !rec[idKey]) return;
      const sid = rec.siswa_id;
      const nid = String(rec[idKey]);
      if (!siswaUsernames.includes(sid)) return; // hanya siswa valid di DB
      if (validIds.length > 0 && !validIds.includes(nid)) return; // pastikan hanya item IPAS
      const when = parseUploadTime(rec);
      if (!when) return;
      // cari index bulan yang cocok
      const idx = months.findIndex(m => m.year === when.year && m.month === when.month);
      if (idx === -1) return;
      const score = rec.nilai;
      if (score === undefined || score === null || score === '') return;
      if (!monthlyStudentScores[idx][sid]) monthlyStudentScores[idx][sid] = [];
      monthlyStudentScores[idx][sid].push(Number(score));
    }

    // Tugas IPAS
    if (Array.isArray(tugasSubs)) {
      tugasSubs.forEach(s => pushScoreToMonth(s, 'tugas_id', tugasKeys));
    }
    // Evaluasi IPAS
    if (Array.isArray(evalSubs)) {
      evalSubs.forEach(s => pushScoreToMonth(s, 'evaluasi_id', evalKeys));
    }

    // Hitung rata-rata kelas per bulan dan jumlah siswa yang punya nilai bulan tsb
    for (let i = 0; i < months.length; i++) {
      const map = monthlyStudentScores[i];
      const students = Object.keys(map);
      // jumlah siswa aktif bulan itu (tidak dipakai untuk grafik, tetapi tetap dihitung)
      const activeCount = students.length;
      if (students.length === 0) { avgSeries[i] = 0; continue; }
      let sumAvg = 0;
      students.forEach(u => {
        const arr = map[u];
        if (arr && arr.length > 0) {
          const avg = Math.round(arr.reduce((a, b) => a + b, 0) / arr.length);
          sumAvg += avg;
        }
      });
      avgSeries[i] = Math.round(sumAvg / students.length);
    }
    // Tampilkan jumlah siswa total setiap bulan agar konsisten dan terlihat jelas
    countSeries = labels.map(() => jumlahSiswaNow);

    // Nilai untuk kartu = bulan terakhir (current window)
    kelasRata2Now = avgSeries[avgSeries.length - 1] || 0;
  } catch (e) {
    // fallback: biarkan nol
  }

  const canvas = document.getElementById('avgScoreChart');
  if (canvas) { canvas.style.height = '300px'; canvas.style.width = '100%'; }
  const ctx = canvas.getContext('2d');
  const data = {
    labels: labels,
    datasets: [
      {
        label: 'Rata-rata Nilai Siswa (IPAS)',
        backgroundColor: 'rgb(54, 162, 235)',
        borderColor: 'rgb(54, 162, 235)',
        data: avgSeries,
        tension: 0.35
      },
      {
        label: 'Jumlah Siswa Memiliki Nilai',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        data: countSeries,
        tension: 0.35,
        yAxisID: 'y2'
      }
    ]
  };

  const config = {
    type: 'line',
    data: data,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: { padding: { left: 20, right: 20, top: 10, bottom: 10 } },
      plugins: {
        title: {
          display: true,
          text: 'Perkembangan Nilai Siswa (Real-time dari Halaman Nilai Guru)',
          color: '#FFFFFF',
          font: { size: 18, weight: 'bold' },
          padding: 20
        },
        legend: {
          position: 'top',
          align: 'start',
          labels: { color: '#FFFFFF', font: { size: 14, weight: 'bold' }, padding: 20, boxWidth: 40, boxHeight: 3 }
        },
        tooltip: {
          mode: 'index',
          intersect: false,
          backgroundColor: 'rgba(255, 255, 255, 0.9)',
          titleColor: '#335165',
          bodyColor: '#335165',
          borderColor: '#335165',
          borderWidth: 1,
          padding: 10,
          titleFont: { size: 14, weight: 'bold' },
          bodyFont: { size: 13 }
        },
        datalabels: {
          color: '#FFFFFF',
          backgroundColor: function(context) { return context.dataset.backgroundColor; },
          borderRadius: 4,
          font: { size: 12, weight: 'bold' },
          padding: 6,
          display: function(context) { return context.dataIndex === 0 || context.dataIndex === (labels.length - 1); },
          formatter: function(value) { return '' + value; },
          anchor: 'end',
          align: 'top',
          offset: 10
        }
      },
      hover: { mode: 'index', intersect: false },
      scales: {
        x: {
          display: true,
          ticks: { color: '#FFFFFF', font: { size: 12, weight: 'bold' }, padding: 8 },
          grid: { color: 'rgba(255, 255, 255, 0.1)', borderDash: [5, 5] }
        },
        y: {
          display: true,
          title: { display: true, text: 'Nilai', color: '#FFFFFF', font: { size: 14, weight: 'bold' }, padding: { bottom: 10, top: 0 } },
          ticks: { color: '#FFFFFF', font: { size: 12, weight: 'bold' }, padding: 8 },
          grid: { color: 'rgba(255, 255, 255, 0.1)', borderDash: [5, 5] }
        },
        y2: {
          display: true,
          position: 'right',
          title: { display: true, text: 'Jumlah Siswa', color: '#FFFFFF', font: { size: 14, weight: 'bold' } },
          ticks: { color: '#FFFFFF', font: { size: 12, weight: 'bold' } },
          grid: { drawOnChartArea: false }
        }
      },
      animation: { duration: 900, easing: 'easeOutQuart' }
    }
  };

  new Chart(ctx, config);

  // Update kartu Nilai dan Kehadiran di dashboard dengan data real-time
  try {
    // Nilai
    const nilaiCard = Array.from(document.querySelectorAll('.info-card')).find(card => {
      const title = card.querySelector('.info-card-title');
      return title && title.textContent.trim().toLowerCase() === 'nilai';
    });
    if (nilaiCard) {
      const val = nilaiCard.querySelector('.info-card-value');
      if (val) val.textContent = String(kelasRata2Now);
    }

    // Kehadiran (ambil bulan berjalan, jika kosong fallback ke bulan terakhir yang memiliki data)
    const ts2 = Date.now();
    const fetchNoCache2 = (url) => fetch(url + (url.includes('?') ? '&' : '?') + '_ts=' + ts2, { cache: 'no-store' });

    async function getAttendancePercentLatest() {
      const now = new Date();
      for (let offset = 0; offset < 12; offset++) {
        let m = now.getMonth() + 1 - offset; // 1..12 rolling back
        let y = now.getFullYear();
        while (m <= 0) { m += 12; y -= 1; }
        const res = await fetchNoCache2(`../api/kehadiran_api.php?bulan=${m}`);
        const data = await res.json();
        if (Array.isArray(data) && data.length > 0) {
          let totalEntri = 0, totalHadir = 0;
          data.forEach(r => {
            if (r && r.status !== undefined && r.status !== null) {
              totalEntri++;
              if (Number(r.status) === 1) totalHadir++;
            }
          });
          if (totalEntri > 0) {
            return Math.round((totalHadir / totalEntri) * 100);
          }
        }
      }
      return 0;
    }

    const persenHadir = await getAttendancePercentLatest();

    const hadirCard = Array.from(document.querySelectorAll('.info-card')).find(card => {
      const title = card.querySelector('.info-card-title');
      return title && title.textContent.trim().toLowerCase() === 'kehadiran';
    });
    if (hadirCard) {
      const val = hadirCard.querySelector('.info-card-value');
      if (val) val.textContent = (persenHadir || 0) + '%';
    }
  } catch (_) {}
}
