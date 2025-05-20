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
    <title>Dashboard</title>
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

        .main-content {
            margin-left: 220px;
            padding: 50px;
            width: calc(100% - 220px);
            background: linear-gradient(to bottom right, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.2));
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.4); /* Adding shadow for depth */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .welcome-section {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
            text-align: center;
            width: 100%;
            max-width: 600px; /* Limiting the width for better readability */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .welcome-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.5); /* Adding hover effect for depth */
        }

        .welcome-section h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #f5f5f5;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.4);
        }

        .welcome-section p {
            font-size: 18px;
            color: #e0e0e0;
            line-height: 1.5;
            font-weight: 400;
        }

        .dashboard-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 30px;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 20px;
            width: 220px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            backdrop-filter: blur(6px);
            transition: 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            text-align: center;
        }

        .card:hover {
            transform: translateY(-5px);
            background-color: rgba(255, 255, 255, 0.2);
        }

        .card h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #fff;
        }

        .card p {
            font-size: 16px;
            font-weight: bold;
            color: #e0e0e0;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 30px 20px;
                margin-left: 180px;
                width: calc(100% - 180px);
            }

            .dashboard-cards {
                flex-direction: column;
                align-items: center;
            }

            .card {
                width: 100%;
                max-width: 350px;
            }

            .sidebar {
                width: 180px;
            }
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

    <div class="main-content">
        <div class="welcome-section">
            <h1>👋 Selamat Datang, <?= htmlspecialchars($_SESSION['name']) ?>!</h1>
            <p>Ini adalah dashboard utama Anda. Ayo kelola keuanganmu dengan bijak 💼</p>
        </div>
    </div>

</body>
</html>
