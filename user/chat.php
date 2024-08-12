<?php
session_start();
require "../functions.php";
require "../session.php";

if ($role !== 'User') {
  header("location:../login.php");
}

// Periksa apakah $_SESSION["id_user"] telah diatur
if (isset($_SESSION["id_user"])) {
    $id_user = $_SESSION["id_user"];
  
    // Ambil data profil pengguna dari database
    $profil = query("SELECT * FROM user WHERE id_user = '$id_user'")[0];
  
    if (isset($_POST["simpan"])) {
        // Lakukan pengeditan profil jika formulir disubmit
        if (edit($_POST) > 0) {
            echo "<script>
                  alert('Berhasil Diubah');
                  </script>";
        } else {
            echo "<script>
                  alert('Gagal Diubah');
                  </script>";
        }
    }
}

// Memproses pesan yang dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pesan = $_POST["pesan"];
    $id_user = $_SESSION["id_user"]; // Ambil ID pengguna dari sesi
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
        // Refresh halaman setelah mengirim pesan
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        // Tampilkan pesan kesalahan jika gagal
        echo "Gagal mengirim pesan.";
    }
}
  
// Fungsi untuk mengambil pesan dari database
function getChatMessages() {
    global $conn;
    $user_me = $_SESSION['id_user'];
    // Query untuk mengambil pesan dari database
    $query_chat = "SELECT * FROM chat WHERE id_user='$user_me' ORDER BY tanggal ASC";
    $result_chat = mysqli_query($conn, $query_chat);
  
    // Membuat array untuk menampung pesan
    $chat_messages = [];
  
    // Looping untuk mengambil data pesan
    while ($row_chat = mysqli_fetch_assoc($result_chat)) {
        // Tambahkan data pesan ke dalam array
        $chat_messages[] = $row_chat;
    }
  
    // Mengembalikan array pesan
    return $chat_messages;
}
  
// Memanggil fungsi untuk mengambil pesan dari database
$chat_messages = getChatMessages();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Lapangan</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../bootstrap.min.css">
  <script src="https://unpkg.com/feather-icons"></script>
  <style>
    .chat-box {
        background-color: #f5f5f5;
        border-radius: 10px;
        padding: 20px;
    }

    .chat-messages {
        margin-bottom: 20px;
        max-height: 400px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .message {
        max-width: 70%;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
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

    .timestamp {
        font-size: 0.8em;
        color: #999999;
    }

    .chat-form {
        margin-top: 20px;
    }

    .user-input {
        margin-top: 20px;
        display: flex;
    }

    .user-input input {
        flex: 1;
        border-radius: 5px;
        padding: 10px;
        border: 1px solid #ccc;
    }

    .user-input button {
        border-radius: 5px;
        padding: 10px 20px;
        margin-left: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .user-input button:hover {
        background-color: #0056b3;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <div class="container">
    <nav class="navbar fixed-top bg-body-secondary navbar-expand-lg">
      <div class="container">
        <a class="navbar-brand" href="#beranda">
          ADEBE FUTSAL
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="../index.php">Beranda</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="lapangan.php">Lapangan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="bayar.php">Pembayaran</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="chat.php">Chat</a>
            </li>
          </ul>
          <?php
          if (isset($_SESSION['id_user'])) {
            echo '<a href="user/profil.php" data-bs-toggle="modal" data-bs-target="#profilModal" class="btn btn-inti">'.$profil["nama_lengkap"].'</a>';
          } else {
            echo '<a href="login.php" class="btn btn-inti" type="submit">Login</a>';
          }
          ?>
        </div>
      </div>
    </nav>
  </div>
  <!-- End Navbar -->

  <!-- Modal Profil -->
  <div class="modal fade" id="profilModal" tabindex="-1" aria-labelledby="profilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="profilModalLabel">Profil Pengguna</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-4 my-5">
                <img src="../img/<?= $profil["foto"]; ?>" alt="Foto Profil" class="img-fluid ">
              </div>
              <div class="col-8">
                <h5 class="mb-3"><?= $profil["nama_lengkap"]; ?></h5>
                <p><?= $profil["jenis_kelamin"]; ?></p>
                <p><?= $profil["email"]; ?></p>
                <p><?= $profil["no_handphone"]; ?></p>
                <p><?= $profil["alamat"]; ?></p>
                <a href="../logout.php" class="btn btn-danger">Logout</a>
                <a href="" data-bs-toggle="modal" data-bs-target="#editProfilModal" class="btn btn-inti">Edit Profil</a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Modal Profil -->

  <!-- Edit profil -->
  <div class="modal fade" id="editProfilModal" tabindex="-1" aria-labelledby="editProfilModalLabel" aria-hidden="true">
    <div class="modal-dialog edit modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProfilModalLabel">Edit Profil</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="fotoLama" class="form-control" id="exampleInputPassword1" value="<?= $profil["foto"]; ?>">
          <div class="modal-body">
            <div class="row justify-content-center align-items-center">
              <div class="mb-3">
                <img src="../img/<?= $profil["foto"]; ?>" alt="Foto Profil" class="img-fluid ">
              </div>
              <div class="col">
                <div class="mb-3">
                  <label for="exampleInputPassword1" class="form-label">Nama Lengkap</label>
                  <input type="text" name="nama_lengkap" class="form-control" id="exampleInputPassword1" value="<?= $profil["nama_lengkap"]; ?>">
                </div>
                <div class="mb-3">
                  <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                  <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="Laki-laki" <?php if ($profil['jenis_kelamin'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php if ($profil['jenis_kelamin'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="mb-3">
                  <label for="exampleInputPassword1" class="form-label">No Telp</label>
                  <input type="number" name="hp" class="form-control" id="exampleInputPassword1" value="<?= $profil["no_handphone"]; ?>">
                </div>
                <div class="mb-3">
                  <label for="exampleInputPassword1" class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" id="exampleInputPassword1" value="<?= $profil["email"]; ?>">
                </div>
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Alamat</label>
                <input type="text" name="alamat" class="form-control" id="exampleInputPassword1" value="<?= $profil["alamat"]; ?>">
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Foto : </label>
                <input type="file" name="foto" class="form-control" id="exampleInputPassword1">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-inti" name="simpan" id="simpan">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End Edit Modal -->

  <!-- Chat -->
  <section id="contact" class="contact" data-aos="fade-down" data-aos-duration="1000">
    <h2><span>ADEBE</span>Chat</h2>
    <p class="text-center m-5">
      Hubungi kami jika ada yang ingin ditanyakan!
    </p>
    <div class="container">
      <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="chat-box">
                <div class="chat-messages">
                <?php if (isset($chat_messages)): ?>
                    <?php foreach ($chat_messages as $message): ?>
                        <div class="message <?php echo $message['admin'] === 'Admin' ? 'incoming' : 'outgoing'; ?>">
                            <p><strong><?php echo $message['admin'] === 'Admin' ? 'Admin' : 'Saya'; ?>:</strong> <?php echo $message['pesan']; ?></p>
                            <span class="timestamp"><?php echo substr($message['jam'], 0, 5); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                </div>
                <!-- Formulir untuk pengguna mengirim pesan -->
                <form method="post" class="chat-form">
                    <div class="form-group">
                        <textarea class="form-control" name="pesan" rows="3" placeholder="Ketik pesan..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </form>
            </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Chat -->

  <!-- footer -->
  <footer class="py-3">
    <div class="credit">
      <p>Created by Alvansyah Hutasoit &copy; 2024</p>
    </div>
  </footer>
  <!-- End Footer -->
  <script src="../bootstrap.bundle.min.js"></script>
</body>
</html>
