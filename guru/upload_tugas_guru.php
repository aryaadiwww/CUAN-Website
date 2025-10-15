<?php
// Proses upload tugas baru oleh guru
// Ambil daftar tugas melalui HTTP agar API mengembalikan JSON
$tugas = json_decode(@file_get_contents('http://localhost/CUAN/api/tugas_api.php'), true);
if (!is_array($tugas)) $tugas = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $deadline = trim($_POST['deadline'] ?? '');

    // Siapkan lampiran file/link opsional
    $lampiran_file = '';
    $lampiran_link = trim($_POST['guru_link'] ?? '');

    if (isset($_FILES['guru_file']) && is_array($_FILES['guru_file']) && $_FILES['guru_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = realpath(__DIR__ . '/../uploads');
        if ($uploadDir === false) { $uploadDir = __DIR__ . '/../uploads'; }
        $uploadDir .= '/bahan_ajar';
        if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0777, true); }
        $ext = pathinfo($_FILES['guru_file']['name'], PATHINFO_EXTENSION);
        $safeExt = preg_replace('/[^a-zA-Z0-9]/', '', $ext);
        $lampiran_file = 'tugas_guru_' . time() . '_' . mt_rand(1000,9999) . ($safeExt ? ('.' . $safeExt) : '');
        @move_uploaded_file($_FILES['guru_file']['tmp_name'], $uploadDir . '/' . $lampiran_file);
    }

    // Tentukan ID baru secara numerik
    $ids = [];
    if (!empty($tugas)) {
        foreach ($tugas as $row) { $ids[] = (int)$row['id']; }
    }
    $id = $ids ? (max($ids) + 1) : 1;

    $tugas[] = [
        'id' => (string)$id,
        'judul' => $judul,
        'deskripsi' => $deskripsi,
        'deadline' => $deadline,
        'lampiran_file' => $lampiran_file,
        'lampiran_link' => $lampiran_link,
    ];

    // Simpan data tugas dalam format JSON murni (bukan PHP)
    $tugasPath = realpath(__DIR__ . '/../api/tugas_api.php');
    if ($tugasPath === false) { $tugasPath = __DIR__ . '/../api/tugas_api.php'; }
    file_put_contents($tugasPath, json_encode($tugas, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}
header('Location: ipas_tugas.php');
exit;
