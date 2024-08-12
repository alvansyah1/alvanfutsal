<?php
session_start();
include 'functions.php'; // Sesuaikan dengan konfigurasi koneksi database Anda
$conn = mysqli_connect("localhost", "root", "", "dbfutsal");
// Cek apakah ID pesan ada
if (isset($_POST['id'])) {
    $messageId = intval($_POST['id']);

    // Query untuk menghapus pesan
    $query = "DELETE FROM messages WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $messageId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}
?>
