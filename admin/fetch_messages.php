<?php
session_start();
require "../functions.php"; // Adjust path as per your file structure

// Assuming you have sanitized and validated session and database connections
$role = $_SESSION['role'];
$user_id = $_SESSION['selected_user_id'];
$chat_messages = getChatMessages($role, $user_id); // Function to get chat messages from database

if (!empty($chat_messages)) {
    foreach ($chat_messages as $message) {
        $message_class = ($message['admin'] === 'Admin') ? 'outgoing' : 'incoming';
        echo '<div class="message ' . $message_class . '">
                <p>' . $message['pesan'] . '</p>
                <span class="timestamp">' . substr($message['jam'], 0, 5) . '</span>
              </div>';
    }
} else {
    echo '<p>Tidak ada pesan untuk ditampilkan.</p>';
}

function getChatMessages($role, $user_id)
{
    global $conn;

    $query_chat = "SELECT * FROM chat WHERE (admin = '$role' AND id_user = '$user_id') OR (id_user = '$user_id') ORDER BY tanggal ASC";
    $result_chat = mysqli_query($conn, $query_chat);

    $chat_messages = [];
    while ($row_chat = mysqli_fetch_assoc($result_chat)) {
        $chat_messages[] = $row_chat;
    }
    return $chat_messages;  
}

?>
