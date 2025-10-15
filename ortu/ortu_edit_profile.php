<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'ortu') {
  header("Location: ../index.html");
  exit();
}

$username = $_SESSION['username'];
$profile_file = __DIR__ . "/profile_data/{$username}.json";

// Pastikan direktori profile_data ada
if (!file_exists(__DIR__ . "/profile_data")) {
  mkdir(__DIR__ . "/profile_data", 0777, true);
}

// Load profile data
$default_profile = [
  "nama_lengkap" => "",
  "nik" => "",
  "tempat_lahir" => "",
  "tanggal_lahir" => "",
  "jenis_kelamin" => "",
  "agama" => "",
  "pekerjaan" => "",
  "alamat" => "",
  "no_telepon" => ""
];
function loadProfile($file) {
  global $default_profile;
  if (file_exists($file)) {
    $data = json_decode(file_get_contents($file), true);
    return array_merge($default_profile, $data);
  } else {
    return $default_profile;
  }
}

$profile = loadProfile($profile_file);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profil Orang Tua - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous"/>
  <style>
    * {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      box-sizing: border-box;
    }
    body {
      background-color: #634338ff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      width: 100vw;
      overflow-x: hidden;
    }
    
    /* Profile Card Styles */
    .profile-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }
    
    .profile-card {
      background: #fff;
      color: #222;
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.07);
      width: 100%;
      max-width: 370px;
      padding: 1.1rem 1.2rem 1.2rem 1.2rem;
      margin: 1.5rem auto 0 auto;
      position: relative;
      overflow: hidden;
      border: 1px solid #eee;
      min-width: 0;
      font-size: 1rem;
      transition: box-shadow 0.2s;
    }
    .profile-card:hover {
      box-shadow: 0 4px 18px rgba(0,0,0,0.10);
    }
    .profile-card::before,
    .profile-card:hover::before,
    .profile-decoration,
    .profile-card:hover .profile-avatar-large {
      display: none !important;
    }
    
    .profile-header {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 1.1rem;
      position: relative;
      z-index: 2;
    }
    .profile-avatar-large {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      background: #eee;
      border: 2px solid #bbb;
      object-fit: cover;
      margin-bottom: 0.7rem;
      box-shadow: none;
    }
    .profile-title {
      font-size: 1.2rem;
      font-weight: 700;
      color: #222;
      text-align: center;
      margin-bottom: 0.2rem;
      text-shadow: none;
      background: none;
      -webkit-background-clip: unset;
      -webkit-text-fill-color: unset;
      background-clip: unset;
    }
    .profile-subtitle {
      font-size: 0.75rem;
      font-style: italic;
      color: #444;
      text-align: center;
      margin-bottom: 0.7rem;
      opacity: 0.8;
    }
    
    .profile-body {
      display: flex;
      flex-direction: column;
      gap: 0.6rem;
      position: relative;
      z-index: 2;
    }
    .profile-field {
      display: flex;
      flex-direction: column;
      background: #f7f7f7;
      border-radius: 10px;
      padding: 0.5rem 0.7rem;
      border: 1px solid #eee;
      margin-bottom: 0.1rem;
      transition: background 0.2s;
    }
    .profile-field:hover {
      background: #ededed;
    }
    .field-label {
      font-size: 0.85rem;
      font-weight: 600;
      color: #222;
      margin-bottom: 0.2rem;
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }
    .field-label svg {
      width: 15px;
      height: 15px;
    }
    .field-value {
      font-size: 1rem;
      color: #222;
      font-weight: 500;
      min-height: 1.2rem;
    }
    .field-input {
      font-size: 1rem;
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 0.3rem 0.5rem;
      color: #222;
      width: 100%;
      display: none;
    }
    .field-input:focus {
      outline: none;
      background: #f3f3f3;
      border: 1px solid #a97c50;
    }
    
    .profile-actions {
      display: flex;
      justify-content: center;
      gap: 0.7rem;
      margin-top: 1.1rem;
    }
    .profile-btn {
      background: linear-gradient(135deg, #a97c50 0%, #7c4a1e 100%);
      color: #ffff;
      border: 1px solid #bbb;
      border-radius: 20px;
      padding: 0.5rem 1.2rem;
      font-size: 0.98rem;
      font-weight: 600;
      cursor: pointer;
      box-shadow: none;
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }
    .profile-btn:hover {
      background: linear-gradient(135deg, #7c4a1e 0%, #a97c50 100%);
      color: #ffffffff;
    }
    .profile-btn svg {
      width: 16px;
      height: 16px;
    }
    .save-btn {
      display: none;
    }
    
    /* Responsive Styles */
    @media screen and (max-width: 900px) {
      header {
        padding: 1rem 1rem 1rem 1.5rem;
      }
      .profile-container {
        padding: 1.1rem 0.7rem;
      }
      .profile-card {
        padding: 1rem 0.7rem;
        border-radius: 13px;
      }
    }
    @media screen and (max-width: 600px) {
      .profile-container {
        padding: 0.7rem 0.3rem;
      }
      .profile-card {
        padding: 0.7rem 0.3rem;
        border-radius: 10px;
      }
      .profile-avatar-large {
        width: 50px;
        height: 50px;
      }
      .profile-title {
        font-size: 1rem;
      }
      .profile-subtitle {
        font-size: 0.8rem;
      }
      .profile-field {
        padding: 0.4rem 0.5rem;
      }
      .field-label {
        font-size: 0.7rem;
      }
      .field-value {
        font-size: 0.9rem;
      }
      .profile-btn {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
      }
    }
    @media screen and (max-width: 400px) {
      .profile-avatar-large {
        width: 36px;
        height: 36px;
      }
      .profile-title {
        font-size: 0.8rem;
      }
      .profile-subtitle {
        font-size: 0.7rem;
      }
      .profile-actions {
        flex-direction: column;
        gap: 0.5rem;
      }
      .profile-btn {
        width: 100%;
        justify-content: center;
      }
    }
  </style>
</head>
<body>
  <?php include 'ortu_navbar.php'; ?>
  
  <div class="main-content">
    <div class="profile-container">
      <div class="profile-card">
        <div class="profile-header">
          <div style="display:flex;justify-content:center;align-items:center;margin-bottom:0.3rem;">
            <img src="../img/profile.png" alt="Profile" style="width:80px;height:80px;border-radius:50%;background:#eee;border:1.5px solid #bbb;object-fit:cover;box-shadow:none;" />
          </div>
          <h1 class="profile-title">Profil Orang Tua</h1>
        </div>
        <div class="profile-body">
          <div class="profile-field">
            <div class="field-label">Nama Lengkap</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['nama_lengkap']); ?></div>
            <input type="text" class="field-input" id="nama_lengkap" value="<?php echo htmlspecialchars($profile['nama_lengkap']); ?>">
          </div>
          <div class="profile-field">
            <div class="field-label">NIK</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['nik']); ?></div>
            <input type="text" class="field-input" id="nik" value="<?php echo htmlspecialchars($profile['nik']); ?>">
          </div>
          <div class="profile-field">
            <div class="field-label">Tempat Lahir</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['tempat_lahir']); ?></div>
            <input type="text" class="field-input" id="tempat_lahir" value="<?php echo htmlspecialchars($profile['tempat_lahir']); ?>">
          </div>
          <div class="profile-field">
            <div class="field-label">Tanggal Lahir</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['tanggal_lahir']); ?></div>
            <input type="date" class="field-input" id="tanggal_lahir" value="<?php echo htmlspecialchars($profile['tanggal_lahir']); ?>">
          </div>
          <div class="profile-field">
            <div class="field-label">Jenis Kelamin</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['jenis_kelamin']); ?></div>
            <select class="field-input" id="jenis_kelamin">
              <option value="" <?php echo $profile['jenis_kelamin'] == '' ? 'selected' : ''; ?>>Pilih Jenis Kelamin</option>
              <option value="Laki-laki" <?php echo $profile['jenis_kelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
              <option value="Perempuan" <?php echo $profile['jenis_kelamin'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
            </select>
          </div>
          <div class="profile-field">
            <div class="field-label">Agama</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['agama']); ?></div>
            <select class="field-input" id="agama">
              <option value="" <?php echo $profile['agama'] == '' ? 'selected' : ''; ?>>Pilih Agama</option>
              <option value="Islam" <?php echo $profile['agama'] == 'Islam' ? 'selected' : ''; ?>>Islam</option>
              <option value="Kristen" <?php echo $profile['agama'] == 'Kristen' ? 'selected' : ''; ?>>Kristen</option>
              <option value="Katolik" <?php echo $profile['agama'] == 'Katolik' ? 'selected' : ''; ?>>Katolik</option>
              <option value="Hindu" <?php echo $profile['agama'] == 'Hindu' ? 'selected' : ''; ?>>Hindu</option>
              <option value="Buddha" <?php echo $profile['agama'] == 'Buddha' ? 'selected' : ''; ?>>Buddha</option>
              <option value="Konghucu" <?php echo $profile['agama'] == 'Konghucu' ? 'selected' : ''; ?>>Konghucu</option>
            </select>
          </div>
          <div class="profile-field">
            <div class="field-label">Pekerjaan</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['pekerjaan']); ?></div>
            <input type="text" class="field-input" id="pekerjaan" value="<?php echo htmlspecialchars($profile['pekerjaan']); ?>">
          </div>
          <div class="profile-field">
            <div class="field-label">Alamat</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['alamat']); ?></div>
            <textarea class="field-input" id="alamat" rows="3"><?php echo htmlspecialchars($profile['alamat']); ?></textarea>
          </div>
          <div class="profile-field">
            <div class="field-label">Nomor Telepon</div>
            <div class="field-value"><?php echo htmlspecialchars($profile['no_telepon']); ?></div>
            <input type="text" class="field-input" id="no_telepon" value="<?php echo htmlspecialchars($profile['no_telepon']); ?>">
          </div>
        </div>
        <div class="profile-actions">
          <button class="profile-btn edit-btn" onclick="editProfile()">Edit Profil</button>
          <button class="profile-btn save-btn" onclick="saveProfile()">Simpan</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function toggleDropdown() {
      const dropdown = document.getElementById("dropdown");
      dropdown.classList.toggle("open");
    }
    document.addEventListener('click', function(e) {
      const dropdown = document.getElementById("dropdown");
      const profileBtn = document.querySelector('.profile-button');
      if (!profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove("open");
      }
    });
    function editProfile() {
      document.querySelectorAll('.field-value').forEach(el => {
        el.style.display = 'none';
      });
      document.querySelectorAll('.field-input').forEach(el => {
        el.style.display = 'block';
      });
      document.querySelector('.edit-btn').style.display = 'none';
      document.querySelector('.save-btn').style.display = 'flex';
      document.querySelector('.profile-card').style.animation = 'pulse 2s infinite';
    }
    function saveProfile() {
      const nama_lengkap = document.getElementById('nama_lengkap').value;
      const nik = document.getElementById('nik').value;
      const tempat_lahir = document.getElementById('tempat_lahir').value;
      const tanggal_lahir = document.getElementById('tanggal_lahir').value;
      const jenis_kelamin = document.getElementById('jenis_kelamin').value;
      const agama = document.getElementById('agama').value;
      const pekerjaan = document.getElementById('pekerjaan').value;
      const alamat = document.getElementById('alamat').value;
      const no_telepon = document.getElementById('no_telepon').value;
      fetch('profile_api.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          nama_lengkap,
          nik,
          tempat_lahir,
          tanggal_lahir,
          jenis_kelamin,
          agama,
          pekerjaan,
          alamat,
          no_telepon
        }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          document.querySelectorAll('.field-value').forEach((el, index) => {
            const inputId = el.nextElementSibling.id;
            el.textContent = document.getElementById(inputId).value;
          });
          document.querySelectorAll('.field-value').forEach(el => {
            el.style.display = 'block';
          });
          document.querySelectorAll('.field-input').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelector('.edit-btn').style.display = 'flex';
          document.querySelector('.save-btn').style.display = 'none';
          showNotification('Profil berhasil disimpan!', 'success');
        } else {
          showNotification('Gagal menyimpan profil. Silakan coba lagi.', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
      });
    }
    function showNotification(message, type) {
      const notification = document.createElement('div');
      notification.className = `notification ${type}`;
      notification.innerHTML = `
        <div class="notification-content">
          <svg class="${type}-icon" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            ${type === 'success' ? '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="M22 4L12 14.01l-3-3"></path>' : '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line>'}
          </svg>
          <span>${message}</span>
        </div>
      `;
      document.body.appendChild(notification);
      setTimeout(() => {
        notification.classList.add('show');
      }, 10);
      setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
          notification.remove();
        }, 300);
      }, 3000);
    }
  </script>
</body>
</html>
