<?php
include "../koneksi.php";
header('Content-Type: application/json');

$guru = [];
$sql = "SELECT username, nama FROM users WHERE level = 'guru' ORDER BY nama ASC";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $guru[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $guru]);
} else {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($koneksi)]);
}