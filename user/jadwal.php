<?php
session_start();
require "../functions.php";
require "../session.php";
if ($role !== 'User') {
  header("location:../login.php");
};

$id_user = $_SESSION["id_user"];
$id_lpg = $_GET["id"];

$sewa = query("SELECT sewa.*, lapangan.nama, user.nama_lengkap
                FROM sewa
                JOIN lapangan ON sewa.id_lapangan = lapangan.id_lapangan
                LEFT JOIN user ON sewa.id_user = user.id_user
                WHERE lapangan.id_lapangan = '$id_lpg'
                ");

$lapangan = query("SELECT * FROM lapangan WHERE id_lapangan = '$id_lpg'")[0];

date_default_timezone_set('Asia/Jakarta');
$tanggal_sekarang = date("Y-m-d");

$query = "SELECT sewa.*, lapangan.nama, user.nama_lengkap FROM sewa JOIN lapangan ON sewa.id_lapangan = lapangan.id_lapangan
LEFT JOIN user ON sewa.id_user = user.id_user";

$profil = query("SELECT * FROM user WHERE id_user = '$id_user'")[0];

if (isset($_POST["simpan"])) {
  if (edit($_POST) > 0) {
    echo "<script>
          alert('Profil berhasil diperbarui!');
          document.location.href = 'jadwal.php?id=$id_lpg';
          </script>";
  } else {
    echo "<script>
          alert('Gagal memperbarui profil!');
          </script>";
  }
}

// Memproses pesan yang dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $pesan = $_POST["pesan"];
  $id_user = $_SESSION["id_user"]; // Ambil ID pengguna dari sesi
  $id_admin = 1;
  $admin = '';
  $tanggal = date('Y-m-d'); // Tanggal pesan
  $jam = date('H:i:s');

  // Data pesan yang akan dimasukkan ke database
  $data = [
      "pesan" => $pesan,
      "id_user" => $id_user,
      "id_admin" => $id_admin,
      "admin" => $admin,
      "tanggal" => $tanggal,
      "jam" => $jam
  ];

  // Memanggil fungsi chat untuk menyimpan pesan ke database
  if (chat($data)) {
      // Refresh halaman setelah mengirim pesan
      header("Location: ".$_SERVER['PHP_SELF']);
      exit();
  } else {
      // Tampilkan pesan kesalahan jika gagal
      echo "Gagal mengirim pesan.";
  }
}

// Fungsi untuk mengambil pesan dari database
function getChatMessages() {
  global $conn;
  $user_me = $_SESSION['id_user'];
  // Query untuk mengambil pesan dari database
  $query_chat = "SELECT * FROM chat WHERE id_user='$user_me' ORDER BY tanggal ASC";
  $result_chat = mysqli_query($conn, $query_chat);

  // Membuat array untuk menampung pesan
  $chat_messages = [];

  // Looping untuk mengambil data pesan
  while ($row_chat = mysqli_fetch_assoc($result_chat)) {
      // Tambahkan data pesan ke dalam array
      $chat_messages[] = $row_chat;
  }

  // Mengembalikan array pesan
  return $chat_messages;
}

// Memanggil fungsi untuk mengambil pesan dari database
$chat_messages = getChatMessages();

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Jadwal Lapangan</title>
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
              <a class="nav-link active" aria-current="page" href="lapangan.php">Lapangan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="bayar.php">Pembayaran</a>
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
            <button type="submit" class="btn btn-primary" name="simpan" id="simpan">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End Edit Profil -->

  <?php
    if (!isset($_POST['tanggal'])) {
      $tanggal = null;
    } else {
      $tanggal = $_POST['tanggal'];
    }

    if (isset($_POST['semua'])) {
      $tanggal = null;
    }          
  ?>

  <div class="container-fluid">
    <h2 class="text-center text-head">Jadwal Pemesanan <span style="color: #198754;"><?= $lapangan['nama'] ?></span> </h2>
    <form action="" method="post" class="px-4">
    <div class="mb-3 row justify-content-end">
        <label for="tanggal" class="col-form-label col-auto">Pilih Tanggal: </label>
        <div class="col-auto">
            <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $tanggal ?>">
        </div>
    </div>
    <div class="mb-3 d-flex justify-content-end">
        <button type="submit" style="background-color: #198754; border-color:#198754;" class="btn btn-primary me-2" name="filter">Filter</button>
        <button type="submit" class="btn btn-secondary" name="semua">Semua</button>
    </div>
</form>


