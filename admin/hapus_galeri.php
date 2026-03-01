<?php
require_once "../config.php";

$id = (int) $_GET['id'];

$q = $mysqli->query("SELECT path_gambar FROM galeri WHERE id=$id");
$data = $q->fetch_assoc();

if ($data && file_exists("../" . $data['path_gambar'])) {
    unlink("../" . $data['path_gambar']);
}

$mysqli->query("DELETE FROM galeri WHERE id=$id");

header("Location: galeri_admin.php");
exit;