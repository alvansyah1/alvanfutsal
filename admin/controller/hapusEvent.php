<?php
require "../../functions.php";
$id_event = $_GET["id"];

if (hapusEvent($id_event) > 0) {
  echo "
  <script>
    alert('Data berhasil dihapus!');
    document.location.href = '../event.php'; 
  </script>
  ";
} else {
  echo "
  <script>
    alert('Data gagal dihapus!');
    document.location.href = '../event.php'; 
  </script>
  ";
}
