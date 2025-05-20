<?php
session_start();
if (!isset($_SESSION['nama']) || !isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}
$nama = $_SESSION['nama'];
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Dashboard Absensi OSIS</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
    /* Reset */
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

    /* Container utama di samping sidebar */
    .main-container {
        margin-left: 240px; /* ruang untuk sidebar */
        width: calc(100vw - 240px);
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Navbar horizontal di atas, tapi hanya di samping sidebar */
    .navbar {
        height: 60px;
        background-color:rgb(235, 110, 37); /* biru */
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
        user-select: none;
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
        white-space: nowrap;
        z-index: 110;
    }
    .dropdown a {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: #333;
        font-weight: 500;
        border-bottom: 1px solid #eee;
        transition: background-color 0.2s;
    }
    .dropdown a:hover {
        background-color: #2563eb;
        color: white;
    }
    .dropdown a:last-child {
        border-bottom: none;
    }

    /* Konten utama di bawah navbar */
    .content {
        flex-grow: 1;
        padding: 30px;
        overflow-y: auto;
        background-color: #f0f4f8;
    }

    h1 {
        color: #111827;
        margin-bottom: 20px;
    }
    .content {
            /* margin-left: 240px; */
            /* margin-top: 56px; */
            padding: 30px 25px;
            min-height: calc(100vh - 56px);
        }

        /* Welcome card dan kalender */
       .top-row {
    display: flex;
    justify-content: flex-start;
    align-items: flex-start;
    gap: 20px;
    flex-wrap: nowrap;
    overflow-x: auto;
    margin-bottom: 40px;
}


        .welcome-card {
    display: flex;
    background: linear-gradient(to right, #f9a826, #f97316);
    color: white;
    border-radius: 10px;
    padding: 30px 50px;
    width: 600px; /* BIARKAN UKURAN INI */
    max-width: 100%;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

        .welcome-text {
            max-width: 60%;
        }
        .welcome-text h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .welcome-text p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .welcome-text a {
            background-color: white;
            color: #f97316;
            padding: 8px 14px;
            border-radius: 20px;
            font-weight: bold;
            text-decoration: none;
            font-size: 14px;
        }
        .welcome-image img {
            width: 200px;
        }
        .calendar-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    padding: 10px;
    width: 350px;
    height: 260px; /* Sesuaikan tingginya agar sejajar secara visual dengan welcome card */
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}

    /* flex-shrink: 0; Supaya tidak mengecil jika ruang sempit */
           
        .calendar-card h3 {
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 250px;
            text-align: center;
            transition: transform 0.2s;
            cursor: pointer;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card i {
            font-size: 36px;
            margin-bottom: 10px;
            color: #f97316;
        }
        .card h3 {
            margin-bottom: 10px;
            font-size: 20px;
            color: #333;
        }
        .card a {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 12px;
            background-color: #f97316;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }
        .card a:hover {
            background-color: #fb923c;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .content {
                margin-left: 0;
                padding: 20px;
            }
            .welcome-card {
                flex-direction: column;
                text-align: center;
            }
            .welcome-text {
                max-width: 100%;
            }
            .top-row, .card-container {
                flex-direction: column;
                align-items: center;
            }
        }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <img src="img\1698917851792.png" alt="Logo OSIS" class="sidebar-logo" />
    <h2>Absensi OSIS</h2>
    <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
    <a href="kegiatan_list.php"><i class="fas fa-calendar-alt"></i> Jadwal Kegiatan</a>
    <a href="absensi_laporan.php"><i class="fas fa-chart-bar"></i> Laporan Absensi</a>
    <?php if ($role == 'admin') : ?>
    <a href="kegiatan_tambah.php"><i class="fas fa-tools"></i> Kelola Jadwal</a>
    <?php endif; ?>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Container navbar + content -->
<div class="main-container">
    <!-- Navbar horizontal -->
    <div class="navbar">
        <div><b>Dashboard</b></div>
        <div class="user-info" id="userInfo">
            <span><?php echo htmlspecialchars($nama); ?></span>
            <i class="fas fa-caret-down"></i>
            <div class="dropdown" id="dropdownMenu">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>

    <!-- Konten utama -->
    <div class="content">
        <div class="top-row">
            <div class="welcome-card">
                <div class="welcome-text">
                    <h2>Halo, <?php echo htmlspecialchars($nama); ?> 👋</h2>
                    <p>Selamat datang di Dashboard Absensi Kegiatan OSIS.<br>Pastikan kamu sudah mengecek jadwal terbaru dan laporan absensimu.</p>
                    <a href="kegiatan_list.php">Lihat Jadwal</a>
                </div>
                <div class="welcome-image">
                    <img src="https://img.pikbest.com/png-images/20240510/spirited-mothers-day-holiday-wishes-222024-png-images-png_10557983.png!bw700" alt="Welcome" />
                </div>
            </div>
            <div class="calendar-card">
                <h3>Kalender</h3>
                <iframe src="https://calendar.google.com/calendar/embed?src=id.indonesian%23holiday%40group.v.calendar.google.com&ctz=Asia%2FJakarta" 
                        style="border: 0" width="100%" height="300" frameborder="0" scrolling="no"></iframe>
            </div>
        </div>
        <div class="card-container">
            <?php if ($role == 'admin') : ?>
                <div class="card" onclick="location.href='kegiatan_tambah.php'">
                    <i class="fas fa-calendar-plus"></i>
                    <h3>Kelola Jadwal</h3>
                    <a href="kegiatan_tambah.php"><b>Buka</b></a>
                </div>
            <?php endif; ?>
            <div class="card" onclick="location.href='absensi_laporan.php'">
                <i class="fas fa-chart-bar"></i>
                <h3>Laporan Absensi</h3>
                <a href="absensi_laporan.php"><b>Buka</b></a>
            </div>
            <div class="card" onclick="location.href='kegiatan_list.php'">
                <i class="fas fa-calendar-alt"></i>
                <h3>Jadwal Kegiatan</h3>
                <a href="kegiatan_list.php"><b>Lihat</b></a>
            </div>
        </div>
    </div>


</div>

<script>
    // Toggle dropdown user menu
    const userInfo = document.getElementById('userInfo');
    const dropdownMenu = document.getElementById('dropdownMenu');

    userInfo.addEventListener('click', () => {
        if (dropdownMenu.style.display === 'block') {
            dropdownMenu.style.display = 'none';
        } else {
            dropdownMenu.style.display = 'block';
        }
    });

    // Tutup dropdown kalau klik di luar
    window.addEventListener('click', (e) => {
        if (!userInfo.contains(e.target)) {
            dropdownMenu.style.display = 'none';
        }
    });
</script>

</body>
</html>
