<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'siswa') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$username = $_SESSION['username'];
$profile_dir = __DIR__ . "/profile_data";
$profile_file = "{$profile_dir}/{$username}.json";

// Pastikan direktori profile_data ada
if (!file_exists($profile_dir)) {
    mkdir($profile_dir, 0777, true);
}

// Fungsi untuk memuat data profil
function loadProfile($file) {
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
    } else {
        // Default profile setup saat pertama kali
        return [
            "nama_lengkap" => "",
            "tempat_lahir" => "",
            "tanggal_lahir" => "",
            "jenis_kelamin" => "",
            "agama" => "",
            "hobi" => "",
            "cita_cita" => ""
        ];
    }
}

// Fungsi untuk menyimpan data profil
function saveProfile($file, $data) {
    return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// Handle GET request - mengembalikan data profil
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $profile = loadProfile($profile_file);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'data' => $profile]);
    exit();
}

// Handle POST request - menyimpan data profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari request body (JSON)
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    
    if ($data === null) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
        exit();
    }
    
    // Validasi data yang diterima
    $valid_fields = [
        'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 
        'jenis_kelamin', 'agama', 'hobi', 'cita_cita'
    ];
    
    $profile = loadProfile($profile_file);
    
    // Update hanya field yang valid
    foreach ($valid_fields as $field) {
        if (isset($data[$field])) {
            $profile[$field] = $data[$field];
        }
    }
    
    // Simpan data profil
    if (saveProfile($profile_file, $profile)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Profile saved successfully']);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Failed to save profile']);
    }
    exit();
}

// Jika metode request tidak didukung
header('Content-Type: application/json');
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
exit();