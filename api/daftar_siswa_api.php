<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../koneksi.php'; // pastikan file koneksi ada dan benar

$siswa = [];
$sql = "SELECT id, username, nama, level FROM users WHERE level='siswa' ORDER BY username ASC";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $siswa[] = [
            'id' => (int)$row['id'],
            'username' => $row['username'],
            'nama' => $row['nama'],
            'level' => $row['level']
        ];
    }
    echo json_encode(['status' => 'success', 'data' => $siswa]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengambil data siswa']);
}
