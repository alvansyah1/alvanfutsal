<?php
require "../../functions.php";
$id_metode = $_GET["id"];

if (hapusMetode($id_metode) > 0) {
  echo "
  <script>
    alert('Data berhasil dihapus!');
    document.location.href = '../metode.php'; 
  </script>
  ";
} else {
  echo "
  <script>
    alert('Data gagal dihapus!');
    document.location.href = '../metode.php'; 
  </script>
  ";
}
