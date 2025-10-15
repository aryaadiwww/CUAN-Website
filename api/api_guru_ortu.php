<?php
include "../koneksi.php";
header('Content-Type: application/json');

$ortu = [];
$sql = "SELECT username, nama FROM users WHERE level = 'ortu' ORDER BY nama ASC";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $ortu[] = $row;
    }
    echo json_encode($ortu);
} else {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($koneksi)]);
}