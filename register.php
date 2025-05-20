<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role = "user"; // Role di-set otomatis

  $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
  if (mysqli_num_rows($cek) > 0) {
    $error = "Username sudah digunakan.";
  } else {
    mysqli_query($koneksi, "INSERT INTO users (nama, username, password, role) VALUES ('$nama', '$username', '$password', '$role')");
    header("Location: index.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register OSIS</title>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: hidden;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTpIvf_4eeI2sGpih4x_K_afalY-yKWk6t5Wg&s');
      background-size: cover;
      background-position: center;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .register-container {
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

    .register-container h2 {
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
      background-color: #007bff;
      color: white;
      font-weight: bold;
      cursor: pointer;
      margin-top: 15px;
    }

    .register-container a {
      color: rgb(139, 204, 232);
      text-decoration: none;
      font-weight: bold;
    }

    .register-container .error-message {
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
  <div class="register-container">
    <h2>Register</h2>

    <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

    <form method="POST">
      <label for="nama">Nama Lengkap:</label>
      <input type="text" id="nama" name="nama" placeholder="Nama Lengkap" required>

      <label for="username">Username:</label>
      <input type="text" id="username" name="username" placeholder="Username" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" placeholder="Password" required>

      <button type="submit" name="register">Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="index.php">Login di sini</a></p>
  </div>
</body>
</html>
