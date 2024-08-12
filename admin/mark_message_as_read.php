<?php
session_start();
require "../functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message_id'])) {
    $message_id = $_POST['message_id'];
    markMessageAsRead($message_id);
}

function markMessageAsRead($message_id) {
    global $conn;
    // Lakukan perubahan di database untuk menandai pesan tertentu sebagai sudah dibaca
    $query = "UPDATE chat SET read_status = 1 WHERE id = '$message_id'";
    mysqli_query($conn, $query);
}
?>
