<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pilih Laporan</title>
    
</head>
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
            background: rgba(255, 255, 255, 0.2); /* Soft white background */
            padding: 40px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 500px;
            margin: 40px auto;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
        }

        .form-container h1 {
            font-size: 26px;
            margin-bottom: 25px;
            color: #2C3E50;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-container label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2C3E50;
            font-size: 16px;
        }

        .form-container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #BDC3C7;
            border-radius: 8px;
            font-size: 16px;
            background: #ECF0F1;
            color: #2C3E50;
            transition: border-color 0.3s ease;
        }

        .form-container input:focus {
            border-color: #27AE60; /* Green focus border */
            outline: none;
        }

        .form-container button {
            width: 100%;
            padding: 15px;
            border: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            background-color: #27AE60;
            color: white;
        }

        .form-container button:hover {
            background-color: #2ECC71;
        }

        /* Laporan Button Styling */
        .laporan-button {
            background-color: #2980B9;
            color: white;
            font-weight: 600;
            padding: 15px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            width: 100%;
            margin: 10px 0;
            cursor: pointer;
            transition: 0.3s;
        }

        .laporan-button:hover {
            background-color: #1F618D;
        }

        .laporan-button:focus {
            outline: none;
        }

        /* Additional Styling */
        h2 {
            color:rgb(245, 250, 249);
            font-size: 22px;
            margin-bottom: 20px;
        }

        a {
            color:rgb(244, 248, 251);
            text-decoration: none;
            font-size: 16px;
            margin-top: 20px;
            display: inline-block;
            font-weight: bold;
            transition: 0.3s;
        }

        a:hover {
            color:rgb(250, 251, 251);
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
            <h2>Pilih Laporan</h2>
            <form action="laporan_admin.php" method="GET">
                <button type="submit" class="laporan-button">Laporan Transaksi Admin</button>
            </form>
            <form action="laporan_user.php" method="GET">
                <button type="submit" class="laporan-button">Laporan Transaksi User</button>
            </form>
        </div>
    </div>
</body>
</html>
