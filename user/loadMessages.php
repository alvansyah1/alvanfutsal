<?php
session_start();
require "../functions.php";
require "../session.php"; // Ensure this file handles session and sets $role

// Check if user is not a 'User', deny access
if ($role !== 'User') {
  http_response_code(403);
  exit("Forbidden");
}

// Function to get chat messages
function getChatMessages() {
  global $conn;
  $user_me = $_SESSION['id_user'];
  
  // Query to fetch messages ordered by date
  $query_chat = "SELECT * FROM chat WHERE id_user='$user_me' ORDER BY tanggal ASC";
  $result_chat = mysqli_query($conn, $query_chat);
  
  // Check if query was successful
  if (!$result_chat) {
    http_response_code(500); // Internal Server Error
    exit("Database query error: " . mysqli_error($conn));
  }

  // Array to store chat messages
  $chat_messages = [];

  // Loop through query results and store messages
  while ($row_chat = mysqli_fetch_assoc($result_chat)) {
    $chat_messages[] = $row_chat;
  }

  // Return array of chat messages
  return $chat_messages;
}

// Check if $_SESSION["id_user"] is set
if (isset($_SESSION["id_user"])) {
  $id_user = $_SESSION["id_user"];
  
  // Call function to get chat messages
  $chat_messages = getChatMessages();
  
  // Generate HTML for each message
  foreach ($chat_messages as $message) {
    $pesan = htmlspecialchars($message['pesan']);
    $jam = substr($message['jam'], 0, 5);
    
    // Output HTML for incoming or outgoing message based on 'admin' field
    if ($message['admin'] === 'Admin') {
      echo '
        <div class="message incoming">
          <div class="text">
            <p>' . $pesan . '</p>
            <span class="timestamp">' . $jam . '</span>
          </div>
        </div>';
    } else {
      echo '
        <div class="message outgoing">
          <div class="text">
            <p>'. $pesan . '</p>
            <span class="timestamp">' . $jam . '</span>
          </div>
        </div>';
    }
  }
} else {
  http_response_code(403); // Forbidden
  exit("Forbidden");
}
?>
