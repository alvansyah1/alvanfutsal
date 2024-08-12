<?php
session_start();
require "../functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    markAllMessagesAsRead($user_id);
}

function markAllMessagesAsRead($user_id) {
    global $conn;
    // Lakukan perubahan di database untuk menandai semua pesan sebagai sudah dibaca
    $query = "UPDATE chat SET read_status = 1 WHERE id_user = '$user_id'";
    mysqli_query($conn, $query);
}
?>
