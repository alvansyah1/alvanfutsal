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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_admin = 1;
    
    // Query untuk menandai pesan sebagai telah dibaca (update read_status_2)
    $query_mark_read = "UPDATE chat SET read_status_2 = 1 WHERE id_user = '$id_user' AND id_admin = '$id_admin' AND admin = 'Admin' AND read_status_2 = 0";
    mysqli_query($conn, $query_mark_read);
}

mysqli_close($conn);
?>