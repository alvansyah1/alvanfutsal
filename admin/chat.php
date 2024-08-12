<?php
session_start();
require "../functions.php";
require "../session.php";

if ($role !== 'Admin') {
    header("location:../login.php");
}


function getUsers()
{
    global $conn;

    $query_users = "SELECT u.*, c.*, COUNT(*) AS new_messages
                    FROM user u
                    LEFT JOIN chat c ON u.id_user = c.id_user AND c.admin != 'Admin'
                    WHERE c.id_chat IN (SELECT MAX(id_chat) FROM chat WHERE admin != 'Admin' GROUP BY id_user)
                    GROUP BY u.id_user
                    ORDER BY c.tanggal DESC, c.jam DESC";
    $result_users = mysqli_query($conn, $query_users);

    $users = [];
    while ($row_user = mysqli_fetch_assoc($result_users)) {
        $row_user['latest_message'] = $row_user['pesan'];
        $row_user['new_messages_count'] = $row_user['new_messages'];
        $users[$row_user['id_user']] = $row_user;
    }
    return $users;
}

function getUsers2() {
    global $conn;

    $query_users = "SELECT * FROM user";
    $result_users = mysqli_query($conn, $query_users);

    $pengguna = [];
    while ($row_user = mysqli_fetch_assoc($result_users)) {
        $pengguna[$row_user['id_user']] = $row_user;
    }
    return $pengguna;
}

// Fungsi untuk mendapatkan pesan terbaru dari pengguna
function getLatestMessage($user_id)
{
    global $conn;

    $query_latest = "SELECT pesan FROM chat WHERE id_user = '$user_id' AND admin != 'Admin' ORDER BY tanggal DESC, jam DESC LIMIT 1";
    $result_latest = mysqli_query($conn, $query_latest);
    $latest_message = mysqli_fetch_assoc($result_latest);
    return $latest_message ? $latest_message['pesan'] : '';
}

// Fungsi untuk mendapatkan jumlah pesan baru dari pengguna
function getNewMessagesCount($user_id)
{
    global $conn;

    $query_new_messages = "SELECT COUNT(*) as new_messages FROM chat WHERE id_user = '$user_id' AND admin != 'Admin' AND read_status = 0";
    $result_new_messages = mysqli_query($conn, $query_new_messages);
    $new_messages = mysqli_fetch_assoc($result_new_messages);
    return $new_messages ? $new_messages['new_messages'] : 0;
}

// Fungsi untuk mendapatkan chat antara admin dan pengguna terkait
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

// Fungsi untuk memotong pesan yang terlalu panjang
function truncateMessage($message, $length = 30)
{
    if (strlen($message) > $length) {
        return substr($message, 0, $length) . '...';
    }
    return $message;
}

$users = getUsers();
$pengguna = getUsers2();

// Memproses pengguna yang dipilih
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_user'])) {
    $_SESSION['selected_user_id'] = $_POST['selected_user'];

    $selected_user_id = $_SESSION['selected_user_id'];

    if (array_key_exists($selected_user_id, $users)) {
        $_SESSION['selected_user_name'] = $users[$selected_user_id]['nama_lengkap'];

        // Tandai pesan sebagai sudah dibaca
        markChatAsRead($selected_user_id);
    } else {
        $_SESSION['selected_user_name'] = $pengguna[$selected_user_id]['nama_lengkap'];
        
        // Tandai pesan sebagai sudah dibaca
        markChatAsRead($selected_user_id);
    } 
}


if (isset($_SESSION["role"]) && isset($_SESSION["selected_user_id"])) {
    $role = $_SESSION["role"];
    $user_id = $_SESSION["selected_user_id"];
    $chat_messages = getChatMessages($role, $user_id);
    markChatAsRead($user_id);
}

