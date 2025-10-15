// Pastikan event listener tombol mulai tidak overwrite dan selalu aktif
function setupSpeedTypeStartBtn() {
  const stStartBtn = document.getElementById('st-start-btn');
  if (!stStartBtn) return;
  stStartBtn.onclick = null;
  stStartBtn.addEventListener('click', function() {
    if (stStarted) return;
    stStarted = true;
    document.getElementById('speedtype-input').disabled = false;
    document.getElementById('speedtype-input').focus();
    stStartBtn.style.display = 'none';
    stStartBtn.disabled = true;
    // Set timer sesuai level
    let timerSet = 30;
    if (stCurrentLevel === 'easy') timerSet = 10;
    else if (stCurrentLevel === 'medium') timerSet = 20;
    stTimer = timerSet;
    document.getElementById('st-timer').textContent = stTimer;
    stInterval = setInterval(() => {
      stTimer--;
      document.getElementById('st-timer').textContent = stTimer;
      if (stTimer <= 0) {
        clearInterval(stInterval);
        document.getElementById('speedtype-input').disabled = true;
        showSpeedTypeResult();
      }
    }, 1000);
  });
}
// Pastikan modal edit pet hidden di awal
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('edit-pet-modal');
  if (modal) modal.style.display = 'none';
});

function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('open');
  updateSidebarArrow();
}

function updateSidebarArrow() {
  const sidebar = document.getElementById('sidebar');
  const arrow = document.getElementById('sidebarArrow');
  if (sidebar.classList.contains('open')) {
    arrow.innerHTML = '<path d="M16 5l-8 7 8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
  } else {
    arrow.innerHTML = '<path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
  }
}

document.addEventListener('DOMContentLoaded', () => {
  updateSidebarArrow();
  toggleDropdownOnOutsideClick();
  initializeGamePet();

  // Tambah event listener manual untuk elemen pet-card (fallback)
  document.querySelectorAll('.pet-card').forEach(card => {
    card.addEventListener('click', () => {
      const petType = card.dataset.pet;
      const petName = card.querySelector('h3').textContent;
      const petDesc = card.querySelector('p').textContent;
      selectPet(petType, petName, petDesc);
    });
  });
});

function toggleDropdown() {
  const dropdown = document.getElementById("dropdown");
  dropdown.classList.toggle("open");
}

function toggleDropdownOnOutsideClick() {
  document.addEventListener('click', function (e) {
    const dropdown = document.getElementById("dropdown");
    const profileBtn = document.querySelector('.profile-button');
    if (!profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.remove("open");
    }
  });
}

function initializeGamePet() {
  fetch("pet_api.php")
    .then(r => r.json())
    .then(data => {
      console.log("Data pet yang diterima:", data);
      document.getElementById("pet-preview-img").src = `../img/${data.pet_type}.png`;
      document.getElementById("pet-name").textContent = data.pet_name;
      document.getElementById("pet-desc").textContent = data.pet_desc;
      document.getElementById("pet-level").textContent = data.pet_level;
      document.getElementById("pet-food").textContent = data.pet_food;
      document.getElementById("level-bar").textContent = data.pet_level;
      document.getElementById("pet-happy").textContent = data.pet_happy + '%';
      
      // Update progress bars
      updateBar("food-bar", data.pet_food);
      updateBar("happy-bar", data.pet_happy / 20); // Konversi persen ke nilai 0-5
      
      // Pastikan happy-bar menampilkan persentase yang benar
      const happyBar = document.getElementById("happy-bar");
      happyBar.style.width = data.pet_happy + '%';
      happyBar.textContent = data.pet_happy + '%';
    })
    .catch(err => {
      console.error("Error saat memuat data pet:", err);
    });
}

let selectedPetType = "";

// Pastikan hanya satu modal edit pet dan overlay
function editPetModal() {
  document.getElementById('edit-pet-modal').style.display = 'block';
  document.querySelector('.modal-overlay').style.display = 'block';
  fetch('pet_api.php?action=load')
    .then(response => response.json())
    .then(data => {
      const petOptions = document.getElementById('pet-options');
      petOptions.innerHTML = '';
      const pets = ['dino', 'robot', 'kucing', 'kelinci', 'burung'];
      pets.forEach(pet => {
        const petDiv = document.createElement('div');
        petDiv.className = 'pet-card';
        petDiv.dataset.pet = pet;
        petDiv.innerHTML = `<img src="../img/${pet}.png" alt="${pet}"><h3>${pet.charAt(0).toUpperCase() + pet.slice(1)}</h3>`;
        petDiv.addEventListener('click', function() {
          document.querySelectorAll('#pet-options .pet-card').forEach(card => card.classList.remove('selected'));
          this.classList.add('selected');
          document.getElementById('edit-pet-name').value = data.pet_type === pet ? data.pet_name : '';
          document.getElementById('edit-pet-desc').value = data.pet_type === pet ? data.pet_desc : '';
          document.getElementById('edit-pet-name').disabled = false;
          document.getElementById('edit-pet-desc').disabled = false;
          document.getElementById('save-pet-btn').disabled = false;
          document.getElementById('save-pet-btn').dataset.pet = pet;
          selectedPetType = pet;
        });
        petOptions.appendChild(petDiv);
      });
      // Reset input fields
      document.getElementById('edit-pet-name').value = '';
      document.getElementById('edit-pet-desc').value = '';
      document.getElementById('edit-pet-name').disabled = true;
      document.getElementById('edit-pet-desc').disabled = true;
      document.getElementById('save-pet-btn').disabled = true;
      document.getElementById('save-pet-btn').dataset.pet = '';
      selectedPetType = '';
    });
}

function closeEditModal() {
  document.getElementById('edit-pet-modal').style.display = 'none';
  document.querySelector('.modal-overlay').style.display = 'none';
}

// Fungsi popup playful
function showPlayfulPopup(message, type = 'success') {
  // Hapus popup lama jika ada
  document.querySelectorAll('.playful-popup').forEach(e => e.remove());
  const popup = document.createElement('div');
  popup.className = 'playful-popup ' + type;
  popup.textContent = message;
  document.body.appendChild(popup);
  setTimeout(() => {
    popup.style.opacity = '0';
    setTimeout(() => popup.remove(), 400);
  }, 1800);
}

// Event listener tombol simpan modal edit pet
const saveBtn = document.getElementById('save-pet-btn');
if (saveBtn) {
  saveBtn.onclick = function() {
    const pet = this.dataset.pet;
    const name = document.getElementById('edit-pet-name').value.trim();
    const desc = document.getElementById('edit-pet-desc').value.trim();
    if (!pet || !name || !desc) {
      showPlayfulPopup('Lengkapi semua data!', 'fail');
      return;
    }
    fetch('pet_api.php', {
      method: 'POST',
      body: new URLSearchParams({
        pet_type: pet,
        pet_name: name,
        pet_desc: desc
      })
    })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        closeEditModal();
        initializeGamePet();
        showPlayfulPopup('Berhasil menyimpan perubahan pet!', 'success');
      } else {
        showPlayfulPopup('Gagal menyimpan perubahan pet!', 'fail');
      }
    })
    .catch(() => {
      showPlayfulPopup('Terjadi kesalahan saat menyimpan!', 'fail');
    });
  };
}

