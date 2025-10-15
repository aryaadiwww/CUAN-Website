<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'ortu') {
  header("Location: ../index.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Galeri Karya Anak - Orang Tua</title>
  <link rel="stylesheet" href="../style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #20bf6b 0%, #38ada9 100%);
      --accent: #20bf6b;
      --accent2: #38ada9;
      --card-bg: #fff;
      --card-shadow: 0 8px 32px 0 rgba(32,191,107,0.13);
      --card-hover: 0 12px 40px 0 rgba(32,191,107,0.18);
      --border-radius: 22px;
    }
    body {
      background: var(--primary-gradient);
      background-attachment: fixed;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      color: #222;
    }
    .main-content {
      max-width: 1200px;
      margin: 2.5rem auto 2rem auto;
      padding: 0 1.5rem;
    }
    .galeri-title {
      font-size: 2.1rem;
      font-weight: 800;
      color: #fff;
      text-align: center;
      margin-bottom: 0.7rem;
      letter-spacing: 1.2px;
      text-shadow: 0 2px 12px #20bf6b55;
    }
    .galeri-desc {
      font-size: 1.13rem;
      color: #e6fff3;
      text-align: center;
      margin-bottom: 2.2rem;
      font-style: italic;
      text-shadow: 0 1.5px 8px #38ada955;
    }
    .galeri-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 2rem 1.5rem;
      margin-bottom: 2.5rem;
      align-items: stretch;
    }
    .galeri-card {
      background: var(--card-bg);
      border-radius: var(--border-radius);
      box-shadow: var(--card-shadow);
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 1.2rem 1rem 1.1rem 1rem;
      transition: box-shadow 0.25s, transform 0.18s;
      position: relative;
      overflow: hidden;
      min-height: 320px;
      cursor: pointer;
      border: 2.5px solid #20bf6b22;
    }
    .galeri-card:hover {
      box-shadow: var(--card-hover);
      transform: scale(1.04) translateY(-4px);
      border-color: #20bf6b88;
    }
    .galeri-img {
      width: 100%;
      max-width: 220px;
      height: 180px;
      object-fit: cover;
      border-radius: 16px;
      box-shadow: 0 4px 18px #20bf6b22;
      margin-bottom: 1.1rem;
      background: #e6fff3;
      transition: filter 0.2s;
    }
    .galeri-card:hover .galeri-img {
      filter: brightness(1.08) saturate(1.13);
    }
    .galeri-label {
      font-size: 1.08rem;
      font-weight: 700;
      color: #20bf6b;
      margin-bottom: 0.3rem;
      text-align: center;
      letter-spacing: 0.7px;
    }
    .galeri-caption {
      font-size: 0.98rem;
      color: #38ada9;
      text-align: center;
      opacity: 0.93;
      margin-bottom: 0.2rem;
    }
    .galeri-badge {
      position: absolute;
      top: 1.1rem;
      right: 1.1rem;
      background: linear-gradient(135deg, #20bf6b 0%, #38ada9 100%);
      color: #fff;
      font-size: 0.93rem;
      font-weight: 700;
      padding: 0.32rem 0.9rem;
      border-radius: 12px;
      box-shadow: 0 2px 8px #20bf6b33;
      letter-spacing: 0.5px;
      opacity: 0.93;
    }
    @media (max-width: 900px) {
      .main-content { padding: 0 0.3rem; }
      .galeri-grid { gap: 1.2rem 0.7rem; }
      .galeri-card { min-height: 220px; }
    }
    @media (max-width: 600px) {
      .galeri-title { font-size: 1.3rem; }
      .galeri-desc { font-size: 0.98rem; }
      .galeri-card { padding: 0.7rem 0.3rem; }
      .galeri-img { height: 120px; }
    }
  </style>
</head>
<body>
  <?php include 'ortu_navbar.php'; ?>
  

  <div class="main-content">
    <div class="galeri-title">Galeri Karya Anak</div>
    <div class="galeri-desc">Kumpulan karya, tugas kreatif, dan dokumentasi prestasi anak. Klik gambar untuk memperbesar.</div>
    <div class="galeri-grid">
      <?php
        // Daftar gambar galeri (bisa diatur sesuai kebutuhan, di sini ambil dari folder img/)
        $galeri_gambar = [
          ["file"=>"beo.jpg", "label"=>"Karya Beo", "caption"=>"Lomba Mewarnai"],
          ["file"=>"kaligrafi.jpg", "label"=>"Kaligrafi", "caption"=>"Tugas PAI"],
          ["file"=>"rumah.jpg", "label"=>"Rumah Impian", "caption"=>"Tugas Seni Budaya"],
          ["file"=>"star.svg", "label"=>"Bintang", "caption"=>"Karya Digital"],
          ["file"=>"cactus.svg", "label"=>"Kaktus", "caption"=>"Karya Digital"],
          ["file"=>"book.png", "label"=>"Buku", "caption"=>"Ilustrasi Buku"],
          ["file"=>"robot.png", "label"=>"Robot", "caption"=>"Karya Sains"],
          ["file"=>"kelinci.png", "label"=>"Kelinci", "caption"=>"Tugas Menggambar"],
          ["file"=>"kucing.png", "label"=>"Kucing", "caption"=>"Tugas Menggambar"],
          ["file"=>"burung.png", "label"=>"Burung", "caption"=>"Karya Digital"],
        ];
        $badge_list = ["Juara", "Tugas", "Kreatif", "Prestasi", "Digital", "Favorit", "Baru", "Pilihan", "Sains", "Seni"];
        foreach ($galeri_gambar as $i => $g) {
          $img = '../img/' . $g['file'];
          $label = $g['label'];
          $caption = $g['caption'];
          $badge = $badge_list[$i % count($badge_list)];
          echo '<div class="galeri-card" onclick="openGaleriModal(\'' . $img . '\', \'$label\', \'$caption\')">';
          echo '<span class="galeri-badge">' . $badge . '</span>';
          echo '<img src="' . $img . '" alt="' . htmlspecialchars($label) . '" class="galeri-img">';
          echo '<div class="galeri-label">' . htmlspecialchars($label) . '</div>';
          echo '<div class="galeri-caption">' . htmlspecialchars($caption) . '</div>';
          echo '</div>';
        }
      ?>
    </div>
    <!-- Modal Preview -->
    <div id="galeriModal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(32,191,107,0.85);align-items:center;justify-content:center;">
      <div style="background:#fff;border-radius:22px;box-shadow:0 8px 32px #20bf6b33;padding:1.5rem 1.2rem 1.2rem 1.2rem;max-width:95vw;max-height:90vh;display:flex;flex-direction:column;align-items:center;position:relative;">
        <span id="galeriModalClose" style="position:absolute;top:0.7rem;right:1.1rem;font-size:2.1rem;color:#20bf6b;cursor:pointer;font-weight:800;">&times;</span>
        <img id="galeriModalImg" src="" alt="Preview" style="max-width:80vw;max-height:60vh;border-radius:16px;box-shadow:0 4px 18px #20bf6b22;margin-bottom:1.1rem;"/>
        <div id="galeriModalLabel" style="font-size:1.18rem;font-weight:700;color:#20bf6b;margin-bottom:0.3rem;text-align:center;"></div>
        <div id="galeriModalCaption" style="font-size:1.01rem;color:#38ada9;text-align:center;"></div>
      </div>
    </div>
  </div>

<script>
  // Modal galeri preview
  function openGaleriModal(img, label, caption) {
    document.getElementById('galeriModalImg').src = img;
    document.getElementById('galeriModalLabel').textContent = label;
    document.getElementById('galeriModalCaption').textContent = caption;
    document.getElementById('galeriModal').style.display = 'flex';
  }
  document.getElementById('galeriModalClose').onclick = function() {
    document.getElementById('galeriModal').style.display = 'none';
  };
  document.getElementById('galeriModal').onclick = function(e) {
    if (e.target === this) this.style.display = 'none';
  };
</script>
</body>
</html>