<?php
require "../../functions.php";
$id_saran = $_GET["id"];

if (hapusSaran($id_saran) > 0) {
  echo "
  <script>
    alert('Data berhasil dihapus!');
    document.location.href = '../saran.php'; 
  </script>
  ";
} else {
  echo "
  <script>
    alert('Data gagal dihapus!');
    document.location.href = '../saran.php'; 
  </script>
  ";
}
