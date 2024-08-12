    document.addEventListener('DOMContentLoaded', function() {
      const chatIcon = document.getElementById('chat-icon');
          const chatBox = document.getElementById('chat-box');
          const chatMessages = document.getElementById('chat-messages');

          chatIcon.addEventListener('click', function(event) {
            event.stopPropagation(); // Hindari event dari bubbling
            if (chatBox.style.display === 'none' || chatBox.style.display === '') {
              chatBox.style.display = 'flex';
              // Gulir ke bawah ketika chat box dibuka
              chatMessages.scrollTop = chatMessages.scrollHeight;
            } else {
              chatBox.style.display = 'none';
            }
          });

          // Gulir ke bawah saat halaman dimuat pertama kali
          chatMessages.scrollTop = chatMessages.scrollHeight;

          // Event listener untuk menutup chat ketika klik di luar chat box
          document.addEventListener('click', function(event) {
            if (!chatBox.contains(event.target) && event.target !== chatIcon) {
              chatBox.style.display = 'none';
            }
          });
    });

    // Fungsi untuk mengirim pesan menggunakan Ajax
    function sendMessage() {
      const pesan = document.querySelector('textarea[name=pesan]').value.trim();
      
      // Buat objek XMLHttpRequest
      const xhr = new XMLHttpRequest();

      // Konfigurasi permintaan Ajax
      xhr.open('POST', 'sendMessage.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      // Tangani respons dari server
      xhr.onload = function() {
        if (xhr.status === 200) {
          // Pesan berhasil dikirim, tampilkan pesan baru tanpa perlu refresh
          const chatMessages = document.getElementById('chat-messages');
          const newMessage = xhr.responseText;
          chatMessages.insertAdjacentHTML('beforeend', newMessage);
          
          // Clear input pesan setelah dikirim
          document.querySelector('textarea[name=pesan]').value = '';
          
          // Auto scroll ke bawah untuk melihat pesan terbaru
          chatMessages.scrollTop = chatMessages.scrollHeight;
        } else {
          // Gagal mengirim pesan, tampilkan pesan kesalahan jika diperlukan
          console.error('Gagal mengirim pesan.');
        }
      };

      // Kirim data pesan ke server
      xhr.send('pesan=' + encodeURIComponent(pesan));
    }

    // Event listener untuk form pengiriman pesan
    document.querySelector('.chat-form').addEventListener('submit', function(event) {
      event.preventDefault(); // Hindari form dari submit default
      
      // Panggil fungsi untuk mengirim pesan
      sendMessage();
    });

    function loadMessages() {
      $.ajax({
        url: 'loadMessages.php',
        type: 'GET',
        success: function(newMessages) {
          let chatMessages = $('#chat-messages');
          
          // Simpan posisi scroll sebelum memuat pesan baru
          let currentScrollPosition = chatMessages.scrollTop();
          
          // Update konten chat dengan pesan baru
          chatMessages.html(newMessages);
          
          // Atur kembali posisi scroll
          chatMessages.scrollTop(currentScrollPosition);
        },
        error: function(xhr, status, error) {
          console.error('Gagal memuat pesan:', error);
        }
      });
    }
    
    // Panggil loadMessages secara berkala
    setInterval(loadMessages, 1000); // Memuat pesan setiap 5 detik    

    // Notifikasi (Tanda Seru)
    document.addEventListener('DOMContentLoaded', function() {
      const chatIcon = document.getElementById('chat-icon');
      let chatOpened = false; // Flag untuk menandai apakah chat sudah dibuka
  
      // Fungsi untuk memeriksa pesan baru dari admin
      function checkNewMessages() {
          const xhr = new XMLHttpRequest();
          xhr.open('GET', 'checkNewMessages.php', true);
          
          xhr.onload = function() {
              if (xhr.status === 200) {
                  let readStatus = parseInt(xhr.responseText);
                  
                  // Jika read_status_2 adalah 0 (belum dibaca) dan chat belum dibuka, tambahkan tanda seru
                  if (readStatus === 0 && !chatOpened) {
                      chatIcon.classList.add('has-new-messages');
                  } else {
                      chatIcon.classList.remove('has-new-messages');
                  }
              } else {
                  console.error('Gagal memeriksa pesan baru.');
              }
          };
        
          xhr.send();
      }
  
      // Fungsi untuk melakukan polling secara berkala
      function startPolling() {
          setInterval(checkNewMessages, 1000); // Memeriksa setiap 5 detik
      }
  
      // Panggil startPolling saat halaman dimuat
      startPolling();
  
      // Event listener untuk menandai chat sebagai dibuka
      chatIcon.addEventListener('click', function() {
          chatOpened = !chatOpened; // Toggle state chatOpened
          if (chatOpened) {
              chatIcon.classList.remove('has-new-messages'); // Hapus tanda seru saat chat dibuka
              markMessagesAsRead(); // Tandai pesan sebagai sudah dibaca (opsional)
          }
      });
  
      // Fungsi untuk menandai pesan sebagai telah dibaca (jika diperlukan)
      function markMessagesAsRead() {
          const xhr = new XMLHttpRequest();
          xhr.open('POST', 'markAsRead.php', true);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.send();
      }
  });
  
    
    feather.replace();