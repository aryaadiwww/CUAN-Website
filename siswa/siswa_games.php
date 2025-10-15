<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'siswa') {
  header("Location: ../index.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Jadwal Mata Pelajaran - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="../responsive.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="games.css" />
</head>
<body>
  <div class="sidebar" id="sidebar">
    <div class="logo-section">
      <img src="../img/cuan.png" alt="Logo CUAN">
    </div>
    <ul>
      <li onclick="location.href='siswa_dashboard.php'">
        <span class="menu-icon"><img src="../img/home.png" alt="Beranda" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Beranda</span>
      </li>
      <li onclick="location.href='siswa_matapelajaran.php'">
        <span class="menu-icon"><img src="../img/book.png" alt="Mata Pelajaran" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Mata Pelajaran</span>
      </li>
      <li onclick="location.href='siswa_jadwal.php'">
        <span class="menu-icon"><img src="../img/calendar.png" alt="Jadwal" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Jadwal</span>
      </li>
      <li onclick="location.href='siswa_games.php'">
        <span class="menu-icon"><img src="../img/games.png" alt="Games" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Games</span>
      </li>
    </ul>
  </div>
  <div class="main-content">
    <header>
      <div class="hamburger-logo">
        <button class="hamburger" id="sidebarToggleBtn" onclick="toggleSidebar()">
          <svg id="sidebarArrow" viewBox="0 0 24 24" fill="none"><path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>
      <div class="profile-menu">
        <button class="profile-button" onclick="toggleDropdown()">
          <img src="../img/profile.png" alt="Avatar" class="profile-avatar" />
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left:2px;"><path d="M5 8L10 13L15 8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="dropdown" id="dropdown">
          <a href="siswa_edit_profile.php"><svg width="18" height="18" fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>Edit Profil</a>
          <a href="../logout.php"><svg width="18" height="18" fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>Logout</a>
        </div>
      </div>
    </header>
    <!-- Bagian dalam <body> setelah header -->
<section id="game-dashboard" style="padding: 2rem;">
  <!-- Pet Card dengan desain modern dan playful -->
  <div class="pet-card-container">
    <div class="pet-card-wrapper">
      <div class="pet-image-container">
        <div class="pet-image-frame">
          <img id="pet-preview-img" src="../img/dino.png" alt="Pet" class="pet-image" />
          <div class="pet-level-badge">Lvl-<span id="level-bar">2</span></div>
        </div>
      </div>

      <div class="pet-info-panel">
        <div class="pet-stats-container">
          <div class="pet-stat">
            <div class="pet-stat-icon">❤️</div>
            <div class="pet-stat-value"><b id="pet-happy">76%</b></div>
            <div class="pet-stat-label">Kebahagiaan</div>
          </div>
          <div class="pet-stat">
            <div class="pet-stat-icon">🥩</div>
            <div class="pet-stat-value"><b id="pet-food">4</b>x</div>
            <div class="pet-stat-label">Makanan</div>
          </div>
          <div class="pet-stat">
            <div class="pet-stat-icon">⭐</div>
            <div class="pet-stat-value"><b id="pet-level">2</b></div>
            <div class="pet-stat-label">Level</div>
          </div>
        </div>

        <div class="pet-identity">
          <h3 id="pet-name">Nama Pet</h3>
          <p id="pet-desc">Deskripsi Pet</p>
        </div>

        <!-- Progress Bars dengan animasi -->
        <div class="progress-container">
          <label class="progress-label"><span class="progress-icon">🥩</span> Makanan <span class="progress-desc">(Makanan)</span></label>
          <div class="progress-bar-container">
            <div id="food-bar" class="progress-bar food-progress" style="width: 20%;">20%</div>
          </div>
        </div>
        
        <div class="progress-container">
          <label class="progress-label"><span class="progress-icon">❤️</span> Kebahagiaan <span class="progress-desc">(Kebahagiaan)</span></label>
          <div class="progress-bar-container">
            <div id="happy-bar" class="progress-bar happy-progress" style="width: 76%;">76%</div>
          </div>
        </div>

        <!-- Tombol Aksi dengan hover effects -->
        <div class="pet-actions">
          <button onclick="beriMakanPet()" class="pet-action-btn feed-btn">
            <span class="btn-icon">🥩</span> Beri Makan
          </button>
          <button onclick="editPetModal()" class="pet-action-btn edit-btn">
            <span class="btn-icon">✏️</span> Edit Pet
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Game Collection dengan card design yang menarik -->
  <h2 class="games-heading">Koleksi Games <span class="games-subtitle">Bermain untuk mendapatkan makanan pet!</span></h2>
  
  <div class="games-container">
    <div class="game-card" onclick="showQuiz()">
      <div class="game-card-icon math-icon">🧮</div>
      <div class="game-card-content">
        <h3 class="game-card-title">Quiz Berhitung</h3>
        <p class="game-card-desc">Asah kemampuan matematikamu dengan soal-soal seru!</p>
      </div>
      <div class="game-card-arrow">→</div>
    </div>
    
    <div class="game-card" onclick="openLogicGame()">
      <div class="game-card-icon memory-icon">🧠</div>
      <div class="game-card-content">
        <h3 class="game-card-title">Logika Mengingat</h3>
        <p class="game-card-desc">Uji daya ingatmu dengan permainan kartu memori!</p>
      </div>
      <div class="game-card-arrow">→</div>
    </div>
    
    <div class="game-card" onclick="openPuzzleGame()">
      <div class="game-card-icon puzzle-icon">🧩</div>
      <div class="game-card-content">
        <h3 class="game-card-title">Puzzle</h3>
        <p class="game-card-desc">Susun potongan puzzle dengan tepat dan cepat!</p>
      </div>
      <div class="game-card-arrow">→</div>
    </div>
    
    <div class="game-card" onclick="openSpeedType()">
      <div class="game-card-icon typing-icon">⌨️</div>
      <div class="game-card-content">
        <h3 class="game-card-title">Speed Type</h3>
        <p class="game-card-desc">Ketik secepat kilat dan kalahkan waktu!</p>
        <p class="game-card-desc2">*Games ini tidak dapat level dan makanan</p>

      </div>
      <div class="game-card-arrow">→</div>
    </div>
    
    <div class="game-card" onclick="openJumpingGame()">
      <div class="game-card-icon jumping-icon">🦖</div>
      <div class="game-card-content">
        <h3 class="game-card-title">Jumping</h3>
        <p class="game-card-desc">Hindari rintangan dan raih skor tertinggi!</p>
      </div>
      <div class="game-card-arrow">→</div>
    </div>
  </div>
  
  <!-- Modal Pilih Pet (hanya satu) -->
  <div id="pet-modal" class="modal" style="display:none;">
    <div class="modal-content">
      <span class="close-btn" onclick="closePetModal()">&times;</span>
      <h2>Pilih Pet Kesukaanmu</h2>
      <div class="pet-list">
        <div class="pet-card" data-pet="dino">
          <img src="../img/dino.png" alt="Dino">
          <h3>Dino</h3>
          <p>Dinosaurus kuat dan ramah!</p>
        </div>
        <div class="pet-card" data-pet="robot">
          <img src="../img/robot.png" alt="Robot">
          <h3>Robot</h3>
          <p>Robot canggih dan setia!</p>
        </div>
        <div class="pet-card" data-pet="kucing">
          <img src="../img/kucing.png" alt="Kucing">
          <h3>Kucing</h3>
          <p>Kucing lucu dan lincah!</p>
        </div>
        <div class="pet-card" data-pet="kelinci">
          <img src="../img/kelinci.png" alt="Kelinci">
          <h3>Kelinci</h3>
          <p>Kelinci imut dan cepat!</p>
        </div>
        <div class="pet-card" data-pet="burung">
          <img src="../img/burung.png" alt="Burung">
          <h3>Burung</h3>
          <p>Burung ceria dan pintar!</p>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Edit Pet (hanya satu, konsisten id dan field) -->
  <div class="modal-overlay"></div>
  <div id="edit-pet-modal" class="modal" style="display:none;">
    <div class="modal-content" style="overflow-y:auto;max-height:80vh;">
      <span class="close-btn" onclick="closeEditModal()">&times;</span>
      <h2>Edit Pet Kamu</h2>
      <div class="pet-options" id="pet-options"></div>
      <input type="text" id="edit-pet-name" placeholder="Nama Pet Baru" style="display:block;" />
      <textarea id="edit-pet-desc" placeholder="Deskripsi Pet" style="display:block;"></textarea>
      <button id="save-pet-btn" style="display:block;">Simpan</button>
    </div>
  </div>

</section>

<!-- Tambahkan di <style> -->
<style>
  /* Styling untuk Pet Card Container */
  .pet-card-container {
    margin-bottom: 2rem;
  }
  
  .pet-card-wrapper {
    background: #ffffff;
    border-radius: 24px;
    padding: 2rem;
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    align-items: center;
    box-shadow: 0 10px 30px rgba(184, 53, 86, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .pet-card-wrapper:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(184, 53, 86, 0.25);
  }
  
  /* Pet Image Styling */
  .pet-image-container {
    position: relative;
  }
  
  .pet-image-frame {
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ffccd5, #ff99ac);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    box-shadow: 0 8px 25px rgba(184, 53, 86, 0.2);
    animation: float 3s ease-in-out infinite alternate;
  }
  
  @keyframes float {
    0% { transform: translateY(0); }
    100% { transform: translateY(-10px); }
  }
  
  .pet-image {
    width: 240px;
    height: 240px;
    object-fit: contain;
    transition: transform 0.3s ease;
  }
  
  .pet-image-frame:hover .pet-image {
    transform: scale(1.1) rotate(5deg);
  }
  
  .pet-level-badge {
    position: absolute;
    bottom: 0;
    right: 0;
    background: #264653;
    color: white;
    border-radius: 50%;
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    border: 3px solid white;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    font-size: 30px;
  }
  
  /* Pet Info Panel Styling */
  .pet-info-panel {
    flex: 1;
    min-width: 280px;
  }
  
  /* Pet Stats Styling */
  .pet-stats-container {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    background: #f8f9fa;
    border-radius: 16px;
    padding: 1rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  }
  
  .pet-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    transition: transform 0.2s ease, background 0.2s ease;
  }
  
  .pet-stat:hover {
    background: #fff;
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
  }
  
  .pet-stat-icon {
    font-size: 1.8rem;
    margin-bottom: 0.3rem;
  }
  
  .pet-stat-value {
    font-weight: bold;
    font-size: 1.2rem;
    color: #264653;
  }
  
  .pet-stat-label {
    font-size: 0.8rem;
    color: #666;
  }
  
  /* Pet Identity Styling */
  .pet-identity {
    background: linear-gradient(135deg, #fd9cb4ff, #ff789aff);
    padding: 1rem;
    border-radius: 16px;
    margin-bottom: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    border-left: 5px solid #B83556;
  }
  
  .pet-identity h3 {
    margin: 0 0 0.5rem 0;
    color: #142d37ff;
    font-size: 3rem;
  }
  
  .pet-identity p {
    margin: 0;
    color: #666;
    font-size: 1.5rem;
  }
  
  /* Progress Bar Styling */
  .progress-container {
    margin-bottom: 1.5rem;
  }
  
  .progress-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: bold;
    color: #264653;
  }
  
  .progress-bar-container {
    background: #e9ecef;
    border-radius: 20px;
    overflow: hidden;
    height: 20px;
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
  }
  
  .progress-bar {
    height: 100%;
    color: white;
    text-align: center;
    font-weight: bold;
    line-height: 20px;
    transition: width 0.5s ease;
  }
  
  .food-progress {
    background: linear-gradient(90deg, #a46339, #d4a373);
    background-size: 200% 100%;
    animation: gradient-shift 2s ease infinite;
  }
  
  .happy-progress {
    background: linear-gradient(90deg, #e63946, #ff66a3);
    background-size: 200% 100%;
    animation: gradient-shift 2s ease infinite;
  }
  
  @keyframes gradient-shift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }
  
  /* Pet Action Buttons */
  .pet-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
  }
  
  .pet-action-btn {
    flex: 1;
    min-width: 120px;
    padding: 0.8rem 1.2rem;
    border: none;
    border-radius: 12px;
    font-weight: bold;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }
  
  .feed-btn {
    background: linear-gradient(135deg, #264653, #2a9d8f);
    color: white;
  }
  
  .edit-btn {
    background: linear-gradient(135deg, #e76f51, #f4a261);
    color: white;
  }
  
  .pet-action-btn:hover {
    transform: translateY(-3px) scale(1.03);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
  }
  
  .pet-action-btn:active {
    transform: translateY(1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }
  
  .btn-icon {
    font-size: 1.2rem;
  }
  
  /* Games Collection Styling */
  .games-heading {
    margin: 2.5rem 0 1.5rem;
    color: white;
    font-size: 1.8rem;
    text-align: center;
    position: relative;
    padding-bottom: 0.5rem;
  }
  
  .games-heading::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: white;
    border-radius: 2px;
  }
  
  .games-subtitle {
    display: block;
    font-size: 1rem;
    font-weight: normal;
    margin-top: 0.5rem;
    opacity: 0.8;
  }
  
  .games-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
  }
  
  .game-card {
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    align-items: center;
    padding: 1rem;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    position: relative;
  }
  /* Variasi warna card berdasarkan urutan */
  .games-container .game-card:nth-child(1) {
    background: linear-gradient(120deg, #fffbe7 60%, #ffe0ec 100%);
  }
  .games-container .game-card:nth-child(2) {
    background: linear-gradient(120deg, #caf0f8 60%, #ffd6e0 100%);
  }
  .games-container .game-card:nth-child(3) {
    background: linear-gradient(120deg, #d8f3dc 60%, #f1f7b5 100%);
  }
  .games-container .game-card:nth-child(4) {
    background: linear-gradient(120deg, #f8edeb 60%, #fec89a 100%);
  }
  .games-container .game-card:nth-child(5) {
    background: linear-gradient(120deg, #e0c3fc 60%, #b5ead7 100%);
  }
  
  .game-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
  }
  
  .game-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background: #B83556;
    opacity: 0;
    transition: opacity 0.3s ease;
  }
  
  .game-card:hover::before {
    opacity: 1;
  }
  
  .game-card-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin-right: 1rem;
    flex-shrink: 0;
    transition: transform 0.3s ease;
  }
  
  .game-card:hover .game-card-icon {
    transform: scale(1.1) rotate(5deg);
  }
  
  .math-icon {
    background: linear-gradient(135deg, #ffccd5, #ff99ac);
  }
  
  .memory-icon {
    background: linear-gradient(135deg, #caf0f8, #90e0ef);
  }
  
  .puzzle-icon {
    background: linear-gradient(135deg, #d8f3dc, #95d5b2);
  }
  
  .typing-icon {
    background: linear-gradient(135deg, #f8edeb, #fec89a);
  }
  
  .jumping-icon {
    background: linear-gradient(135deg, #e0c3fc, #8ec5fc);
  }
  
  .game-card-content {
    flex: 1;
  }
  
  .game-card-title {
    margin: 0 0 0.3rem 0;
    font-size: 1.2rem;
    color: #264653;
  }
  
  .game-card-desc {
    margin: 0;
    font-size: 0.85rem;
    color: #666;
    line-height: 1.4;
  }

    .game-card-desc2 {
    margin: 0;
    font-size: 0.5rem;
    color: #000000ff;
    line-height: 1.4;
  }
  
  .game-card-arrow {
    font-size: 1.5rem;
    color: #B83556;
    margin-left: 1rem;
    opacity: 0;
    transform: translateX(-10px);
    transition: opacity 0.3s ease, transform 0.3s ease;
  }
  
  .game-card:hover .game-card-arrow {
    opacity: 1;
    transform: translateX(0);
  }
  
  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .pet-card-wrapper {
      padding: 1.5rem;
    }
    
    .pet-image-frame {
      width: 150px;
      height: 150px;
      margin: 0 auto 1.5rem;
    }
    
    .pet-image {
      width: 120px;
      height: 120px;
    }
    
    .pet-level-badge {
      width: 40px;
      height: 40px;
      font-size: 0.9rem;
    }
    
    .games-container {
      grid-template-columns: 1fr;
    }
  }
  
  /* Original game-btn styling for backward compatibility */
  .game-btn {
    padding: 1rem;
    border-radius: 16px;
    border: none;
    font-weight: bold;
    background: white;
    cursor: pointer;
    transition: 0.3s;
  }
  .game-btn:hover {
    background: #ffd9e0;
    transform: scale(1.05);
  }
</style>

<style>
.modal.hidden {
  display: none;
}
.modal {
  position: fixed;
  z-index: 1000;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex; justify-content: center; align-items: center;
}
.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 1rem;
  max-width: 500px;
  width: 90%;
  position: relative;
}
.close-btn {
  position: absolute;
  top: 1rem;
  right: 1rem;
  font-size: 1.5rem;
  cursor: pointer;
}
.pet-options {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  margin-bottom: 1rem;
}
.pet-option {
  border: 2px solid transparent;
  border-radius: 12px;
  padding: 0.5rem;
  cursor: pointer;
  text-align: center;
  width: 100px;
}
.pet-option.selected {
  border-color: #B83556;
}
.pet-option img {
  width: 60px;
  height: 60px;
  object-fit: contain;
}
</style>

<script src="games.js"></script>

<!-- QUIZ MATEMTIKA -->

<div id="quiz-modal" class="modal hidden">
  <div class="modal-content" style="max-width: 600px; width: 95%; position: relative;">
    <span class="close-btn" onclick="closeQuiz()">&times;</span>
    <h2 style="text-align:center; color:#B83556;">Quiz Matematika</h2>

    <!-- Level Selection -->
    <div id="quiz-level-select" style="text-align:center; margin-bottom:1.2rem;">
      <button class="game-btn quiz-level-btn" data-level="easy" style="background:#7ed957; color:white; margin:0 0.5rem;">Easy</button>
      <button class="game-btn quiz-level-btn" data-level="medium" style="background:#ffb300; color:white; margin:0 0.5rem;">Medium</button>
      <button class="game-btn quiz-level-btn" data-level="hard" style="background:#e74c3c; color:white; margin:0 0.5rem;">Hard</button>
    </div>
    <div id="quiz-start" style="text-align:center;">
      <button onclick="startQuiz()" class="game-btn" style="margin-top:1rem; background:#B83556; color:white;">Mulai Quiz</button>
    </div>

    <div id="quiz-question" style="display:none;">
      <p id="question-text" style="font-weight:bold; font-size:1.2rem;"></p>
      <div id="answer-options" style="margin-top:1rem; display:flex; flex-direction:column; gap:0.5rem;"></div>
      <button onclick="nextQuestion()" class="game-btn" style="margin-top:1rem; background:#264653; color:white;">Selanjutnya</button>
    </div>

    <div id="quiz-end" style="display:none; text-align:center;">
      <h3>Quiz Selesai!</h3>
      <p>Skor kamu: <span id="quiz-score"></span>/10</p>
      <button onclick="closeQuiz()" class="game-btn" style="background:#B83556; color:white;">Tutup</button>
    </div>
  </div>
</div>

<script>
// Quiz Matematika dengan Level
const quizBank = {
  easy: [
    { q: "3 + 4 = ?", options: ["6", "7", "8", "9"], answer: "7" },
    { q: "5 x 2 = ?", options: ["10", "12", "8", "9"], answer: "10" },
    { q: "9 - 3 = ?", options: ["6", "5", "7", "8"], answer: "6" },
    { q: "12 / 4 = ?", options: ["3", "2", "4", "6"], answer: "3" },
    { q: "7 + 6 = ?", options: ["14", "13", "12", "15"], answer: "13" },
    { q: "4 x 3 = ?", options: ["12", "10", "9", "14"], answer: "12" },
    { q: "15 - 9 = ?", options: ["6", "5", "4", "7"], answer: "6" },
    { q: "2 + 5 = ?", options: ["8", "7", "6", "5"], answer: "7" },
    { q: "6 / 2 = ?", options: ["2", "3", "4", "5"], answer: "3" },
    { q: "10 x 1 = ?", options: ["10", "1", "11", "9"], answer: "10" },
  ],
  medium: [
    { q: "18 / 3 = ?", options: ["6", "5", "7", "8"], answer: "6" },
    { q: "7 x 6 = ?", options: ["42", "36", "40", "48"], answer: "42" },
    { q: "25 - 9 = ?", options: ["16", "15", "14", "17"], answer: "16" },
    { q: "8 x 4 = ?", options: ["32", "24", "28", "36"], answer: "32" },
    { q: "45 / 5 = ?", options: ["9", "8", "7", "10"], answer: "9" },
    { q: "13 + 17 = ?", options: ["30", "29", "31", "28"], answer: "30" },
    { q: "36 / 6 = ?", options: ["6", "5", "7", "8"], answer: "6" },
    { q: "9 x 3 = ?", options: ["27", "18", "21", "24"], answer: "27" },
    { q: "50 - 28 = ?", options: ["22", "20", "18", "24"], answer: "22" },
    { q: "14 + 19 = ?", options: ["33", "32", "31", "34"], answer: "33" },
  ],
  hard: [
    { q: "72 / 8 = ?", options: ["9", "8", "7", "6"], answer: "9" },
    { q: "15 x 7 = ?", options: ["105", "95", "100", "110"], answer: "105" },
    { q: "120 - 47 = ?", options: ["73", "67", "77", "83"], answer: "73" },
    { q: "56 / 7 = ?", options: ["8", "7", "6", "9"], answer: "8" },
    { q: "23 x 3 = ?", options: ["69", "66", "63", "72"], answer: "69" },
    { q: "99 / 9 = ?", options: ["11", "9", "12", "10"], answer: "11" },
    { q: "88 - 29 = ?", options: ["59", "61", "57", "63"], answer: "59" },
    { q: "17 x 4 = ?", options: ["68", "64", "72", "76"], answer: "68" },
    { q: "144 / 12 = ?", options: ["12", "11", "13", "14"], answer: "12" },
    { q: "35 + 47 = ?", options: ["82", "81", "83", "80"], answer: "82" },
  ]
};

let quizLevel = 'easy';
let shuffledQuiz = [];
let currentIndex = 0;
let score = 0;

// Level selection logic
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.quiz-level-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      quizLevel = btn.getAttribute('data-level');
      document.querySelectorAll('.quiz-level-btn').forEach(b => b.classList.remove('selected'));
      btn.classList.add('selected');
    });
  });
});

function showQuiz() {
  document.getElementById('quiz-modal').classList.remove('hidden');
  document.getElementById('quiz-start').style.display = 'block';
  document.getElementById('quiz-question').style.display = 'none';
  document.getElementById('quiz-end').style.display = 'none';
  document.getElementById('quiz-level-select').style.display = 'block';
  quizLevel = 'easy';
  document.querySelectorAll('.quiz-level-btn').forEach(b => b.classList.remove('selected'));
  document.querySelector('.quiz-level-btn[data-level="easy"]').classList.add('selected');
}

function closeQuiz() {
  document.getElementById('quiz-modal').classList.add('hidden');
}

function startQuiz() {
  // Ambil 10 soal dari level terpilih
  let bank = quizBank[quizLevel];
  shuffledQuiz = bank.slice().sort(() => Math.random() - 0.5).slice(0, 10);
  currentIndex = 0;
  score = 0;
  document.getElementById('quiz-start').style.display = 'none';
  document.getElementById('quiz-end').style.display = 'none';
  document.getElementById('quiz-question').style.display = 'block';
  document.getElementById('quiz-level-select').style.display = 'none';
  loadQuestion();
}

function loadQuestion() {
  const q = shuffledQuiz[currentIndex];
  document.getElementById('question-text').innerHTML = `Soal ${currentIndex + 1} :<br><span style="font-size:1.3em; font-weight:600; color:#B83556;">${q.q}</span>`;
  const options = q.options.slice().sort(() => Math.random() - 0.5);
  const container = document.getElementById('answer-options');
  container.innerHTML = '';
  options.forEach(opt => {
    const btn = document.createElement('button');
    btn.textContent = opt;
    btn.className = 'game-btn';
    btn.onclick = () => {
      if (opt === q.answer) score++;
      nextQuestion();
    };
    container.appendChild(btn);
  });
}

function nextQuestion() {
  currentIndex++;
  if (currentIndex < shuffledQuiz.length) {
    loadQuestion();
  } else {
    document.getElementById('quiz-question').style.display = 'none';
    document.getElementById('quiz-end').style.display = 'block';
    document.getElementById('quiz-score').textContent = score;
    
    // Reward berdasarkan level
    let levelGain = 0;
    let foodGain = 0;
    let happyGain = 0;
    
    if (quizLevel === 'easy') {
      levelGain = 1;
      foodGain = 1;
      happyGain = 10;
    } else if (quizLevel === 'medium') {
      levelGain = 2;
      foodGain = 2;
      happyGain = 20;
    } else if (quizLevel === 'hard') {
      levelGain = 3;
      foodGain = 3;
      happyGain = 30;
    }
    
    // Hanya berikan reward jika skor minimal 6
    if (score >= 6) {
      updatePetStats(levelGain, foodGain, happyGain);
      showPlayfulPopup(`🎉 Hebat! Pet kamu mendapat +${levelGain} level, +${foodGain} makanan, dan +${happyGain}% kebahagiaan!`, 'success');
    } else {
      showPlayfulPopup('😢 Skor kurang dari 6. Coba lagi untuk mendapatkan reward!', 'fail');
    }
  }
}
</script>

<style>
#quiz-modal .game-btn {
  padding: 0.8rem 1rem;
  font-size: 1rem;
  border-radius: 12px;
  background: #fff;
  border: 2px solid #B83556;
  color: #B83556;
  cursor: pointer;
  transition: 0.3s;
}
#quiz-modal .game-btn:hover {
  background: #ffe6ea;
  transform: scale(1.05);
}
/* Highlight selected quiz level button */
#quiz-modal .quiz-level-btn.selected {
  border: 6px solid #2a9d8f;
  background: linear-gradient(90deg, #fffbe7 60%, #b2f7ef 100%);
  color: #264653;
  box-shadow: 0 0 0 4px #b2f7ef55;
  animation: quizLevelPulse 0.7s;
}

