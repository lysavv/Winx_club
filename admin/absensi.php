<?php
session_start();
require_once "../config.php";

/* =====================================================
   AMBIL DATA SISWA DARI TABEL USERS
===================================================== */
$result = $mysqli->query("SELECT id, nama_lengkap, nis, kelas FROM users");
$anggota = $result->fetch_all(MYSQLI_ASSOC);

/* =====================================================
   PROSES SIMPAN ABSENSI
===================================================== */
$notifikasi = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['tanggal']) || !isset($_POST['status'])) {
        header("Location: absensi.php");
        exit;
    }

    $tanggal = $_POST['tanggal'];
    $status_data = $_POST['status'];

    foreach ($status_data as $user_id => $status) {

        $sql = "INSERT INTO absensi (user_id, tanggal, status)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE status = VALUES(status)";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iss", $user_id, $tanggal, $status);
        $stmt->execute();
        $stmt->close();
    }

    $notifikasi = "Absensi berhasil disimpan untuk tanggal $tanggal 🎉";
}

/* =====================================================
   TANGGAL DIPILIH
===================================================== */
$tanggal_pilihan = $_GET['tanggal'] ?? date('Y-m-d');

/* =====================================================
   AMBIL ABSENSI SESUAI TANGGAL
===================================================== */
$absensi_hari_ini = [];

$stmt = $mysqli->prepare("SELECT user_id, status FROM absensi WHERE tanggal = ?");
$stmt->bind_param("s", $tanggal_pilihan);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $absensi_hari_ini[$row['user_id']] = $row['status'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen Absensi</title>

<style>
body {
    margin:0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #c8b6ff, #f8cdda);
    padding:40px;
}
.card {
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 20px 50px rgba(0,0,0,0.1);
}
h2 {
    text-align:center;
    color:#6a4c93;
}
input[type="date"] {
    padding:8px;
    border-radius:10px;
    border:1px solid #ccc;
}
table {
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}
th {
    background:#b388eb;
    color:white;
    padding:12px;
}
td {
    padding:10px;
    text-align:center;
    border-bottom:1px solid #eee;
}
tr:hover {
    background:#f3e8ff;
}
input[type="radio"] {
    transform:scale(1.2);
    cursor:pointer;
}
button {
    margin-top:20px;
    padding:12px 30px;
    border:none;
    border-radius:25px;
    background:#b388eb;
    color:white;
    font-weight:bold;
    cursor:pointer;
}
button:hover {
    background:#9c6ade;
}
.notif {
    margin-top:15px;
    padding:10px;
    background:#d4edda;
    color:#155724;
    border-radius:10px;
    text-align:center;
}
</style>
</head>
<body>

<div class="card">

<h2>Manajemen Absensi</h2>

<?php if($notifikasi != ""): ?>
<div class="notif"><?= $notifikasi ?></div>
<?php endif; ?>

<!-- FORM PILIH TANGGAL -->
<form method="GET">
    <label>Pilih Tanggal:</label>
    <input type="date" name="tanggal" value="<?= $tanggal_pilihan ?>" onchange="this.form.submit()">
</form>

<form method="POST">
<input type="hidden" name="tanggal" value="<?= $tanggal_pilihan ?>">

<table>
<tr>
    <th>Nama</th>
    <th>NIS</th>
    <th>Kelas</th>
    <th>Hadir</th>
    <th>Izin</th>
    <th>Sakit</th>
    <th>Alpha</th>
</tr>

<?php foreach ($anggota as $a): 
$status = $absensi_hari_ini[$a['id']] ?? "alpha";
?>
<tr>
<td><?= htmlspecialchars($a['nama_lengkap']) ?></td>
<td><?= htmlspecialchars($a['nis']) ?></td>
<td><?= htmlspecialchars($a['kelas']) ?></td>

<td><input type="radio" name="status[<?= $a['id'] ?>]" value="hadir" <?= $status=="hadir"?"checked":"" ?>></td>
<td><input type="radio" name="status[<?= $a['id'] ?>]" value="izin" <?= $status=="izin"?"checked":"" ?>></td>
<td><input type="radio" name="status[<?= $a['id'] ?>]" value="sakit" <?= $status=="sakit"?"checked":"" ?>></td>
<td><input type="radio" name="status[<?= $a['id'] ?>]" value="alpha" <?= $status=="alpha"?"checked":"" ?>></td>
</tr>
<?php endforeach; ?>

</table>

<button type="submit">Simpan Absensi</button>
</form>

</div>

</body>
</html>