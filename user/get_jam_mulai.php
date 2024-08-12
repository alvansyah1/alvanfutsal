<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "dbfutsal");
if (!$conn) {
  die("Koneksi ke database gagal: " . mysqli_connect_error());
}

$tgl_main = mysqli_real_escape_string($conn, $_GET['tgl_main']);
$lama_main = mysqli_real_escape_string($conn, $_GET['lama_main']);

$mulai_waktu = strtotime($tgl_main);
$habis_waktu = $mulai_waktu + (intval($lama_main) * 3600);
$habis = date('Y-m-d H:i:s', $habis_waktu);

$jam_habis = date("Y-m-d H:i:s", strtotime($tgl_main . " +" . $lama_main . " jam"));

// Query untuk memeriksa apakah ada booking pada rentang waktu yang dipilih oleh pengguna dengan status pembayaran terkonfirmasi
$sql = "SELECT * FROM sewa s
        INNER JOIN bayar b ON s.id_sewa = b.id_sewa
        WHERE (s.jam_mulai BETWEEN '$tgl_main' AND '$jam_habis')
        OR (s.jam_habis BETWEEN '$tgl_main' AND '$jam_habis')
        AND b.konfirmasi = 'Terkonfirmasi'";
        
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // Jika ada booking pada rentang waktu yang dipilih oleh pengguna dengan status pembayaran terkonfirmasi, kirimkan pesan Lapangan Tidak Tersedia
  echo "Lapangan tidak tersedia. Silahkan pilih waktu lain";
} else {
  // Jika tidak ada booking pada rentang waktu yang dipilih oleh pengguna dengan status pembayaran terkonfirmasi, kirimkan pesan Lapangan Tersedia
  echo "Lapangan sedang tidak digunakan dan siap untuk reservasi. Segera pesan untuk memastikan waktu yang Anda inginkan";
}

mysqli_close($conn);
?>
