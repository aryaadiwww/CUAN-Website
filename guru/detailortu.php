<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'guru') {
  header("Location: ../index.html");
  exit();
}
// Ambil username ortu dari query string
$username = isset($_GET['username']) ? trim($_GET['username']) : '';
$profile = [
  "nama_lengkap" => '',
  "tempat_lahir" => '',
  "tanggal_lahir" => '',
  "jenis_kelamin" => '',
  "agama" => '',
  "hobi" => '',
  "cita_cita" => ''
];
if ($username !== '') {
  $profile_file = __DIR__ . '/../ortu/profile_data/' . $username . '.json';
  if (file_exists($profile_file)) {
    $data = json_decode(file_get_contents($profile_file), true);
    if (is_array($data)) {
      $profile = array_merge($profile, $data);
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detail Orang Tua Siswa - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous"/>
  <style>
    .profile-card { background: #fff; color: #222; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); width: 100%; max-width: 450px; padding: 1.1rem 1.2rem 2rem 1.2rem; margin: 2rem auto; border: 1px solid #eee; }
    .profile-header { display: flex; flex-direction: column; align-items: center; margin-bottom: 1.1rem; }
    .profile-avatar-large { width: 70px; height: 70px; border-radius: 50%; background: #eee; border: 2px solid #bbb; object-fit: cover; margin-bottom: 0.7rem; }
    .profile-title { font-size: 1.2rem; font-weight: 700; color: #222; text-align: center; margin-bottom: 0.2rem; }
    .profile-body { display: flex; flex-direction: column; gap: 0.6rem; }
    .profile-field { display: flex; flex-direction: column; background: #f7f7f7; border-radius: 10px; padding: 0.5rem 0.7rem; border: 1px solid #eee; margin-bottom: 0.1rem; }
    .field-label { font-size: 0.85rem; font-weight: 600; color: #222; margin-bottom: 0.2rem; display: flex; align-items: center; gap: 0.4rem; }
    .field-value { font-size: 1rem; color: #222; font-weight: 500; min-height: 1.2rem; }
  </style>
</head>
<body>
  <div class="profile-card">
    <div class="profile-header">
      <img src="../img/profile.png" alt="Profile" class="profile-avatar-large" />
      <h1 class="profile-title">Biodata Orang Tua Siswa</h1>
    </div>
    <div class="profile-body">
      <div class="profile-field"><div class="field-label">Nama Lengkap</div><div class="field-value"><?php echo htmlspecialchars($profile['nama_lengkap']); ?></div></div>
      <div class="profile-field"><div class="field-label">Tempat Lahir</div><div class="field-value"><?php echo htmlspecialchars($profile['tempat_lahir']); ?></div></div>
      <div class="profile-field"><div class="field-label">Tanggal Lahir</div><div class="field-value"><?php echo htmlspecialchars($profile['tanggal_lahir']); ?></div></div>
      <div class="profile-field"><div class="field-label">Jenis Kelamin</div><div class="field-value"><?php echo htmlspecialchars($profile['jenis_kelamin']); ?></div></div>
      <div class="profile-field"><div class="field-label">Agama</div><div class="field-value"><?php echo htmlspecialchars($profile['agama']); ?></div></div>
      <div class="profile-field"><div class="field-label">Hobi</div><div class="field-value"><?php echo htmlspecialchars($profile['hobi']); ?></div></div>
      <div class="profile-field"><div class="field-label">Cita-cita</div><div class="field-value"><?php echo htmlspecialchars($profile['cita_cita']); ?></div></div>
    </div>
  </div>
</body>
</html>
