<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deskripsi = $_POST['deskripsi'];
    $debet = $_POST['debet'];
    $kredit = $_POST['kredit'];
    $id_user = $_SESSION['id_user'];

    // Insert ke tabel transaksi
    $stmt = $conn->prepare("INSERT INTO tb_transaksi (id_user, tgl_transaksi) VALUES (?, NOW())");
    $stmt->bind_param("s", $id_user);
    $stmt->execute();
    $id_trans = $conn->insert_id;

    // Insert ke tabel transaksi_sub
    $stmt = $conn->prepare("INSERT INTO tb_transaksi_sub (id_trans, deskripsi, debet, kredit) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isdd", $id_trans, $deskripsi, $debet, $kredit);
    $stmt->execute();

    // Redirect ke laporan sesuai role
    if ($_SESSION['role'] == 'admin') {
        header("Location: laporan_admin.php");
    } else {
        header("Location: laporan_user.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Tambah Transaksi</title></head>
<body>
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

        /* Main Content */
        .main-content {
            margin-left: 220px;
            padding: 50px;
            width: calc(100% - 220px);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            color: #fff;
        }

        /* Form Styling */
        .form-container {
            background: rgba(255, 255, 255, 0.2);
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
            text-align: center;
            width: 100%;
            max-width: 600px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin: 0 auto;
        }

        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.5);
        }

        .form-container h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #f5f5f5;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.4);
        }

        .form-container label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-bottom: 5px;
            color:rgb(252, 253, 254);
        }

        .form-container input {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #D5DBDB;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            border: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .form-container button:hover {
            background-color:rgb(62, 126, 62);
        }

        /* Responsif */
        @media (max-width: 400px) {
            .form-container {
                width: 90%;
            }
        }
    </style>

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

    <div class="main-content">
        <div class="form-container">
            <h1>Tambah Transaksi</h1>
            <form method="POST">
                <label>Deskripsi:</label>
                <input type="text" name="deskripsi" required><br><br>

                <label>Debet:</label>
                <input type="number" name="debet" step="0.01" required><br><br>

                <label>Kredit:</label>
                <input type="number" name="kredit" step="0.01" required><br><br>

                <button type="submit">Simpan</button>
            </form>
        </div>
    </div>

</body>
</html>
