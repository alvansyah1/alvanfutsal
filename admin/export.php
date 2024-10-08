<?php
session_start();
require "../session.php";
require "../functions.php";

if ($role !== 'Admin') {
  header("location:../login.php");
}

$pesan = query("SELECT sewa.id_sewa,user.nama_lengkap,sewa.tanggal_pesan,sewa.jam_mulai,sewa.lama_sewa,sewa.total,bayar.bukti,bayar.konfirmasi
FROM sewa
JOIN user ON sewa.id_user = user.id_user
JOIN bayar ON sewa.id_sewa = bayar.id_sewa
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
  <link rel="stylesheet" href="../bootstrap.min.css">
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"> -->
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

  <title>Document</title>
</head>

<body>
  <div class="container-fluid">
    <div class="row min-vh-100">
      <div class="col-12 p-5 mt-5">
        <!-- Konten -->
        <h3 class="judul text-center">Data Pesanan</h3>
        <hr>
        <table class="table table-hover mt-3">
          <thead class="table-inti">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Nama Pemesan</th>
              <th scope="col">Tanggal Pesan</th>
              <th scope="col">Tanggal Main</th>
              <th scope="col">Lama</th>
              <th scope="col">Total</th>
              <th scope="col">Bukti</th>
              <th scope="col">Konfirmasi</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody class="text">
            <?php $i = 1; ?>
            <?php foreach ($pesan as $row) : ?>
              <tr>
                <td><?= $i++; ?></td>
                <td><?= $row["nama_lengkap"]; ?></td>
<td><?= $row["tanggal_pesan"];?></td>
 <td><?= $row["jam_mulai"]; ?></td>
                <td><?= $row["lama_sewa"]; ?></td>
                <td><?= $row["total"]; ?></td>
                <td><img src="../img/<?= $row["bukti"]; ?>" width="100" height="100"></td>
                <td><?= $row["konfirmasi"]; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <script src="../bootstrap.bundle.min.js"></script>
      <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script> -->
      <?php
      require_once __DIR__ . '/vendor/autoload.php'; // path ke autoload.php di folder vendor

      use Dompdf\Dompdf;

      // Membuat objek Dompdf
      $dompdf = new Dompdf();

      // Mengambil isi file DataPesanan.php
      $html = ob_get_clean();

      // Mengubah HTML menjadi PDF
      $dompdf->loadHtml($html);

      // Mengatur ukuran dan orientasi kertas
      $dompdf->setPaper('A4', 'landscape');

      // Render HTML sebagai PDF
      $dompdf->render();

      // Menghasilkan PDF dan menampilkannya di browser
      $dompdf->stream("DataPesanan.pdf", array("Attachment" => false));
      ?>

</body>


</html>