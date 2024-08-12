<?php
require "../../functions.php";
$idsewa = $_GET["id"];

if (konfirmasi($idsewa) > 0) {
  echo "
  <script>
    alert('Data berhasil dikonfirmasi!');
    document.location.href = '../bayar.php'; 
  </script>
  ";
} else {
  echo "
  <script>
    alert('Data gagal dihapus!');
    document.location.href = '../bayar.php'; 
  </script>
  ";
}
