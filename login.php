<?php
session_start();
include "koneksi.php";

// Validasi form
if (!isset($_POST['username'], $_POST['password'], $_POST['level'])) {
    echo "<script>
        alert('Mohon lengkapi semua field!');
        window.location.href='index.html';
    </script>";
    exit();
}

$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = $_POST['password'];
$level    = mysqli_real_escape_string($koneksi, $_POST['level']);

// Cek user berdasarkan username dan level
$query = "SELECT * FROM users WHERE username='$username' AND level='$level'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['level'] = $user['level'];
        // Jika ortu, simpan username siswa ke session
        if ($level === 'ortu') {
            $_SESSION['siswa'] = $user['anak_username'];
            header("Location: ortu/ortu_dashboard.php");
        } elseif ($level === 'siswa') {
            header("Location: siswa/siswa_dashboard.php");
        } elseif ($level === 'guru') {
            header("Location: guru/guru_dashboard.php");
        }
        exit();
    } else {
        echo "<script>
            alert('Password salah!');
            window.location.href='index.html';
        </script>";
    }
} else {
    echo "<script>
        alert('Akun tidak ditemukan!');
        window.location.href='index.html';
    </script>";
}
?>