function beriMakanPet() {
  const stok = parseInt(document.getElementById("pet-food").textContent);
  if (stok <= 0) {
    showPlayfulPopup("Stok makanan habis! Main game untuk mendapat makanan.", "fail");
    return;
  }
  fetch("pet_api.php", {
    method: "POST",
    body: new URLSearchParams({ action: "feed", food_gain: -1, level_gain: 1, happy_gain: 5 })
  }).then(r => r.json()).then(res => {
    if (res.success) {
      console.log("Hasil pemberian makan:", res);
      // Update tampilan
      document.getElementById("pet-food").textContent = res.pet_food;
      document.getElementById("pet-level").textContent = res.pet_level;
      document.getElementById("level-bar").textContent = res.pet_level;
      document.getElementById("pet-happy").textContent = res.pet_happy + '%';
      
      // Update progress bars
      updateBar("food-bar", res.pet_food);
      
      // Update happy bar langsung dengan persentase
      const happyBar = document.getElementById("happy-bar");
      happyBar.style.width = res.pet_happy + '%';
      happyBar.textContent = res.pet_happy + '%';
      
      showPlayfulPopup("Pet kamu senang karena diberi makan! 😊", "success");
    } else {
      showPlayfulPopup("Gagal memberi makan pet. Coba lagi!", "fail");
    }
  }).catch(err => {
    console.error("Error saat memberi makan pet:", err);
    showPlayfulPopup("Terjadi kesalahan saat memberi makan pet.", "fail");
  });
}

function updateBar(id, val) {
  const bar = document.getElementById(id);
  const percent = Math.min(100, Math.max(0, val * 20));
  bar.style.width = percent + "%";
  bar.textContent = percent + "%";
}

// Hapus fungsi editPetModal yang duplikat dan bentrok

// Fungsi untuk memilih pet
function pilihPet(petType) {
  document.querySelectorAll(".pet-option").forEach(el => el.classList.remove("selected"));
  document.getElementById("pet-" + petType).classList.add("selected");
  document.getElementById("selected-pet-type").value = petType;
}

// Simpan pet ke database
function simpanPet() {
  const pet_type = document.getElementById("selected-pet-type").value;
  const pet_name = document.getElementById("edit-pet-name").value;
  const pet_desc = document.getElementById("edit-pet-desc").value;

  if (!pet_type || !pet_name || !pet_desc) {
    alert("Semua kolom wajib diisi.");
    return;
  }

  fetch("pet_api.php", {
    method: "POST",
    body: new URLSearchParams({
      pet_type, pet_name, pet_desc
    })
  }).then(r => r.json())
    .then(res => {
      if (res.success) {
        document.getElementById("pet-modal").classList.remove("show");
        initializeGamePet(); // Refresh tampilan dashboard
      }
    });
}

// Tutup modal
function tutupModalPet() {
  document.getElementById("pet-modal").classList.remove("show");
}

function closePetModal() {
  document.getElementById("pet-modal").style.display = "none";
}

function selectPet(petType, name, desc) {
  fetch("pet_api.php", {
    method: "POST",
    body: new URLSearchParams({
      pet_type: petType,
      pet_name: name,
      pet_desc: desc
    })
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // Update tampilan pet di dashboard
        document.getElementById("pet-name").textContent = name;
        document.getElementById("pet-desc").textContent = desc;
        document.getElementById("pet-preview-img").src = `../img/${petType}.png`;
        closePetModal(); // Tutup modal
      } else {
        alert("Gagal menyimpan pet. Silakan coba lagi.");
      }
    })
    .catch(err => {
      console.error("Error memilih pet:", err);
      alert("Terjadi kesalahan. Coba lagi nanti.");
    });
}

function closePetModal() {
  document.getElementById("pet-modal").style.display = "none";
}

function savePetEdit() {
  const selectedCard = Array.from(document.querySelectorAll('#pet-options div')).find(c => c.style.border.includes('#B83556'));
  if (!selectedCard) return alert("Pilih pet terlebih dahulu!");

  const pet_type = selectedCard.getAttribute('data-pet');
  const pet_name = document.getElementById('edit-name').value;
  const pet_desc = document.getElementById('edit-desc').value;

  if (!pet_type || !pet_name || !pet_desc) {
    alert("Lengkapi semua data!");
    return;
  }

  fetch("pet_api.php", {
    method: "POST",
    body: new URLSearchParams({
      pet_type,
      pet_name,
      pet_desc
    })
  }).then(r => r.json()).then(res => {
    if (res.success) {
      document.getElementById("pet-name").textContent = pet_name;
      document.getElementById("pet-desc").textContent = pet_desc;
      document.getElementById("pet-preview-img").src = `../img/${pet_type}.png`;
      closePetModal();
    }
  });
}

// Tambahkan CSS untuk popup playful
document.head.insertAdjacentHTML('beforeend', `<style>
.playful-popup {
  position: fixed;
  top: 30px;
  left: 50%;
  transform: translateX(-50%);
  background: #fffbe7;
  color: #B83556;
  border: 2px solid #ffd9e0;
  border-radius: 16px;
  padding: 1rem 2rem;
  font-size: 1.3rem;
  font-weight: bold;
  z-index: 9999;
  box-shadow: 0 4px 16px rgba(0,0,0,0.12);
  animation: popIn 0.2s;
}
.playful-popup.success { border-color: #7ed957; color: #388e3c; }
.playful-popup.fail { border-color: #ff6f91; color: #B83556; }
@keyframes popIn { from { opacity: 0; transform: translateX(-50%) scale(0.8);} to { opacity: 1; transform: translateX(-50%) scale(1);} }
</style>`);

// Tambahkan CSS untuk .pet-card.selected
document.head.insertAdjacentHTML('beforeend', `<style>
.pet-card.selected {
  border: 3px solid #B83556 !important;
  background: #ffe6ea !important;
  box-shadow: 0 4px 16px rgba(184,53,86,0.15);
}
</style>`);

// Tambah event listener untuk modal pilih pet agar card yang dipilih terlihat
function setupPetModalSelection() {
  const petCards = document.querySelectorAll('#pet-modal .pet-card');
  petCards.forEach(card => {
    card.addEventListener('click', function() {
      petCards.forEach(c => c.classList.remove('selected'));
      this.classList.add('selected');
    });
  });
}

// Jalankan setupPetModalSelection setiap kali modal pilih pet dibuka
const petModal = document.getElementById('pet-modal');
if (petModal) {
  const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (petModal.style.display !== 'none') {
        setupPetModalSelection();
      }
    });
  });
  observer.observe(petModal, { attributes: true, attributeFilter: ['style'] });
}


// Game Logika Memory Match with flip icons
let logicFlipped = [], logicMatched = 0, totalPairs = 0;

function openLogicGame() {
  document.getElementById('logic-game-modal').classList.remove('hidden');
}

function closeLogicGame() {
  document.getElementById('logic-game-modal').classList.add('hidden');
  const board = document.getElementById('logic-game-board');
  board.innerHTML = ''; board.style.display = 'none';
  document.getElementById('logic-level-selection').style.display = 'block';
  removeLogicWinPopup();
}

