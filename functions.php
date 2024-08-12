<?php

$conn = mysqli_connect("localhost", "root", "", "dbfutsal1");

function query($query)
{
  global $conn;
  $result = mysqli_query($conn, $query);
  if (!$result) {
    // Jika terjadi kesalahan dalam kueri, tampilkan pesan kesalahan
    echo "Error in query: " . mysqli_error($conn);
    exit; // Berhenti eksekusi skrip
  }
  // Jika kueri berhasil, kembalikan hasilnya
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
      $rows[] = $row;
  }
  return $rows;
}
function hapusPengguna($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM user WHERE id_user = $id");

  return mysqli_affected_rows($conn);
}

function hapusLpg($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM lapangan WHERE id_lapangan = $id");

  return mysqli_affected_rows($conn);
}

function hapusAdmin($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM admin WHERE id_user = $id");

  return mysqli_affected_rows($conn);
}

function hapusPesan($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM sewa WHERE id_sewa = $id");

  return mysqli_affected_rows($conn);
}

function daftar($data)
{
  global $conn;

  $username = strtolower(stripslashes($data["email"]));
  $password = $data["password"];
  $nama = $data["nama"];
  $no_handphone = $data["hp"];
  $alamat = $data["alamat"];
  $gender = $data["gender"];
  //Upload Gambar
  $upload = upload();
  if (!$upload) {
    return false;
  }

  $result = mysqli_query($conn, "SELECT email FROM user WHERE email = '$username'");

  if (mysqli_fetch_assoc($result)) {
    echo "<script>
            alert('Username sudah terdaftar!');
        </script>";
    return false;
  }
  mysqli_query($conn, "INSERT INTO user (email,password,no_handphone,jenis_kelamin,nama_lengkap,alamat,foto) VALUES ('$username','$password','$no_handphone','$gender','$nama','$alamat','$upload')");
  return mysqli_affected_rows($conn);
}

