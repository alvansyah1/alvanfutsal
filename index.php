<?php
  session_start();
  require "functions.php";
  // require "session.php";
  // if ($role !== 'User') {
  //   header("location:../login.php");
  // };

  // $id_user = $_SESSION["id_user"];

  $lapangan = query("SELECT * FROM lapangan");
  $tanggal_sekarang = date("Y-m-d");
  $berita = query("SELECT * FROM event");

  // Cek status login pengguna
  $is_logged_in = isset($_SESSION['id_user']);

  // Periksa apakah $_SESSION["id_user"] telah diatur
  if (isset($_SESSION["id_user"])) {
    $id_user = $_SESSION["id_user"];
    $role = $_SESSION["role"];
    if ($role == "Admin") {
      header("Location: admin/beranda.php");
    }

    // Ambil data profil pengguna dari database
    $profil = query("SELECT * FROM user WHERE id_user = '$id_user'")[0];

    if (isset($_POST["simpan"])) {
        // Lakukan pengeditan profil jika formulir disubmit
        if (edit($_POST) > 0) {
            echo "<script>
                  alert('Berhasil Diubah');
                  </script>";
        } else {
            echo "<script>
                  alert('Gagal Diubah');
                  </script>";
        }
    }
  }

  ?>

  <!doctype html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BP Futsal</title>
    <link rel="icon" href="img/logo-bpfutsalcenter.png" type="image" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif&family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="tampilan.css">
  </head>
  <style>
  .card-img-top {
    width: 100%;
    height: 270px; /* Anda bisa mengatur tinggi sesuai kebutuhan */
    object-fit: cover; /* Ini memastikan gambar dipotong dengan proporsional yang benar */
  }
    .alert-message {
      display: none;
      margin-bottom: 20px;
      padding: 10px;
      border-radius: 5px;
      color: #ffffff;
    }
    .alert-success {
      background-color: #28a745;
    }
    .delete-message {
  background: none;
  border: none;
  color: red;
  cursor: pointer;
  font-size: 0.8rem;
}