function startLogicGame(level) {
  const board = document.getElementById('logic-game-board');
  logicFlipped = []; logicMatched = 0;
  board.innerHTML = ''; board.style.display='grid';
  document.getElementById('logic-level-selection').style.display = 'none';

  const icons = ['🐶','🐱','🐰','🐦','🐸','🐵','🦁','🐮','🐷','🐔','🦊','🦄','🐙','🦋','🐞','🦓','🦒','🐢','🦎','🦜'];
  const pairs = level==='easy'?6: level==='medium'?10:14;
  totalPairs = pairs;
  const selected = icons.sort(() => 0.5 - Math.random()).slice(0, pairs);
  const deck = [...selected, ...selected].sort(() => 0.5 - Math.random());

  const cols = Math.ceil(Math.sqrt(deck.length));
  board.style.gridTemplateColumns = `repeat(${cols},80px)`;

  deck.forEach((icon, idx) => {
    const card = document.createElement('div');
    card.className = 'logic-card';
    const inner = document.createElement('div');
    inner.className = 'logic-inner';
    inner.innerHTML = `
      <div class="logic-face logic-front">🎲</div>
      <div class="logic-face logic-back">${icon}</div>`;
    card.append(inner);
    card.onclick = () => flipLogic(card, icon);
    board.append(card);
  });
}

