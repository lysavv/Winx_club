<?php include 'header.php'; ?>
<?php require_once "config.php"; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - BDC</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .gallery-item {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transition: 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
        }

        .gallery-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .gallery-info {
            padding: 12px;
            text-align: center;
        }

        .upload-date {
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>

<main>
<section class="page-section" style="padding-top: 120px;">
<div class="container">

<div class="reveal">
    <h2>Galeri Kegiatan BDC</h2>
    <p style="text-align: center; margin-bottom: 3rem;">
        Momen-momen terbaik kami, dari latihan hingga panggung.
    </p>
</div>

<div class="gallery-grid reveal">

<?php
$query = "SELECT * FROM galeri ORDER BY uploaded_at DESC";
$result = $mysqli->query($query);

if ($result && $result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $tanggal = !empty($row['uploaded_at'])
            ? date("d F Y - H:i", strtotime($row['uploaded_at']))
            : "Tanggal tidak tersedia";
?>

    <div class="gallery-item">
        <img src="<?php echo htmlspecialchars($row['path_gambar']); ?>" alt="Galeri BDC">

        <div class="gallery-info">
            <h4><?php echo htmlspecialchars($row['judul_gambar']); ?></h4>
            <p class="upload-date">📅 <?php echo $tanggal; ?></p>
        </div>
    </div>

<?php
    }

} else {
    echo "<p style='text-align:center;'>Tidak ada gambar di galeri saat ini.</p>";
}
?>

</div>
</div>
</section>
</main>

<footer style="padding: 4rem 0; text-align: center; background-color: #b57edc; color: white;">
    <div class="container">
        <div style="margin-bottom: 1.5rem;">
            <a href="https://www.instagram.com/dance.smkn1bawang" target="_blank" style="color: white; margin: 0 15px;">Instagram</a>
            <a href="https://www.tiktok.com/@dance.smkn1bawang" target="_blank" style="color: white; margin: 0 15px;">TikTok</a>
            <a href="https://youtube.com/@beyonddancecrew" target="_blank" style="color: white; margin: 0 15px;">YouTube</a>
        </div>

        <p>&copy; 2025 BDC - SMKN 1 Bawang. All Rights Reserved.</p>
        <p style="margin-top: 1rem; opacity: 0.8;">
            Be the one, forever one, BDC LASGOOO!!!
        </p>
    </div>
</footer>

<script src="script.js"></script>
</body>
</html>