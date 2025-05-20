<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Menangani pencarian
$search = '';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query_users = mysqli_query($conn, "SELECT id_user, name FROM tb_users WHERE name LIKE '%$search%'");
} else {
    $query_users = mysqli_query($conn, "SELECT id_user, name FROM tb_users");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Admin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            min-height: 100vh;
            background: url('https://images.unsplash.com/photo-1556742044-3c52d6e88c62?auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            color: #fff;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 220px;
            background-color: #2C3E50;
            color: white;
            height: 100vh;
            padding: 30px 20px;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .sidebar .user-info img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .sidebar .user-info .name {
            font-size: 20px;
            font-weight: 600;
            color: #fff;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            margin: 12px 0;
            padding: 10px 15px;
            border-radius: 8px;
            transition: 0.3s;
            font-weight: 500;
        }

        .sidebar a:hover {
            background-color: #34495E;
        }
        .nasabah-box {
    margin: 40px auto 10px auto;
    width: 90%;
    background-color: #EBF5FB;
    border-left: 6px solid #3498DB;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 18px;
    color: #2C3E50;
    font-weight: bold;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.nasabah-box span {
    color: #2980B9;
}

        /* Main Content */
        .main-content {
            margin-left: 220px;
            padding: 50px;
            width: calc(100% - 220px);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            color: #fff;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 800px;
            margin: 40px auto;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        h2 {
            color: #2C3E50;
            font-size: 26px;
            margin-bottom: 30px;
            text-align: center;
        }
        h3 {
            color: black;
        }

        .back-button {
            background-color: #2980B9;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .back-button:hover {
            background-color: #1F618D;
        }

        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-container input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #BDC3C7;
            border-radius: 5px;
            width: 300px;
            margin-right: 10px;
        }

        .search-container button {
            padding: 10px 20px;
            background-color: #16A085;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .search-container button:hover {
            background-color: #1ABC9C;
        }

        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
    background-color: #16A085;
    color: white;
}

td {
    color:rgb(14, 14, 15); /* Warna teks data */
    font-weight: 500;
}

        .aksi a {
            margin: 0 5px;
            text-decoration: none;
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
        }

        .edit {
            background-color: #3498DB;
        }

        .delete {
            background-color: #E74C3C;
        }

        .edit:hover {
            background-color: #2980B9;
        }

        .delete:hover {
            background-color: #C0392B;
        }

        .saldo {
            font-weight: bold;
            background-color: #D1F2EB;
            text-align: right;
            padding: 10px;
            border-top: 2px solid #BDC3C7;
        }

    </style>
</head>
<body>

    <div class="sidebar">
        <div class="user-info">
            <img src="https://www.teknoredaksi.com/wp-content/uploads/2024/04/2a90a89df0ea59aa6dde4eb8dcfdd97a-1.png" alt="Foto Pengguna">
            <div class="name"><?= htmlspecialchars($_SESSION['name']) ?></div>
        </div>
        <a href="dashboard.php">🏠 Dashboard </a>
        <a href="login.php">🔐 Login</a>
        <a href="transaksi.php">➕ Tambah Transaksi</a>
        <a href="pilih_role.php">📊 Lihat Laporan</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

        <!-- Kolom Pencarian -->
        <div class="search-container">
            <form action="" method="GET">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama nasabah...">
                <button type="submit">Cari</button>
            </form>
        </div>

        <div class="form-container">
            <h2>Laporan Keuangan Semua Nasabah</h2>

            <?php while ($user = mysqli_fetch_assoc($query_users)) :
                $id_user = $user['id_user'];
                $nama_user = $user['name'];

                echo "<h3>Nasabah: $nama_user</h3>";

                $query_transaksi = mysqli_query($conn, "
                    SELECT ts.id, t.tgl_transaksi, ts.deskripsi, ts.debet, ts.kredit 
                    FROM tb_transaksi_sub ts 
                    JOIN tb_transaksi t ON ts.id_trans = t.id_trans 
                    WHERE t.id_user = '$id_user'
                    ORDER BY t.tgl_transaksi DESC
                ");

                if (mysqli_num_rows($query_transaksi) > 0) {
                    $total_debet = 0;
                    $total_kredit = 0;

                    echo "<table>
                            <tr>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th>Debet</th>
                                <th>Kredit</th>
                                <th>Aksi</th>
                            </tr>";

                    while ($row = mysqli_fetch_assoc($query_transaksi)) {
                        $total_debet += $row['debet'];
                        $total_kredit += $row['kredit'];

                        echo "<tr>
                                <td>{$row['tgl_transaksi']}</td>
                                <td>{$row['deskripsi']}</td>
                                <td>" . number_format($row['debet'], 0, ',', '.') . "</td>
                                <td>" . number_format($row['kredit'], 0, ',', '.') . "</td>
                                <td class='aksi'>
                                    <a href='edit_transaksi.php?id={$row['id']}' class='edit'>Edit</a>
                                    <a href='delete_transaksi.php?id={$row['id']}' class='delete' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
                                </td>
                              </tr>";
                    }

                    $saldo = $total_debet - $total_kredit;

                    echo "
                        <tr style='font-weight: bold; background-color: #ECF0F1;'>
                            <td colspan='2'>Total</td>
                            <td>" . number_format($total_debet, 0, ',', '.') . "</td>
                            <td>" . number_format($total_kredit, 0, ',', '.') . "</td>
                            <td></td>
                        </tr>
                        <tr class='saldo'>
                            <td colspan='4'>Saldo Akhir:</td>
                            <td style='color:" . ($saldo < 0 ? "#E74C3C" : "#27AE60") . ";'>
                                " . number_format($saldo, 0, ',', '.') . "
                            </td>
                        </tr>";
                    echo "</table>";
                } else {
                    echo "<p style='text-align:center;'>Belum ada transaksi untuk nasabah ini.</p>";
                }
            endwhile; ?>

        </div>
    </div>

</body>
</html>