@keyframes quizLevelPulse {
  0% { box-shadow: 0 0 0 0 #b2f7ef55; }
  70% { box-shadow: 0 0 0 8px #b2f7ef33; }
  100% { box-shadow: 0 0 0 4px #b2f7ef55; }
}
</style>

<!-- Tombol trigger -->
<script>
// Fungsi untuk menampilkan quiz matematika
document.querySelectorAll('.game-btn').forEach(btn => {
  if (btn.textContent.includes("QUIZ MATEMATIKA") || btn.textContent.includes("QUIZ BERHITUNG")) {
    btn.addEventListener('click', showQuiz);
  }
});

// Fungsi untuk menampilkan game puzzle
// ...fungsi puzzle di sini dihapus agar tidak bentrok dengan games.js...
  
  // Shuffle tiles (ensure solvable)
  do {
    shuffleTiles();
  } while (!isSolvable());
  
  // Create the board
  puzzleTiles.forEach((tileValue, index) => {
    const tile = document.createElement('div');
    tile.className = 'puzzle-tile';
    tile.dataset.index = index;
    // (You may want to add more logic here for tile rendering)
  });
    


function isSolvable() {
  // For odd-sized puzzles, count inversions
  // For even-sized puzzles, count inversions + row of empty tile
  let inversions = 0;
  for (let i = 0; i < puzzleTiles.length; i++) {
    if (puzzleTiles[i] === emptyTileIndex) continue;
    for (let j = i + 1; j < puzzleTiles.length; j++) {
      if (puzzleTiles[j] === emptyTileIndex) continue;
      if (puzzleTiles[i] > puzzleTiles[j]) inversions++;
    }
  }
  
  if (puzzleSize % 2 === 1) {
    return inversions % 2 === 0;
  } else {
    const emptyRow = Math.floor(emptyTileIndex / puzzleSize);
    return (inversions + emptyRow) % 2 === 1;
  }
}

function moveTile(index) {
  // Check if tile is adjacent to empty tile
  const row = Math.floor(index / puzzleSize);
  const col = index % puzzleSize;
  const emptyRow = Math.floor(emptyTileIndex / puzzleSize);
  const emptyCol = emptyTileIndex % puzzleSize;
  
  if (
    (row === emptyRow && Math.abs(col - emptyCol) === 1) ||
    (col === emptyCol && Math.abs(row - emptyRow) === 1)
  ) {
    // Swap tiles
    [puzzleTiles[index], puzzleTiles[emptyTileIndex]] = [puzzleTiles[emptyTileIndex], puzzleTiles[index]];
    
    // Update UI
    const tiles = document.querySelectorAll('.puzzle-tile');
    tiles[index].style.visibility = 'hidden';
    tiles[index].textContent = '';
    
    tiles[emptyTileIndex].style.visibility = 'visible';
    tiles[emptyTileIndex].textContent = puzzleTiles[emptyTileIndex] + 1;
    
    // Update empty tile index
    emptyTileIndex = index;
    
    // Check if puzzle is solved
    if (isPuzzleSolved()) {
      showPuzzleVictory();
    }
  }
}

function isPuzzleSolved() {
  for (let i = 0; i < puzzleTiles.length - 1; i++) {
    if (puzzleTiles[i] !== i) return false;
  }
  return true;
}

function showPuzzleVictory() {
  const popup = document.createElement('div');
  popup.className = 'popup-win';
  popup.innerHTML = `
    <h3>🎉 Yeay! Puzzle Berhasil Disusun 🎉</h3>
    <button onclick="closePuzzleGame()">Main Lagi Yuk!</button>
  `;
  document.body.appendChild(popup);
  setTimeout(() => popup.classList.add('show'), 50);
  setTimeout(() => {
    popup.classList.remove('show');
    setTimeout(() => popup.remove(), 300);
  }, 4000);
  
  // Reward berdasarkan level
  let levelGain = 0;
  let foodGain = 0;
  let happyGain = 0;
  
  if (puzzleSize === 3) { // easy
    levelGain = 1;
    foodGain = 1;
    happyGain = 10;
  } else if (puzzleSize === 4) { // medium
    levelGain = 2;
    foodGain = 2;
    happyGain = 20;
  } else if (puzzleSize === 5) { // hard
    levelGain = 3;
    foodGain = 3;
    happyGain = 30;
  }
  
  // Berikan reward
  updatePetStats(levelGain, foodGain, happyGain);
  showPlayfulPopup(`🎉 Hebat! Pet kamu mendapat +${levelGain} level, +${foodGain} makanan, dan +${happyGain}% kebahagiaan!`, 'success');
}

// Efek hover untuk card pet
document.addEventListener('DOMContentLoaded', function() {
  // Animasi untuk progress bar
  const foodBar = document.getElementById('food-bar');
  if (foodBar) {
    const currentWidth = foodBar.style.width;
    foodBar.style.width = '0%';
    setTimeout(() => {
      foodBar.style.width = currentWidth;
    }, 300);
  }
  
  // Efek hover untuk game cards
  const gameCards = document.querySelectorAll('.game-card');
  gameCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
      this.querySelector('.game-card-icon').classList.add('pulse');
    });
    card.addEventListener('mouseleave', function() {
      this.querySelector('.game-card-icon').classList.remove('pulse');
    });
  });
});
</script>

