<?php
// Koneksi ke database (sesuaikan dengan informasi database Anda)
$conn = mysqli_connect("localhost", "root", "", "dbfutsal");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Pastikan session telah dimulai
session_start();

if (!isset($_SESSION['id_user'])) {
    die("Session tidak valid");
}

$id_user = $_SESSION['id_user'];

// Query untuk mendapatkan status terbaru pesan yang dibaca oleh pengguna
$query_read_status = "SELECT read_status_2 FROM chat WHERE id_admin = 1 AND admin = 'Admin' ORDER BY tanggal DESC, jam DESC LIMIT 1";
$result_read_status = mysqli_query($conn, $query_read_status);

if ($result_read_status && mysqli_num_rows($result_read_status) > 0) {
    $row = mysqli_fetch_assoc($result_read_status);
    echo $row['read_status_2'];
} else {
    echo '0'; // Jika tidak ada pesan atau belum ada yang dibaca
}

mysqli_close($conn);
?>
