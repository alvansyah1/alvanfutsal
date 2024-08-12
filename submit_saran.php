<?php
session_start();

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $saran = $_POST['saran'];
    $id_user = $_SESSION['id_user'];

    // Koneksi ke database menggunakan PDO
    $dsn = 'mysql:host=localhost;dbname=dbfutsal'; // Ganti dengan nama database Anda
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO saran (saran, id_user) VALUES (:saran, :id_user)");
        $stmt->bindParam(':saran', $saran);
        $stmt->bindParam(':id_user', $id_user);
        

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Saran berhasil dikirim!";
        } 
    } catch (PDOException $e) {
        $response['message'] = "Koneksi gagal: " . $e->getMessage();
    }

    echo json_encode($response);
    exit;
}
