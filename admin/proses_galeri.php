<?php
session_start();
require_once "../config.php";

// Proteksi admin
if (!isset($_SESSION["loggedin"]) || 
    $_SESSION["loggedin"] !== true || 
    $_SESSION["role"] !== 'admin') {
    die("Akses ditolak.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["foto"])) {

    $judul = trim($_POST['judul']);
    $target_dir = "../img/";

    // Buat nama file unik
    $file_extension = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
    $unique_file_name = uniqid('img_', true) . '.' . $file_extension;
    $target_file = $target_dir . $unique_file_name;

    // Validasi gambar
    $check = getimagesize($_FILES["foto"]["tmp_name"]);
    if($check === false) {
        header("location: galeri_admin.php?error=bukan_gambar");
        exit;
    }

    // Format diizinkan
    $allowed = ['jpg','jpeg','png','gif'];
    if(!in_array($file_extension, $allowed)) {
        header("location: galeri_admin.php?error=format");
        exit;
    }

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {

        $path_to_db = "img/" . $unique_file_name;
        $uploaded_by = $_SESSION['id'];

        // Simpan ke database + tanggal otomatis
        $sql = "INSERT INTO galeri (judul_gambar, path_gambar, uploaded_at, uploaded_by) 
                VALUES (?, ?, NOW(), ?)";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssi", $judul, $path_to_db, $uploaded_by);
        $stmt->execute();
        $stmt->close();

    } else {
        header("location: galeri_admin.php?error=move");
        exit;
    }

    $mysqli->close();
}

header("location: galeri_admin.php");
exit;
?>