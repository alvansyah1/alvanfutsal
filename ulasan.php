<?php
  session_start();
  require "functions.php";

  $tanggal_sekarang = date("Y-m-d");

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

  $ulasan = query("SELECT saran.*, user.nama_lengkap, user.foto AS foto_user FROM saran JOIN user ON saran.id_user = user.id_user");
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
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif&family=Poppins:ital,wght@0,100;0,300;0,400;0,700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <script src="https://unpkg.com/feather-icons"></script>
  <link rel="stylesheet" href="tampilan.css">
  <style>
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
    .alert-danger {
      background-color: #dc3545;
    }

    .ulasan-container {
      padding-top: 110px;
    }

    .ulasan-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .ulasan-header h2 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      color: #343a40;
    }

    .ulasan-header span {
      color: #007bff;
    }

    .ulasan-card {
      position: relative;
      background: linear-gradient(145deg, #f3f3f3, #ffffff);
      border: 1px solid #dee2e6;
      border-radius: 15px;
      margin-bottom: 20px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 4px 20px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: flex-start;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .ulasan-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .ulasan-card .avatar {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      overflow: hidden;
      margin-right: 20px;
      border: 2px solid #007bff;
    }

    .ulasan-card .avatar img {
      width: 100%;
      height: auto;
    }

    .ulasan-card .content {
      flex: 1;
      position: relative;
    }

    .ulasan-card h5 {
      color: #343a40;
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .ulasan-card p {
      color: #495057;
      font-family: 'Poppins', sans-serif;
      font-weight: 400;
      margin-bottom: 10px;
    }

    .ulasan-card .tanggal {
      position: absolute;
      top: 0;
      right: 0;
      color: #6c757d;
      font-size: 0.9em;
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
              <a class="nav-link" aria-current="page" href="/futsal/#beranda">Beranda</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="/futsal/#about">Tentang</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="/futsal/#bayar">Tata Cara</a>
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
              <a class="nav-link" aria-current="page" href="/futsal/#lapangan">Lapangan</a>
            </li>';
            }
            ?>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="ulasan.php">Ulasan Pengguna</a>
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

  <!-- Ulasan Section -->
  <div class="container ulasan-container">
    <div class="ulasan-header">
      <h2><span>Ulasan</span> Pengguna</h2>
    </div>
    <div class="row mt-4">
      <div class="col-md-8 offset-md-2">
        <?php foreach ($ulasan as $row): ?>
          <div class="ulasan-card">
            <div class="avatar">
              <img src="img/<?= htmlspecialchars($row['foto_user']); ?>" alt="Avatar">
            </div>
            <div class="content">
              <h5><?= htmlspecialchars($row['nama_lengkap']); ?></h5>
              <p><?= htmlspecialchars($row['saran']); ?></p>
              <div class="tanggal">
                <?= htmlspecialchars(date('d-m-Y', strtotime($row['tanggal']))); ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <!-- End Ulasan Section -->

  <script src="../bootstrap.bundle.min.js"></script>
  <script>
    feather.replace()
  </script>
</body>
</html>
