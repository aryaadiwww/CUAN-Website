<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'ortu') {
  header("Location: ../index.html");
  exit();
}

// Ambil data siswa dari session
$siswa_username = isset($_SESSION['siswa_username']) ? $_SESSION['siswa_username'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Diskusi dengan Guru - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous"/>
  <style>
    :root {
    --primary-color: #5e72e4;
    --secondary-color: #3b5998;
    --success-color: #1cc88a;
    --info-color: #36b9cc;
    --warning-color: #f6c23e;
    --danger-color: #e74a3b;
    --light-color: #f8f9fc;
    --dark-color: #5a5c69;
    --diskusi-gradient: linear-gradient(135deg, #5e72e4 0%, #3b5998 100%);
  }
    
    body {
      font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      background-color: #f8f9fc;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      color: #333;
    }
    
    .main-content {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1.5rem;
      background-color: #f8f9fc;
    }
    
    .title {
      color: #ffffffff;
      font-size: 1.8rem;
      margin: 1rem 0 1.5rem 0;
      font-weight: 700;
      text-align: center;
      width: 100%;
      display: block;
    }
    
    .diskusi-container {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      width: 100%;
      height: auto;
      min-height: 400px;
      max-width: 100vw;
    }
    
    .guru-list {
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
      margin-bottom: 2rem;
      justify-content: center;
      align-items: flex-start;
      width: 100%;
    }
    
    .guru-card {
      background: white;
      border-radius: 10px;
      padding: 1.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      width: calc(33.33% - 1rem);
      min-width: 280px;
      display: flex;
      flex-direction: column;
      align-items: center;
      cursor: pointer;
      transition: transform 0.3s, box-shadow 0.3s;
      animation: fadeIn 0.5s;
    }
    
    .guru-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 7px 14px rgba(0, 0, 0, 0.1);
    }
    
    .guru-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: var(--diskusi-gradient);
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: white;
      box-shadow: 0 4px 16px rgba(94, 114, 228, 0.3);
    }
    
    .guru-name {
      color: #333;
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      text-align: center;
    }
    
    .guru-subject {
      color: #6c757d;
      font-size: 0.9rem;
      margin-bottom: 1rem;
      text-align: center;
    }
    
    .guru-button {
      background: var(--diskusi-gradient);
      color: #fff;
      border: none;
      border-radius: 20px;
      padding: 0.5rem 1.2rem;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      transition: opacity 0.3s;
    }
    
    .guru-button:hover {
    opacity: 0.9;
  }
  
  .loading-guru {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px;
    color: #6c757d;
    text-align: center;
  }
  
  .loading-guru i {
    font-size: 2rem;
    margin-bottom: 10px;
    color: var(--primary-color);
  }
    
    .chatbox {
      background: white;
      border-radius: 10px;
      padding: 1.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      margin-bottom: 2rem;
      animation: fadeIn 0.5s;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      min-width: 0;
      min-height: 350px;
      max-width: 100vw;
    }
    
    .chatbox-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #e9ecef;
      background: var(--diskusi-gradient);
      color: white;
      padding: 15px 20px;
      margin: -1.5rem -1.5rem 1rem -1.5rem;
    }
    
    .chatbox-title {
      color: white;
      font-size: 1.3rem;
      font-weight: 600;
    }
    
    .chatbox-panel {
      min-height: 220px;
      display: flex;
      flex-direction: column;
      height: 100%;
      max-height: 60vh;
    }
    
    .chatbox-messages {
      flex: 1;
      overflow-y: auto;
      max-height: 40vh;
      min-height: 120px;
      padding: 1rem 1.2rem 0.5rem 1.2rem;
      margin-bottom: 0.5rem;
      background: #f1f3f5;
      border-radius: 12px;
      font-size: 1rem;
    }
    
    .chatbox-message {
      margin-bottom: 0.7rem;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      animation: fadeIn 0.4s;
      max-width: 70%;
    }
    
    .chatbox-message.me {
      align-items: flex-end;
      align-self: flex-end;
    }
    
    .chatbox-message .chatbox-meta {
      font-size: 0.85rem;
      color: #6c757d;
      margin-bottom: 2px;
      font-weight: 600;
    }
    
    .chatbox-message .chatbox-meta.me {
      text-align: right;
    }
    
    .chatbox-message .chatbox-text {
      background: #f1f3f5;
      color: #333;
      padding: 0.6rem 1.1rem;
      border-radius: 14px 14px 14px 0;
      max-width: 100%;
      word-break: break-word;
      font-size: 1rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .chatbox-message.me .chatbox-text {
      background: var(--diskusi-gradient);
      color: #fff;
      border-radius: 14px 14px 0 14px;
      box-shadow: 0 2px 8px rgba(94, 114, 228, 0.3);
    }
    
    .chatbox-form {
      display: flex;
      gap: 0.7rem;
      margin-top: 0.5rem;
      padding: 15px;
      border-top: 1px solid #e9ecef;
    }
    
    .chatbox-input {
      flex: 1;
      border-radius: 20px;
      border: 1px solid #ced4da;
      padding: 0.7rem 1rem;
      font-size: 1rem;
      background: #fff;
      color: #333;
      font-family: 'Poppins', sans-serif;
      outline: none;
      transition: border-color 0.3s;
    }
    
    .chatbox-input:focus {
      border-color: #5e72e4;
    }
    
    .chatbox-send {
      background: var(--diskusi-gradient);
      color: #fff;
      border: none;
      border-radius: 20px;
      padding: 0.7rem 1.3rem;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      transition: opacity 0.3s;
    }
    
    .chatbox-send:hover {
      opacity: 0.9;
    }
    
  .chatbox-empty {
    padding: 20px;
    color: #6c757d;
    text-align: center;
    font-style: italic;
  }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    @media screen and (max-width: 900px) {
      .guru-card {
        width: calc(50% - 1rem);
      }
      .diskusi-container {
        min-height: 300px;
      }
      .chatbox {
        padding: 1rem;
      }
    }
    
    @media screen and (max-width: 600px) {
      .guru-card {
        width: 100%;
      }
      .main-content {
        padding: 0.5rem;
      }
      .diskusi-container {
        min-height: 200px;
        gap: 1rem;
      }
      .chatbox {
        padding: 0.5rem;
        min-height: 200px;
        font-size: 0.95rem;
      }
      .chatbox-panel {
        min-height: 120px;
        max-height: 40vh;
      }
      .chatbox-messages {
        max-height: 25vh;
        min-height: 60px;
        font-size: 0.95rem;
      }
    }
  </style>
</head>
<body>
  <?php include 'ortu_navbar.php'; ?>
  
  <div class="main-content">
    <h1 class="title">Diskusi dengan Guru</h1>
    
    <div class="diskusi-container">
      <div class="guru-list" id="guru-list">
        <!-- Daftar guru akan dimuat secara dinamis dari API -->
        <div class="loading-guru">
          <i class="fas fa-spinner fa-spin"></i>
          <p>Memuat daftar guru...</p>
        </div>
      </div>
      
      <div id="chatbox" class="chatbox" style="display: none;">
        <div class="chatbox-header">
          <div class="chatbox-title" id="chatbox-title">Diskusi dengan Guru</div>
          <button onclick="hideChat()" style="background: none; border: none; color: #fff; cursor: pointer; font-size: 1.2rem;">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="chatbox-panel">
          <div class="chatbox-messages" id="chatbox-messages"></div>
          <form class="chatbox-form" id="chatbox-form">
            <input type="text" class="chatbox-input" id="chatbox-input" placeholder="Ketik pesan..." autocomplete="off">
            <button type="submit" class="chatbox-send">Kirim</button>
          </form>
        </div>
      </div>
    </div>

  </div>

  <script>
    // Data guru akan diambil dari API
    let guruData = [];
    let currentGuru = null;
    let messages = [];
    const myUsername = '<?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : ""; ?>';
    const siswaUsername = '<?php echo isset($_SESSION["siswa_username"]) ? $_SESSION["siswa_username"] : ""; ?>';
    
    // Fungsi untuk memuat daftar guru dari API
    function loadGuruList() {
      fetch('../api/daftar_guru_api.php')
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            guruData = data.data;
            renderGuruList();
          } else {
            console.error('Error loading guru data:', data.message);
            document.getElementById('guru-list').innerHTML = `
              <div class="loading-guru">
                <i class="fas fa-exclamation-circle"></i>
                <p>Gagal memuat daftar guru. Silakan coba lagi nanti.</p>
              </div>
            `;
          }
        })
        .catch(error => {
          console.error('Error fetching guru data:', error);
          document.getElementById('guru-list').innerHTML = `
            <div class="loading-guru">
              <i class="fas fa-exclamation-circle"></i>
              <p>Gagal memuat daftar guru. Silakan coba lagi nanti.</p>
            </div>
          `;
        });
    }
    
    // Fungsi untuk menampilkan daftar guru
    function renderGuruList() {
      const guruListElement = document.getElementById('guru-list');
      guruListElement.innerHTML = '';
      
      if (guruData.length === 0) {
        guruListElement.innerHTML = `
          <div class="loading-guru">
            <i class="fas fa-info-circle"></i>
            <p>Tidak ada guru yang tersedia saat ini.</p>
          </div>
        `;
        return;
      }
      
      guruData.forEach(guru => {
        // Tentukan mata pelajaran berdasarkan username atau gunakan default
        let subject = 'Guru';
        if (guru.username.includes('matematika') || guru.username.includes('math')) {
          subject = 'Matematika';
        } else if (guru.username.includes('ipas') || guru.username.includes('ipa')) {
          subject = 'IPAS';
        } else if (guru.username.includes('bahasa') || guru.username.includes('indo')) {
          subject = 'Bahasa Indonesia';
        }
        
        const guruCard = document.createElement('div');
        guruCard.className = 'guru-card';
        guruCard.onclick = () => showChat(guru.username);
        
        guruCard.innerHTML = `
          <div class="guru-avatar">
            <i class="fas fa-user"></i>
          </div>
          <div class="guru-name">${guru.nama}</div>
          <div class="guru-subject"> ${subject}</div>
          <button class="guru-button">Mulai Diskusi</button>
        `;
        
        guruListElement.appendChild(guruCard);
      });
    }
    
    function showChat(guruUsername) {
      // Cari guru berdasarkan username
      const guru = guruData.find(g => g.username === guruUsername);
      if (!guru) return;
      
      currentGuru = {
        username: guru.username,
        name: guru.nama,
        subject: 'Guru' // Default, bisa ditambahkan logika untuk menentukan mata pelajaran
      };
      
      document.getElementById('chatbox').style.display = 'block';
      document.getElementById('chatbox-title').textContent = `Diskusi dengan ${currentGuru.name}`;
      loadMessages();
    }
    
    function hideChat() {
      document.getElementById('chatbox').style.display = 'none';
      currentGuru = null;
    }
    
    function loadMessages() {
      if (!currentGuru) return;
      
      fetch('../ipas_chat_api.php?target=' + encodeURIComponent(currentGuru.username))
        .then(response => response.json())
        .then(data => {
          messages = data;
          renderMessages();
        })
        .catch(error => {
          console.error('Error loading messages:', error);
        });
    }
    
    function renderMessages() {
      const messagesContainer = document.getElementById('chatbox-messages');
      messagesContainer.innerHTML = '';
      
      if (messages.length === 0) {
        messagesContainer.innerHTML = '<div class="chatbox-empty">Belum ada pesan. Mulai diskusi sekarang!</div>';
        return;
      }
      
      messages.forEach(msg => {
        const isMe = msg.from === myUsername;
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbox-message ${isMe ? 'me' : ''}`;
        
        const metaDiv = document.createElement('div');
        metaDiv.className = `chatbox-meta ${isMe ? 'me' : ''}`;
        metaDiv.textContent = isMe ? 'Anda' : currentGuru.name;
        
        const textDiv = document.createElement('div');
        textDiv.className = 'chatbox-text';
        textDiv.textContent = msg.msg;
        
        messageDiv.appendChild(metaDiv);
        messageDiv.appendChild(textDiv);
        messagesContainer.appendChild(messageDiv);
      });
      
      // Scroll to bottom
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    // Setup form submission
    document.getElementById('chatbox-form').addEventListener('submit', function(e) {
      e.preventDefault();
      if (!currentGuru) return;
      
      const input = document.getElementById('chatbox-input');
      const message = input.value.trim();
      
      if (!message) return;
      
      fetch('../ipas_chat_api.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          msg: message,
          target: currentGuru.username
        })
      })
      .then(() => {
        input.value = '';
        loadMessages();
      })
      .catch(error => {
        console.error('Error sending message:', error);
      });
    });
    
    // Refresh messages periodically
    setInterval(() => {
      if (currentGuru) {
        loadMessages();
      }
    }, 5000);
    
    // Load guru list when page loads
    document.addEventListener('DOMContentLoaded', function() {
      loadGuruList();
    });
  </script>
</body>
</html>
