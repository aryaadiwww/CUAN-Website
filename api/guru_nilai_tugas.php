<?php
// API: Guru memberi nilai untuk tugas
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

$tugas_id = $_POST['tugas_id'] ?? '';
$siswa_id = $_POST['siswa_id'] ?? '';
$nilai = isset($_POST['nilai']) ? intval($_POST['nilai']) : null;

if ($tugas_id === '' || $siswa_id === '' || $nilai === null) {
    http_response_code(400);
    echo 'Param tidak lengkap';
    exit;
}

$path = realpath(__DIR__ . '/../tugas_submissions.json');
if ($path === false || !file_exists($path)) {
    // Jika file belum ada, tidak ada yang dinilai
    file_put_contents(__DIR__ . '/../tugas_submissions.json', json_encode([], JSON_PRETTY_PRINT));
    $path = realpath(__DIR__ . '/../tugas_submissions.json');
}

$submissions = json_decode(file_get_contents($path), true);
if (!is_array($submissions)) $submissions = [];

foreach ($submissions as &$sub) {
    if ((string)$sub['tugas_id'] === (string)$tugas_id && (string)$sub['siswa_id'] === (string)$siswa_id) {
        $sub['nilai'] = $nilai;
        break;
    }
}
unset($sub);

file_put_contents($path, json_encode($submissions, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

header('Location: ../guru/ipas_tugas.php');
exit;
