<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

$sql = "SELECT tb_transaksi.id_trans, tb_transaksi.tgl_transaksi, tb_transaksi_sub.id AS id_sub, 
               tb_transaksi_sub.deskripsi, tb_transaksi_sub.debet, tb_transaksi_sub.kredit
        FROM tb_transaksi 
        JOIN tb_transaksi_sub ON tb_transaksi.id_trans = tb_transaksi_sub.id_trans
        WHERE tb_transaksi.id_user = ? AND tb_transaksi.delete_at IS NULL
        ORDER BY tb_transaksi.tgl_transaksi ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$saldo = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan (User)</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1556742044-3c52d6e88c62?auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
        }
        .sidebar {
            width: 200px;
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
        .main-content {
            margin-left: 220px;
            padding: 30px;
        }
        .top-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .btn {
            padding: 10px 18px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        h2 {
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .actions a {
            margin-right: 5px;
            padding: 8px 12px;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            text-decoration: none;
        }
        .actions .btn {
            background-color: #27ae60;
        }
        .actions .btn:hover {
            background-color: #1e8449;
        }
        .actions .btn-danger {
            background-color: #e74c3c;
        }
        .actions .btn-danger:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<div class="sidebar">
        <div class="user-info">
            <img src="https://www.teknoredaksi.com/wp-content/uploads/2024/04/2a90a89df0ea59aa6dde4eb8dcfdd97a-1.png" alt="Foto Pengguna">
            <div class="name"><?= htmlspecialchars($_SESSION['name']) ?></div>
        </div>
        <a href="login.php">🔐 Login</a>
        <a href="transaksi.php">➕ Tambah Transaksi</a>
        <a href="pilih_role.php">📊 Lihat Laporan</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

<div class="main-content">
    <h2>Laporan Keuangan (User)</h2>
    <div class="top-buttons">
        <a href="dashboard.php" class="btn">⬅ Kembali</a>
        <a href="export_excel.php?role=user" target="_blank" class="btn">📁 Export ke Excel</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Saldo</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): 
                $saldo += $row['debet'] - $row['kredit'];
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['tgl_transaksi']) ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td><?= number_format($row['debet'], 0, ',', '.') ?></td>
                    <td><?= number_format($row['kredit'], 0, ',', '.') ?></td>
                    <td><?= number_format($saldo, 0, ',', '.') ?></td>
                    <td class="actions">
                        <a href="edit_transaksi.php?id=<?= $row['id_sub'] ?>&redirect=laporan_user.php" class="btn">Edit</a>
                        <a href="delete_transaksi.php?id=<?= $row['id_sub'] ?>&redirect=laporan_user.php" class="btn-danger" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
