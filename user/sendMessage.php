<?php
session_start();
require "../functions.php";
require "../session.php";

if ($role !== 'User') {
  http_response_code(403);
  exit("Forbidden");
}

// Periksa apakah $_SESSION["id_user"] telah diatur
if (isset($_SESSION["id_user"])) {
  $id_user = $_SESSION["id_user"];
  
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pesan"])) {
    $pesan = $_POST["pesan"];
    $id_admin = 1;
    $admin = '';
    $tanggal = date('Y-m-d'); // Tanggal pesan
    $jam = date('H:i:s');

    // Data pesan yang akan dimasukkan ke database
    $data = [
        "pesan" => $pesan,
        "id_user" => $id_user,
        "id_admin" => $id_admin,
        "admin" => $admin,
        "tanggal" => $tanggal,
        "jam" => $jam
    ];
  
    // Memanggil fungsi chat untuk menyimpan pesan ke database
    if (chat($data)) {
      // Kirim balasan berupa HTML pesan yang baru
      $newMessageHTML = '
        <div class="message outgoing">
          <div class="text">
            <p>'. $pesan . '</p>
            <span class="timestamp">' . substr($jam, 0, 5) . '</span>
          </div>
        </div>';
      
      echo $newMessageHTML;
    } else {
      http_response_code(500);
      exit("Gagal menyimpan pesan.");
    }
  } else {
    http_response_code(400);
    exit("Invalid request.");
  }
} else {
  http_response_code(403);
  exit("Forbidden");
}
?>
