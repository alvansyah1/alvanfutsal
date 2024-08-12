<?php
session_start();
require "functions.php";

if (isset($_SESSION["role"])) {
  $role = $_SESSION["role"];
  if ($role == "Admin") {
    header("Location: admin/beranda.php");
  } else {
    header("Location: user/lapangan.php");
  }
}

if (isset($_POST["login"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $cariadmin = query("SELECT * FROM admin WHERE email = '$username' AND password = '$password'");
  $cariuser = query("SELECT * FROM user WHERE email = '$username' AND password = '$password'");

  if ($cariadmin) {
    // set session
    $_SESSION['id_user'] = $cariadmin[0]['id_user'];
    $_SESSION['email'] = $cariadmin[0]['email'];
    $_SESSION['username'] = $cariadmin[0]['username'];
    $_SESSION['nama'] = $cariadmin[0]['nama'];
    $_SESSION['foto'] = $cariadmin[0]['foto'];
    $_SESSION['role'] = "Admin";
    header("Location: admin/beranda.php");
  } else if ($cariuser) {
    // set session
    $_SESSION['email'] = $cariuser[0]['email'];
    $_SESSION['id_user'] = $cariuser[0]['id_user'];
    $_SESSION['role'] = "User";
    header("Location: user/lapangan.php");
  } else {
    echo "<div class='alert alert-warning'>Username atau Password salah</div>
    <meta http-equiv='refresh' content='2'>";
  }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Login BP Futsal</title>
  <link rel="icon" href="img/logo-bpfutsalcenter.png" type="image" />
  <link rel="stylesheet" href="bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    body.login {
      background-color: #1b1b1b; /* Warna latar belakang yang lebih gelap */
      color: #fff; /* Warna teks putih */
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .center {
      background-color: #2d2d2d; /* Warna latar belakang form yang lebih gelap */
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
      max-width: 400px;
      width: 100%;
    }

    .center h1 {
      margin-bottom: 30px;
      color: #198754; /* Warna hijau untuk judul */
    }

    .txt_field {
      position: relative;
      margin-bottom: 30px;
    }

    .txt_field input {
      width: 100%;
      padding: 10px 10px 10px 5px;
      background: none;
      border: none;
      border-bottom: 2px solid #198754; /* Warna hijau untuk garis bawah */
      color: #fff;
      font-size: 18px;
    }

    .txt_field label {
      position: absolute;
      top: 50%;
      left: 5px;
      color:#198754; /* Warna hijau untuk label */
      transform: translateY(-50%);
      font-size: 18px;
      transition: .5s;
    }

    .txt_field input:focus ~ label,
    .txt_field input:valid ~ label {
      top: -5px;
      color: #198754;
      font-size: 14px;
    }

    .txt_field span::before {
      content: '';
      position: absolute;
      top: 40px;
      left: 0;
      width: 100%;
      height: 2px;
      background: #198754; /* Warna hijau untuk garis bawah */
      transition: .5s;
    }

    .pass {
      margin: -20px 0 20px;
      text-align: right;
      color: #198754; /* Warna hijau untuk teks */
    }

    .button.btn-inti {
      background: #198754; /* Warna hijau untuk tombol */
      border: none;
      padding: 10px 20px;
      color: #fff;
      font-size: 18px;
      cursor: pointer;
      transition: .5s;
      border-radius: 5px;
    }

    .button.btn-inti:hover {
      background: #28a745; /* Warna hijau yang lebih gelap saat dihover */
    }

    .signup_link {
      margin-top: 20px;
      text-align: center;
      color: #fff; /* Warna hijau untuk teks */
    }

    .signup_link a {
      color: #198754; /* Warna hijau untuk link */
      text-decoration: none;
    }

    .signup_link a:hover {
      text-decoration: none;
    }

    .alert {
      position: absolute;
      top: 20px;
      right: 20px;
      padding: 15px;
      background-color: #ffc107;
      color: #000;
      border-radius: 5px;
    }
    .txt_field {
      position: relative;
    }
    .txt_field input {
      padding-right: 40px; /* Menambahkan padding agar ikon mata tidak menutupi teks */
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

<body class="login">
  <div class="center">
    <h1>Login</h1>
    <form method="POST">
      <div class="txt_field">
        <input type="email" name="username" required>
        <span></span>
        <label>Email</label>
      </div>
      <div class="txt_field">
    <input type="password" id="password" name="password" required>
    <span></span>
    <label>Password</label>
    <i class="toggle-password" onclick="togglePassword()">👁️</i> <!-- Ikon mata -->
  </div>
      <button class="button btn-inti" name="login" id="login">Login</button>
      <div class="signup_link">
        Belum punya akun? <a href="user/daftar.php">Daftar</a>.
      </div>
    </form>
  </div>
  <script>
    function togglePassword() {
      var passwordField = document.getElementById("password");
      var toggleIcon = document.querySelector(".toggle-password");
      if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.textContent = "🙈"; // Ikon untuk menutup mata
      } else {
        passwordField.type = "password";
        toggleIcon.textContent = "👁️"; // Ikon untuk membuka mata
      }
    }b
  </script>
</body>

</html>
