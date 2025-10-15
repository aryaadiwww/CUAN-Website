<?php
include "koneksi.php";

// Set header untuk JSON response
header('Content-Type: application/json');

// Debug: Log semua data yang diterima
error_log("Register test - POST data: " . print_r($_POST, true));
error_log("Register test - GET data: " . print_r($_GET, true));

// Tangkap data dari form
$nama = isset($_POST['nama']) ? $_POST['nama'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
$level = isset($_POST['level']) ? $_POST['level'] : '';

// Validasi data
if (empty($nama) || empty($email) || empty($username) || empty($password) || empty($confirm_password) || empty($level)) {
    echo json_encode([
        'success' => false, 
        'message' => 'Semua field harus diisi!',
        'debug' => [
            'nama' => $nama,
            'email' => $email,
            'username' => $username,
            'password' => !empty($password) ? 'filled' : 'empty',
            'confirm_password' => !empty($confirm_password) ? 'filled' : 'empty',
            'level' => $level
        ]
    ]);
    exit;
}

// Validasi password match
if ($password !== $confirm_password) {
    echo json_encode([
        'success' => false, 
        'message' => 'Password dan Konfirmasi Password tidak cocok!'
    ]);
    exit;
}

// Validasi panjang password
if (strlen($password) < 6) {
    echo json_encode([
        'success' => false, 
        'message' => 'Password harus minimal 6 karakter!'
    ]);
    exit;
}

// Enkripsi password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Simpan ke database
$query = "INSERT INTO users (nama, email, username, password, level) VALUES (?, ?, ?, ?, ?)";

// Gunakan prepared statement untuk keamanan
$stmt = mysqli_prepare($koneksi, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sssss", $nama, $email, $username, $hashed, $level);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Akun berhasil didaftarkan!',
            'user_id' => mysqli_insert_id($koneksi)
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Error: ' . mysqli_stmt_error($stmt),
            'debug' => 'Statement execution failed'
        ]);
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . mysqli_error($koneksi),
        'debug' => 'Statement preparation failed'
    ]);
}
?> 