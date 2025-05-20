<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['nama']) || !isset($_SESSION['role'])) {
    header("Location: index.php");
    exit;
}

$nama = $_SESSION['nama'];
$role = $_SESSION['role'];

$kegiatan_id = $_GET['kegiatan_id'] ?? '';

$kegiatan_list = mysqli_query($koneksi, "SELECT id, judul FROM kegiatan ORDER BY tanggal DESC");

$absensi = [];
$kegiatan_nama = '';

if ($kegiatan_id) {
    // Ambil nama kegiatan
    $keg = mysqli_query($koneksi, "SELECT judul FROM kegiatan WHERE id = $kegiatan_id");
    if ($keg && mysqli_num_rows($keg) > 0) {
        $data_keg = mysqli_fetch_assoc($keg);
        $kegiatan_nama = $data_keg['judul'];

        // Ambil data absensi untuk kegiatan tersebut
        $absensi_query = "
            SELECT a.*, u.nama, a.dokumentasi
            FROM absensi a
            JOIN users u ON a.user_id = u.id
            WHERE a.kegiatan_id = $kegiatan_id
            ORDER BY u.nama ASC
        ";
        $absensi = mysqli_query($koneksi, $absensi_query);
    } else {
        $kegiatan_nama = 'Kegiatan tidak ditemukan';
    }
}

// Warna latar kartu untuk ikon kalender (bisa ditambah/ubah sesuai selera)
$warna_kartu = ['#f97316', '#2563eb', '#16a34a', '#db2777', '#eab308', '#8b5cf6', '#14b8a6'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Laporan Absensi - Absensi OSIS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* Sidebar, navbar tetap sama seperti sebelumnya */
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
            user-select: none;
        }
        .navbar .user-info span {
            margin-right: 10px;
            font-weight: 600;
        }

        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            margin-top: -25px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .search-input {
            padding: 10px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            width: 250px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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
        .content {
            flex-grow: 1;
            padding: 30px 25px;
            overflow-y: auto;
            background-color: #f0f4f8;
            min-height: calc(100vh - 60px);
        }
        h2{
            color: #f0f4f8;
        }
        h1, h3 {
            color:rgb(38, 39, 42);
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
            color: #333;
        }
        th {
            background-color: #f97316;
            color: white;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        a.button-link {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 16px;
            background-color: #f97316;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        a.button-link:hover {
            background-color: #fb923c;
        }
        p.no-data {
            font-style: italic;
            color: #555;
            margin-top: 15px;
        }

        /* Card container & card */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill,minmax(280px,1fr));
            gap: 20px;
        }
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgb(0 0 0 / 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #111827;
            min-height: 48px;
        }
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 15px;
            user-select: none;
        }
        .btn-lihat {
            background-color: #f97316;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        .btn-lihat:hover {
            background-color: #fb923c;
        }
        img.foto-dok {
            max-width: 80px;
            max-height: 60px;
            border-radius: 4px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        img.foto-dok:hover {
            transform: scale(1.05);
        }
        
        /* Modal untuk preview gambar */
        .modal-preview {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
            overflow: auto;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 90%;
        }
        .close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: #f97316;
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
            .card-container {
                grid-template-columns: 1fr;
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
        <a href="kegiatan_list.php"><i class="fas fa-calendar-alt"></i> Jadwal Kegiatan</a>
        <a href="absensi_laporan.php" class="active"><i class="fas fa-chart-bar"></i> Laporan Absensi</a>
        <?php if ($role == 'admin') : ?>
            <a href="kegiatan_tambah.php"><i class="fas fa-tools"></i> Kelola Jadwal</a>
        <?php endif; ?>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main container -->
    <div class="main-container">
        <!-- Navbar -->
        <div class="navbar">
            <div><b>Laporan Absensi</b></div>
            <div class="user-info" id="userInfo">
                <span><?= htmlspecialchars($nama) ?></span>
                <i class="fas fa-caret-down"></i>
                <div class="dropdown" id="dropdownMenu">
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="top-actions">
                <a href="dashboard.php" class="button-link"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
                <input type="text" id="searchInput" class="search-input" placeholder="Cari kegiatan...">
            </div>

            <!-- Tampilkan card kegiatan jika belum pilih kegiatan -->
            <?php if (!$kegiatan_id): ?>
                <h3>Pilih Kegiatan untuk Laporan Absensi</h3>
                <div class="card-container">
                    <?php 
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($kegiatan_list)): 
                        $warna = $warna_kartu[$i % count($warna_kartu)];
                        $i++;
                    ?>
                        <div class="card">
                            <div class="card-icon" style="background-color: <?= $warna ?>">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="card-title"><?= htmlspecialchars($row['judul']) ?></div>
                            <a href="?kegiatan_id=<?= $row['id'] ?>" class="btn-lihat">Lihat</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <!-- Tampilkan tabel absensi -->
                <h3>Absensi Kegiatan: <?= htmlspecialchars($kegiatan_nama) ?></h3>

                <?php if ($absensi && mysqli_num_rows($absensi) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Status Kehadiran</th>
                                <th>Dokumentasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php while ($row = mysqli_fetch_assoc($absensi)) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars($row['status']) ?></td>
                                    <td>
                                        <?php 
                                        $file_path = 'uploads/' . $row['dokumentasi'];
                                        if (!empty($row['dokumentasi']) && file_exists($file_path)): 
                                        ?>
                                            <img src="<?= $file_path ?>" 
                                                 alt="Dokumentasi" 
                                                 class="foto-dok" 
                                                 onclick="showModal('<?= $file_path ?>')">
                                        <?php else: ?>
                                            <span>-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    
                    <!-- Modal untuk preview gambar -->
                    <div id="imageModal" class="modal-preview">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <img class="modal-content" id="modalImage">
                    </div>

                    <a href="export_excel.php?kegiatan_id=<?= $kegiatan_id ?>" class="button-link" style="margin-top: 20px;">
                        <i class="fas fa-file-excel"></i> Export ke Excel
                    </a>
                <?php else: ?>
                    <p class="no-data">Data absensi belum tersedia untuk kegiatan ini.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Dropdown user info
        const userInfo = document.getElementById('userInfo');
        const dropdownMenu = document.getElementById('dropdownMenu');

        userInfo.addEventListener('click', () => {
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', (e) => {
            if (!userInfo.contains(e.target)) {
                dropdownMenu.style.display = 'none';
            }
        });

        // Fitur pencarian kegiatan
        const searchInput = document.getElementById('searchInput');
        const cards = document.querySelectorAll('.card');

        searchInput.addEventListener('input', () => {
            const keyword = searchInput.value.toLowerCase();
            cards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                if (title.includes(keyword)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Fungsi untuk modal gambar
        function showModal(imageUrl) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = 'flex';
            modalImg.src = imageUrl;
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // Tutup modal ketika klik di luar gambar
        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>