<?php
include "koneksi.php";

// Set header untuk JSON response
header('Content-Type: application/json');

// Debug: Log data yang diterima
error_log("Register attempt - POST data: " . print_r($_POST, true));

// Tangkap data dari form
$nama = $_POST['nama'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$level = $_POST['level']; // siswa, guru, ortu

// Validasi data
if (empty($nama) || empty($email) || empty($username) || empty($password) || empty($confirm_password) || empty($level)) {
    echo json_encode(['success' => false, 'message' => 'Semua field harus diisi!']);
    exit;
}

// Validasi password match
if ($password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Password dan Konfirmasi Password tidak cocok!']);
    exit;
}

// Validasi panjang password
if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password harus minimal 6 karakter!']);
    exit;
}

// Enkripsi password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Simpan ke database
$query = "INSERT INTO users (nama, email, username, password, level) VALUES (?, ?, ?, ?, ?)";

// Gunakan prepared statement untuk keamanan
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "sssss", $nama, $email, $username, $hashed, $level);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Akun berhasil didaftarkan!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($koneksi)]);
}

mysqli_stmt_close($stmt);
?>