<table class="table">
        <thead>
            <tr>
                <th scope="col">Mulai</th>
                <th scope="col">Jam Main</th>
                <th scope="col">Selesai</th>
                <th scope="col">Jam Habis</th>
            </tr>
        </thead>
        <tbody>
        <?php
          $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : null;
          if (isset($_POST['semua'])) {
              $tanggal = null;
          }

          $data = query("SELECT sewa.*, lapangan.nama, user.nama_lengkap 
                        FROM sewa 
                        JOIN lapangan ON sewa.id_lapangan = lapangan.id_lapangan 
                        LEFT JOIN user ON sewa.id_user = user.id_user 
                        JOIN bayar ON sewa.id_sewa = bayar.id_sewa 
                        WHERE bayar.konfirmasi = 'Terkonfirmasi' AND lapangan.id_lapangan = '$id_lpg'
                        AND DATE(sewa.jam_mulai) >= '$tanggal_sekarang'
                        ORDER BY sewa.jam_mulai ASC");

          if ($tanggal !== null) {
              $data = query("SELECT sewa.*, lapangan.nama, user.nama_lengkap 
                        FROM sewa 
                        JOIN lapangan ON sewa.id_lapangan = lapangan.id_lapangan 
                        LEFT JOIN user ON sewa.id_user = user.id_user 
                        JOIN bayar ON sewa.id_sewa = bayar.id_sewa 
                        WHERE bayar.konfirmasi = 'Terkonfirmasi' AND lapangan.id_lapangan = '$id_lpg'
                        AND DATE(sewa.jam_mulai) >= '$tanggal_sekarang'
                        AND DATE(sewa.jam_mulai) = '$tanggal'
                        ORDER BY sewa.jam_mulai ASC");
          }

            foreach ($data as $row) {
              echo "<tr>";
              echo "<td>" . date("d-m-Y", strtotime($row['jam_mulai'])) . "</td>";
              echo "<td>" . date("H:i", strtotime($row['jam_mulai'])) . "</td>";
              echo "<td>" . date("d-m-Y", strtotime($row['jam_habis'])) . "</td>";
              echo "<td>" . date("H:i", strtotime($row['jam_habis'])) . "</td>";
              echo "</tr>";
            }
        ?>
            <?php foreach ($sewa as $row) : ?>
                <!-- Modal Bayar -->
                <div class="modal fade" id="bayarModal<?= $row["id_sewa"] ?>" tabindex="-1" role="dialog" aria-labelledby="bayarModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Bayar Lapangan <?= $row["nama"]; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form action="" method="post">
                        <input type="hidden" name="idsewa" value="<?= $row["id_sewa"]; ?>">
                        <div class="modal-body">
                          <!-- konten form modal -->
                          <div class="row justify-content-center align-items-center">
                            <div class="col">
                              <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Jam Main</label>
                                <input type="datetime-local" name="tgl_main" class="form-control" id="exampleInputPassword1" value="<?= $row["jam_mulai"]; ?>" disabled>
                              </div>
                              <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Jam Habis</label>
                                <input type="datetime-local" name="jam_habis" class="form-control" id="exampleInputPassword1" value="<?= $row["jam_habis"]; ?>" disabled>
                              </div>
                            </div>
                            <div class="col">
                              <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Lama Main</label>
                                <input type="number" name="jam_mulai" class="form-control" id="exampleInputPassword1" value="<?= $row["lama"]; ?>" disabled>
                              </div>
                              <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Harga</label>
                                <input type="number" name="harga" class="form-control" id="exampleInputPassword1" value="<?= $row["harga"]; ?>" disabled>
                              </div>
                            </div>
                            <div class="input-group ">
                              <div class="input-group-prepend border border-danger">
                                <span class="input-group-text">Total</span>
                              </div>
                              <input type="number" name="total" class="form-control border border-danger" id="exampleInputPassword1" value="<?= $row["total"]; ?>" disabled>
                            </div>
                            <div class="mt-3">
                              <label for="exampleInputPassword1" class="form-label">Upload Bukti</label>
                              <input type="file" name="bukti" class="form-control" id="exampleInputPassword1">
                            </div>
                          </div>
                        </div>
                        <div class="mt-3 mx-3">
                          <h6 class=" text-center border border-danger">Status : Belum Bayar</h6>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-inti" name="bayar" id="bayar">Bayar</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- End Modal Bayar -->

                <!-- Modal Detail -->
                <div class="modal fade" id="detailModal<?= $row["id_sewa"] ?>" tabindex="-1" role="dialog" aria-labelledby="bayarModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Detail Pembayaran Lapangan <?= $row["nama"]; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form action="" method="post">
                        <div class="modal-body">
                          <!-- konten form modal -->
                          <div class="row justify-content-center align-items-center">
                            <div class="col">
                              <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Jam Main</label>
                                <input type="datetime-local" name="tgl_main" class="form-control" id="exampleInputPassword1" value="<?= $row["jam_mulai"]; ?>" disabled>
                              </div>
                              <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Jam Habis</label>
                                <input type="datetime-local" name="jam_habis" class="form-control" id="exampleInputPassword1" value="<?= $row["jam_habis"]; ?>" disabled>
                              </div>
                            </div>
                            <div class="col">
                              <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Lama Main</label>
                                <input type="number" name="jam_mulai" class="form-control" id="exampleInputPassword1" value="<?= $row["lama_sewa"]; ?>" disabled>
                              </div>
                              <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Harga</label>
                                <input type="number" name="harga" class="form-control" id="exampleInputPassword1" value="<?= $row["harga"]; ?>" disabled>
                              </div>
                            </div>
                            <div class="input-group ">
                              <div class="input-group-prepend">
                                <span class="input-group-text">Total</span>
                              </div>
                              <input type="number" name="total" class="form-control " id="exampleInputPassword1" value="<?= $row["total"]; ?>" disabled>
                            </div>
                            <div class="mt-3">
                              <label for="exampleInputPassword1" class="form-label">Upload Bukti</label>
                              <input type="file" name="bukti" class="form-control" id="exampleInputPassword1">
                            </div>
                          </div>
                        </div>
                        <div class="mt-3 mx-3">
                          <h6 class="text-center border border-danger">Status : <?= $row["konfirmasi"]; ?></h6>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- End Modal Detail -->

                <!-- Modal Hapus -->
                <div class="modal fade" id="hapusModal<?= $row["id_sewa"]; ?>" tabindex="-1" aria-labelledby="profilModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="hapusModalLabel">Konfirmasi Hapus Data</h5>
                      </div>
                      <div class="modal-body">
                        <p>Anda yakin ingin menghapus data ini?</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <a href="./controller/hapus.php?id=<?= $row["id_sewa"] ?>" class="btn btn-danger">Hapus</a>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End Modal Hapus -->

                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </form>
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
</body>

</html>