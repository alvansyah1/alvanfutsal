<?php
require "../functions.php";

if (isset($_POST["daftar"])) {
  if (daftar($_POST) > 0) {
    echo "<div class='alert alert-success'>Berhasil mendaftar, silakan login.</div>
            <meta http-equiv='refresh' content='2; url= ../login.php'/>  ";
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrasi</title>
  <link rel="icon" href="img/logo-bpfutsalcenter.png" type="image" />
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif&family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet" />
  <style>
    body {
      background-color: #1b1b1b; /* Warna latar belakang yang lebih gelap */
      color: #fff; /* Warna teks putih */
      font-family: 'Poppins', sans-serif;
      height: 900px; /* Atur tinggi elemen sesuai kebutuhan */
      overflow-y: auto; /* Aktifkan scroll vertikal */
    }

    .center {
      position: relative;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 100%;
      max-width: 600px;
      background: #2d2d2d; /* Warna latar belakang form yang lebih gelap */
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
      padding: 40px;
      box-sizing: border-box;
    }

    .center h1 {
      text-align: center;
      padding: 10px 0;
      color: #198754   ; /* Warna hijau untuk judul */
    }

    .form-control {
      background-color: #3b3b3b; /* Warna latar belakang input */
      color: #fff; /* Warna teks input */
      border: none;
      border-bottom: 2px solid #198754; /* Garis bawah input warna hijau */
      margin-bottom: 20px;
    }

    .form-control:focus {
      background-color: #3b3b3b; /* Warna latar belakang input saat fokus */
      color: #fff; /* Warna teks input saat fokus */
      box-shadow: none;
      border-bottom: 2px solid #28a745; /* Garis bawah input warna hijau saat fokus */
    }

    .form-label {
      color: #198754; /* Warna teks label */
    }

    .btn-inti {
      background-color: #38b000; /* Warna tombol hijau */
      border: none;
      color: #fff; /* Warna teks tombol */
      padding: 10px 20px;
      cursor: pointer;
      border-radius: 5px;
      transition: background-color 0.3s ease;
      width: 100%;
    }

    .btn-inti:hover {
      background-color: #28a745; /* Warna tombol hijau lebih gelap saat hover */
    }

    .alert {
      background-color: #ffc107; /* Warna latar belakang alert */
      color: #000; /* Warna teks alert */
      padding: 15px;
      border-radius: 5px;
      margin-top: 20px;
      
    }
    .txt_field {
      position: relative;
      margin-bottom: 20px;
    }
    .txt_field input {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 4px;
      color: #fff;
    }
    .txt_field label {
      position: absolute;
      top: 50%;
      left: 10px;
      color: #999;
      pointer-events: none;
      transform: translateY(-50%);
      transition: all 0.2s;
    }
    .txt_field input:focus + label,
    .txt_field input:not(:placeholder-shown) + label {
      top: 0;
      left: 10px;
      font-size: 12px;
      color: #333;
    }
    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }

  </style>
</head>

<body>
  <div class="center">
    <form action="" method="post" enctype="multipart/form-data">
      <h1>Registrasi</h1>
      <div class="mb-3">
        <label for="nama" class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" id="nama" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" id="email" required>
      </div>
      <div class="mb-3">
        <label for="hp" class="form-label">No Hp</label>
        <input type="number" name="hp" class="form-control" id="hp" required>
      </div>
      <div class="txt_field">
    <input type="password" id="password" name="password" required placeholder=" " minlength="5">
    <span></span>
    <label for="password">Password</label>
    <i class="toggle-password" onclick="togglePassword()">👁️</i> <!-- Ikon mata -->
  </div>
      <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea name="alamat" class="form-control" id="alamat" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Jenis Kelamin</label><br>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="gender" id="male" value="Laki-Laki">
          <label class="form-check-label" for="male">Laki-Laki</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="gender" id="female" value="Perempuan">
          <label class="form-check-label" for="female">Perempuan</label>
        </div>
      </div>
      <div class="mb-3">
        <label for="foto" class="form-label">Foto</label>
        <input type="file" name="foto" class="form-control" id="foto" required>
      </div>
      <button type="submit" class="btn btn-inti" name="daftar">Daftar</button>
    </form>
  </div>
  <script> 
    function togglePassword() {
    const passwordField = document.getElementById('password');
    const passwordToggle = document.querySelector('.toggle-password');
    if (passwordField.type === 'password') {
      passwordField.type = 'text';
      passwordToggle.textContent = '🙈';
    } else {
      passwordField.type = 'password';
      passwordToggle.textContent = '👁️';
    }
  }

  function validatePassword() {
    const password = document.getElementById('password').value;
    if (password.length < 5) {
      alert('Password harus memiliki minimal 5 karakter.');
      return false;
    }
    return true;
  }
  </script>
</body>
</html>