function edit($data)
{
  global $conn;

  $userid = $_SESSION["id_user"];
  $username = strtolower(stripslashes($data["email"]));
  $nama = $data["nama_lengkap"];
  $no_handphone = $data["hp"];
  $gender = $data["jenis_kelamin"];
  $gambar = $data["foto"];
  $gambarLama = $data["fotoLama"];
 
  // Cek apakah User pilih gambar baru
  if ($_FILES["foto"]["error"] === 4) {
    $gambar = $gambarLama;
  } else {
    $gambar = upload();
  }

  $query = "UPDATE user SET email = '$username', 
  nama_lengkap = '$nama',
  no_handphone = '$no_handphone',
  jenis_kelamin = '$gender',
  foto = '$gambar'
  WHERE id_user = '$userid'
  ";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function pesan($data)
{
  global $conn;

  date_default_timezone_set('Asia/Jakarta');

  $userid = $_SESSION["id_user"];
  $idlpg = $data["id_lpg"];
  $tglpesan = $data["tgl_pesan"];
  $jam_pesan = date('H:i:s');
  $lama =  $data["lama_main"];
  $mulai = $data["tgl_main"];
  $mulai_waktu = strtotime($mulai); // mengubah format datetime-local menjadi format UNIX timestamp
  $habis_waktu = $mulai_waktu + (intval($lama) * 3600); // menambahkan waktu dalam menit ke waktu awal
  $habis = date('Y-m-d\TH:i', $habis_waktu); // mengubah format waktu kembali ke datetime-local
  $habis_datetime_local = date('Y-m-d\TH:i:s', strtotime($habis)); // mengubah format waktu dari Y-m-d\TH:i ke format datetime-local
  $habis = $habis_datetime_local; // menyimpan hasil ke dalam variabel $habis_datetime_local
  $harga = $data["harga"];
  $total = $lama * $harga;

  mysqli_query($conn, "INSERT INTO sewa (id_user, id_lapangan, tanggal_pesan, jam_pesan, lama_sewa, jam_mulai, jam_habis, harga, total) VALUES ('$userid','$idlpg','$tglpesan','$jam_pesan','$lama','$mulai','$habis','$harga','$total') ");

  return mysqli_affected_rows($conn);
}

function bayar($data)
{
    global $conn;
    $id_sewa = $data["id_sewa"];
    $metode = $data["metode_pembayaran"];
    $status = $data["status_pembayaran"];
    $dp = $data["dp"];
    $kurang = $data["kekurangan_bayar"];

    // Upload Gambar
    $upload = uploadPembayaran();

    // Insert into database
    $query = "INSERT INTO bayar (id_sewa, metode_bayar, status, bayar_dp, kekurangan, bukti, konfirmasi)
              VALUES ('$id_sewa', '$metode', '$status', '$dp', '$kurang', '$upload', 'Sudah Bayar')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function tambahLpg($data)
{
  global $conn;

  $lapangan = $data["lapangan"];
  $harga1 = $data["harga1"];
  $harga2 = $data["harga2"];
  $harga3 = $data["harga3"];
  $ket = $data["keterangan"];

  //Upload Gambar
  $upload = upload();
  if (!$upload) {
    return false;
  }


  $query = "INSERT INTO lapangan (nama,harga1,harga2,harga3,keterangan,foto) VALUES ('$lapangan','$harga1','$harga2','$harga3','$ket','$upload')";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function upload()
{
  $namaFile = $_FILES['foto']['name'];
  $ukuranFile = $_FILES['foto']['size'];
  $error = $_FILES['foto']['error'];
  $tmpName = $_FILES['foto']['tmp_name'];

  // Cek apakah tidak ada gambar yang di upload
  if ($error === 4) {
    echo "<script>
    alert('Pilih gambar terlebih dahulu!');
    </script>";
    return false;
  }

  // Cek apakah gambar
  $extensiValid = ['jpg', 'png', 'jpeg'];
  $extensiGambar = explode('.', $namaFile);
  $extensiGambar = strtolower(end($extensiGambar));

  if (!in_array($extensiGambar, $extensiValid)) {
    echo "<script>
    alert('Yang anda upload bukan gambar!');
    </script>";
    return false;
  }

  if ($ukuranFile > 1000000) {
    echo "<script>
    alert('Ukuran Gambar Terlalu Besar!');
    </script>";
    return false;
  }

  $namaFileBaru = uniqid();
  $namaFileBaru .= '.';
  $namaFileBaru .= $extensiGambar;
  // Move File
  move_uploaded_file($tmpName, '../img/' . $namaFileBaru);
  return $namaFileBaru;
}

function uploadPembayaran()
{
  $namaFile = $_FILES['foto']['name'];
  $ukuranFile = $_FILES['foto']['size'];
  $tmpName = $_FILES['foto']['tmp_name'];

  // Cek apakah gambar
  $extensiValid = ['jpg', 'png', 'jpeg'];
  $extensiGambar = explode('.', $namaFile);
  $extensiGambar = strtolower(end($extensiGambar));

  if (!in_array($extensiGambar, $extensiValid)) {
    echo "<script>
    alert('Yang anda upload bukan gambar!');
    </script>";
    return false;
  }

  if ($ukuranFile > 1000000) {
    echo "<script>
    alert('Ukuran Gambar Terlalu Besar!');
    </script>";
    return false;
  }

  $namaFileBaru = uniqid();
  $namaFileBaru .= '.';
  $namaFileBaru .= $extensiGambar;
  // Move File
  move_uploaded_file($tmpName, '../img/' . $namaFileBaru);
  return $namaFileBaru;
}

function editLpg($data)
{
  global $conn;

  $id = $data["idlap"];
  $lapangan = $data["lapangan"];
  $ket = $data["ket"];
  $harga1 = $data["harga1"];
  $harga2 = $data["harga2"];
  $harga3 = $data["harga3"];
  $gambarLama =  $data["fotoLama"];

  // Cek apakah User pilih gambar baru
  if ($_FILES["foto"]["error"] === 4) {
    $gambar = $gambarLama;
  } else {
    $gambar = upload();
  }


  $query = "UPDATE lapangan SET 
  nama = '$lapangan',
  keterangan = '$ket',
  harga1 = '$harga1',
  harga2 = '$harga2',
  harga3 = '$harga3',
  foto = '$gambar' WHERE id_lapangan = '$id'
  ";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}


function tambahAdmin($data)
{
  global $conn;

  $username = $data["username"];
  $password = $data["password"];
  $nama = $data["nama"];
  $no_handphone= $data["hp"];
  $email = $data["email"];
  
  //Upload Gambar
  $upload = upload();
  if (!$upload) {
    return false;
  }

  $query = "INSERT INTO admin (username,password,nama,no_handphone,email,foto) VALUES ('$username','$password','$nama','$no_handphone','$email','$upload')";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function editAdmin($data)
{
  global $conn;

  $id = $data["id"];
  $username = $data["username"];
  $password = $data["password"];
  $nama = $data["nama"];
  $no_handphone= $data["hp"];
  $email = $data["email"];
  $gambarLama =  $data["fotoLama"];

  // Cek apakah User pilih gambar baru
  if ($_FILES["foto"]["error"] === 4) {
    $gambar = $gambarLama;
  } else {
    $gambar = upload();
  }

  $query = "UPDATE admin SET 
  username = '$username',
  password = '$password',
  nama = '$nama',
  no_handphone = '$no_handphone',
  email  = '$email',
  foto = '$gambar' WHERE id_user = '$id'
  ";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function konfirmasi($id_sewa)
{
  global $conn;

  $id = $id_sewa;

  mysqli_query($conn, "UPDATE bayar set konfirmasi = ('Terkonfirmasi') WHERE id_sewa = '$id'");
  return mysqli_affected_rows($conn);
}

function chat($data)
{
  global $conn;

  $pesan = $data["pesan"];
  $id_user = $data["id_user"];
  $id_admin = $data["id_admin"];
  $admin = $data["admin"];
  $tanggal = $data["tanggal"];
  $jam = $data["jam"];

  $query = "INSERT INTO chat (id_user,id_admin,admin,pesan,tanggal,jam) VALUES ('$id_user','$id_admin','$admin','$pesan','$tanggal','$jam')";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function tambahEvent($data)
{
  global $conn;

  $event = $data["nama"];
  $harga = $data["harga"];
  $ket = $data["keterangan"];

  //Upload Gambar
  $upload = upload();
  if (!$upload) {
    return false;
  }


  $query = "INSERT INTO event (nama,harga,keterangan,foto) VALUES ('$event','$harga','$ket','$upload')";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function hapusEvent($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM event WHERE id = $id");

  return mysqli_affected_rows($conn);
}

function editEvent($data)
{
  global $conn;

  $id = $data["id"];
  $event = $data["nama"];
  $ket = $data["keterangan"];
  $harga = $data["harga"];
  $gambarLama =  $data["fotoLama"];

  // Cek apakah User pilih gambar baru
  if ($_FILES["foto"]["error"] === 4) {
    $gambar = $gambarLama;
  } else {
    $gambar = upload();
  }


  $query = "UPDATE event SET 
  nama = '$event',
  keterangan = '$ket',
  harga = '$harga',
  foto = '$gambar' WHERE id = '$id'
  ";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function tambahSaran($data)
{
  global $conn;

  $userid = $_SESSION["id_user"];
  $saran = $data["saran"];

  $query = "INSERT INTO saran (saran,id_user) VALUES ('$saran','$userid')";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function hapusSaran($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM saran WHERE id_saran = '$id'");

  return mysqli_affected_rows($conn);
}

function tambahMetode($data)
{
  global $conn;

  $nama = $data["nama"];
  $metode = $data["metode"];
  $nomor = $data["nomor"];
  $dp = $data["dp"];

  $query = "INSERT INTO metode_pembayaran (nama,metode,nomor,nominal_dp) VALUES ('$nama','$metode','$nomor','$dp')";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function hapusMetode($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM metode_pembayaran WHERE id_metode_bayar = $id");

  return mysqli_affected_rows($conn);
}

function editMetode($data)
{
  global $conn;

  $id = $data["id_metode_bayar"];
  $nama = $data["nama"];
  $metode = $data["metode"];
  $nomor = $data["nomor"];
  $dp = $data["dp"];

  $query = "UPDATE metode_pembayaran SET 
  nama = '$nama',
  metode = '$metode',
  nomor = '$nomor', nominal_dp = '$dp' WHERE id_metode_bayar = '$id'";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function tambahSlider($data)
{
  global $conn;

  //Upload Gambar
  $upload = upload();
  if (!$upload) {
    return false;
  }


  $query = "INSERT INTO slider (foto) VALUES ('$upload')";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function hapusSlider($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM slider WHERE id = $id");

  return mysqli_affected_rows($conn);
}

function editSlider($data)
{
  global $conn;

  $id = $data["id"];

  $gambarLama =  $data["fotoLama"];

  // Cek apakah User pilih gambar baru
  if ($_FILES["foto"]["error"] === 4) {
    $gambar = $gambarLama;
  } else {
    $gambar = upload();
  }


  $query = "UPDATE slider SET 
  foto = '$gambar' WHERE id = '$id'
  ";

  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}