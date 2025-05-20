<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $checkUser = $conn->prepare("SELECT id_user FROM tb_users WHERE username = ? AND delete_at IS NULL");
    $checkUser->bind_param("s", $username);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username sudah digunakan!');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO tb_users (name, username, password, role, create_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $name, $username, $password, $role);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Akun berhasil dibuat! Silakan login.');
                    window.location.href = 'login.php';
                  </script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat membuat akun.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buat Akun</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            position: relative;
        }

        .background-blur {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1556742044-3c52d6e88c62?auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            filter: blur(10px);
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(0px);
            padding: 10px;
            border-radius: 10px;
            width: 400px;
            color: white;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        h1, h2 {
            text-align: center;
            margin: 0;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 18px;
            color: #f1f1f1;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 95%;
            padding: 10px;
            margin-bottom: 18px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            background: rgba(255,255,255,0.8);
            color: #333;
        }

        input::placeholder {
            color: #777;
        }

        button {
            width: 100%;
            padding: 12px;
            font-weight: bold;
            font-size: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button[type="submit"] {
            background-color: #2980B9;
            color: white;
        }

        button[type="submit"]:hover {
            background-color: #1F618D;
        }

        .buat-akun {
            text-align: center;
            margin-top: 15px;
        }

        .buat-akun a {
            color: #ecf0f1;
            font-weight: 600;
            text-decoration: none;
        }

        .buat-akun a:hover {
            text-decoration: underline;
            color: #1abc9c;
        }

        @media (max-width: 420px) {
            .container {
                margin: 60px 15px;
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="background-blur"></div>

    <div class="container">
        <h1>Aplikasi Catatan Keuangan</h1>
        <h2>Buat Akun</h2>
        <form method="POST">
            <label>Nama:</label>
            <input type="text" name="name" required placeholder="Masukkan nama">

            <label>Username:</label>
            <input type="text" name="username" required placeholder="Masukkan username">

            <label>Password:</label>
            <input type="password" name="password" required placeholder="Masukkan password">

            <label>Pilih Peran:</label>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Buat Akun</button>
        </form>

        <div class="buat-akun">
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</body>
</html>
