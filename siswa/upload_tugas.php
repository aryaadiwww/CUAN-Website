<?php
// Proses upload tugas siswa
session_start();
$level = $_SESSION['level'] ?? '';
$username = $_SESSION['username'] ?? '';
// Gunakan username siswa yang login; fallback ke siswa_id lama jika tidak tersedia
$siswa_id = ($level === 'siswa' && $username) ? $username : ($_SESSION['siswa_id'] ?? 'guest');
$tugas_id = $_POST['tugas_id'] ?? '';
$link = trim($_POST['link_upload'] ?? '');
$file_name = '';

if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
    $ext = pathinfo($_FILES['file_upload']['name'], PATHINFO_EXTENSION);
    $file_name = 'tugas_' . $siswa_id . '_' . $tugas_id . '_' . time() . '.' . $ext;
    $uploadDir = '../uploads/bahan_ajar';
    if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
    move_uploaded_file($_FILES['file_upload']['tmp_name'], $uploadDir . '/' . $file_name);
}

$path = '../tugas_submissions.json';
if (!file_exists($path)) {
    file_put_contents($path, json_encode([], JSON_PRETTY_PRINT));
}
$submissions = json_decode(file_get_contents($path), true);
if (!is_array($submissions)) $submissions = [];
// Cegah upload dua kali
foreach ($submissions as $sub) {
    if ($sub['tugas_id'] == $tugas_id && $sub['siswa_id'] == $siswa_id) {
        header('Location: ipas_tugas.php');
        exit;
    }
}
$submissions[] = [
    'tugas_id' => $tugas_id,
    'siswa_id' => $siswa_id,
    'file' => $file_name,
    'link' => $link,
    'nilai' => null,
    'upload_time' => date('Y-m-d H:i:s')
];
file_put_contents('../tugas_submissions.json', json_encode($submissions, JSON_PRETTY_PRINT));
header('Location: ipas_tugas.php');
exit;
