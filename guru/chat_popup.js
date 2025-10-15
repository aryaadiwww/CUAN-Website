/*
  Chatbox popup untuk diskusi guru dengan orang tua siswa
  File ini hanya frontend, backend gunakan ipas_chat_api.php (seperti ortu_diskusi.php)
*/

const chatPopup = {
  popup: null,
  guruUsername: (typeof window.GURU_USERNAME !== 'undefined') ? window.GURU_USERNAME : '',
  ortuUsername: '',
  ortuName: '',
  messages: [],
  show(ortuUsername, ortuName) {
    this.ortuUsername = ortuUsername;
    this.ortuName = ortuName;
    if (!this.popup) this.createPopup();
    this.popup.style.display = 'flex';
    document.getElementById('chatbox-popup-title').textContent = `Diskusi dengan ${ortuName}`;
    this.loadMessages();
  },
  hide() {
    if (this.popup) this.popup.style.display = 'none';
  },
  createPopup() {
    this.popup = document.createElement('div');
    this.popup.className = 'chatbox-popup-overlay';
    this.popup.innerHTML = `
      <div class="chatbox-popup">
        <div class="chatbox-popup-header">
          <span id="chatbox-popup-title">Diskusi</span>
          <button class="chatbox-popup-close" onclick="chatPopup.hide()">&times;</button>
        </div>
        <div class="chatbox-popup-messages" id="chatbox-popup-messages"></div>
        <form class="chatbox-popup-form" id="chatbox-popup-form">
          <input type="text" id="chatbox-popup-input" class="chatbox-popup-input" placeholder="Ketik pesan..." autocomplete="off" />
          <button type="submit" class="chatbox-popup-send">Kirim</button>
        </form>
      </div>
    `;
    document.body.appendChild(this.popup);
    document.getElementById('chatbox-popup-form').onsubmit = (e) => {
      e.preventDefault();
      this.sendMessage();
    };
  },
  loadMessages() {
    fetch('../ipas_chat_api.php?target=' + encodeURIComponent(this.ortuUsername))
      .then(r => r.json())
      .then(data => {
        this.messages = data;
        this.renderMessages();
      });
  },
  renderMessages() {
    const box = document.getElementById('chatbox-popup-messages');
    box.innerHTML = '';
    if (!this.messages.length) {
      box.innerHTML = '<div class="chatbox-popup-empty">Belum ada pesan.</div>';
      return;
    }
    this.messages.forEach(msg => {
      const isMe = msg.from === this.guruUsername;
      const div = document.createElement('div');
      div.className = 'chatbox-popup-message' + (isMe ? ' me' : '');
      div.innerHTML = `<div class="chatbox-popup-meta">${isMe ? 'Anda' : this.ortuName}</div><div class="chatbox-popup-text">${msg.msg}</div>`;
      box.appendChild(div);
    });
    box.scrollTop = box.scrollHeight;
  },
  sendMessage() {
    const input = document.getElementById('chatbox-popup-input');
    const msg = input.value.trim();
    if (!msg) return;
    fetch('../ipas_chat_api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ msg, target: this.ortuUsername })
    }).then(() => {
      input.value = '';
      this.loadMessages();
    });
  }
};

// Periodik refresh jika popup terbuka
setInterval(() => {
  if (chatPopup.popup && chatPopup.popup.style.display === 'flex') chatPopup.loadMessages();
}, 5000);

// CSS inject
(function(){
  const style = document.createElement('style');
  style.innerHTML = `
  .chatbox-popup-overlay {
    position: fixed; z-index: 9999; left: 0; top: 0; width: 100vw; height: 100vh;
    background: rgba(0,0,0,0.25); display: none; align-items: center; justify-content: center;
  }
  .chatbox-popup {
    background: #fff; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    width: 95vw; max-width: 370px; min-width: 220px; min-height: 320px; display: flex; flex-direction: column;
    animation: fadeIn 0.3s;
  }
  .chatbox-popup-header {
    background: linear-gradient(135deg, #5e72e4 0%, #3b5998 100%); color: #fff;
    padding: 1rem 1.2rem; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center;
    font-weight: 600; font-size: 1.1rem;
  }
  .chatbox-popup-close {
    background: none; border: none; color: #fff; font-size: 1.5rem; cursor: pointer;
  }
  .chatbox-popup-messages {
    flex: 1; overflow-y: auto; padding: 1rem; background: #f1f3f5; font-size: 1rem;
  }
  .chatbox-popup-message { margin-bottom: 0.7rem; display: flex; flex-direction: column; align-items: flex-start; max-width: 70%; }
  .chatbox-popup-message.me { align-items: flex-end; align-self: flex-end; }
  .chatbox-popup-meta { font-size: 0.85rem; color: #6c757d; margin-bottom: 2px; font-weight: 600; }
  .chatbox-popup-meta.me { text-align: right; }
  .chatbox-popup-text { background: #f1f3f5; color: #333; padding: 0.6rem 1.1rem; border-radius: 14px 14px 14px 0; max-width: 100%; word-break: break-word; font-size: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
  .chatbox-popup-message.me .chatbox-popup-text { background: linear-gradient(135deg, #5e72e4 0%, #3b5998 100%); color: #fff; border-radius: 14px 14px 0 14px; box-shadow: 0 2px 8px rgba(94,114,228,0.3); }
  .chatbox-popup-form { display: flex; gap: 0.7rem; padding: 1rem; border-top: 1px solid #e9ecef; }
  .chatbox-popup-input { flex: 1; border-radius: 20px; border: 1px solid #ced4da; padding: 0.7rem 1rem; font-size: 1rem; background: #fff; color: #333; font-family: 'Poppins', sans-serif; outline: none; transition: border-color 0.3s; }
  .chatbox-popup-input:focus { border-color: #5e72e4; }
  .chatbox-popup-send { background: linear-gradient(135deg, #5e72e4 0%, #3b5998 100%); color: #fff; border: none; border-radius: 20px; padding: 0.7rem 1.3rem; font-size: 1rem; font-weight: 700; cursor: pointer; transition: opacity 0.3s; }
  .chatbox-popup-send:hover { opacity: 0.9; }
  .chatbox-popup-empty { padding: 20px; color: #6c757d; text-align: center; font-style: italic; }
  `;
  document.head.appendChild(style);
})();