<style>
/* Animasi tambahan */
@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.1); }
  100% { transform: scale(1); }
}

.pulse {
  animation: pulse 0.6s ease-in-out;
}

/* Styling untuk progress bar label */
.progress-icon {
  font-size: 1.2rem;
  margin-right: 5px;
  vertical-align: middle;
}

.progress-desc {
  font-size: 0.8rem;
  color: #666;
  font-weight: normal;
  margin-left: 5px;
}

/* Styling untuk progress bar */
.food-progress {
  background: linear-gradient(90deg, #8B4513, #CD853F);
  animation: fillBar 1.5s ease-out;
}

.happy-progress {
  background: linear-gradient(90deg, #B83556, #FF6B8B);
  animation: fillBar 1.5s ease-out;
}

@keyframes fillBar {
  from { width: 0%; }
}

/* Popup notifikasi playful */
.playful-popup {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  background: white;
  color: #333;
  padding: 12px 24px;
  border-radius: 50px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.2);
  z-index: 9999;
  font-weight: bold;
  opacity: 1;
  transition: opacity 0.4s ease;
}

.playful-popup.success {
  background: #d4edda;
  color: #155724;
  border-left: 5px solid #28a745;
}

.playful-popup.fail {
  background: #f8d7da;
  color: #721c24;
  border-left: 5px solid #dc3545;
}
</style>

<!-- Modal Game Logika -->
<div id="logic-game-modal" class="modal hidden">
  <div class="modal-content" style="max-width:800px; width:90%; overflow-y:auto;">
    <span class="close-btn" onclick="closeLogicGame()">&times;</span>
    <h2>Games Logika (Memory Match)</h2>
    <div id="logic-level-selection" style="text-align:center; margin:1rem 0;">
      <p>Pilih Level:</p>
      <button class="game-btn" onclick="startLogicGame('easy')">Easy</button>
      <button class="game-btn" onclick="startLogicGame('medium')">Medium</button>
      <button class="game-btn" onclick="startLogicGame('hard')">Hard</button>
    </div>
    <div id="logic-game-board" class="logic-board" style="display:none;"></div>
  </div>
</div>

<script>
let logicLevel = 'easy';
let logicCards = [];
let flippedCards = [];
let matchedPairs = 0;
let canFlip = true;

function openLogicGame() {
  document.getElementById('logic-game-modal').classList.remove('hidden');
  document.getElementById('logic-level-selection').style.display = 'block';
  document.getElementById('logic-game-board').style.display = 'none';
}

function closeLogicGame() {
  document.getElementById('logic-game-modal').classList.add('hidden');
  const popup = document.getElementById('logic-win-popup');
  if (popup) popup.remove();
}

function startLogicGame(level) {
  logicLevel = level;
  let pairs = 6; // Default for easy
  
  if (level === 'medium') pairs = 8;
  if (level === 'hard') pairs = 12;
  
  // Setup game board
  const board = document.getElementById('logic-game-board');
  board.innerHTML = '';
  board.style.display = 'grid';
  
  // Set grid based on level
  if (level === 'easy') {
    board.style.gridTemplateColumns = 'repeat(4, 1fr)';
  } else if (level === 'medium') {
    board.style.gridTemplateColumns = 'repeat(4, 1fr)';
  } else {
    board.style.gridTemplateColumns = 'repeat(6, 1fr)';
  }
  
  document.getElementById('logic-level-selection').style.display = 'none';
  
  // Create card pairs
  const emojis = ['🐶', '🐱', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼', '🐨', '🐯', '🦁', '🐮', '🐷', '🐸', '🐵'];
  const gameEmojis = emojis.slice(0, pairs);
  
  // Double the emojis and shuffle
  logicCards = [...gameEmojis, ...gameEmojis];
  logicCards.sort(() => Math.random() - 0.5);
  
  // Reset game state
  flippedCards = [];
  matchedPairs = 0;
  canFlip = true;
  
  // Create the cards
  logicCards.forEach((emoji, index) => {
    const card = document.createElement('div');
    card.className = 'logic-card';
    card.dataset.index = index;
    
    card.innerHTML = `
      <div class="logic-inner">
        <div class="logic-face logic-front">?</div>
        <div class="logic-face logic-back">${emoji}</div>
      </div>
    `;
    
    card.addEventListener('click', () => flipCard(card, index));
    board.appendChild(card);
  });
}

function flipCard(card, index) {
  // Prevent flipping if animation in progress or card already flipped
  if (!canFlip || flippedCards.includes(index) || card.classList.contains('flipped')) return;
  
  // Flip the card
  card.classList.add('flipped');
  flippedCards.push(index);
  
  // Check if we have a pair
  if (flippedCards.length === 2) {
    canFlip = false;
    const [first, second] = flippedCards;
    
    if (logicCards[first] === logicCards[second]) {
      // Match found
      matchedPairs++;
      flippedCards = [];
      canFlip = true;
      
      // Check if game is complete
      if (matchedPairs === logicCards.length / 2) {
        setTimeout(() => {
          showLogicWinPopup();
        }, 500);
      }
    } else {
      // No match
      setTimeout(() => {
        document.querySelector(`[data-index="${first}"]`).classList.remove('flipped');
        document.querySelector(`[data-index="${second}"]`).classList.remove('flipped');
        flippedCards = [];
        canFlip = true;
      }, 1000);
    }
  }
}

function showLogicWinPopup() {
  const popup = document.createElement('div');
  popup.id = 'logic-win-popup';
  popup.innerHTML = `
    <div style="position:fixed;top:0;left:0;width:100%;height:100%;background:#00000088;z-index:9998;"></div>
    <div style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff0f5;padding:2rem 3rem;border-radius:20px;z-index:9999;text-align:center;box-shadow:0 0 20px rgba(0,0,0,0.3);">
      <h2 style="font-size:2rem;margin-bottom:1rem;color:#B83556;">🎉 Yeay! Kamu Menang! 🎉</h2>
      <p style="font-size:1.1rem;color:#444;margin-bottom:1rem;">Kamu berhasil mencocokkan semua pasangan! Hebat! 🧠💡</p>
      <button onclick="closeLogicGame()" style="background:#ff66a3;color:white;padding:0.8rem 2rem;border:none;border-radius:12px;font-weight:bold;font-size:1rem;cursor:pointer;">Main Lagi Yuk!</button>
    </div>
  `;
  document.body.appendChild(popup);
  
  // Reward berdasarkan level
  let levelGain = 0;
  let foodGain = 0;
  let happyGain = 0;
  
  if (logicLevel === 'easy') {
    levelGain = 1;
    foodGain = 1;
    happyGain = 10;
  } else if (logicLevel === 'medium') {
    levelGain = 2;
    foodGain = 2;
    happyGain = 20;
  } else if (logicLevel === 'hard') {
    levelGain = 3;
    foodGain = 3;
    happyGain = 30;
  }
  
  // Berikan reward
  updatePetStats(levelGain, foodGain, happyGain);
  showPlayfulPopup(`🎉 Hebat! Pet kamu mendapat +${levelGain} level, +${foodGain} makanan, dan +${happyGain}% kebahagiaan!`, 'success');
}

// Fungsi untuk menampilkan popup notifikasi
function showPlayfulPopup(message, type) {
  const popup = document.createElement('div');
  popup.className = `playful-popup ${type}`;
  popup.textContent = message;
  document.body.appendChild(popup);
  
  setTimeout(() => {
    popup.style.opacity = '0';
    setTimeout(() => popup.remove(), 400);
  }, 3000);
}

// Fungsi untuk memperbarui statistik pet
function updatePetStats(levelGain, foodGain, happyGain) {
  // Ambil nilai saat ini
  let currentLevel = parseInt(document.getElementById('pet-level').innerText) || 0;
  let currentFood = parseInt(document.getElementById('pet-food').innerText) || 0;
  let currentHappy = parseInt(document.getElementById('pet-happy').innerText.replace('%', '')) || 0;
  
  // Update nilai
  currentLevel += levelGain;
  currentFood += foodGain;
  currentHappy = Math.min(100, currentHappy + happyGain); // Max 100%
  
  // Update tampilan
  document.getElementById('pet-level').innerText = currentLevel;
  document.getElementById('level-bar').innerText = currentLevel;
  document.getElementById('pet-food').innerText = currentFood;
  document.getElementById('pet-happy').innerText = currentHappy + '%';
  
  // Update food bar
  document.getElementById('food-bar').style.width = (currentFood * 10) + '%';
  document.getElementById('food-bar').innerText = (currentFood * 10) + '%';
  
  // Update happy bar
  document.getElementById('happy-bar').style.width = currentHappy + '%';
  document.getElementById('happy-bar').innerText = currentHappy + '%';
  
  // Simpan perubahan ke database
  fetch("pet_api.php", {
    method: "POST",
    body: new URLSearchParams({ 
      action: "update_stats", 
      level_gain: levelGain, 
      food_gain: foodGain, 
      happy_gain: happyGain 
    })
  }).then(r => r.json()).then(res => {
    if (res.success) {
      console.log("Berhasil menyimpan perubahan statistik pet:", res);
    } else {
      console.error("Gagal menyimpan perubahan statistik pet");
    }
  }).catch(err => {
    console.error("Error saat menyimpan statistik pet:", err);
  });
}
</script>

<style>
/* Struktur & flip animation */
  .logic-board {
    display: grid;
    gap: 10px;
    justify-content: center;
    margin-top: 1rem;
    padding: 1rem;
    background: #fffaf4;
    border-radius: 16px;
  }
  .logic-card {
    position: relative;
    width: 80px;
    height: 80px;
    perspective: 800px;
    cursor: pointer;
  }
  .logic-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transform-style: preserve-3d;
    transition: transform 0.35s;
  }
  .logic-card.flipped .logic-inner {
    transform: rotateY(180deg);
  }
  .logic-face {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    border-radius: 12px;
    font-size: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
  }
  .logic-front {
    background: linear-gradient(135deg, #FF9A8B, #FF6A88);
    color: white;
    border: 2px solid #fff;
  }
  .logic-back {
    transform: rotateY(180deg);
    background: #fff3e6;
    border: 2px solid #ffcc70;
  }
  #logic-level-selection {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 1rem;
    padding: 1rem;
  }
  #logic-level-selection button {
    background: #ffd9e0;
    border: none;
    border-radius: 16px;
    padding: 1rem;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  #logic-level-selection button:hover {
    background: #ffb3c6;
    transform: scale(1.05);
  }

    </style>

    <!-- Modal Game Puzzle -->
