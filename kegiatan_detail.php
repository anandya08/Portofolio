<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$nama = $_SESSION['nama'];
$role = $_SESSION['role'];

if (!isset($_GET['id'])) {
    echo "ID kegiatan tidak ditemukan.";
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM kegiatan WHERE id = '$id'");
$kegiatan = mysqli_fetch_assoc($query);

if (!$kegiatan) {
    echo "Kegiatan tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Kegiatan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden; /* Hilangkan scroll bar */
            font-family: 'Segoe UI', sans-serif;
            background-color: #f1f5f9;
        }

        .container {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            width: 90%;
            max-width: 700px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 22px;
            color: #1e293b;
        }

        .detail {
            margin-bottom: 10px;
            font-size: 14px;
            color: #334155;
        }

        .detail i {
            margin-right: 8px;
            color: #3b82f6;
        }

        .label {
            font-weight: 600;
            color: #0f172a;
        }

        .deskripsi {
            margin-top: 10px;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 8px;
            font-size: 14px;
            color: #475569;
            white-space: pre-wrap;
            max-height: 100px;
            overflow-y: auto;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #2563eb;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 14px;
            transition: 0.3s ease;
        }

        .btn-back:hover {
            background-color: #1d4ed8;
        }

        .top-icon {
            text-align: center;
            font-size: 48px;
            color: #3b82f6;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="top-icon"><i class="fas fa-calendar-check"></i></div>
        <h2><?= htmlspecialchars($kegiatan['judul']) ?></h2>

        <div class="detail"><i class="fas fa-calendar-day"></i><span class="label">Tanggal:</span> <?= $kegiatan['tanggal'] ?></div>
        <div class="detail"><i class="fas fa-clock"></i><span class="label">Waktu:</span> <?= $kegiatan['waktu'] ?></div>
        <div class="detail"><i class="fas fa-map-marker-alt"></i><span class="label">Tempat:</span> <?= htmlspecialchars($kegiatan['tempat']) ?></div>
        
        <div class="detail">
            <i class="fas fa-info-circle"></i><span class="label">Deskripsi:</span>
            <div class="deskripsi"><?= nl2br(htmlspecialchars($kegiatan['deskripsi'])) ?></div>
        </div>

        <a href="kegiatan_list.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</div>

</body>
</html>
