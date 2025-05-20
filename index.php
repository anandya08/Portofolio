<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
  $data = mysqli_fetch_assoc($query);

  if ($data && password_verify($password, $data['password'])) {
    $_SESSION['id'] = $data['id'];
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['role'] = $data['role'];

    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Username atau Password salah!";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login OSIS</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTpIvf_4eeI2sGpih4x_K_afalY-yKWk6t5Wg&s');
      background-size: cover;
      background-position: center;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 30px 25px;
      width: 100%;
      max-width: 320px;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
      color: #fff;
      text-align: center;
    }

    .login-container img {
      width: 100px;
      margin-bottom: 10px;
    }

    .login-container h2 {
      margin: 5px 0 20px 0;
      color: black;
    }

    form {
      text-align: left;
    }

    label {
      color: rgb(19, 17, 17);
      font-weight: bold;
      display: block;
      margin-top: 10px;
    }

    input, button {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: none;
      border-radius: 8px;
      outline: none;
      box-sizing: border-box;
    }

    button {
      background-color: #28a745;
      color: white;
      font-weight: bold;
      cursor: pointer;
      margin-top: 15px;
    }

    .login-container a {
      color: rgb(139, 204, 232);
      text-decoration: none;
      font-weight: bold;
    }

    .login-container .error-message {
      color: #ff4d4d;
      margin-bottom: 15px;
      text-align: center;
    }

    p {
      color: rgb(20, 18, 18);
      text-align: center;
      margin-top: 15px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <img src="img\1698917851792.png" alt="Logo OSIS">
    <h2>Login</h2>

    <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

    <form method="POST">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" placeholder="Masukkan username" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" placeholder="Masukkan password" required>

      <button type="submit" name="login">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Buat Akun</a></p>
  </div>
</body>
</html>