<div id="puzzle-game-modal" class="modal hidden">
  <div class="modal-content" style="max-width:800px; width:90%; overflow-y:auto;">
    <span class="close-btn" onclick="closePuzzleGame()">&times;</span>
    <h2>🎯 Games Puzzle - Susun Angka</h2>
    <div id="puzzle-level-selection" style="text-align:center; margin:1rem 0;">
      <p>Pilih Level:</p>
      <button class="game-btn" onclick="startPuzzleGame('easy')">Easy</button>
      <button class="game-btn" onclick="startPuzzleGame('medium')">Medium</button>
      <button class="game-btn" onclick="startPuzzleGame('hard')">Hard</button>
    </div>
    <div id="puzzle-board" class="logic-board" style="display:grid;"></div>
  </div>
</div>

  <style>
    .puzzle-tile {
      width: 60px; height: 60px;
      display: flex; align-items: center; justify-content: center;
      background: #ffe6e6;
      font-weight: bold; font-size: 1.2rem;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: background 0.3s;
    }
    .puzzle-tile:hover { background: #ffd6d6; }
    .popup-win {
      position: fixed; top: 30%; left: 50%; transform: translateX(-50%);
      background: #fff0f5;
      padding: 2rem; border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      z-index: 9999;
      text-align: center;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    .popup-win.show { opacity: 1; }
    .popup-win h3 {
      margin-bottom: 1rem;
      color: #b83556;
    }
    .popup-win button {
      background: #ffb6c1;
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 12px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }
    .popup-win button:hover { background: #ffa6b6; }
  </style>

<!-- Modal Game Speed Type -->
<div id="speedtype-modal" class="modal hidden">
  <div class="modal-content" style="max-width:600px; width:90%; background:#f0f8ff;">
    <span class="close-btn" onclick="closeSpeedType()">×</span>
    <h2 style="text-align:center; color:#1b4f72;">📝 Speed Typing Challenge</h2>

    <!-- LEVEL SELECTION -->
    <div id="st-level-selection" style="display: flex; justify-content: center; gap: 1rem; margin: 1rem 0;">
      <button class="st-level-btn" data-level="easy">🟢 Easy</button>
      <button class="st-level-btn" data-level="medium">🟠 Medium</button>
      <button class="st-level-btn" data-level="hard">🔴 Hard</button>
    </div>

    <!-- BOARD -->
    <div id="speedtype-board" style="display: none;">
      <p id="speedtype-text" style="font-size:1.5rem; color:#333;"></p>
      <input id="speedtype-input" type="text" placeholder="Ketik di sini..." style="width:100%; padding:0.5rem; font-size:1.2rem;" />
      <div style="display:flex; justify-content: space-between; margin-top:1rem;">
        <span>Waktu tersisa: <b id="st-timer">30</b>s</span>
        <span>Skor: <b id="st-score">0</b></span>
      </div>
      <button id="st-start-btn" class="game-btn" style="margin-top:1rem; background-color:burlywood;">Mulai Game</button>
    </div>
  </div>
</div>

<style>
/* Speed Type Style */
#speedtype-modal .modal-content {
  background: #fff8f0;
  border: 3px solid #ffd9e0;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  padding: 2rem;
  border-radius: 24px;
  font-family: 'Comic Sans MS', 'Poppins', sans-serif;
}

.st-level-btn {
  padding: 1rem 2rem;
  font-size: 1.1rem;
  border: none;
  border-radius: 16px;
  background: #ffe0ec;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}
.st-level-btn:hover {
  background: #ffbfd4;
  transform: scale(1.1);
}

#st-sentence-box {
  background: #fefefe;
  border: 2px dashed #ff6f91;
  padding: 1rem;
  margin: 1rem 0;
  border-radius: 16px;
  font-size: 1.4rem;
  min-height: 80px;
  text-align: center;
}

#speedtype-input {
  width: 100%;
  padding: 0.7rem 1rem;
  font-size: 1.2rem;
  border: 2px solid #ffa4c0;
  border-radius: 12px;
  margin-top: 0.5rem;
  transition: background 0.3s;
}
#speedtype-input.correct {
  background: #d4edda;
}
#speedtype-input.wrong {
  background: #f8d7da;
}

