<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'siswa') {
  header("Location: ../index.html");
  exit();
}

$username = $_SESSION['username'];
$profile_file = __DIR__ . "/profile_data/{$username}.json";
$upload_dir = __DIR__ . '/../uploads/portofolio/';

if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['foto']['tmp_name'];
    $file_name = basename($_FILES['foto']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($file_ext, $allowed_ext)) {
        header('Location: siswa_dashboard.php?error=type');
        exit();
    }

    // Nama file unik: username_timestamp.ext
    $new_name = $username . '_' . time() . '.' . $file_ext;
    $target_file = $upload_dir . $new_name;

    if (move_uploaded_file($file_tmp, $target_file)) {
        // Update file JSON profil
        $profile_data = [];
        if (file_exists($profile_file)) {
            $profile_data = json_decode(file_get_contents($profile_file), true);
        }
        $profile_data['foto'] = $new_name;
        file_put_contents($profile_file, json_encode($profile_data, JSON_PRETTY_PRINT));
        header('Location: siswa_dashboard.php?success=1');
        exit();
    } else {
        header('Location: siswa_dashboard.php?error=upload');
        exit();
    }
} else {
    header('Location: siswa_dashboard.php?error=nofile');
    exit();
}
