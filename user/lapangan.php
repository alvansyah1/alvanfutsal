<?php
session_start();
require "../functions.php";
require "../session.php";
if ($role !== 'User') {
  header("location:../login.php");
}

$id = $_SESSION["id_user"];

$lapangan = query("SELECT * FROM lapangan");
$profil = query("SELECT * FROM user WHERE id_user = '$id'")[0];

date_default_timezone_set('Asia/Jakarta');
$tanggal_sekarang = date("Y-m-d");

if (isset($_POST["simpan"])) {
  if (edit($_POST) > 0) {
    echo "<script>
          alert('Berhasil diubah!');
          </script>";
  } else {
    echo "<script>
          alert('Gagal diubah!');
          </script>";
  }
}


if (isset($_POST["pesan"])) {
  if (pesan($_POST) > 0) {
    echo "<script>
          alert('Berhasil dipesan!');
          document.location.href = 'bayar.php';
          </script>";
  } else {
    echo "<script>
          alert('Gagal dipesan!');
          </script>";
  }
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Lapangan | BP Futsal</title>
  <link rel="icon" href="img/logo-bpfutsalcenter.png" type="image" />
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif&family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <script src="https://unpkg.com/feather-icons"></script>
  <link rel="stylesheet" href="../tampilan.css">
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
              <a class="nav-link active" aria-current="page" href="lapangan.php">Lapangan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="bayar.php">Pembayaran</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="../ulasan.php">Ulasan Pengguna</a>
            </li>
            ';
            } else {
              echo '<li class="nav-item">
              <a class="nav-link" aria-current="page" href="#lapangan">Lapangan</a>
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
                <a href="" data-bs-toggle="modal" data-bs-target="#editProfilModal" class="btn btn-primary">Edit Profil</a>
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
                <label for="exampleInputPassword1" class="form-label">Alamat</label>
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
            <button type="submit" class="btn btn-primary" name="simpan" id="simpan">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End Edit Modal -->

  <section class="lapangan" id="lapangan">
    <div class="container">
      <main class="contain" data-aos="fade-right" data-aos-duration="1000">
        <h2 class="text-head">Lapangan di <span>BP</span> Futsal </h2>
        <div class="row row-cols-1 row-cols-md-3 justify-content-center">
          <?php foreach ($lapangan as $row) : ?>
            <div class="col">
              <div class="card">
                <img src="../img/<?= $row["foto"]; ?>" alt="gambar lapangan" class="card-img-top">
                <div class="card-body text-center">
                  <h5 class="card-title mb-4"><?= $row["nama"]; ?></h5>
                  <p class="card-price">08:00-12:00 : Rp. <?= $row["harga1"]; ?></p>
                  <p class="card-price">13:00-18:00 : Rp. <?= $row["harga2"]; ?></p>
                  <p class="card-price">19:00-23:00 : Rp. <?= $row["harga3"]; ?></p>
                  <a href="jadwal.php?id=<?= $row["id_lapangan"]; ?>" type="button" class="btn btn-non-inti">Jadwal</a>
                  <button type="button" class="btn btn-inti" data-bs-toggle="modal" data-bs-target="#pesanModal<?= $row["id_lapangan"]; ?>">Pesan</button>
                </div>
              </div>
            </div>

            <!-- Modal Pesan -->
            <div class="modal fade" id="pesanModal<?= $row["id_lapangan"]; ?>" tabindex="-1" aria-labelledby="pesanModalLabel<?= $row["id_lapangan"]; ?>" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="pesanModalLabel<?= $row["id_lapangan"]; ?>">Pesan Lapangan <?= $row["nama"]; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="" method="post" id="pesanForm">
                    <div class="modal-body">
                      <!-- konten form modal -->
                      <div class="row justify-content-center align-items-center">
                        <div class="mb-3">
                          <img src="../img/<?= $row["foto"]; ?>" alt="gambar lapangan" class="img-fluid">
                        </div>
                        <div class="text-center">
                          <h6 name="harga_label">Harga : -</h6>
                        </div>
                        <div class="col">
                          <input type="hidden" name="id_lpg" class="form-control" id="exampleInputPassword1" value="<?= $row["id_lapangan"]; ?>">
                          <input type="hidden" name="tgl_pesan" value="<?= $tanggal_sekarang ?>">
                          <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Tanggal Main</label>
                            <input type="datetime-local" name="tgl_main" class="form-control" id="tgl_main_<?= $row["id_lapangan"]; ?>">
                          </div>
                        </div>
                        <div class="col">
                          <input type="hidden" name="harga" class="form-control" id="exampleInputPassword1">
                          <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Lama Main (Jam)</label>
                            <input type="number" name="lama_main" class="form-control" id="lama_main_<?= $row["id_lapangan"]; ?>" min="1" step="1">
                        </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      <button type="button" class="btn btn-status" id="cekKetersediaan" data-modal-id="<?= $row["id_lapangan"]; ?>">Periksa Status Lapangan</button>
                      <button type="submit" class="btn btn-inti" name="pesan" id="pesan_<?= $row["id_lapangan"]; ?>" disabled>Pesan</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- End Modal Pesan -->
          <?php endforeach; ?>
        </div>
      </main>
    </div>
  </section>

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
          </div>
        <?php endforeach; ?>
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

  <script src="../bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGz1XNTxS4M3zfxrS5J+4lA6pPBEO6yR08nLP1jTwpJ6B" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-QY6srLvFdGqZ2ENV3TqsVnm1Lkp8yu6ANpZxC8CHibNEE+0QF6WpgTDk04GIyMNr" crossorigin="anonymous"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script> -->
  <script src="chat.js"></script>
  <script>
document.addEventListener("DOMContentLoaded", function() {
    feather.replace();

    // Event listener untuk tombol "Cek Ketersediaan"
    document.addEventListener('click', function(event) {
        if (event.target && event.target.id === 'cekKetersediaan') {
            var modalId = event.target.dataset.modalId;
            var tgl_main = document.getElementById('tgl_main_' + modalId).value;
            var lama_main = document.getElementById('lama_main_' + modalId).value;

            // Periksa apakah tgl_main dan lama_main telah diisi
            if (tgl_main === "" && lama_main === "") {
                alert("Lengkapi Data: Tanggal Main & Lama Main");
                return;
            } else if (tgl_main === "") {
                alert("Lengkapi Data: Tanggal Main");
                return;
            } else if (lama_main === "") {
                alert("Lengkapi Data: Lama Main");
                return;
            }

            var tglMain = new Date(tgl_main);
            var jamMulai = tglMain.getHours();
            var menitMulai = tglMain.getMinutes();
            var lamaMain = parseInt(lama_main);

            // Hitung jam selesai
            var jamSelesai = jamMulai + lamaMain;
            var menitSelesai = menitMulai; // Jika perlu hitung menit juga

            // Periksa apakah jam mulai berada dalam rentang 23:00 - 08:00
            if (jamMulai >= 23 || jamMulai < 8) {
                document.getElementById('pesan_' + modalId).disabled = true;
                alert("Lakukan pemesanan dalam rentang waktu buka [08:00-23:00].");
                return;
            }

            // Periksa apakah jam selesai melewati tengah malam
            if (jamSelesai >= 24) {
                document.getElementById('pesan_' + modalId).disabled = true;
                alert("Lapangan tidak tersedia karena waktu selesai melebihi jam 00:00.");
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var status = xhr.responseText;
                        if (status === "Lapangan Tidak Tersedia") {
                            // Jika lapangan tidak tersedia, nonaktifkan tombol "Pesan"
                            document.getElementById('pesan_' + modalId).disabled = true;
                            alert(status);
                        } else {
                            // Jika lapangan tersedia, aktifkan tombol "Pesan"
                            document.getElementById('pesan_' + modalId).disabled = false;
                            alert(status);
                        }
                    } else {
                        alert('Ada kesalahan dalam permintaan.');
                    }
                }
            };
            xhr.open("GET", "get_jam_mulai.php?tgl_main=" + tgl_main + "&lama_main=" + lama_main, true);
            xhr.send();
        }
    });
});


    // Fungsi untuk mengupdate harga berdasarkan waktu yang dipilih
   function updateHarga(input) {
       const idLapangan = input.id.split('_')[2]; // Ambil id_lapangan dari id input
       const hargaElement = document.querySelector(`#pesanModal${idLapangan} h6[name=harga_label]`); // Ambil elemen harga yang akan ditampilkan
       const hiddenHargaInput = document.querySelector(`#pesanModal${idLapangan} input[name=harga]`); // Ambil input hidden untuk harga

       const tglMain = new Date(input.value); // Ambil tanggal yang dipilih
       const jam = tglMain.getHours(); // Ambil jam dari tanggal yang dipilih

       let harga = ''; // Variabel untuk menyimpan harga yang akan ditampilkan

       // Tentukan harga berdasarkan rentang jam
       if (jam >= 8 && jam < 13) {
           harga = <?= $row["harga1"]; ?>;
       } else if (jam >= 13 && jam < 19) {
           harga = <?= $row["harga2"]; ?>;
       } else if (jam >= 19 && jam <= 23) {
           harga = <?= $row["harga3"]; ?>;
       }

       // Tampilkan harga yang sesuai
       hargaElement.textContent = `Harga : Rp. ${harga}`;
       // Update nilai input hidden dengan harga yang sesuai
       hiddenHargaInput.value = harga;
   }

   // Ambil semua elemen input datetime-local
   const inputTanggal = document.querySelectorAll('input[type=datetime-local]');

   // Tambahkan event listener untuk setiap input datetime-local
   inputTanggal.forEach(input => {
       input.addEventListener('change', function() {
           updateHarga(this);
       });
   });
  </script>
</body>
</html>