.st-info-row {
  display: flex;
  justify-content: space-between;
  margin-top: 1rem;
  font-weight: bold;
  font-size: 1rem;
  color: #b83556;
}

.st-start {
  background: #ffb3c6;
  margin-top: 1.5rem;
  border-radius: 16px;
  font-weight: bold;
  padding: 0.7rem 1.5rem;
  font-size: 1rem;
  border: none;
  cursor: pointer;
}
.st-start:hover {
  background: #ff85a1;
}

#st-level-selection button {
  padding: 0.8rem 1.2rem;
  border-radius: 12px;
  font-weight: bold;
  border: none;
  cursor: pointer;
  font-size: 1.1rem;
  background: #ffefef;
  transition: 0.2s ease;
}
#st-level-selection button:hover {
  background: #ffd9d9;
  transform: scale(1.05);
}

</style>

<!-- Modal Game Jumping -->
<div id="jumping-game-modal" class="modal hidden">
  <div class="modal-content" style="max-width:800px; width:90%; background:#f0f8ff;">
    <span class="close-btn" onclick="closeJumpingGame()">&times;</span>
    <h2 style="text-align:center; color:#1b4f72;">🦖 Jumping Game</h2>
    
    <!-- Game Instructions -->
    <div id="jumping-instructions" style="text-align:center; margin:1rem 0;">
      <p>Tekan <b>Spasi</b> atau <b>Panah Atas</b> untuk melompat. Hindari rintangan!</p>
      <p>Setiap rintangan yang berhasil dihindari akan menambah skor.</p>
      <p>GAMES INI AKAN MELATIH KESABARAN DAN KETELITIAN ANDA.</p>

      <!-- Menghapus pesan tentang pertanyaan bonus karena fitur ini telah dihapus -->
      <button id="jumping-start-btn" class="game-btn" style="margin-top:1rem; background-color:#4CAF50; color:white;">Mulai Game</button>
    </div>
    
    <!-- Game Board -->
    <div id="jumping-game-board" style="display:none; position:relative; width:100%; height:300px; background:#87CEEB; overflow:hidden; border-radius:10px; border:3px solid #333;">
      <div id="jumping-score-display" style="position:absolute; top:10px; right:10px; background:rgba(255,255,255,0.7); padding:5px 10px; border-radius:5px; font-weight:bold;">Skor: 0</div>
      <div id="jumping-highscore-display" style="position:absolute; top:10px; left:10px; background:rgba(255,255,255,0.7); padding:5px 10px; border-radius:5px; font-weight:bold;">Skor Tertinggi: 0</div>
      <div id="jumping-character" style="position:absolute; bottom:0; left:50px; width:50px; height:50px; background-image:url('../img/dino.png'); background-size:contain; background-repeat:no-repeat;"></div>
      <div id="jumping-ground" style="position:absolute; bottom:0; width:100%; height:20px; background:#8B4513;"></div>
      <button id="jumping-mobile-btn" style="position:absolute; bottom:30px; right:20px; width:80px; height:80px; background:rgba(76, 175, 80, 0.8); border-radius:50%; border:3px solid white; font-size:30px; color:white; display:none; box-shadow:0 4px 8px rgba(0,0,0,0.3);">↑</button>
    </div>
    
    <!-- Game Over Screen -->
    <div id="jumping-game-over" style="display:none; text-align:center; margin-top:1rem;">
      <h3 style="color:#FF5252;">Game Over!</h3>
      <p>Skor Akhir: <span id="jumping-final-score">0</span></p>
      <button onclick="restartJumpingGame()" class="game-btn" style="margin-top:1rem; background-color:#4CAF50; color:white;">Main Lagi</button>
    </div>
    
    <!-- Menghapus popup pertanyaan bonus karena fitur ini telah dihapus -->
  </div>
