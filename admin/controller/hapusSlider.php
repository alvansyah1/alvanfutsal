<?php
require "../../functions.php";
$id_slider = $_GET["id"];

if (hapusSlider($id_slider) > 0) {
  echo "
  <script>
    alert('Data berhasil dihapus!');
    document.location.href = '../slider.php'; 
  </script>
  ";
} else {
  echo "
  <script>
    alert('Data gagal dihapus!');
    document.location.href = '../slider.php'; 
  </script>
  ";
}