// Fungsi untuk menandai pesan sebagai sudah dibaca
function markChatAsRead($user_id) {
    global $conn;

    $query_mark_read = "UPDATE chat SET read_status = 1 WHERE id_user = '$user_id' AND admin != 'Admin' AND read_status = 0";
    mysqli_query($conn, $query_mark_read);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Chat | BP Futsal</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="../img/logo-bpfutsalcenter.png" type="image" />
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["assets/css/fonts.min.css"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
    <style>
        .chat-box {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 10px;
            max-height: 400px;
            overflow-y: auto;
        }

        .chat-messages {
            margin-bottom: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .message-box {
            margin-bottom: 10px;
        }

        .message {
            max-width: 70%;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .outgoing {
            background-color: #DCF8C6;
            align-self: flex-end;
            text-align: left;
        }

        .incoming {
            background-color: #EDEDED;
            align-self: flex-start;
            text-align: left;
        }

        .message strong {
            font-weight: bold;
        }

        .timestamp {
            font-size: 0.8em;
            color: #999999;
        }

        .user-input {
            margin-top: 20px;
        }

        .user-input input {
            border-radius: 5px;
        }

        .user-input button {
            border-radius: 5px;
        }

        .latest-message {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
            max-width: 120px;
            /* Adjust this value as needed */
            vertical-align: middle;
        }

        .user-list {
            max-height: 400px;
            /* Adjust this value as needed */
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="beranda.php" class="logo" style="color: #ffffff; font-weight:bold;">
                        <img src="../img/logo-bpfutsalcenter.png" alt="navbar brand" class="navbar-brand"
                            height="40" />
                        SI | BP FUTSAL
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item">
                            <a href="beranda.php" class="collapsed" aria-expanded="false">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">MENU</h4>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#pengguna">
                                <i class="fas fa-users"></i>
                                <p>Data Pengguna</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="pengguna">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="admin.php">
                                            <span class="sub-item">Admin</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="pengguna.php">
                                            <span class="sub-item">Pengguna</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#pesan">
                                <i class="fas fa-envelope"></i>
                                <p>Pemesanan</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="pesan">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="pesan.php">
                                            <span class="sub-item">Belum Bayar</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="bayar.php">
                                            <span class="sub-item">Sudah Bayar</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="lapangan.php">
                                <i class="fas fa-futbol"></i>
                                <p>Kelola Lapangan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="event.php">
                                <i class="fas fa-tags"></i>
                                <p>Kelola Event & Berita</p>
                            </a>
                        </li>
                        <li class="nav-item active">
                            <a href="chat.php">
                                <i class="fas fa-comments"></i>
                                <p>Chat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="saran.php">
                                <i class="fas fa-receipt"></i>
                                <p>Saran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="metode.php">
                                <i class="fas fa-money-check-alt"></i>
                                <p>Metode Pembayaran</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.html" class="logo">
                            <img src="assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand"
                                height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">

                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="../img/<?= $_SESSION['foto']; ?>" alt="Foto Profil"
                                            class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">
                                        <span class="op-7">Hi,</span>
                                        <span class="fw-bold"><?= $_SESSION["nama"]; ?></span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img src="../img/<?= $_SESSION['foto']; ?>" alt="Foto Profil"
                                                        class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">
                                                    <h4><?= $_SESSION["nama"]; ?></h4>
                                                    <p class="text-muted"><?= $_SESSION["email"]; ?></p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="../logout.php">Logout</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>

            <div class="container">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Chat</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card card-round">
                                <div class="card-body">
                                    <div class="card-head-row card-tools-still-right">
                                        <div class="card-title">Daftar Pengguna</div>
                                        <div class="card-tools">
                                            <div class="dropdown">
                                                <button
                                                    class="btn btn-icon btn-clean me-0"
                                                    type="button"
                                                    id="dropdownMenuButton"
                                                    data-bs-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false"
                                                >
                                                    <i class="fas fa-plus-circle"></i>
                                                </button>
                                                <div
                                                    class="dropdown-menu"
                                                    aria-labelledby="dropdownMenuButton"
                                                    style="max-height: 400px; overflow-y: auto;">
                                                    <?php foreach ($pengguna as $user): ?>
                                                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                                            <input type="hidden" name="selected_user" value="<?php echo $user['id_user']; ?>">
                                                            <button type="submit" class="dropdown-item"><?php echo $user['nama_lengkap']; ?></button>
                                                        </form>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-list py-4" style="max-height: 400px; overflow-y: auto;">
                            <?php foreach ($users as $user): ?>
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <input type="hidden" name="selected_user" value="<?php echo $user['id_user']; ?>">
                                    <div class="item-list">
                                        <div class="avatar">
                                            <img src="../img/<?= $user["foto"]; ?>" alt="..."
                                                class="avatar-img rounded-circle" />
                                        </div>
                                        <div class="info-user ms-3">
                                            <div class="username"><?php echo $user['nama_lengkap']; ?></div>
                                            <div class="status">
                                                <?php echo truncateMessage(getLatestMessage($user['id_user'])); ?>
                                            </div>
                                        </div>

                                        <?php if ($new_messages_count = getNewMessagesCount($user['id_user'])): ?>
                                            <button class="btn btn-icon btn-round btn-danger op-8 me-1">
                                                <?php echo $new_messages_count; ?>
                                            </button>
                                        <?php endif; ?>

                                        <button type="submit" style="text-decoration:none; color: #0D6EFD; border:none;"
                                            class="btn btn-icon op-8">
                                            <i class="far fa-envelope"></i>
                                        </button>
                                    </div>
                                </form>
                            <?php endforeach; ?>
                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
    <div class="card card-round">
        <div class="card-header">
            <div class="card-head-row card-tools-still-right">
                <div class="card-title">
                    <?php echo isset($_SESSION['selected_user_name']) ? $_SESSION['selected_user_name'] : 'Pilih Pengguna'; ?>
                </div>
                <div class="card-tools">
                    <div class="dropdown">
                        <!-- Tambahkan dropdown atau tombol untuk memilih pengguna di sini jika diperlukan -->
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="chat-box" id="chat-box">
                <?php if (isset($_SESSION["selected_user_id"])): ?>
                    <div class="chat-messages" id="chatMessages">
                        <?php if (!empty($chat_messages)): ?>
                            <?php foreach ($chat_messages as $message): ?>
                                <div class="message <?php echo $message['admin'] === 'Admin' ? 'outgoing' : 'incoming'; ?>">
                                    <p><?php echo $message['pesan']; ?></p>
                                    <span class="timestamp"><?php echo substr($message['jam'], 0, 5); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Tidak ada pesan untuk ditampilkan.</p>
                        <?php endif; ?>
                    </div>
            </div>
        </div>
<div class="card-footer p-0">
        <div style="padding: 10px">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="sendMessageForm">
                    <div class="form-group user-input d-flex">
                        <textarea class="form-control" name="pesan" rows="3" placeholder="Ketik pesan..." id="pesanSaya" required></textarea>
                        <button type="submit" class="btn btn-primary mr-2">Kirim</button>
                    </div>
                </form>
            <?php else: ?>
                <p>Pilih pengguna untuk memulai percakapan.</p>
            <?php endif; ?>
        </div>
    </div>
    </div>
</div>

                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
                <div class="copyright">
                    @ 2024, dikembangkan oleh
                    <a href="http://www.themekita.com">Politeknik Caltex Riau</a>
                </div>
            </div>
        </footer>
    </div>
    </div>

    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>
    <script src="assets/js/kaiadmin.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
    // Function to load chat messages initially and refresh every 5 seconds
    function loadChatMessages() {
        $.ajax({
            url: 'fetch_messages.php', // Replace with your PHP script handling chat messages
            method: 'GET',
            dataType: 'html',
            success: function(data) {
                $('#chatMessages').html(data); // Replace content of chatMessages div
            }
        });
    }

    function scrollToBottom() {
        var chatMessagesDiv = document.getElementById('chat-box');
        chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
    }

    scrollToBottom();

    // Load chat messages on page load
    loadChatMessages();

    // Submit form using AJAX for sending messages
    $('#sendMessageForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: 'send_message.php', // Replace with your PHP script handling message sending
            method: 'POST',
            data: formData,
            success: function(response) {
                // On successful send, reload chat messages
                loadChatMessages();
                $('#pesanSaya').val(''); // Clear the message input field
            }
        });
    });

    // Refresh chat messages every 1 seconds
    setInterval(loadChatMessages, 1000); // Adjust interval as needed
});
    </script>
</body>

</html>