.delete-message:hover {
  color: darkred;
}

    .alert-danger {
      background-color: #dc3545;
    }
  </style>
  <>
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
                <a class="nav-link active" aria-current="page" href="#beranda">Beranda</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="#about">Tentang</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="#bayar">Tata Cara</a>
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
              ';
              } else {
                echo '<li class="nav-item">
                <a class="nav-link" aria-current="page" href="#lapangan">Lapangan</a>
              </li>';
              }
              ?>
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="ulasan.php">Ulasan Pengguna</a>
              </li>
            </ul>
            <?php
            if (isset($_SESSION['id_user'])) {
              echo '<a href="" data-bs-toggle="modal" data-bs-target="#profilModal" class="btn btn-inti">'.$profil["nama_lengkap"].'</a>';
            } else {
              echo '<a href="login.php" class="btn btn-inti" type="submit">Login</a>';
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
                  <img src="img/<?= $profil["foto"]; ?>" alt="Foto Profil" class="img-fluid ">
                </div>
                <div class="col-8">
                  <h5 class="mb-3"><?= $profil["nama_lengkap"]; ?></h5>
                  <p><?= $profil["jenis_kelamin"]; ?></p>
                  <p><?= $profil["email"]; ?></p>
                  <p><?= $profil["no_handphone"]; ?></p>
                  <p><?= $profil["alamat"]; ?></p>
                  <a href="logout.php" class="btn btn-danger">Logout</a>
                  <a href="" data-bs-toggle="modal" data-bs-target="#editProfilModal" class="btn btn-success">Edit Profil</a>
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
                  <img src="img/<?= $profil["foto"]; ?>" alt="Foto Profil" class="img-fluid ">
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
                    <input type="number" name="no_handphone" class="form-control" id="exampleInputPassword1" value="<?= $profil["no_handphone"]; ?>">
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
              <button type="submit" class="btn btn-success" name="simpan" id="simpan">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- End Edit Modal -->

    <!-- Jumbotron -->
    <section class="jumbotron" id="beranda">
      <div class="overlay">
        <div class="container">
          <h1 class="display-4">Main futsalnya di <span>BP</span> Futsal aja</h1>
          <p class="lead">Kapan lagi main futsal disini? Yuk booking sekarang!</p>
          <a href="user/lapangan.php" class="btn btn-inti btn-lg">Booking Sekarang</a>
        </div>
      </div>
    </section>
    <!-- End Jumbotron -->

    
    <!-- About -->
    <section class="about" id="about">
      <h2 data-aos="fade-down" data-aos-duration="1000">
        <span>Tentang</span> Kami
      </h2>
      <div class="row">
        <div class="about-img" data-aos="fade-right" data-aos-duration="1000">
          <img src="img/lapangan-bpfutsal3.jpg" alt="" style="border-radius:10px"/>
        </div>
        <div class="contain" data-aos="fade-left" data-aos-duration="1000">
          <h4 class="text-center mb-3">Kenapa Memilih kami?</h4>
          <p class="about-text">
  BP Futsal Center adalah penyedia berbagai fasilitas dan layanan penyewaan lapangan futsal. Kami menawarkan lapangan futsal yang nyaman dan berkualitas tinggi untuk menyelenggarakan beragam turnamen futsal. Setiap lapangan dilengkapi dengan fasilitas yang sesuai, termasuk garis-garis permainan, jaring, sistem interlock, dan peralatan yang dibutuhkan untuk menjalankan aktivitas olahraga futsal dengan lancar.
</p>

<style>
  .about-text {
    text-align: justify; /* Menyelaraskan teks secara justify */
    font-size: 1.125rem; /* Ukuran font yang nyaman dibaca */
    color: #fff; /* Warna teks yang lebih lembut dari hitam */
    line-height: 1.8; /* Jarak antar baris untuk keterbacaan */
    margin: 0; /* Menghilangkan margin default */
    padding: 20px; /* Padding di sekitar teks */
    background: black; /* Latar belakang abu-abu terang untuk kontras lembut */
    border-left: 4px solid #198754; /* Garis aksen biru di sebelah kiri untuk kesan mewah */
    border-right: 4px solid #198754; /* Garis aksen biru di sebelah kiri untuk kesan mewah */
    
    border-radius: 8px; /* Sudut radius yang halus */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Bayangan lembut untuk efek kedalaman */
  }
</style>
 </div>
      </div>
    </section>
    <!-- End About -->

 <!-- Tata Cara -->
<section class="pembayaran" id="bayar">
  <h2 data-aos="fade-down" data-aos-duration="1000">
    <span>Tata Cara</span> Pemesanan
  </h2>
  <p class="text-center">Ikuti langkah-langkah berikut untuk memesan lapangan di website BP Futsal:</p>
  <div class="step-list">
    <div class="step">
      <div class="step-icon">1</div>
      <div class="step-content">Pengguna harus membuat akun atau mendaftar sebagai anggota pada website BP Futsal ini.</div>
    </div>
    <div class="step">
      <div class="step-icon">2</div>
      <div class="step-content">Pengguna dapat memilih jenis lapangan yang ingin dipesan, memilih tanggal dan waktu tertentu.</div>
    </div>
    <div class="step">
      <div class="step-icon">3</div>
      <div class="step-content">Pengguna harus memilih tanggal dan waktu, melihat harga sewa lapangan, mengisi jumlah jam atau durasi, melengkapi formulir pemesanan.</div>
    </div>
    <div class="step">
      <div class="step-icon">4</div>
      <div class="step-content">Bila dirasa sudah sesuai, pengguna dapat mengklik tombol pesan.</div>
    </div>
    <div class="step">
      <div class="step-icon">5</div>
      <div class="step-content">Pengguna akan diarahkan ke menu pembayaran.</div>
    </div>
    <div class="step">
      <div class="step-icon">6</div>
      <div class="step-content">Lakukan pembayaran ke rekening yang sudah tertera dan upload bukti pembayaran.</div>
    </div>
    <div class="step">
      <div class="step-icon">7</div>
      <div class="step-content">Setelah upload, tunggu admin menyetujui pembayaran Anda.</div>
    </div>
    <div class="step">
      <div class="step-icon">8</div>
      <div class="step-content">Setelah status disetujui, silakan datang ke BP Futsal sesuai jadwal yang dipesan.</div>
    </div>
  </div>
</section>
<!-- End Tata Cara -->

<style>
  .pembayaran {
    padding: 40px 20px;
    color: #fff;
  }
  
  .pembayaran h2 {
    font-size: 2rem;
    margin-bottom: 20px;
    text-align: center;
    color: #fff;
  }

  .pembayaran p {
    font-size: 1.1rem;
    margin-bottom: 30px;
    text-align: center;
    color: #fff;
  }

  .step-list {
    max-width: 800px;
    margin: 0 auto;
    padding: 0;
    list-style: none;
  }

  .step {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
    background-color: #333;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(255, 255, 255, 0.3);
    padding: 15px 20px;
    position: relative;
  }

  .step-icon {
    background-color: #198754;
    color: #fff;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    font-weight: bold;
    margin-right: 15px;
  }

  .step-content {
    font-size: 1rem;
    line-height: 1.5;
    color: #fff;
  }
</style>
<!-- end tata cara-->

    <!-- Lapangan -->
    <?php if (!isset($_SESSION['id_user'])) { ?>
      <section id="lapangan" class="lapangan" data-aos="fade-down" data-aos-duration="1000">
        <div class="container">
            <h2 class="text-head">Ketersediaan <span>Lapangan</span> di BP Futsal </h2>
            <p class="text-center m-5">
              Yuk cek ketersediaan lapangan dulu!
            </p>
            <div class="row row-cols-1 row-cols-md-3 justify-content-center">
              <?php foreach ($lapangan as $row) : ?>
                <div class="col">
                  <div class="card">
                    <img src="img/<?= $row["foto"]; ?>" alt="Gambar Lapangan" class="card-img-top">
                    <div class="card-body text-center">
                      <h5 class="card-title"><?= $row["nama"]; ?></h5>
                      <a href="user/jadwal_no_login.php?id=<?= $row["id_lapangan"]; ?>" type="button" class="btn btn-inti mt-4">Detail</a>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
        </div>
      </section>
    <?php } else { ?>
    <?php } ?>

    <!-- End Lapangan -->
    
 <!-- berita -->
<section id="lapangan" class="lapangan" data-aos="fade-down" data-aos-duration="1000">
  <div class="container">
    <h2 class="text-head">Berita <span>Kami</span></h2>
    <p class="text-center m-5">
      Yuk cek informasi terbaru!
    </p>
    <div class="row row-cols-1 row-cols-md-3 justify-content-left">
      <?php 
      // Menentukan jumlah berita per halaman
      $per_page = 3;

      // Menentukan halaman saat ini
      $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

      // Memulai indeks data berita yang akan ditampilkan
      $start = ($current_page - 1) * $per_page;
      
      // Mengambil data berita untuk halaman saat ini
      $berita_page = array_slice($berita, $start, $per_page);

      // Menampilkan data berita
      foreach ($berita_page as $infoberita) : 
      ?>
        <div class="col">
          <div class="card">
            <img src="img/<?= $infoberita["foto"]; ?>" alt="Gambar Berita" class="card-img-top">
            <div class="card-body text-center">
              <h5 class="card-title" style="font-weight: bold; color: white;"><?= $infoberita["nama"]; ?></h5>

              <a href="user/detail_berita.php?id=<?= $infoberita["id"]; ?>" type="button" class="btn btn-inti mt-4">Detail</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Tampilkan navigasi halaman -->
    <div class="row justify-content-center mt-4">
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          <?php 
          // Hitung jumlah halaman
          $total_pages = ceil(count($berita) / $per_page);

          // Tampilkan link ke setiap halaman
          for ($i = 1; $i <= $total_pages; $i++) {
            $active_class = ($i == $current_page) ? 'active' : '';
            echo '<li class="page-item ' . $active_class . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
          }
          ?>
        </ul>
      </nav>
    </div>
  </div>
</section>
<!-- end berita-->

 <!-- Saran -->
    <section class="saran" id="saran">
      <h2 data-aos="fade-down" data-aos-duration="1000">
        <span>Saran</span> Kepada Kami
      </h2>
      <p class="text-center m-5">Yuk berikan saran atau kritik kepada BP Futsal!</p>
      <div class="row">
        <div class="saran-img" data-aos="fade-right" data-aos-duration="1000">
          <img src="img/lapangan-bpfutsal.jpg" alt="" style="border-radius:10px;"/>
        </div>
        <div class="contain" data-aos="fade-left" data-aos-duration="1000">
        <div id="alertMessage" class="alert-message"></div>
          <form id="saranForm" action="submit_saran.php" method="post">
                  <div class="form-group">
                      <label for="saran">Saran Anda</label>
                      <textarea class="form-control mb-2 mt-2" rows="5" id="saran" name="saran" required></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary">Kirim Saran</button>
          </form>
        </div>
        <!-- Modal -->

      </div>
    </section>
    <!-- End Saran -->
    
    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <h5 style="color: black;">Tentang Kami</h5>
            <p style="text-align: justify;">Lapangan futsal standar FIFA dengan Interlock System. Dengan bangga, BP Futsal Center menawarkan lapangan futsal yang nyaman dan berkualitas tinggi untuk menyelenggarakan beragam turnamen futsal. Hubungi kami sekarang untuk detail lebih lanjut dan jadwalkan acara Anda bersama kami!</p>
          </div>
          <div class="col-md-4">
            <h5 style="color: black;">Kontak Kami</h5>
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
            <h5 style="color: black;">Lokasi Kami</h5>
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
                <!-- Tombol Hapus -->
  <button class="btn btn-danger btn-sm delete-message" data-id="<?php echo $message['id']; ?>">
    <i class="fa fa-trash"></i>
  </button>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGz1XNTxS4M3zfxrS5J+4lA6pPBEO6yR08nLP1jTwpJ6B" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-QY6srLvFdGqZ2ENV3TqsVnm1Lkp8yu6ANpZxC8CHibNEE+0QF6WpgTDk04GIyMNr" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script> -->
    <!-- <script src="user/chat.js"></script> -->
    <script>
        $(document).ready(function() {
            $('#saranForm').on('submit', function(event) {
                event.preventDefault(); // Mencegah form melakukan refresh halaman

                $.ajax({
                    url: 'submit_saran.php',
                    type: 'post',
                    data: $(this).serialize(),
                    success: function(response) {
                        let alertMessage = $('#alertMessage');
                        let data = JSON.parse(response);
                        if (data.success) {
                            alertMessage.removeClass('alert-danger').addClass('alert-success').text(data.message).show();
                            $('#saranForm')[0].reset(); // Mengosongkan form
                        } else {
                            alertMessage.removeClass('alert-success').addClass('alert-danger').text(data.message).show();
                        }

                        // Menghilangkan pesan setelah 5 detik
                        setTimeout(function() {
                            alertMessage.fadeOut('slow');
                        }, 2000); // 5000 ms = 5 detik
                    },
                    error: function() {
                        let alertMessage = $('#alertMessage');
                        alertMessage.removeClass('alert-success').addClass('alert-danger').text('Terjadi kesalahan.').show();

                        // Menghilangkan pesan setelah 5 detik
                        setTimeout(function() {
                            alertMessage.fadeOut('slow');
                        }, 2000); // 5000 ms = 5 detik
                    }
                });
            });
        });

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
          xhr.open('POST', 'user/sendMessage.php', true);
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
        $(document).ready(function() {
  // Event listener untuk tombol hapus pesan
  $(document).on('click', '.delete-message', function() {
    var messageId = $(this).data('id');
    
    if (confirm('Apakah Anda yakin ingin menghapus pesan ini?')) {
      $.ajax({
        url: 'user/deleteMessage.php',
        type: 'POST',
        data: { id: messageId },
        success: function(response) {
          if (response.success) {
            // Hapus pesan dari tampilan
            $('button[data-id="' + messageId + '"]').closest('.message').remove();
          } else {
            alert('Gagal menghapus pesan.');
          }
        },
        error: function() {
          alert('Terjadi kesalahan.');
        }
      });
    }
  });
});

        // Fungsi untuk memuat pesan chat
        function loadMessages() {
          const xhr = new XMLHttpRequest();
          
          xhr.open('GET', 'user/loadMessages.php', true);
          
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
                xhr.open('GET', 'user/checkNewMessages.php', true);
                
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
                xhr.open('POST', 'user/markAsRead.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send();
            }
        });
      feather.replace();
    </script>
  </body>

  </html>