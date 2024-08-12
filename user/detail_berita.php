<?php
session_start();
require "../functions.php";

$id_berita = $_GET["id"];

// Cek status login pengguna
$is_logged_in = isset($_SESSION['id_user']);

$berita = query("SELECT * FROM event WHERE id = '$id_berita'");

date_default_timezone_set('Asia/Jakarta');
$tanggal_sekarang = date("Y-m-d");


?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Lapangan</title>
  <link rel="icon" href="img/logo-bpfutsalcenter.png" type="image" />
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif&family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <script src="https://unpkg.com/feather-icons"></script>
  <link rel="stylesheet" href="../tampilan.css">
  <style>
    .table {
      background-color: #2d2d2d;
      color: #ffffff;
    }

    .table th,
    .table td {
      border: 1px solid #6c757d;
    }

    .table th {
      background-color: #198754;
      color: #ffffff;
    }

    .table tbody {
      color: #ffffff;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <div class="container">
    <nav class="navbar fixed-top navbar-expand-lg">
      <div class="container">
        <a class="navbar-brand" href="#beranda">
          BP FUTSAL
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
            <li class="nav-item ">
              <a class="nav-link" aria-current="page" href="../#beranda">Beranda</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="../#about">Tentang</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="../#bayar">Tata Cara</a>
            </li>
            <?php
            if (isset($_SESSION['id_user'])) {
              echo '
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="user/lapangan.php">Lapangan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="user/bayar.php">Pembayaran</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="user/chat.php">Chat</a>
            </li>
            ';
            } else {
              echo '<li class="nav-item">
              <a class="nav-link active" aria-current="page" href="../#lapangan">Lapangan</a>
            </li>';
            }
            ?>
          </ul>
          <?php
          if (isset($_SESSION['id_user'])) {
            echo '<a href="" data-bs-toggle="modal" data-bs-target="#profilModal" class="btn btn-inti">'.$profil["nama_lengkap"].'</a>';
          } else {
            echo '<a href="../login.php" class="btn btn-inti" type="submit">Login</a>';
          }
          ?>
        </div>
      </div>
    </nav>
  </div>
  <!-- End Navbar -->
  <!-- Modal Profil -->
  <div class="modal fade" id="profilModal" tabindex="-1" aria-labelledby="profilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="profilModalLabel">Profil Pengguna</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-4 my-5">
                <img src="../img/<?= $profil["foto"]; ?>" alt="Foto Profil" class="img-fluid ">
              </div>
              <div class="col-8">
                <h5 class="mb-3"><?= $profil["nama_lengkap"]; ?></h5>
                <p><?= $profil["jenis_kelamin"]; ?></p>
                <p><?= $profil["email"]; ?></p>
                <p><?= $profil["no_handphone"]; ?></p>
                <p><?= $profil["alamat"]; ?></p>
                <a href="../logout.php" class="btn btn-danger">Logout</a>
                <a href="" data-bs-toggle="modal" data-bs-target="#editProfilModal" class="btn btn-inti">Edit Profil</a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Modal Profil -->

  <!-- Edit profil -->
  <div class="modal fade" id="editProfilModal" tabindex="-1" aria-labelledby="editProfilModalLabel" aria-hidden="true">
    <div class="modal-dialog edit modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProfilModalLabel">Edit Profil</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="fotoLama" class="form-control" id="exampleInputPassword1" value="<?= $profil["foto"]; ?>">
          <div class="modal-body">
            <div class="row justify-content-center align-items-center">
              <div class="mb-3">
                <img src="../img/<?= $profil["foto"]; ?>" alt="Foto Profil" class="img-fluid ">
              </div>
              <div class="col">
                <div class="mb-3">
                  <label for="exampleInputPassword1" class="form-label">Nama Lengkap</label>
                  <input type="text" name="nama_lengkap" class="form-control" id="exampleInputPassword1" value="<?= $profil["nama_lengkap"]; ?>">
                </div>
                <div class="mb-3">
                  <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                  <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="Laki-laki" <?php if ($profil['jenis_kelamin'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php if ($profil['jenis_kelamin'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="mb-3">
                  <label for="exampleInputPassword1" class="form-label">No Telp</label>
                  <input type="number" name="hp" class="form-control" id="exampleInputPassword1" value="<?= $profil["no_handphone"]; ?>">
                </div>
                <div class="mb-3">
                  <label for="exampleInputPassword1" class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" id="exampleInputPassword1" value="<?= $profil["email"]; ?>">
                </div>
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">alamat</label>
                <input type="text" name="alamat" class="form-control" id="exampleInputPassword1" value="<?= $profil["alamat"]; ?>">
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Foto : </label>
                <input type="file" name="foto" class="form-control" id="exampleInputPassword1">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-inti" name="simpan" id="simpan">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End Edit Profil -->

  <!-- Detail --><?php foreach ($berita as $infoberita) : ?>
  <section id="detail_lapangan" class="about" style="padding-top:120px">
      <h2><span><?= $infoberita['nama'] ?></span> </h2>
      <img src="../img/<?= $infoberita["foto"]; ?>" alt="Gambar Berita" style="border-radius: 6px; width: 60%; height: 60%; display: block; margin: auto;">
    <!-- Deskripsi -->
      <div class="row mt-4">
        <div class="contain">
          <div class="about-img" data-aos="fade-right" data-aos-duration="1000">
            <h4 class="mb-3" style="color:#ff9800;">Deskripsi</h4>
            <p style="text-align:justify;"><?= $infoberita['keterangan'] ?></p>
          </div>
        </div>
      </div>
    <!-- End Deskripsi -->
    </section><?php endforeach; ?>
    <!-- End Detail -->

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <h5>Tentang Kami</h5>
          <p style="text-align: justify;">Lapangan futsal standar FIFA dengan Interlock System. Dengan bangga, BP Futsal Center menawarkan lapangan futsal yang nyaman dan berkualitas tinggi untuk menyelenggarakan beragam turnamen futsal. Hubungi kami sekarang untuk detail lebih lanjut dan jadwalkan acara Anda bersama kami!</p>
        </div>
        <div class="col-md-4">
          <h5>Kontak Kami</h5>
          <p><i class="fas fa-phone-alt"></i> +62 895 0955 9117</p>
          <!-- <p><i class="fas fa-envelope"></i> bpfutsal@gmail.com </p> -->
          <div class="social-icons">
            <!-- <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a> -->
            <a href="http://instagram.com/bpfutsalcenter"><i class="fab fa-instagram"></i></a>@BPFutsalCenter
            <!-- <a href="#"><i class="fab fa-linkedin-in"></i></a> -->
          </div>
        </div>
        <div class="col-md-4">
          <h5>Lokasi Kami</h5>
          <p style="text-align: justify;">JL Teluk Leok, No. A-4, Limbungan, Rumbai Pesisir, Rumbai, Limbungan, Kec. Rumbai Pesisir, Kota Pekanbaru, Riau 28266</p>
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3981.708757258611!2d101.45814391944176!3d0.5623415994066646!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5aced67ffbde1%3A0x16e202f523ea997c!2sJL%20Teluk%20Leok%2C%20No.%20A-4%2C%20Limbungan%2C%20Rumbai%20Pesisir%2C%20Rumbai%2C%20Limbungan%2C%20Kec.%20Rumbai%20Pesisir%2C%20Kota%20Pekanbaru%2C%20Riau%2028266!5e0!3m2!1sen!2sid!4v1625050610921!5m2!1sen!2sid" width="100%" height="150" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col text-center">
          <p>&copy; 2024 BP Futsal. All Rights Reserved.</p>
        </div>
      </div>
    </div>
  </footer>
  <!-- End Footer -->

  <!-- Icon Chat -->
  <div id="chat-icon">
      <i class="fa fa-comments"></i>
    </div>

    <!-- Chat Box -->
    <div id="chat-box" class="chat-box" style="display: none;">
      <div class="chat-messages" id="chat-messages">
        <?php if (isset($chat_messages)): ?>
          <?php foreach ($chat_messages as $message): ?>
            <div class="message <?php echo $message['admin'] === 'Admin' ? 'incoming' : 'outgoing'; ?>">
              <div class="text">
                <?php echo $message['pesan']; ?>
                <span class="timestamp"><?php echo substr($message['jam'], 0, 5); ?></span>
              </div>
            </div>
          <?php endforeach; ?>
          <?php else: ?>
            <div class="chat-messages">
              <div class="message">
            <div class="text">
              Chat Admin jika ada yang ingin ditanyakan.
          </div>
            </div>
          </div>
          <?php endif; ?>
      </div>
      <!-- Formulir untuk pengguna mengirim pesan -->
      <form method="post" class="chat-form">
        <div class="form-group">
          <textarea class="form-control" name="pesan" rows="1" placeholder="Ketik pesan..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Kirim</button>
      </form>
    </div>

    <!-- Modal Login | ditampilkan jika belum login tapi ingin mengirim chat -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="loginModalLabel">Login Diperlukan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            Anda perlu login terlebih dahulu untuk mengirim pesan.
          </div>
          <div class="modal-footer">
            <a href="login.php" class="btn btn-primary">Login</a>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>

  <script src="../bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGz1XNTxS4M3zfxrS5J+4lA6pPBEO6yR08nLP1jTwpJ6B" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-QY6srLvFdGqZ2ENV3TqsVnm1Lkp8yu6ANpZxC8CHibNEE+0QF6WpgTDk04GIyMNr" crossorigin="anonymous"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script> -->
  <!-- <script src="chat.js"></script> -->
  <script>
        document.addEventListener("DOMContentLoaded", function() {
          const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

          navLinks.forEach(link => {
            link.addEventListener('click', function() {
              // Hilangkan kelas 'active' dari semua link
              navLinks.forEach(link => link.classList.remove('active'));

              // Tambahkan kelas 'active' ke link yang diklik
              this.classList.add('active');
            });
          });
        
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

          // Event listener untuk form pengiriman pesan
          document.getElementById('chat-form').addEventListener('submit', function(event) {
            <?php if (!$is_logged_in): ?>
              event.preventDefault(); // Hindari form dari submit default
              $('#loginModal').modal('show'); // Tampilkan modal
            <?php else: ?>
              // Jika pengguna sudah login, kirim pesan seperti biasa
              sendMessage();
            <?php endif; ?>
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
          <?php if (!$is_logged_in): ?>
            event.preventDefault();
            $('#loginModal').modal('show');
          <?php endif; ?>
          
          event.preventDefault(); // Hindari form dari submit default
          
          // Panggil fungsi untuk mengirim pesan
          sendMessage();
        });

        // Fungsi untuk memuat pesan chat
        function loadMessages() {
          const xhr = new XMLHttpRequest();
          
          xhr.open('GET', 'loadMessages.php', true);
          
          xhr.onload = function() {
            if (xhr.status === 200) {
              let newMessages = xhr.responseText;
              let chatMessages = document.getElementById('chat-messages');
              
              // Simpan posisi scroll sebelum memuat pesan baru
              let currentScrollPosition = chatMessages.scrollTop;
              
              // Update konten chat dengan pesan baru
              chatMessages.innerHTML = newMessages;
              
              // Atur kembali posisi scroll
              chatMessages.scrollTop = currentScrollPosition;
            } else {
              console.error('Gagal memuat pesan.');
            }
          };
          
          xhr.send();
        }

        // Panggil loadMessages secara berkala
        setInterval(loadMessages, 0); // Memuat pesan terus menerus (0 detik)

        // Notifikasi (Tanda Seru)
        document.addEventListener('DOMContentLoaded', function() {
            const chatIcon = document.getElementById('chat-icon');
            let chatOpened = false; // Flag untuk menandai apakah chat sudah dibuka
            let isNewMessage = false; // Flag untuk menandai adanya pesan baru

            // Fungsi untuk memeriksa pesan baru dari admin
            function checkNewMessages() {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'checkNewMessages.php', true);
                
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        let readStatus = parseInt(xhr.responseText);
                        
                        // Jika read_status_2 adalah 0 (belum dibaca) dan chat belum dibuka
                        if (readStatus === 0 && !chatOpened) {
                            chatIcon.classList.add('has-new-messages');
                            isNewMessage = true; // Set flag pesan baru
                        } else {
                            chatIcon.classList.remove('has-new-messages');
                            isNewMessage = false; // Reset flag pesan baru
                        }
                    } else {
                        console.error('Gagal memeriksa pesan baru.');
                    }
                };
              
                xhr.send();
            }

            // Fungsi untuk melakukan polling secara berkala
            function startPolling() {
                setInterval(function() {
                    checkNewMessages();
                    
                    // Jika chat dibuka dan ada pesan baru, tandai sebagai dibaca
                    if (chatOpened && isNewMessage) {
                        markMessagesAsRead();
                        isNewMessage = false; // Reset flag pesan baru setelah ditandai
                    }
                }, 1000); // Memeriksa setiap 1 detik
            }

            // Panggil startPolling saat halaman dimuat
            startPolling();

            // Event listener untuk menandai chat sebagai dibuka
            chatIcon.addEventListener('click', function() {
                chatOpened = !chatOpened; // Toggle state chatOpened
                if (chatOpened) {
                    chatIcon.classList.remove('has-new-messages'); // Hapus tanda seru saat chat dibuka
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
    </script>
</body>

</html>