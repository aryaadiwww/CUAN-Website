<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'guru') {
  http_response_code(403);
  echo json_encode(['success' => false, 'message' => 'Unauthorized']);
  exit();
}

$username = $_SESSION['username'];
$profile_file = __DIR__ . "/profile_data/{$username}.json";

// Pastikan direktori profile_data ada
if (!file_exists(__DIR__ . "/profile_data")) {
  mkdir(__DIR__ . "/profile_data", 0777, true);
}

// Ambil data JSON dari body
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Invalid input']);
  exit();
}

// Data yang diizinkan
$fields = [
  'nama_lengkap', 'nip', 'tempat_lahir', 'tanggal_lahir',
  'jenis_kelamin', 'agama', 'mata_pelajaran', 'pendidikan_terakhir'
];

// Ambil data lama jika ada
$profile = [];
if (file_exists($profile_file)) {
  $profile = json_decode(file_get_contents($profile_file), true) ?: [];
}

// Update data
foreach ($fields as $field) {
  $profile[$field] = isset($input[$field]) ? $input[$field] : '';
}

// Simpan ke file
if (file_put_contents($profile_file, json_encode($profile, JSON_PRETTY_PRINT))) {
  echo json_encode(['success' => true]);
} else {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data']);
}
