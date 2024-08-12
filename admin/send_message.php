<?php
session_start();
require "../functions.php"; // Adjust path as per your file structure

// Assuming you have sanitized and validated session and database connections
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pesan'])) {
    $pesan = $_POST["pesan"];
    $role = $_SESSION["role"];
    $admin_id = $_SESSION["id_user"];
    $user_id = $_SESSION["selected_user_id"];
    $tanggal = date('Y-m-d H:i:s');
    $jam = date('H:i:s');

    $data = [
        "pesan" => $pesan,
        "admin" => $role,
        "id_user" => $user_id,
        "id_admin" => $admin_id,
        "tanggal" => $tanggal,
        "jam" => $jam
    ];

    if (chat($data)) {
        markChatAsRead($user_id); // Function to mark message as read
        echo "Message sent successfully";
    } else {
        echo "Failed to send message";
    }
} else {
    echo "Invalid request";
}
?>
