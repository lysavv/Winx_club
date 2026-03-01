<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /login.php");
    exit;
}
require_once "../config.php";

// Ambil data galeri + uploaded_at
$sql = "SELECT id, judul_gambar, path_gambar, uploaded_at 
        FROM galeri 
        ORDER BY uploaded_at DESC";
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Galeri - BDC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="admin_style.css">

<style>
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
}

.admin-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    text-align: center;
}

.admin-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.admin-card-body {
    padding: 12px;
}

.admin-actions {
    margin-top: 10px;
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-small {
    padding: 6px 10px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    color: white;
}

.btn-edit { background: #4CAF50; }
.btn-delete { background: #e74c3c; }
</style>    
</head>

<body>
<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="main-content">

        <div class="content-area">
            <h2>Tambah Foto Baru</h2>
            <form action="proses_galeri.php" method="POST" enctype="multipart/form-data" class="simple-form">
                <div class="form-group">
                    <label for="judul">Judul Foto</label>
                    <input type="text" id="judul" name="judul" required>
                </div>
                <div class="form-group">
                    <label for="foto">Pilih File Gambar (JPG, PNG, JPEG)</label>
                    <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload Foto</button>
            </form>
        </div>

        <div class="content-area" style="margin-top: 2rem;">
            <h2>Galeri Tersimpan</h2>
            <div class="gallery-grid" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
                
                <?php if ($result && $result->num_rows > 0): ?>
                    
                    <?php while($row = $result->fetch_assoc()): ?>
    <?php $path = trim($row['path_gambar']); ?>

    <div class="admin-card">
        <img src="../<?= htmlspecialchars($path); ?>" 
             alt="<?= htmlspecialchars($row['judul_gambar']); ?>">

        <div class="admin-card-body">
            <h4><?= htmlspecialchars($row['judul_gambar']); ?></h4>

            <small style="color:gray;">
                📅 <?= date("d F Y - H:i", strtotime($row['uploaded_at'])); ?>
            </small>

            <div class="admin-actions">
                <a class="btn-small btn-edit"
                   href="edit_galeri.php?id=<?= $row['id']; ?>">
                   Edit
                </a>

                <a class="btn-small btn-delete"
                   href="hapus_galeri.php?id=<?= $row['id']; ?>"
                   onclick="return confirm('Hapus foto ini?')">
                   Hapus
                </a>
            </div>
        </div>
    </div>
<?php endwhile; ?>
                
                <?php else: ?>
                    <p>Belum ada gambar di galeri.</p>
                <?php endif; ?>
            
            </div>
        </div>

    </main>
</div>
</body>
</html>