<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['id'];
$nama = $_SESSION['nama'];
$role = $_SESSION['role'];

$kegiatan_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($kegiatan_id <= 0) {
    echo "ID kegiatan tidak diberikan.";
    exit;
}

// Cek kegiatan ada
$cek_kegiatan = mysqli_query($koneksi, "SELECT * FROM kegiatan WHERE id = $kegiatan_id");
$kegiatan = mysqli_fetch_assoc($cek_kegiatan);

if (!$kegiatan) {
    echo "Kegiatan tidak ditemukan.";
    exit;
}

// Cek sudah absen
$cek_absen = mysqli_query($koneksi, "SELECT * FROM absensi WHERE user_id = $user_id AND kegiatan_id = $kegiatan_id");
if (mysqli_num_rows($cek_absen) > 0) {
    echo "Kamu sudah mengisi absensi untuk kegiatan ini.";
    echo "<br><a href='dashboard.php'>Kembali</a>";
    exit;
}

$error = "";
$success = "";

if (isset($_POST['submit'])) {
    $status = $_POST['status'] ?? '';
    $file_name = '';

    if (isset($_FILES['dokumentasi']) && $_FILES['dokumentasi']['error'] == 0) {
        $tmp = $_FILES['dokumentasi']['tmp_name'];
        $nama_file = basename($_FILES['dokumentasi']['name']);
        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','pdf'];

        if (in_array($ext, $allowed)) {
            $target_dir = 'uploads/';
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            $new_filename = time() . '_' . uniqid() . '.' . $ext;
            $tujuan = $target_dir . $new_filename;

            if (move_uploaded_file($tmp, $tujuan)) {
                $file_name = $new_filename;
            } else {
                $error = "Gagal upload file dokumentasi.";
            }
        } else {
            $error = "Format file dokumentasi tidak diperbolehkan.";
        }
    }

    if (!$error) {
        $insert = mysqli_query($koneksi, "INSERT INTO absensi (user_id, kegiatan_id, status, dokumentasi) VALUES ($user_id, $kegiatan_id, '$status', '$file_name')");

        if ($insert) {
            $success = "Absensi berhasil disimpan.";
        } else {
            $error = "Gagal menyimpan absensi: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Isi Absensi</title>
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
        /* top: 100%; */
        right: 0;
        background-color: white;
        color: #333;
        border-radius: 5px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        display: none;
        white-space: nowrap;
        margin-top: 0; 
        padding-top: 0;
        
    }
    .dropdown a {
    display: block;
    padding: 8px 15px; 
    margin-top: 0;      
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

       .content {
    flex-grow: 1;
    padding: 30px;
    overflow-y: auto;
    background-color: #f0f4f8;
}

.form-box {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    max-width: 600px;
    margin: 0 auto;
}

h2 {
    color: rgb(243, 245, 248);
    margin-bottom: 20px;
}
h3 {
    color: black;
}

label {
    font-weight: 600;
    display: block;
    margin-bottom: 6px;
}

.form-group {
    margin-top: 20px;
}

.radio-group {
    display: flex;
    gap: 20px;
    margin-top: 5px;
}

input[type="file"] {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    width: 100%;
}

small {
    color: #666;
    font-size: 12px;
}

.error {
    color: red;
    margin-top: 10px;
}
.success {
    color: green;
    margin-top: 10px;
}

button {
    background-color: #f97316;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 25px;
    font-weight: bold;
}

button:hover {
    background-color: #fb923c;
}

a.btn-link {
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    color: #2563eb;
    font-weight: 600;
}

.bottom-nav-button {
    padding: 20px 30px;
    background-color: #ffffff;
    border-top: 1px solid #e2e8f0;
    position: relative;
    z-index: 1;
    text-align: left;
}

.btn-back {
    color: #1d4ed8;
    text-decoration: none;
    font-weight: bold;
}

.btn-back:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .sidebar {
        display: none;
    }

    .main-container {
        margin-left: 0;
    }

    .content {
        padding: 20px;
    }

    .radio-group {
        flex-direction: column;
    }

    .bottom-nav-button {
        text-align: center;
    }
}

        
    </style>
</head>
<body>

<div class="sidebar">
    <img src="img/1698917851792.png" alt="Logo OSIS" class="sidebar-logo" />
    <h2>Absensi OSIS</h2>
    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
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
        <div><b>Absensi</b></div>
        <div class="user-info" id="userInfo">
            <span><?php echo htmlspecialchars($nama); ?></span>
            <i class="fas fa-caret-down"></i>
            <div class="dropdown" id="dropdownMenu">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
    
   <div class="content">

<!-- Tombol kembali di bawah navbar -->
<div class="bottom-nav-button">
    <a href="kegiatan_list.php" class="btn-back">← Kembali ke Jadwal Kegiatan</a>
</div>

    <div class="form-box">
        <h3>Isi Absensi untuk: <?= htmlspecialchars($kegiatan['judul']) ?></h3>

        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
            <a href="dashboard.php" class="btn-link">Kembali ke Dashboard</a>
        <?php else: ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Status Kehadiran:</label>
                    <div class="radio-group">
                        <label><input type="radio" name="status" value="Hadir" required> Hadir</label>
                        <label><input type="radio" name="status" value="Izin" required> Izin</label>
                        <label><input type="radio" name="status" value="Tidak Hadir" required> Sakit</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Upload Dokumentasi (jpg, jpeg, png, gif, pdf):</label>
                    <input type="file" name="dokumentasi" accept=".jpg,.jpeg,.png,.gif,.pdf">
                    <small>Format: jpg, jpeg, png, gif, pdf</small>
                </div>

                <button type="submit" name="submit">Simpan Absensi</button>
            </form>
        <?php endif; ?>
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