</div>

<style>
/* Jumping Game Styles */
#jumping-game-modal .modal-content {
  background: #e6f7ff;
  border: 3px solid #4CAF50;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  padding: 2rem;
  border-radius: 24px;
  font-family: 'Poppins', sans-serif;
  position: relative;
}

#jumping-start-btn {
  padding: 0.8rem 2rem;
  font-size: 1.2rem;
  border: none;
  border-radius: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}

#jumping-start-btn:hover {
  transform: scale(1.05);
  background-color: #45a049;
}

/* Menghapus style untuk tombol jawaban karena fitur pertanyaan bonus telah dihapus */

.obstacle {
   position: absolute;
   bottom: 20px; /* Sits on the ground */
   width: 30px;
   height: 40px;
   background-image: url('../img/cactus.svg');
   background-size: contain;
   background-repeat: no-repeat;
   background-position: bottom;
 }
 
/* Menghapus style untuk bonus item karena fitur pertanyaan bonus telah dihapus */

@keyframes float {
  from { transform: translateY(0); }
  to { transform: translateY(-5px); }
}

.jumping {
  animation: jump 0.7s;
}

.jumping-double {
  animation: jump-double 0.8s;
}

@keyframes jump {
  0% { bottom: 0; }
  50% { bottom: 100px; }
  100% { bottom: 0; }
}

@keyframes jump-double {
  0% { bottom: 0; }
  50% { bottom: 130px; } /* Lompatan kedua lebih tinggi */
  100% { bottom: 0; }
}
</style>
<script src="../music-player.js"></script>
</body>
</html>
