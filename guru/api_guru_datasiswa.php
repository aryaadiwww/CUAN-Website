<?php
include "../koneksi.php";
header('Content-Type: application/json');

$siswa = [];
$siswa = [];
$sql = "SELECT username, nama FROM users WHERE level = 'siswa' ORDER BY nama ASC";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $siswa[] = $row;
    }
}
echo json_encode($siswa);
