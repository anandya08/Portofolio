<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM tb_users WHERE username = ? AND delete_at IS NULL");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            header("Location: dashboard.php");
            exit();
        }
    }
    echo "<script>alert('Login gagal! Periksa kembali username dan password.');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Blur background full screen */
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

        /* Login container jernih */
        .login-box {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border-radius: 20px;
            padding: 40px 30px;
            width: 380px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            color: #fff;
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
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            background: rgba(255,255,255,0.9);
            color: #333;
        }

        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
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

        button[name="login"] {
            background-color: #27ae60;
            color: white;
        }

        button[name="login"]:hover {
            background-color: #1e874b;
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
            .login-box {
                margin: 60px 15px;
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Background blur -->
    <div class="background-blur"></div>

    <!-- Login Box -->
    <div class="login-box">
        <h1>Aplikasi Catatan Keuangan</h1>
        <h2>Login</h2>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" required placeholder="Masukkan username">
            <label>Password:</label>
            <input type="password" name="password" required placeholder="Masukkan password">
            <button type="submit" name="login">Login</button>
        </form>
        <div class="buat-akun">
            <p>Belum punya akun? <a href="tambah_user.php">Buat Akun</a></p>
        </div>
    </div>
</body>
</html>
