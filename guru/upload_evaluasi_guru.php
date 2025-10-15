<?php
// Proses upload evaluasi baru oleh guru
$evaluasi = json_decode(@file_get_contents('http://localhost/CUAN/api/evaluasi_api.php'), true);
if (!is_array($evaluasi)) $evaluasi = [];

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
        $lampiran_file = 'evaluasi_guru_' . time() . '_' . mt_rand(1000,9999) . ($safeExt ? ('.' . $safeExt) : '');
        @move_uploaded_file($_FILES['guru_file']['tmp_name'], $uploadDir . '/' . $lampiran_file);
    }

    // Tentukan ID numerik baru
    $ids = [];
    if (!empty($evaluasi)) { foreach ($evaluasi as $row) { $ids[] = (int)$row['id']; } }
    $id = $ids ? (max($ids) + 1) : 1;

    $evaluasi[] = [
        'id' => (string)$id,
        'judul' => $judul,
        'deskripsi' => $deskripsi,
        'deadline' => $deadline,
        'lampiran_file' => $lampiran_file,
        'lampiran_link' => $lampiran_link,
    ];

    // Simpan data evaluasi dalam format JSON murni (bukan PHP)
    $evalPath = realpath(__DIR__ . '/../api/evaluasi_api.php');
    if ($evalPath === false) { $evalPath = __DIR__ . '/../api/evaluasi_api.php'; }
    file_put_contents($evalPath, json_encode($evaluasi, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}
header('Location: ipas_evaluasi.php');
exit;