function flipLogic(card, value) {
  if (logicFlipped.length >= 2 || card.classList.contains('matched') || logicFlipped.find(f => f.card === card)) return;
  card.classList.add('flipped');
  logicFlipped.push({ card, value });
  if (logicFlipped.length === 2) {
    const [a,b] = logicFlipped;
    if (a.value === b.value) {
      a.card.classList.add('matched');
      b.card.classList.add('matched');
      logicMatched++;
      logicFlipped = [];
      if (logicMatched === totalPairs) {
        setTimeout(() => {
          showLogicWinPopup();
        }, 500);
      }
    } else {
      setTimeout(() => {
        a.card.classList.remove('flipped');
        b.card.classList.remove('flipped');
        logicFlipped = [];
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
}

function removeLogicWinPopup() {
  const popup = document.getElementById('logic-win-popup');
  if (popup) popup.remove();
}

// Trigger tombol pembuka modal game logika
document.querySelectorAll('.game-btn').forEach(btn => {
  if (btn.textContent.toUpperCase().includes('LOGIKA')) {
    btn.addEventListener('click', openLogicGame);
  }
  // Event listener untuk tombol Berhitung
  if (btn.textContent.toUpperCase().includes('BERHITUNG')) {
    btn.addEventListener('click', openBerhitungGame);
  }
});

// Modal dan fungsi sederhana untuk game berhitung

// Quiz berhitung interaktif

// Quiz berhitung pilihan ganda 10 soal
let berhitungSoalList = [];
let berhitungScore = 0;
let berhitungIndex = 0;

function openBerhitungGame() {
  let modal = document.getElementById('berhitung-game-modal');
  if (!modal) {
    modal = document.createElement('div');
    modal.id = 'berhitung-game-modal';
    modal.className = 'game-modal';
    modal.innerHTML = `
      <div id="berhitung-modal-content" style="background:#fffbe7;padding:2rem 3rem;border-radius:20px;z-index:9999;text-align:center;box-shadow:0 0 20px rgba(0,0,0,0.3);min-width:300px;">
        <h2 style="color:#B83556;">Quiz Berhitung</h2>
        <div id="berhitung-quiz-area">
          <button id="berhitung-start-btn" style="background:#7ed957;color:white;padding:0.8rem 2rem;border:none;border-radius:12px;font-weight:bold;font-size:1rem;cursor:pointer;">Mulai Quiz</button>
          <button onclick="closeBerhitungGame()" style="background:#ff66a3;color:white;padding:0.8rem 2rem;border:none;border-radius:12px;font-weight:bold;font-size:1rem;cursor:pointer;margin-left:1rem;">Tutup</button>
        </div>
      </div>
    `;
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100vw';
    modal.style.height = '100vh';
    modal.style.background = '#00000088';
    modal.style.zIndex = '9998';
    modal.style.display = 'flex';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    document.body.appendChild(modal);
    setTimeout(() => {
      document.getElementById('berhitung-start-btn').onclick = mulaiBerhitungQuiz;
    }, 100);
  } else {
    modal.style.display = 'flex';
    document.getElementById('berhitung-quiz-area').innerHTML = `
      <button id="berhitung-start-btn" style="background:#7ed957;color:white;padding:0.8rem 2rem;border:none;border-radius:12px;font-weight:bold;font-size:1rem;cursor:pointer;">Mulai Quiz</button>
      <button onclick="closeBerhitungGame()" style="background:#ff66a3;color:white;padding:0.8rem 2rem;border:none;border-radius:12px;font-weight:bold;font-size:1rem;cursor:pointer;margin-left:1rem;">Tutup</button>
    `;
    setTimeout(() => {
      document.getElementById('berhitung-start-btn').onclick = mulaiBerhitungQuiz;
    }, 100);
  }
}

function mulaiBerhitungQuiz() {
  berhitungScore = 0;
  berhitungIndex = 0;
  berhitungSoalList = [];
  for (let i = 0; i < 10; i++) {
    berhitungSoalList.push(generateSoalBerhitungPG());
  }
  tampilkanSoalBerhitungPG();
}

function tampilkanSoalBerhitungPG() {
  const area = document.getElementById('berhitung-quiz-area');
  if (berhitungIndex >= berhitungSoalList.length) {
    // Selesai, tampilkan skor
    showBerhitungResult();
    return;
  }
  const soalObj = berhitungSoalList[berhitungIndex];
  let pilihanHTML = '';
  soalObj.pilihan.forEach((pil, idx) => {
    const abcd = String.fromCharCode(65 + idx);
    pilihanHTML += `<button class="berhitung-pilihan-btn" style="margin:0.5rem 0.5rem 0 0.5rem;padding:0.7rem 2rem;font-size:1.1rem;border-radius:10px;border:2px solid #ffd9e0;background:#fff;color:#B83556;font-weight:bold;cursor:pointer;" data-idx="${idx}">${abcd}. ${pil}</button>`;
  });
  area.innerHTML = `
    <div style="margin-bottom:1rem;font-size:1.2rem;">Soal ${berhitungIndex+1} dari 10<br><b>${soalObj.soal}</b></div>
    <div style="margin-bottom:1rem;">${pilihanHTML}</div>
    <div id="berhitung-feedback" style="margin-top:1rem;font-size:1.1rem;"></div>
  `;
  document.querySelectorAll('.berhitung-pilihan-btn').forEach(btn => {
    btn.onclick = function() {
      const idx = parseInt(this.dataset.idx);
      if (soalObj.pilihan[idx] === soalObj.jawaban) berhitungScore++;
      berhitungIndex++;
      tampilkanSoalBerhitungPG();
    };
  });
}

function showBerhitungResult() {
  const area = document.getElementById('berhitung-quiz-area');
  let msg = '';
  if (berhitungScore === 10) {
    msg = `🌟 WAW! Kamu jenius berhitung!<br><b>Skor: ${berhitungScore}/10</b><br>Semua benar, otakmu kayak kalkulator! 🤯`;
  } else if (berhitungScore >= 7) {
    msg = `🎉 Keren!<br><b>Skor: ${berhitungScore}/10</b><br>Kamu jago banget, lanjut latihan biar makin GG! 🧠🔥`;
  } else if (berhitungScore >= 4) {
    msg = `😎 Lumayan!<br><b>Skor: ${berhitungScore}/10</b><br>Masih bisa lebih baik, jangan nyerah ya! 💪🐣`;
  } else {
    msg = `😂 Waduh!<br><b>Skor: ${berhitungScore}/10</b><br>Tenang, semua bisa belajar! Coba lagi yuk! 🐢✨`;
  }
  area.innerHTML = `<div style="font-size:1.5rem;margin-bottom:1rem;">${msg}</div><button onclick="closeBerhitungGame()" style="background:#ff66a3;color:white;padding:0.8rem 2rem;border:none;border-radius:12px;font-weight:bold;font-size:1rem;cursor:pointer;">Tutup</button>`;
}

function generateSoalBerhitungPG() {
  // Soal acak penjumlahan, pengurangan, perkalian
  const ops = ['+', '-', '×'];
  const op = ops[Math.floor(Math.random()*ops.length)];
  let a, b, soal, jawaban;
  if (op === '+') {
    a = Math.floor(Math.random()*50)+1;
    b = Math.floor(Math.random()*50)+1;
    soal = `${a} + ${b}`;
    jawaban = a + b;
  } else if (op === '-') {
    a = Math.floor(Math.random()*50)+20;
    b = Math.floor(Math.random()*20)+1;
    soal = `${a} - ${b}`;
    jawaban = a - b;
  } else {
    a = Math.floor(Math.random()*12)+2;
    b = Math.floor(Math.random()*12)+2;
    soal = `${a} × ${b}`;
    jawaban = a * b;
  }
  // Pilihan ganda
  let pilihan = [jawaban];
  while (pilihan.length < 4) {
    let salah = jawaban + Math.floor(Math.random()*11)-5;
    if (op === '-') salah = jawaban + Math.floor(Math.random()*7)-3;
    if (op === '×') salah = jawaban + Math.floor(Math.random()*7)-3;
    if (salah !== jawaban && !pilihan.includes(salah) && salah >= 0) pilihan.push(salah);
  }
  pilihan = shuffleArrayBerhitung(pilihan);
  return { soal, jawaban, pilihan };
}

function shuffleArrayBerhitung(arr) {
  for (let i = arr.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [arr[i], arr[j]] = [arr[j], arr[i]];
  }
  return arr;
}

function closeBerhitungGame() {
  const modal = document.getElementById('berhitung-game-modal');
  if (modal) modal.style.display = 'none';
}


// Game Puzzle - Menyusun Angka
let puzzleSize = 0, puzzleEmptyIndex = 0, puzzleData = [];

function openPuzzleGame() {
  document.getElementById('puzzle-game-modal').classList.remove('hidden');
  document.getElementById('puzzle-board').innerHTML = '';
  document.getElementById('puzzle-level-selection').style.display = 'grid';
}

function closePuzzleGame() {
  document.getElementById('puzzle-game-modal').classList.add('hidden');
  document.getElementById('puzzle-board').innerHTML = '';
  document.getElementById('puzzle-level-selection').style.display = 'grid';
}

function startPuzzleGame(level) {
  puzzleSize = level === 'easy' ? 3 : level === 'medium' ? 4 : 5;
  const board = document.getElementById('puzzle-board');
  document.getElementById('puzzle-level-selection').style.display = 'none';
  board.innerHTML = '';
  board.style.gridTemplateColumns = `repeat(${puzzleSize}, 60px)`;

  puzzleData = Array.from({ length: puzzleSize * puzzleSize }, (_, i) => i);
  shuffleArray(puzzleData);
  puzzleEmptyIndex = puzzleData.indexOf(0);

  puzzleData.forEach((num, i) => {
    const tile = document.createElement('div');
    tile.className = 'puzzle-tile';
    if (num !== 0) tile.textContent = num;
    tile.onclick = () => movePuzzleTile(i);
    board.appendChild(tile);
  });
}

function movePuzzleTile(index) {
  const validMoves = getValidPuzzleMoves(puzzleEmptyIndex);
  if (!validMoves.includes(index)) return;

  [puzzleData[puzzleEmptyIndex], puzzleData[index]] = [puzzleData[index], puzzleData[puzzleEmptyIndex]];
  puzzleEmptyIndex = index;
  renderPuzzleBoard();

  if (puzzleData.every((val, i) => val === (i === puzzleData.length - 1 ? 0 : i + 1))) {
    setTimeout(showPuzzleVictory, 300);
  }
}

function getValidPuzzleMoves(index) {
  const moves = [];
  const row = Math.floor(index / puzzleSize);
  const col = index % puzzleSize;
  if (row > 0) moves.push(index - puzzleSize);
  if (row < puzzleSize - 1) moves.push(index + puzzleSize);
  if (col > 0) moves.push(index - 1);
  if (col < puzzleSize - 1) moves.push(index + 1);
  return moves;
}

function renderPuzzleBoard() {
  const board = document.getElementById('puzzle-board');
  board.innerHTML = '';
  puzzleData.forEach((num, i) => {
    const tile = document.createElement('div');
    tile.className = 'puzzle-tile';
    if (num !== 0) tile.textContent = num;
    tile.onclick = () => movePuzzleTile(i);
    board.appendChild(tile);
  });
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
}

function shuffleArray(arr) {
  for (let i = arr.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [arr[i], arr[j]] = [arr[j], arr[i]];
  }
}

// Trigger buka modal puzzle
const puzzleBtn = Array.from(document.querySelectorAll('.game-btn')).find(btn => btn.textContent.includes('PUZZLE'));
if (puzzleBtn) puzzleBtn.addEventListener('click', openPuzzleGame);

// ================= JUMPING GAME ===================
// Data pertanyaan edukatif untuk game jumping telah dihapus karena mengganggu gameplay

// Variabel game jumping
let jumpingGameInterval;
let obstacleInterval;
let jumpingScore = 0;
let jumpingHighScore = 0;
let isJumping = false;
let gameRunning = false;
let obstacles = [];
let character;
let gameBoard;
let groundHeight;
let obstacleSpeed = 3; // Kecepatan awal dikurangi dari 5 menjadi 3
let obstacleFrequency = 3000; // ms - Interval kemunculan rintangan diperpanjang dari 2000ms menjadi 3000ms
let difficultyInterval;

// Fungsi untuk memuat skor tertinggi dari localStorage
function loadJumpingHighScore() {
  const savedHighScore = localStorage.getItem('jumpingHighScore');
  if (savedHighScore !== null) {
    jumpingHighScore = parseInt(savedHighScore);
    document.getElementById('jumping-highscore-display').textContent = 'Skor Tertinggi: ' + jumpingHighScore;
  }
}

// Fungsi untuk menyimpan skor tertinggi ke localStorage
function saveJumpingHighScore() {
  if (jumpingScore > jumpingHighScore) {
    jumpingHighScore = jumpingScore;
    localStorage.setItem('jumpingHighScore', jumpingHighScore);
    document.getElementById('jumping-highscore-display').textContent = 'Skor Tertinggi: ' + jumpingHighScore;
    return true; // Mengembalikan true jika ada rekor baru
  }
  return false; // Mengembalikan false jika tidak ada rekor baru
}

// Fungsi untuk membuka modal game jumping
function openJumpingGame() {
  const modal = document.getElementById('jumping-game-modal');
  if (!modal) return;
  modal.classList.remove('hidden');
  const instructions = document.getElementById('jumping-instructions');
  if (instructions) instructions.style.display = 'block';
  const gameBoard = document.getElementById('jumping-game-board');
  if (gameBoard) gameBoard.style.display = 'none';
  const gameOver = document.getElementById('jumping-game-over');
  if (gameOver) gameOver.style.display = 'none';
  const questionPopup = document.getElementById('jumping-question-popup');
  if (questionPopup) questionPopup.style.display = 'none';

  // Setup event listener untuk tombol mulai
  const startBtn = document.getElementById('jumping-start-btn');
  if (startBtn) {
    // Hapus event listener lama jika ada
    startBtn.onclick = null;
    startBtn.addEventListener('click', startJumpingGame);
    startBtn.disabled = false;
    startBtn.style.pointerEvents = 'auto';
  }
}

// Fungsi untuk menutup modal game jumping
function closeJumpingGame() {
  document.getElementById('jumping-game-modal').classList.add('hidden');
  stopJumpingGame();
}

// Fungsi untuk memulai game jumping
function startJumpingGame() {
  // Reset game state
  jumpingScore = 0;
  obstacles = [];
  isJumping = false;
  gameRunning = true;
  obstacleSpeed = 5; // Kecepatan awal dikurangi untuk memudahkan permainan
  obstacleFrequency = 2000; // ms - Interval kemunculan rintangan diperpanjang
  
  // Tampilkan game board dan sembunyikan instruksi
  document.getElementById('jumping-instructions').style.display = 'none';
  document.getElementById('jumping-game-board').style.display = 'block';
  document.getElementById('jumping-game-over').style.display = 'none';
  document.getElementById('jumping-score-display').textContent = 'Skor: 0';
  
  // Muat skor tertinggi dari localStorage
  loadJumpingHighScore();
  
  // Hapus semua rintangan yang ada
  const existingObstacles = document.querySelectorAll('.obstacle');
  existingObstacles.forEach(obs => obs.remove());
  
  // Setup karakter dan game board
  character = document.getElementById('jumping-character');
  gameBoard = document.getElementById('jumping-game-board');
  groundHeight = document.getElementById('jumping-ground').offsetHeight;
  
  // Buat fungsi handler untuk event listener
  window.jumpClickHandler = function() {
    if (gameRunning && !isJumping) {
      jump();
    }
  };
  
  window.jumpTouchHandler = function(e) {
    if (gameRunning && !isJumping) {
      e.preventDefault(); // Prevent scrolling
      jump();
    }
  };
  
  window.mobileBtnHandler = function(e) {
    e.stopPropagation(); // Mencegah event bubbling ke game board
    if (gameRunning && !isJumping) {
      jump();
    }
  };
  
  // Setup event listener untuk melompat dengan keyboard
  document.addEventListener('keydown', handleJump);
  
  // Setup event listener untuk melompat dengan klik/sentuh
  gameBoard.addEventListener('click', window.jumpClickHandler);
  
  // Setup event listener untuk melompat dengan sentuhan (mobile)
  gameBoard.addEventListener('touchstart', window.jumpTouchHandler, { passive: false });
  
  // Deteksi perangkat mobile
  const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
  const mobileBtn = document.getElementById('jumping-mobile-btn');
  
  // Tampilkan tombol mobile jika di perangkat mobile
  if (isMobile) {
    mobileBtn.style.display = 'block';
  }
  
  // Tambahkan event listener untuk tombol mobile
  mobileBtn.addEventListener('click', window.mobileBtnHandler);
  
  // Mulai interval game
  jumpingGameInterval = setInterval(updateJumpingGame, 20);
  
  // Interval untuk menambahkan rintangan
  obstacleInterval = setInterval(addObstacle, obstacleFrequency);
  
  
  // Interval untuk meningkatkan kesulitan
  difficultyInterval = setInterval(increaseDifficulty, 10000); // Tingkatkan kesulitan setiap 10 detik
}

// Fungsi untuk meningkatkan kesulitan game
function increaseDifficulty() {
  if (!gameRunning) return;
  
  // Tingkatkan kecepatan rintangan dengan lebih lambat
  obstacleSpeed += 0.3; // Dikurangi dari 0.5 menjadi 0.3
  
  // Kurangi interval kemunculan rintangan (lebih sering muncul) dengan lebih lambat
  if (obstacleFrequency > 1500) { // Minimal 1500ms (lebih lambat dari sebelumnya 800ms)
    obstacleFrequency -= 100; // Dikurangi dari 200 menjadi 100
    
    // Perbarui interval rintangan
    clearInterval(obstacleInterval);
    obstacleInterval = setInterval(addObstacle, obstacleFrequency);
  }
  
  // Pesan level up dihilangkan karena mengganggu penglihatan
}

// Fungsi untuk menghentikan game jumping
function stopJumpingGame() {
  gameRunning = false;
  clearInterval(jumpingGameInterval);
  clearInterval(obstacleInterval);
  clearInterval(difficultyInterval);
  
  // Hapus event listener
  document.removeEventListener('keydown', handleJump);
  
  // Hapus event listener untuk klik dan sentuh jika game board ada
  const gameBoard = document.getElementById('jumping-game-board');
  if (gameBoard && window.jumpClickHandler && window.jumpTouchHandler) {
    gameBoard.removeEventListener('click', window.jumpClickHandler);
    gameBoard.removeEventListener('touchstart', window.jumpTouchHandler);
  }
  
  // Hapus event listener dari tombol mobile
  const mobileBtn = document.getElementById('jumping-mobile-btn');
  if (mobileBtn && window.mobileBtnHandler) {
    mobileBtn.removeEventListener('click', window.mobileBtnHandler);
  }
}

// Fungsi untuk restart game jumping
function restartJumpingGame() {
  document.getElementById('jumping-game-over').style.display = 'none';
  startJumpingGame();
}

// Fungsi untuk menangani lompatan
function handleJump(event) {
  // Spasi atau panah atas untuk melompat
  if ((event.code === 'Space' || event.code === 'ArrowUp') && !isJumping && gameRunning) {
    jump();
  }
}

// Fungsi untuk melompat
function jump() {
  // Jika sedang melompat, kita izinkan lompatan kedua (double jump)
  if (isJumping) {
    // Jika ini adalah lompatan kedua, tambahkan kelas jumping-double
    character.classList.remove('jumping');
    character.classList.add('jumping-double');
    
    // Reset timer untuk lompatan kedua
    setTimeout(() => {
      character.classList.remove('jumping-double');
      isJumping = false;
    }, 800); // Waktu lompatan kedua lebih lama
    
    return;
  }
  
  isJumping = true;
  character.classList.add('jumping');
  
  // Setelah animasi selesai, hapus kelas jumping
  // Waktu lompatan diperpanjang dari 500ms menjadi 700ms
  setTimeout(() => {
    character.classList.remove('jumping');
    // Hanya atur isJumping ke false jika tidak dalam lompatan kedua
    if (!character.classList.contains('jumping-double')) {
      isJumping = false;
    }
  }, 700);
}

// Fungsi untuk menambahkan rintangan
function addObstacle() {
  if (!gameRunning) return;
  
  const obstacle = document.createElement('div');
  obstacle.className = 'obstacle';
  
  // Tambahkan jarak acak untuk rintangan (0-100px tambahan)
  // Ini membuat rintangan lebih tersebar dan lebih mudah dihindari
  const extraSpace = Math.floor(Math.random() * 100);
  obstacle.style.left = (gameBoard.offsetWidth + extraSpace) + 'px';
  
  // Acak ukuran rintangan (70-100% dari ukuran normal)
  // Ini membuat beberapa rintangan lebih kecil dan lebih mudah dihindari
  const scale = 0.7 + (Math.random() * 0.3);
  obstacle.style.transform = `scale(${scale})`;
  
  gameBoard.appendChild(obstacle);
  obstacles.push(obstacle);
}

// Fungsi untuk update game jumping
function updateJumpingGame() {
  if (!gameRunning) return;
  
  // Update posisi rintangan
  for (let i = 0; i < obstacles.length; i++) {
    const obstacle = obstacles[i];
    let obstacleLeft = parseInt(obstacle.style.left) - obstacleSpeed;
    
    // Jika rintangan keluar dari layar, hapus
    if (obstacleLeft < -30) {
      obstacle.remove();
      obstacles.splice(i, 1);
      i--;
      // Tambah skor ketika berhasil melewati rintangan
      jumpingScore += 10;
      document.getElementById('jumping-score-display').textContent = 'Skor: ' + jumpingScore;
      continue;
    }
    
    obstacle.style.left = obstacleLeft + 'px';
    
    // Deteksi tabrakan dengan rintangan
    if (checkCollision(character, obstacle)) {
      gameOver();
      return;
    }
  }
  }

// Fungsi untuk deteksi tabrakan
function checkCollision(element1, element2) {
  const rect1 = element1.getBoundingClientRect();
  const rect2 = element2.getBoundingClientRect();
  
  // Buat area tabrakan lebih kecil (80% dari ukuran asli)
  // Ini membuat permainan lebih mudah karena pemain memiliki lebih banyak ruang untuk menghindari rintangan
  const collisionMargin = 0.2; // 20% margin
  
  // Perkecil area tabrakan karakter
  const adjustedRect1 = {
    left: rect1.left + (rect1.width * collisionMargin),
    right: rect1.right - (rect1.width * collisionMargin),
    top: rect1.top + (rect1.height * collisionMargin),
    bottom: rect1.bottom - (rect1.height * collisionMargin)
  };
  
  // Perkecil area tabrakan rintangan
  const adjustedRect2 = {
    left: rect2.left + (rect2.width * collisionMargin),
    right: rect2.right - (rect2.width * collisionMargin),
    top: rect2.top,
    bottom: rect2.bottom - (rect2.height * collisionMargin / 2) // Kurangi sedikit di bagian bawah saja
  };
  
  return (
    adjustedRect1.left < adjustedRect2.right &&
    adjustedRect1.right > adjustedRect2.left &&
    adjustedRect1.top < adjustedRect2.bottom &&
    adjustedRect1.bottom > adjustedRect2.top
  );
}

// Fungsi untuk menampilkan pesan
function showMessage(message, color, duration = 2500) {
  const messageElement = document.createElement('div');
  messageElement.style.position = 'absolute';
  messageElement.style.top = '50%';
  messageElement.style.left = '50%';
  messageElement.style.transform = 'translate(-50%, -50%)';
  messageElement.style.background = color;
  messageElement.style.color = 'white';
  messageElement.style.padding = '12px 24px';
  messageElement.style.borderRadius = '8px';
  messageElement.style.fontWeight = 'bold';
  messageElement.style.fontSize = '18px';
  messageElement.style.zIndex = '1000';
  messageElement.style.boxShadow = '0 4px 8px rgba(0,0,0,0.3)';
  messageElement.style.textAlign = 'center';
  messageElement.style.maxWidth = '80%';
  messageElement.style.animation = 'fadeInOut 2.5s';
  messageElement.textContent = message;
  
  // Tambahkan animasi CSS jika belum ada
  if (!document.getElementById('message-animation')) {
    const style = document.createElement('style');
    style.id = 'message-animation';
    style.textContent = '@keyframes fadeInOut { 0% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); } 10% { opacity: 1; transform: translate(-50%, -50%) scale(1); } 80% { opacity: 1; } 100% { opacity: 0; } }';
    document.head.appendChild(style);
  }
  
  gameBoard.appendChild(messageElement);
  
  // Hapus pesan setelah durasi yang ditentukan
  setTimeout(() => {
    messageElement.remove();
  }, duration);
}

// Fungsi game over
function gameOver() {
  stopJumpingGame();
  
  // Simpan skor tertinggi
  const isNewHighScore = saveJumpingHighScore();
  
  // Tampilkan skor akhir
  document.getElementById('jumping-final-score').textContent = jumpingScore;
  
  // Tampilkan pesan rekor baru jika ada
  const gameOverTitle = document.querySelector('#jumping-game-over h3');
  if (isNewHighScore) {
    gameOverTitle.textContent = '🎉 REKOR BARU! 🎉';
    gameOverTitle.style.color = '#FFD700'; // Warna emas untuk rekor baru
    
    // Tambahkan efek animasi untuk rekor baru
    gameOverTitle.style.animation = 'pulse 1s infinite';
    if (!document.querySelector('#highscore-animation')) {
      const style = document.createElement('style');
      style.id = 'highscore-animation';
      style.textContent = '@keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }';
      document.head.appendChild(style);
    }
  } else {
    gameOverTitle.textContent = 'Game Over!';
    gameOverTitle.style.color = '#FF5252'; // Warna default
    gameOverTitle.style.animation = 'none';
  }
  
  // Berikan reward jika skor melebihi 100
  if (jumpingScore > 100) {
    // Reward untuk jumping game
    const levelGain = 1;
    const foodGain = 1;
    const happyGain = 10;
    
    // Berikan reward
    updatePetStats(levelGain, foodGain, happyGain);
    setTimeout(() => {
      showPlayfulPopup(`🎉 Hebat! Pet kamu mendapat +${levelGain} level, +${foodGain} makanan, dan +${happyGain}% kebahagiaan!`, 'success');
    }, 1000);
  }
  
  // Tampilkan layar game over
  document.getElementById('jumping-game-board').style.display = 'none';
  document.getElementById('jumping-game-over').style.display = 'block';
}

// Game Speed Type - Versi Perbaikan Lengkap (Tombol Level Bisa Diklik)


// ================= SPEED TYPE GAME BARU ===================
// Paragraf untuk setiap level
const speedTypeTexts = {
  easy: [
    "Aku suka makan nasi goreng di pagi hari.",
    "Kucing itu lucu dan suka bermain bola.",
    "Hari ini cuaca sangat cerah dan indah.",
    "Ayah pergi ke pasar membeli buah-buahan.",
    "Aku belajar menulis dan membaca setiap hari."
  ],
  medium: [
    "Setiap hari Minggu, keluarga kami selalu berkumpul di ruang tamu untuk sarapan bersama sambil menonton televisi.",
    "Burung-burung berkicau riang di pagi hari, membuat suasana rumah menjadi lebih hidup dan menyenangkan.",
    "Ibu memasak sup ayam hangat yang sangat lezat, aromanya memenuhi seluruh dapur dan membuatku lapar.",
    "Aku dan teman-teman bermain petak umpet di halaman sekolah saat jam istirahat tiba.",
    "Setelah belajar, aku membantu ibu menyiram tanaman di taman belakang rumah."
  ],
  hard: [
    "Pada suatu sore yang cerah, aku berjalan-jalan di taman kota sambil menikmati angin sepoi-sepoi dan melihat anak-anak bermain layang-layang berwarna-warni di langit biru yang luas.",
    "Ketika liburan tiba, keluargaku merencanakan perjalanan ke pegunungan, membawa perlengkapan lengkap dan makanan untuk berkemah selama dua hari di alam terbuka.",
    "Di perpustakaan sekolah, aku menemukan buku cerita petualangan yang sangat menarik, lalu membacanya dengan penuh semangat hingga lupa waktu pulang.",
    "Setiap malam sebelum tidur, nenek selalu menceritakan dongeng-dongeng indah yang membuatku bermimpi tentang negeri ajaib dan pahlawan pemberani.",
    "Saat hujan turun deras, aku duduk di dekat jendela sambil menikmati secangkir cokelat hangat dan mendengarkan suara rintik hujan yang menenangkan."
  ]
};

let stCurrentLevel = 'easy';
let stCurrentText = '';
let stScore = 0;
let stTimer = 30;
let stInterval = null;
let stStarted = false;

// Fungsi untuk membuka modal speed type
function openSpeedType() {
  document.getElementById('speedtype-modal').classList.remove('hidden');
  document.getElementById('speedtype-board').style.display = 'none';
  document.getElementById('st-level-selection').style.display = 'flex';
  document.getElementById('speedtype-input').value = '';
  document.getElementById('speedtype-input').disabled = true;
  stStarted = false;
}

function closeSpeedType() {
  document.getElementById('speedtype-modal').classList.add('hidden');
  resetSpeedType();
}

function resetSpeedType() {
  // Bersihkan interval timer
  if (stInterval) {
    clearInterval(stInterval);
    stInterval = null;
  }
  
  // Reset variabel game
  stStarted = false;
  stScore = 0;
  stTimer = 30;
  stCurrentLevel = 'easy';
  
  // Reset UI elements
  const scoreElement = document.getElementById('st-score');
  if (scoreElement) scoreElement.textContent = '0';
  
  const timerElement = document.getElementById('st-timer');
  if (timerElement) timerElement.textContent = '30';
  
  const inputField = document.getElementById('speedtype-input');
  if (inputField) {
    inputField.value = '';
    inputField.style.color = 'black';
    inputField.disabled = true;
  }
  
  const boardElement = document.getElementById('speedtype-board');
  if (boardElement) boardElement.style.display = 'none';
  
  const levelSelectionElement = document.getElementById('st-level-selection');
  if (levelSelectionElement) levelSelectionElement.style.display = 'flex';
  
  const startButton = document.getElementById('st-start-btn');
  if (startButton) {
    startButton.style.display = 'inline-block';
    startButton.disabled = false;
  }
  
  // Hapus popup hasil jika ada
  document.querySelectorAll('.speedtype-popup').forEach(popup => popup.remove());
}

// Pilih level

// Pastikan event listener tombol level diinisialisasi setelah DOM siap
function setupSpeedTypeLevelButtons() {
  document.querySelectorAll('.st-level-btn').forEach(btn => {
    btn.onclick = function() {
      stCurrentLevel = this.dataset.level;
      document.getElementById('st-level-selection').style.display = 'none';
      document.getElementById('speedtype-board').style.display = 'block';
      document.getElementById('speedtype-input').value = '';
      document.getElementById('speedtype-input').disabled = true;
      document.getElementById('st-score').textContent = '0';
      // Set timer sesuai level
      let timerSet = 30;
      if (stCurrentLevel === 'easy') timerSet = 10;
      else if (stCurrentLevel === 'medium') timerSet = 20;
      document.getElementById('st-timer').textContent = timerSet;
      stScore = 0;
      stTimer = timerSet;
      stStarted = false;
      tampilkanSpeedTypeText();
      // Pastikan tombol mulai muncul dan aktif
      document.getElementById('st-start-btn').style.display = 'inline-block';
      document.getElementById('st-start-btn').disabled = false;
    };
  });
}

// Inisialisasi event listener saat DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
  setupSpeedTypeLevelButtons();
  setupSpeedTypeInput();
  setupSpeedTypeStartBtn();
});

// Juga panggil setupSpeedTypeLevelButtons() setiap kali modal speed type dibuka (untuk jaga-jaga)
function openSpeedType() {
  // Reset game state
  resetSpeedType();
  
  // Tampilkan modal
  document.getElementById('speedtype-modal').classList.remove('hidden');
  document.getElementById('speedtype-board').style.display = 'none';
  document.getElementById('st-level-selection').style.display = 'flex';
  
  // Reset input field
  const inputField = document.getElementById('speedtype-input');
  if (inputField) {
    inputField.value = '';
    inputField.style.color = 'black';
    inputField.disabled = true;
  }
  
  // Setup event listeners
  setupSpeedTypeLevelButtons();
  setupSpeedTypeInput();
}

function tampilkanSpeedTypeText() {
  // Pilih random paragraf dari level
  const arr = speedTypeTexts[stCurrentLevel];
  stCurrentText = arr[Math.floor(Math.random()*arr.length)];
  document.getElementById('speedtype-text').textContent = stCurrentText;
  
  const inputField = document.getElementById('speedtype-input');
  if (inputField) {
    inputField.value = '';
    inputField.style.color = 'black'; // Reset warna teks
    
    // Jika game sudah dimulai, aktifkan input field
    if (stStarted) {
      inputField.disabled = false;
      inputField.focus();
    } else {
      inputField.disabled = true;
    }
  }
  
  document.getElementById('st-start-btn').style.display = 'inline-block';
  document.getElementById('st-start-btn').disabled = false;
  setupSpeedTypeStartBtn();
  
  // Pastikan event listener untuk input field sudah terpasang
  setupSpeedTypeInput();
}

// Fungsi untuk setup tombol mulai game
function setupSpeedTypeStartBtn() {
  const stStartBtn = document.getElementById('st-start-btn');
  if (stStartBtn) {
    // Hapus event listener lama jika ada
    if (stStartBtn.onclick) stStartBtn.onclick = null;
    
    stStartBtn.onclick = function() {
      if (stStarted) return;
      stStarted = true;
      
      const inputField = document.getElementById('speedtype-input');
      if (inputField) {
        inputField.disabled = false;
        inputField.value = '';
        inputField.style.color = 'black';
        inputField.focus();
      }
      
      // Pastikan event listener untuk input field sudah terpasang
      setupSpeedTypeInput();
      
      document.getElementById('st-start-btn').style.display = 'none';
      document.getElementById('st-start-btn').disabled = true;
      
      // Set timer sesuai level
      let timerSet = 30;
      if (stCurrentLevel === 'easy') timerSet = 10;
      else if (stCurrentLevel === 'medium') timerSet = 20;
      stTimer = timerSet;
      document.getElementById('st-timer').textContent = stTimer;
      
      // Bersihkan interval lama jika ada
      if (stInterval) clearInterval(stInterval);
      
      stInterval = setInterval(() => {
        stTimer--;
        document.getElementById('st-timer').textContent = stTimer;
        if (stTimer <= 0) {
          clearInterval(stInterval);
          const inputField = document.getElementById('speedtype-input');
          if (inputField) inputField.disabled = true;
          showSpeedTypeResult();
        }
      }, 1000);
    };
  }
}

// Cek input - Perbaikan validasi teks
document.addEventListener('DOMContentLoaded', function() {
  setupSpeedTypeInput();
});

function setupSpeedTypeInput() {
  const stInput = document.getElementById('speedtype-input');
  if (stInput) {
    // Hapus event listener lama jika ada
    if (stInput.oninput) stInput.oninput = null;
    if (stInput.onkeyup) stInput.onkeyup = null;
    
    // Tambahkan event listener baru
    stInput.addEventListener('input', function() {
      if (!stStarted) return;
      
      const inputText = this.value;
      const targetText = stCurrentText.substring(0, inputText.length);
      
      if (inputText === targetText) {
        // Teks benar (hijau)
        this.style.color = '#4CAF50';
        
        // Jika teks sudah lengkap dan benar
        if (inputText === stCurrentText) {
          stScore += 100; // Mengubah penambahan skor dari 1 menjadi 100
          document.getElementById('st-score').textContent = stScore;
          tampilkanSpeedTypeText();
          this.value = '';
          this.style.color = 'black';
          this.focus();
        }
      } else {
        // Teks salah (merah)
        this.style.color = '#F44336';
      }
    });
  }
}

function showSpeedTypeResult() {
  // Hapus popup lama jika ada
  document.querySelectorAll('.speedtype-popup').forEach(e => e.remove());
  
  // Buat pesan berdasarkan skor
  let msg = '';
  let levelGain = 0;
  let foodGain = 0;
  let happyGain = 0;
  let rewardEarned = false;
  
  if (stScore >= 500) {
    msg = `<div style='font-size:2rem;'>🎉 WAH KEREN! 🎉</div><div style='margin:1rem 0;font-size:1.2rem;'>Kamu berhasil mendapatkan skor <b>${stScore}</b> poin!<br>Jari-jarimu secepat kilat! ⚡️</div><button class="st-close-btn" style='background:#7ed957;color:white;padding:0.8rem 2rem;border:none;border-radius:12px;font-weight:bold;font-size:1rem;cursor:pointer;'>Tutup</button>`;
    rewardEarned = true;
  } else if (stScore >= 300) {
    msg = `<div style='font-size:1.5rem;'>👏 Bagus! 👏</div><div style='margin:1rem 0;font-size:1.1rem;'>Kamu mendapatkan skor <b>${stScore}</b> poin.<br>Latihan terus, pasti makin jago! 🚀</div><button class="st-close-btn" style='background:#7ed957;color:white;padding:0.8rem 2rem;border:none;border-radius:12px;font-weight:bold;font-size:1rem;cursor:pointer;'>Tutup</button>`;
    rewardEarned = true;
  } else {
    msg = `<div style='font-size:1.3rem;'>😅 Semangat Lagi! 😅</div><div style='margin:1rem 0;font-size:1.1rem;'>Kamu baru mendapatkan skor <b>${stScore}</b> poin.<br>Jangan menyerah, terus latihan ya! 💪</div><button class="st-close-btn" style='background:#7ed957;color:white;padding:0.8rem 2rem;border:none;border-radius:12px;font-weight:bold;font-size:1rem;cursor:pointer;'>Tutup</button>`;
  }
  
  // Berikan reward berdasarkan level jika skor cukup
  if (rewardEarned) {
    if (stCurrentLevel === 'easy') {
      levelGain = 1;
      foodGain = 1;
      happyGain = 10;
    } else if (stCurrentLevel === 'medium') {
      levelGain = 2;
      foodGain = 2;
      happyGain = 20;
    } else if (stCurrentLevel === 'hard') {
      levelGain = 3;
      foodGain = 3;
      happyGain = 30;
    }
    
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
        console.log("Berhasil menyimpan perubahan statistik pet dari Speed Type:", res);
        setTimeout(() => {
          showPlayfulPopup(`🎉 Hebat! Pet kamu mendapat +${levelGain} level, +${foodGain} makanan, dan +${happyGain}% kebahagiaan!`, 'success');
        }, 1000);
      } else {
        console.error("Gagal menyimpan perubahan statistik pet dari Speed Type");
      }
    }).catch(err => {
      console.error("Error saat menyimpan statistik pet dari Speed Type:", err);
    });
  }
  
  // Buat popup
  const popup = document.createElement('div');
  popup.className = 'speedtype-popup';
  popup.innerHTML = msg;
  document.body.appendChild(popup);
  
  // Tambahkan event listener untuk tombol tutup menggunakan delegasi event
  popup.addEventListener('click', function(e) {
    if (e.target.classList.contains('st-close-btn')) {
      // Tutup game dan hapus popup
      closeSpeedType();
      document.querySelectorAll('.speedtype-popup').forEach(p => p.remove());
    }
  });
  
  // Tambahkan event listener langsung ke tombol tutup (pendekatan kedua untuk memastikan)
  setTimeout(() => {
    const closeButtons = document.querySelectorAll('.st-close-btn');
    closeButtons.forEach(btn => {
      btn.onclick = function() {
        closeSpeedType();
        document.querySelectorAll('.speedtype-popup').forEach(p => p.remove());
      };
    });
  }, 100);
  
  // Tampilkan popup dengan animasi
  setTimeout(() => {
    popup.classList.add('show');
  }, 50);
}

// CSS untuk popup speed type
if (!document.getElementById('speedtype-popup-style')) {
  const style = document.createElement('style');
  style.id = 'speedtype-popup-style';
  style.innerHTML = `
  .speedtype-popup {
    position: fixed;
    top: 30%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.8);
    background: #fffbe7;
    color: #B83556;
    border: 3px solid #ffd9e0;
    border-radius: 24px;
    padding: 2rem 3rem;
    font-size: 1.3rem;
    font-weight: bold;
    z-index: 9999;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    opacity: 0;
    transition: all 0.3s;
    text-align: center;
    animation: popIn 0.2s;
  }
  .speedtype-popup.show {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
  }
  @keyframes popIn { from { opacity: 0; transform: translate(-50%, -50%) scale(0.7);} to { opacity: 1; transform: translate(-50%, -50%) scale(1);} }
  `;
  document.head.appendChild(style);
}





