
// Ambil data kehadiran dan hitung presentase secara real-time untuk seluruh siswa
function updateKehadiranPersen() {
  fetch('../api/kehadiran_api.php')
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        // Total pertemuan yang sudah diinput (jumlah data unik hari/bulan/siswa)
        var totalPertemuan = data.data.length;
        // Total hadir (status == 1)
        var hadir = data.data.filter(row => row.status == 1).length;
        var persen = totalPertemuan > 0 ? Math.round((hadir / totalPertemuan) * 100) : 0;
        document.getElementById('kehadiranPersen').textContent = persen + '%';
      } else {
        document.getElementById('kehadiranPersen').textContent = '0%';
      }
    })
    .catch(() => {
      document.getElementById('kehadiranPersen').textContent = '0%';
    });
}

document.addEventListener('DOMContentLoaded', function() {
  updateKehadiranPersen();
});
