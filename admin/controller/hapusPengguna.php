<?php
require "../../functions.php";
$id_user = $_GET["id"];

if (hapusPengguna($id_user) > 0) {
  echo "
  <script>
    alert('Data berhasil dihapus!');
    document.location.href = '../pengguna.php'; 
  </script>
  ";
} else {
  echo "
  <script>
    alert('Data gagal dihapus!');
    document.location.href = '../pengguna.php'; 
  </script>
  ";
}
