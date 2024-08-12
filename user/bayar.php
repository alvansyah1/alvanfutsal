<?php
session_start();
require "../functions.php";
require "../session.php";
if ($role !== 'User') {
  header("location:../login.php");
};

$id_user = $_SESSION["id_user"];

// Pagination
$jmlHalamanPerData = 5;
$jumlahData = count(query("SELECT sewa.*, lapangan.nama, bayar.bukti, bayar.konfirmasi
FROM sewa
JOIN lapangan ON sewa.id_lapangan = lapangan.id_lapangan
left JOIN bayar ON sewa.id_sewa = bayar.id_sewa
WHERE sewa.id_user = '$id_user'"));
$jmlHalaman = ceil($jumlahData / $jmlHalamanPerData);

if (isset($_GET["halaman"])) {
  $halamanAktif = $_GET["halaman"];
} else {
  $halamanAktif = 1;
}

$awalData = ($jmlHalamanPerData * $halamanAktif) - $jmlHalamanPerData;

$sewa = query("SELECT sewa.*, lapangan.nama, bayar.status, bayar.bayar_dp, bayar.kekurangan, bayar.bukti, bayar.konfirmasi
FROM sewa
JOIN lapangan ON sewa.id_lapangan = lapangan.id_lapangan
left JOIN bayar ON sewa.id_sewa = bayar.id_sewa
WHERE sewa.id_user = '$id_user' 
ORDER BY sewa.tanggal_pesan DESC, sewa.jam_pesan DESC
LIMIT $awalData, $jmlHalamanPerData");

$profil = query("SELECT * FROM user WHERE id_user = '$id_user'")[0];

$metode = query("SELECT * FROM metode_pembayaran");

if (isset($_POST["simpan"])) {
  if (edit($_POST) > 0) {
    echo "<script>
          alert('Profil berhasil diperbarui!');
          document.location.href = 'bayar.php';
          </script>";
  } else {
    echo "<script>
          alert('Gagal memperbarui profil!');
          </script>";
  }
}


if (isset($_POST["bayar"])) {
  if (bayar($_POST) > 0) {
    echo "<script>
          alert('Berhasil dibayar!');
          document.location.href = 'bayar.php';
          </script>";
  } else {
    echo "<script>
          alert('Pembayaran gagal!');
          </script>";
  }
}

// Periksa apakah $_SESSION["id_user"] telah diatur
if (isset($_SESSION["id_user"])) {
  $id_user = $_SESSION["id_user"];

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
  <title>Pembayaran | BP Futsal</title>
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
          <?php
           if (isset($_SESSION['id_user'])) {
            ?>
          <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
            <li class="nav-item ">
              <a class="nav-link" aria-current="page" href="..//#beranda">Beranda</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="../#about">Tentang</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="../#bayar">Tata Cara</a>
            </li>
            <?php
           }
              if (isset($_SESSION['id_user'])) {
              echo '
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="lapangan.php">Lapangan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="bayar.php">Pemesanan Saya</a>
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
                    <option value="Perempuan" <?if ($profil['jenis_kelamin'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
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
                <label for="exampleInputPassword1" class="form-label">Ganti Foto : </label>
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

  <section class="lapangan mb-5" id="lapangan">
  <div class="container-fluid">
    <h2 class="text-head"><span>Detail</span> Pemesanan </h2>
    <div class="table-responsive">
      <table class="table table-bordered table-dark table-striped">
        <thead>
          <tr>
            <th scope="col">No</th>
            <th scope="col">Tanggal Pesan</th>
            <th scope="col">Jam Pesan</th>
            <th scope="col">Nama Lapangan</th>
            <th scope="col">Tanggal Main</th>
            <th scope="col">Jam Mulai</th>
            <th scope="col">Lama Sewa</th>
            <th scope="col">Jam Selesai</th>
            <th scope="col">Total</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; ?>
          <?php foreach ($sewa as $row) : ?>
            <tr>
              <th scope="row"><?= $i++; ?></th>
              <td><?= date("d-m-Y", strtotime($row["tanggal_pesan"])); ?></td>
              <td><?= date("H:i", strtotime($row['jam_pesan'])) ?></td>
              <td><?= $row["nama"] ?></td>
              <td><?= date("d-m-Y", strtotime($row["jam_mulai"])); ?></td>
              <td><?= date("H:i", strtotime($row['jam_mulai'])) ?></td>
              <td><?= $row["lama_sewa"] ?> Jam</td>
              <td><?= date("H:i", strtotime($row['jam_habis'])) ?></td>
              <td><?= number_format($row["total"], 0, ',', '.') ?></td>
              <td>
                <?php
                $id_sewa = $row["id_sewa"];
                $tgl_main = $row["jam_mulai"];
                $tgl_habis = $row["jam_habis"];

                // Query to check other confirmed or booked orders overlapping with the current one
                $query_pesanan_lain = "SELECT * FROM sewa s
                                      INNER JOIN bayar b ON s.id_sewa = b.id_sewa
                                      WHERE s.id_sewa != '$id_sewa' AND ((s.jam_mulai BETWEEN '$tgl_main' AND '$tgl_habis')
                                      OR (s.jam_habis BETWEEN '$tgl_main' AND '$tgl_habis'))
                                      AND b.konfirmasi = 'Terkonfirmasi'";
                $result_pesanan_lain = mysqli_query($conn, $query_pesanan_lain);

                if (mysqli_num_rows($result_pesanan_lain) > 0) {
                  // Another booking confirmed or booked during the same time
                  echo "Sudah Dibooking Pengguna Lain";
                } else {
                  // Show Pay or Delete buttons as usual
                  if ($row["konfirmasi"] == "Sudah Bayar") {
                    echo '<button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal' . $row["id_sewa"] . '">Pesanan sedang ditinjau admin, harap menunggu</button>';
                  } elseif ($row["konfirmasi"] == "Terkonfirmasi") {
                    echo '<button type="button" style="background-color:#ff9800;" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal' . $row["id_sewa"] . '">Pemesanan disetujui, silakan datang sesuai waktu yang dipilih</button>';
                  } else {
                    echo '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bayarModal' . $row["id_sewa"] . '">Bayar</button>
                          <a href="" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusModal' . $row["id_sewa"] . '">Hapus</a>';
                  }
                }
                ?>

                  <!-- Modal Bayar -->
<div class="modal fade" id="bayarModal<?= $row["id_sewa"] ?>" tabindex="-1" role="dialog" aria-labelledby="bayarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proses Pembayaran <?= $row["nama"]; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="mt-3 mx-3">
                    <h6 class="text-center border border-danger">Silahkan lakukan pembayaran untuk menyelesaikan pemesanan lapangan!&#x1F609;</h6>
                </div>
            <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm(<?= $row["id_sewa"]; ?>)">
                <input type="hidden" name="id_sewa" value="<?= $row["id_sewa"]; ?>">
                <div class="modal-body">
                    <!-- konten form modal -->
                    <div class="row justify-content-center align-items-center">
                        <div class="col">
                            <div class="mb-3">
                                <label for="jam_main_<?= $row["id_sewa"]; ?>" class="form-label">Jam Main</label>
                                <input type="datetime-local" name="tgl_main" class="form-control" id="jam_main_<?= $row["id_sewa"]; ?>" value="<?= $row["jam_mulai"]; ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="jam_habis_<?= $row["id_sewa"]; ?>" class="form-label">Jam Habis</label>
                                <input type="datetime-local" name="jam_habis" class="form-control" id="jam_habis_<?= $row["id_sewa"]; ?>" value="<?= $row["jam_habis"]; ?>" disabled>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="lama_main_<?= $row["id_sewa"]; ?>" class="form-label">Lama Main</label>
                                <input type="text" name="jam_mulai" class="form-control" id="lama_main_<?= $row["id_sewa"]; ?>" value="<?= $row["lama_sewa"]; ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="harga_<?= $row["id_sewa"]; ?>" class="form-label">Harga</label>
                                <input type="number" name="harga" class="form-control" id="harga_<?= $row["id_sewa"]; ?>" value="<?= $row["harga"]; ?>" disabled>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span style="background-color:#ff9800;border: 2px solid #dc3545;border-radius: 10px 0 0 10px;" class="input-group-text">Total</span>
                            </div>
                            <input type="number" name="total" style="background-color:#dc3545;" class="form-control border border-danger" id="total_<?= $row["id_sewa"]; ?>" value="<?= $row["total"]; ?>" disabled>
                        </div>
                        <div class="mt-3">
                            <label for="metode_pembayaran_<?= $row["id_sewa"]; ?>" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" name="metode_pembayaran" id="metode_pembayaran_<?= $row["id_sewa"]; ?>" onchange="updateMetode(<?= $row["id_sewa"]; ?>)">
                                <option value="">Pilih Metode Pembayaran</option>
                                <?php foreach ($metode as $metode_row): ?>
                                    <option value="<?= $metode_row["metode"]; ?>"><?= $metode_row["metode"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mt-3" id="metode_info_<?= $row["id_sewa"]; ?>" style="display: none;">
                            <?php foreach ($metode as $metode_row): ?>
                                <div id="info_<?= $metode_row["metode"]; ?>_<?= $row["id_sewa"]; ?>" style="display: none;">
                                    <label for="exampleInputPassword1" class="form-label">
                                        Transfer ke <?= $metode_row["metode"]; ?> <?= $metode_row["nomor"]; ?> a/n <?= $metode_row["nama"]; ?>
                                    </label>
                                    <input type="hidden" name="nominal_dp" class="form-control" id="nominal_dp_<?= $row["id_sewa"]; ?>" value="<?= $metode_row["nominal_dp"]; ?>" readonly />
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="status_pembayaran_<?= $row["id_sewa"]; ?>" class="form-label">Status Pembayaran</label>
                            <select class="form-select" name="status_pembayaran" id="status_pembayaran_<?= $row["id_sewa"]; ?>" onchange="updateTotal(<?= $row["id_sewa"]; ?>)">
                                <option value="lunas">Lunas</option>
                                <option value="dp">DP</option>
                            </select>
                        </div>
                        <div id="kekurangan_bayar_group_<?= $row["id_sewa"]; ?>" style="display: none;">
                            <label for="dp_<?= $row["id_sewa"]; ?>" class="form-label">Jumlah DP yang harus dibayarkan saat ini</label>
                            <input type="number" name="dp" class="form-control" id="dp_<?= $row["id_sewa"]; ?>" readonly />
                            <div class="input-group-prepend mt-3">
                                <span>Kekurangan bayar pada saat di BP Futsal</span>
                            </div>
                            <input type="number" name="kekurangan_bayar" class="form-control" id="kekurangan_bayar_<?= $row["id_sewa"]; ?>" readonly />
                        </div>
                        <div class="mt-3">
                            <label for="fotoBukti_<?= $row["id_sewa"]; ?>" class="form-label">Unggah Bukti Transfer</label>
                            <input type="file" name="foto" class="form-control" id="fotoBukti_<?= $row["id_sewa"]; ?>">
                        </div>
                    </div>
                </div>
               
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="bayar" id="bayar">Bayar</button>
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
                              <div class="mb-3">
                                <img src="../img/<?= $row["bukti"]; ?>" alt="gambar lapangan" class="img-fluid">
                              </div>
                              <div class="col">
                                <div class="mb-3">
                                  <label for="jam_main" class="form-label">Jam Main</label>
                                  <input type="datetime-local" name="tgl_main" class="form-control" id="jam_main" value="<?= $row["jam_mulai"]; ?>" disabled>
                                </div>
                                <div class="mb-3">
                                  <label for="jam_habis" class="form-label">Jam Habis</label>
                                  <input type="datetime-local" name="jam_habis" class="form-control" id="jam_habis" value="<?= $row["jam_habis"]; ?>" disabled>
                                </div>
                              </div>
                              <div class="col">
                                <div class="mb-3">
                                  <label for="lama_main" class="form-label">Lama Main</label>
                                  <input type="number" name="jam_mulai" class="form-control" id="lama_main" value="<?= $row["lama_sewa"]; ?>" disabled>
                                </div>
                                <div class="mb-3">
                                  <label for="harga" class="form-label">Harga Per Jam</label>
                                  <input type="number" name="harga" class="form-control" id="harga" value="<?= $row["harga"]; ?>" disabled>
                                </div>
                              </div>
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span style="background-color:#ff9800;border: 2px solid #dc3545;border-radius: 10px 0 0 10px;" class="input-group-text">Total</span>
                                </div>
                                <input type="number" name="total" style="background-color:#dc3545;" class="form-control border border-danger" id="exampleInputPassword1" value="<?= $row["total"]; ?>" disabled>
                              </div>
                              <div class="mb-3">
                                  <label for="status" class="form-label">Status Pembayaran</label>
                                  <input type="text" name="status" class="form-control" id="status" value="<?= $row["status"]; ?>" disabled>
                              </div>
                              <div class="mb-3">
                                  <label for="nominaldp" class="form-label">Nominal DP</label>
                                  <input type="text" name="nominaldp" class="form-control" id="nominaldp" value="<?= $row["bayar_dp"]; ?>" disabled>
                              </div>
                              <div class="mb-3">
                                  <label for="kekuranganbayar" class="form-label">Yang perlu dilunasin saat di BP Futsal</label>
                                  <input type="text" name="kekuranganbayar" class="form-control" id="kekuranganbayar" value="<?= $row["kekurangan"]; ?>" disabled>
                              </div>
                            </div>
                          </div>
                          <div class="mt-3 mx-3">
                            <h6 class="text-center border border-danger"> 
                                <?php 
                                if ($row["konfirmasi"] === "Sudah Bayar") {
                                    echo "Telah dibayar, silahkan tunggu konfirmasi BP Futsal! &#x1F60E;";
                                } elseif ($row["konfirmasi"] === "Terkonfirmasi") {
                                    echo "Telah terkonfirmasi, sampai ketemu di BP Futsal! &#x1F60A;&#x1F44B;";
                                } else {
                                    // Default text if the value doesn't match any condition
                                    echo $row["konfirmasi"];
                                }
                                ?>
                            </h6>
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
        <!-- Pagination -->
        <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center">
            <?php if ($halamanAktif > 1) : ?>
              <li class="page-item">
                <a class="page-link" href="?halaman=<?= $halamanAktif - 1; ?>" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $jmlHalaman; $i++) : ?>
              <?php if ($i == $halamanAktif) : ?>
                <li class="page-item active"><span class="page-link"><?= $i; ?></span></li>
              <?php else : ?>
                <li class="page-item"><a class="page-link" href="?halaman=<?= $i; ?>"><?= $i; ?></a></li>
              <?php endif; ?>
            <?php endfor; ?>

            <?php if ($halamanAktif < $jmlHalaman) : ?>
              <li class="page-item">
                <a class="page-link" href="?halaman=<?= $halamanAktif + 1; ?>" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
        <!-- Pagination -->
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
  <script>
    function updateMetode(idSewa) {
    const metodePembayaran = document.getElementById('metode_pembayaran_' + idSewa).value;
    const metodeInfo = document.getElementById('metode_info_' + idSewa);

    metodeInfo.style.display = 'block';
    const metodeDivs = metodeInfo.children;
    for (let i = 0; i < metodeDivs.length; i++) {
        metodeDivs[i].style.display = 'none';
    }

    const selectedMetode = document.getElementById('info_' + metodePembayaran + '_' + idSewa);
    if (selectedMetode) {
        selectedMetode.style.display = 'block';
    }
}

function updateTotal(idSewa) {
    const statusPembayaran = document.getElementById('status_pembayaran_' + idSewa).value;
    const totalElement = parseInt(document.getElementById('total_' + idSewa).value, 10);
    const nominalDP_Element = parseInt(document.getElementById('nominal_dp_' + idSewa).value, 10);
    const kekuranganBayarGroup = document.getElementById('kekurangan_bayar_group_' + idSewa);
    const kekuranganBayarElement = document.getElementById('kekurangan_bayar_' + idSewa);
    const dpElement = document.getElementById('dp_' + idSewa);

    if (statusPembayaran === 'dp') {
        const kekuranganBayar = totalElement - nominalDP_Element;
        kekuranganBayarGroup.style.display = 'block';
        kekuranganBayarElement.value = kekuranganBayar;
        dpElement.value = nominalDP_Element;
    } else {
        kekuranganBayarGroup.style.display = 'none';
        kekuranganBayarElement.value = '';
        dpElement.value = '';
    }
}

function validateForm(idSewa) {
    const metodePembayaran = document.getElementById('metode_pembayaran_' + idSewa).value;
    const fileFoto = document.getElementById('fotoBukti_' + idSewa).files;

    if (metodePembayaran === '') {
        alert('Silakan pilih metode pembayaran sebelum melanjutkan!');
        return false;
    }

    if (!fileFoto || !fileFoto[0]) {
        alert('Silakan unggah bukti transfer sebelum melanjutkan!');
        return false;
    }

    return true;
}


  </script>
</body>
</html>