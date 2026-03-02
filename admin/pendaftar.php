<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: ../login.php");
    exit;
}

require_once "../config.php";

/* ================== DATA PENDING ================== */
$sql_pending = "SELECT * FROM pendaftar WHERE status = 'pending' ORDER BY tanggal_daftar ASC";
$result_pending = $mysqli->query($sql_pending);
$total_pending = $result_pending->num_rows;

/* ================== DATA DITERIMA ================== */
$sql_diterima = "SELECT * FROM pendaftar WHERE status = 'diterima' ORDER BY tanggal_daftar ASC";
$result_diterima = $mysqli->query($sql_diterima);
$total_diterima = $result_diterima->num_rows;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Pendaftar - BDC</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="admin_style.css">

<style>
/* ===== PANEL ===== */
.top-panel{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
    gap:20px;
}

.info-card{
    padding:18px 26px;
    border-radius:18px;
    color:white;
    box-shadow:0 12px 30px rgba(0,0,0,.15);
}
.info-pending{background:linear-gradient(135deg,#6366f1,#8b5cf6);}
.info-diterima{background:linear-gradient(135deg,#22c55e,#16a34a);}

.info-card span{font-size:12px;opacity:.9;}
.info-card h2{margin:4px 0 0;font-size:28px;}

.search-box input{
    padding:12px 18px;
    border-radius:999px;
    border:1px solid #e5e7eb;
    width:260px;
    font-size:14px;
}

/* ===== TABEL CARD ===== */
.table-wrapper table{
    width:100%;
    border-collapse:separate;
    border-spacing:0 14px;
    font-size:14px;
}

.table-wrapper thead th{
    font-size:12px;
    color:#64748b;
    text-transform:uppercase;
    padding:10px;
}

.table-wrapper tbody tr{
    background:white;
    border-radius:18px;
    box-shadow:0 8px 20px rgba(0,0,0,.06);
}

.table-wrapper tbody td{
    padding:14px;
    color:#1f2937;
}

.table-wrapper tbody tr td:first-child{border-radius:18px 0 0 18px;}
.table-wrapper tbody tr td:last-child{border-radius:0 18px 18px 0;}

.alasan-column{max-width:280px;font-size:13px;color:#475569;}

.video-link{
    background:#eef2ff;
    color:#4338ca;
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
    text-decoration:none;
}

.action-btn{
    padding:6px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
    text-decoration:none;
}

.btn-terima{background:#dcfce7;color:#166534;}
.btn-tolak{background:#fee2e2;color:#991b1b;}

.badge-diterima{
    background:#dcfce7;
    color:#166534;
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

.section-title{
    margin:50px 0 20px;
    font-size:18px;
    font-weight:700;
    display:flex;
    align-items:center;
    gap:10px;
}
.section-title::after{
    content:"";
    flex:1;
    height:2px;
    background:#e5e7eb;
}
</style>
</head>

<body>
<div class="dashboard-wrapper">
<?php include 'sidebar.php'; ?>

<main class="main-content">
<div class="content-area">

<!-- ================= PENDING ================= -->
<div class="top-panel">
    <div class="info-card info-pending">
        <span>Pendaftar Pending</span>
        <h2><?= $total_pending ?></h2>
    </div>
    <div class="search-box">
        <input type="text" id="searchPending" placeholder="Cari pendaftar pending...">
    </div>
</div>

<div class="table-wrapper">
<table>
<thead>
<tr>
<th>Nama</th><th>NIS</th><th>Kelas</th><th>WhatsApp</th>
<th>Video</th><th>Alasan</th><th>Tanggal</th><th>Aksi</th>
</tr>
</thead>
<tbody id="pendingTable">
<?php if($total_pending>0): while($row=$result_pending->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
<td><?= htmlspecialchars($row['nis']) ?></td>
<td><?= htmlspecialchars($row['kelas']) ?></td>
<td><?= htmlspecialchars($row['no_hp']) ?></td>
<td>
<?php if($row['video_path']): ?>
<a href="../<?= htmlspecialchars($row['video_path']) ?>" target="_blank" class="video-link">🎬 Lihat</a>
<?php else: ?>-<?php endif; ?>
</td>
<td class="alasan-column"><?= htmlspecialchars($row['alasan_bergabung']) ?></td>
<td><?= date("d M Y",strtotime($row['tanggal_daftar'])) ?></td>
<td>
<a href="proses_persetujuan.php?id=<?= $row['id'] ?>&aksi=terima" class="action-btn btn-terima">Terima</a>
<a href="proses_persetujuan.php?id=<?= $row['id'] ?>&aksi=tolak" class="action-btn btn-tolak">Tolak</a>
</td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="8" style="text-align:center">Tidak ada pendaftar pending.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>

<!-- ================= DITERIMA ================= -->
<div class="section-title">🟢 Siswa Diterima</div>

<div class="top-panel">
    <div class="info-card info-diterima">
        <span>Total Diterima</span>
        <h2><?= $total_diterima ?></h2>
    </div>
    <div class="search-box">
        <input type="text" id="searchDiterima" placeholder="Cari siswa diterima...">
    </div>
</div>

<div class="table-wrapper">
<table>
<thead>
<tr>
<th>Nama</th><th>NIS</th><th>Kelas</th>
<th>WhatsApp</th><th>Alasan</th><th>Tanggal</th><th>Status</th>
</tr>
</thead>
<tbody id="diterimaTable">
<?php if($total_diterima>0): while($row=$result_diterima->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
<td><?= htmlspecialchars($row['nis']) ?></td>
<td><?= htmlspecialchars($row['kelas']) ?></td>
<td><?= htmlspecialchars($row['no_hp']) ?></td>
<td class="alasan-column"><?= htmlspecialchars($row['alasan_bergabung']) ?></td>
<td><?= date("d M Y",strtotime($row['tanggal_daftar'])) ?></td>
<td><span class="badge-diterima">Diterima</span></td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="7" style="text-align:center">Belum ada siswa diterima.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>

</div>
</main>
</div>

<script>
function liveSearch(inputId, tableId){
    document.getElementById(inputId).addEventListener("keyup", function(){
        let val=this.value.toLowerCase();
        document.querySelectorAll("#"+tableId+" tr").forEach(tr=>{
            tr.style.display = tr.innerText.toLowerCase().includes(val) ? "" : "none";
        });
    });
}
liveSearch("searchPending","pendingTable");
liveSearch("searchDiterima","diterimaTable");
</script>

</body>
</html>