<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username']) || $_SESSION['level'] != 'siswa') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$username = $_SESSION['username'];
$prestasi_file = __DIR__ . '/../uploads/prestasi/prestasi_' . $username . '.json';

if (!file_exists($prestasi_file)) {
    echo json_encode(['status' => 'success', 'data' => []]);
    exit();
}

$data = json_decode(file_get_contents($prestasi_file), true);
if (!$data) $data = [];

echo json_encode(['status' => 'success', 'data' => $data]);
