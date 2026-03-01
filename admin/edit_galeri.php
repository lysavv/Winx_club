<?php
require_once "../config.php";

$id = (int) $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);

    $stmt = $mysqli->prepare("UPDATE galeri SET judul_gambar=? WHERE id=?");
    $stmt->bind_param("si", $judul, $id);
    $stmt->execute();

    header("Location: galeri_admin.php");
    exit;
}

$data = $mysqli->query("SELECT * FROM galeri WHERE id=$id")->fetch_assoc();
$path = "../" . $data['path_gambar'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Foto Galeri</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f9;
}

.edit-container {
    max-width: 420px;
    margin: 60px auto;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-align: center;
}

.edit-container img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 15px;
}

.form-group {
    text-align: left;
    margin-bottom: 15px;
}

input[type="text"] {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

.btn {
    padding: 10px 16px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    margin-top: 10px;
}

.btn-save {
    background: #4CAF50;
    color: white;
}

.btn-back {
    background: #777;
    color: white;
    text-decoration: none;
    display: inline-block;
    margin-left: 8px;
}
</style>
</head>

<body>

<div class="edit-container">
    <h2>Edit Foto</h2>

    <img src="<?= htmlspecialchars($path); ?>" alt="Preview Foto">

    <form method="POST">
        <div class="form-group">
            <label>Judul Foto</label>
            <input type="text" name="judul"
                   value="<?= htmlspecialchars($data['judul_gambar']); ?>"
                   required>
        </div>

        <button class="btn btn-save" type="submit">Simpan Perubahan</button>
        <a class="btn btn-back" href="galeri_admin.php">Kembali</a>
    </form>
</div>

</body>
</html>