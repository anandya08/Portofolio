<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$nama = $_SESSION['nama'];
$role = $_SESSION['role'];

$result = mysqli_query($koneksi, "SELECT * FROM kegiatan ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Kegiatan OSIS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
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
        /* Sidebar kiri full tinggi */
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
        box-sizing: border-box;
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
    min-height: 60px;
    background-color: #2563eb;
    color: white;
    display: flex;
    align-items: center;
    padding: 0 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    justify-content: space-between;
    flex-shrink: 0;
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

        .content {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
            background-color: #f0f4f8;
        }

        .card-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
    margin-top: 20px;
}

.card {
    background-color: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
}

.card-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.card-body {
    padding: 15px;
    flex-grow: 1;
}

.card-body h3 {
    font-size: 20px;
    margin-bottom: 10px;
    color: #1f2937;
}

.card-body p {
    font-size: 14px;
    color: #4b5563;
    margin: 5px 0;
}

.card-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 15px;
    background-color: #f9fafb;
    border-top: 1px solid #eee;
    justify-content: center; 
}

.btn {
    padding: 8px 12px;
    text-decoration: none;
    font-weight: 600;
    border-radius: 5px;
    font-size: 14px;
    transition: 0.3s ease;
    color: white;
    text-align: center;
}

.btn.blue {
    background-color: #2563eb;
}
.btn.blue:hover {
    background-color: #1e40af;
}

.btn.gray {
    background-color: #6b7280;
}
.btn.gray:hover {
    background-color: #4b5563;
}

.btn.orange {
    background-color: #f97316;
}
.btn.orange:hover {
    background-color: #ea580c;
}

.btn.red {
    background-color: #ef4444;
}
.btn.red:hover {
    background-color: #dc2626;
}


        h2 {
            margin-bottom: 20px;
            color:rgb(240, 242, 245);
        }

        a.button-link {
            display: inline-block;
            background-color: #f97316;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
        }
        a.button-link:hover {
            background-color: #fb923c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        th {
            background-color: #2563eb;
            color: white;
        }
        tr:hover {
            background-color: #f1f5f9;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .main-container {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <img src="img/1698917851792.png" alt="Logo OSIS" class="sidebar-logo" />
    <h2>Absensi OSIS</h2>
    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="kegiatan_list.php" class="active"><i class="fas fa-calendar-alt"></i> Jadwal Kegiatan</a>
    <a href="absensi_laporan.php"><i class="fas fa-chart-bar"></i> Laporan Absensi</a>
    <?php if ($role == 'admin') : ?>
    <a href="kegiatan_tambah.php"><i class="fas fa-tools"></i> Kelola Jadwal</a>
    <?php endif; ?>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Main Container -->
<div class="main-container">
    <div class="navbar">
        <div><b>Jadwal Kegiatan</b></div>
        <div class="user-info" id="userInfo">
            <span><?= htmlspecialchars($nama); ?></span>
            <i class="fas fa-caret-down"></i>
            <div class="dropdown" id="dropdownMenu">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>

    
        <div class="content">
    <a href="dashboard.php" class="button-link"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
    <?php if ($role == 'admin'): ?>
        <a href="kegiatan_tambah.php" class="button-link"><i class="fas fa-plus"></i> Tambah Kegiatan</a>
    <?php endif; ?>

    <div class="card-container">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="card">
    <div style="font-size: 100px; color: #2563eb; text-align: center; padding: 20px;">
        <i class="fas fa-calendar-alt"></i>
    </div>
    <div class="card-body">
        <h3><?= htmlspecialchars($row['judul']) ?></h3>
        <p><i class="fas fa-calendar-alt"></i> <?= $row['tanggal'] ?> &nbsp; <i class="fas fa-clock"></i> <?= $row['waktu'] ?></p>
        <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($row['tempat']) ?></p>
        <p><?= htmlspecialchars($row['deskripsi']) ?></p>
    </div>
    <div class="card-actions">
        <a href="absensi_isi.php?id=<?= $row['id'] ?>" class="btn blue">Isi Absensi</a>
        <a href="kegiatan_detail.php?id=<?= $row['id'] ?>" class="btn gray">Lihat Detail</a>
        <?php if ($role == 'admin'): ?>
            <a href="kegiatan_edit.php?id=<?= $row['id'] ?>" class="btn orange">Edit</a>
            <a href="kegiatan_hapus.php?id=<?= $row['id'] ?>" class="btn red" onclick="return confirm('Yakin ingin menghapus kegiatan ini?');">Hapus</a>
        <?php endif; ?>
    </div>
</div>

        <?php endwhile; ?>
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
