<?php
session_start();
include 'koneksi.php';

// Cek login & role
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$nama = $_SESSION['nama'];
$role = $_SESSION['role'];

// Ambil data kegiatan berdasarkan ID
$id = $_GET['id'];
$result = mysqli_query($koneksi, "SELECT * FROM kegiatan WHERE id = $id");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}

// Proses update jika form disubmit
if (isset($_POST['submit'])) {
    $judul = $_POST['judul'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $tempat = $_POST['tempat'];
    $deskripsi = $_POST['deskripsi'];

    mysqli_query($koneksi, "UPDATE kegiatan SET 
        judul = '$judul',
        tanggal = '$tanggal',
        waktu = '$waktu',
        tempat = '$tempat',
        deskripsi = '$deskripsi'
        WHERE id = $id");

    header("Location: kegiatan_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Kegiatan - Absensi OSIS</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    body, html {
        height: 100%;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f0f4f8;
        display: flex;
        height: 100vh;
        overflow: hidden;
    }
    .sidebar {
        width: 240px;
        background-color: #1f2937;
        color: white;
        height: 100vh;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: fixed;
        left: 0;
        top: 0;
        overflow-y: auto;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        z-index: 100;
    }
    .sidebar-logo {
        width: 100px;
        margin-bottom: 15px;
    }
    .sidebar h2 {
        margin-bottom: 30px;
        text-align: center;
        font-size: 22px;
        user-select: none;
        width: 100%;
    }
    .sidebar a {
        display: block;
        color: white;
        text-decoration: none;
        padding: 12px 15px;
        margin: 8px 0;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
        transition: background-color 0.3s;
    }
    .sidebar a:hover, .sidebar a.active {
        background-color: #374151;
    }
    .sidebar a i {
        margin-right: 10px;
    }
    .main-container {
        margin-left: 240px;
        width: calc(100vw - 240px);
        height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .navbar {
        height: 60px;
        background-color: rgb(235, 110, 37);
        color: white;
        display: flex;
        align-items: center;
        padding: 0 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        flex-shrink: 0;
        justify-content: space-between;
    }
    .navbar .user-info {
        cursor: pointer;
        position: relative;
    }
    .navbar .user-info span {
        margin-right: 10px;
        font-weight: 600;
    }
    .dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background-color: white;
        color: #333;
        border-radius: 5px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        display: none;
        min-width: 140px;
        z-index: 110;
    }
    .dropdown a {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: #333;
        font-weight: 500;
        border-bottom: 1px solid #eee;
    }
    .dropdown a:hover {
        background-color: #2563eb;
        color: white;
    }
    .dropdown a:last-child {
        border-bottom: none;
    }
    .content {
        flex-grow: 1;
        padding: 30px 40px;
        overflow-y: auto;
        background-color: #f0f4f8;
    }
    .card-form {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        padding: 30px 40px;
        max-width: 600px;
        margin: auto;
    }
    .card-form h2 {
        margin-bottom: 20px;
        color: #1f2937;
        text-align: center;
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
    }
    input[type="text"],
    input[type="date"],
    input[type="time"],
    textarea {
        width: 100%;
        padding: 10px 15px;
        margin-bottom: 20px;
        border: 1.8px solid #d1d5db;
        border-radius: 8px;
        font-size: 16px;
        font-family: inherit;
    }
    input:focus, textarea:focus {
        border-color: #f97316;
        outline: none;
    }
    textarea {
        resize: vertical;
        min-height: 100px;
    }
    button[type="submit"] {
        background-color: #f97316;
        color: white;
        padding: 12px 20px;
        font-size: 16px;
        font-weight: 700;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.3s;
    }
    button[type="submit"]:hover {
        background-color: #fb923c;
    }
    .btn-back {
        display: inline-block;
        margin-bottom: 25px;
        padding: 12px 20px;
        background-color:#f97316;
        color: white;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }
    .btn-back:hover {
        background-color:rgb(228, 160, 70);
    }
    @media (max-width: 768px) {
        .sidebar {
            display: none;
        }
        .main-container {
            margin-left: 0;
            width: 100vw;
        }
        .content {
            padding: 20px;
        }
        .card-form {
            max-width: 100%;
            padding: 20px;
        }
    }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <img src="img\1698917851792.png" alt="Logo OSIS" class="sidebar-logo" />
    <h2>Absensi OSIS</h2>
    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="kegiatan_list.php" class="active"><i class="fas fa-calendar-alt"></i> Jadwal Kegiatan</a>
    <a href="absensi_laporan.php"><i class="fas fa-chart-bar"></i> Laporan Absensi</a>
    <?php if ($role == 'admin') : ?>
    <a href="kegiatan_tambah.php"><i class="fas fa-tools"></i> Kelola Jadwal</a>
    <?php endif; ?>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Main container -->
<div class="main-container">
    <div class="navbar">
        <div><b>Edit Kegiatan</b></div>
        <div class="user-info" id="userInfo">
            <span><?= htmlspecialchars($nama); ?></span>
            <i class="fas fa-caret-down"></i>
            <div class="dropdown" id="dropdownMenu">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>

    <div class="content">
        <a href="kegiatan_list.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Kegiatan</a>
        <div class="card-form">
            <h2>Edit Data Kegiatan</h2>
            <form method="POST">
                <label for="judul">Nama Kegiatan:</label>
                <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" required>

                <label for="tanggal">Tanggal Kegiatan:</label>
                <input type="date" id="tanggal" name="tanggal" value="<?= $data['tanggal'] ?>" required>

                <label for="waktu">Waktu Kegiatan:</label>
                <input type="time" id="waktu" name="waktu" value="<?= $data['waktu'] ?>" required>

                <label for="tempat">Tempat Kegiatan:</label>
                <input type="text" id="tempat" name="tempat" value="<?= htmlspecialchars($data['tempat']) ?>" required>

                <label for="deskripsi">Deskripsi (Opsional):</label>
                <textarea id="deskripsi" name="deskripsi"><?= htmlspecialchars($data['deskripsi']) ?></textarea>

                <button type="submit" name="submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>

<script>
    const userInfo = document.getElementById('userInfo');
    const dropdownMenu = document.getElementById('dropdownMenu');
    userInfo.addEventListener('click', () => {
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });
    window.addEventListener('click', (e) => {
        if (!userInfo.contains(e.target)) {
            dropdownMenu.style.display = 'none';
        }
    });
</script>

</body>
</html>
