<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'dashboard.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deskripsi = $_POST['deskripsi'];
    $debet = $_POST['debet'];
    $kredit = $_POST['kredit'];

    $stmt = $conn->prepare("UPDATE tb_transaksi_sub SET deskripsi=?, debet=?, kredit=? WHERE id=?");
    $stmt->bind_param("sddi", $deskripsi, $debet, $kredit, $id);
    $stmt->execute();

    header("Location: $redirect");
    exit();
}

$sql = "SELECT deskripsi, debet, kredit FROM tb_transaksi_sub WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head><title>Edit Transaksi</title></head>
<body>
<style>
    * {
        box-sizing: border-box;
    }

    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        font-family: 'Arial', sans-serif;
        background-image: url('https://images.unsplash.com/photo-1556742044-3c52d6e88c62?auto=format&fit=crop&w=1950&q=80');
        background-size: cover;
        background-position: center;
        color: #444;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    form {
        background: white;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.26);
        width: 100%;
        max-width: 400px;
        border: 2px solid #D5DBDB;
    }

    h2 {
        color: #16A085;
        font-size: 24px;
        margin-bottom: 20px;
        text-align: center;
    }

    label {
        display: block;
        text-align: left;
        font-weight: bold;
        margin-bottom: 5px;
        color: #2C3E50;
    }

    input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 2px solid #D5DBDB;
        border-radius: 5px;
        font-size: 16px;
    }

    button {
        width: 100%;
        padding: 12px;
        border: none;
        font-size: 16px;
        font-weight: bold;
        border-radius: 5px;
        background-color: #27AE60;
        color: white;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background-color: #219150;
    }

    @media (max-width: 400px) {
        form {
            padding: 25px;
        }
    }
</style>

<form method="POST">
    <h2>Edit Transaksi</h2>
    <label>Deskripsi:</label>
    <input type="text" name="deskripsi" value="<?= $result['deskripsi'] ?>" required>

    <label>Debet:</label>
    <input type="number" name="debet" value="<?= $result['debet'] ?>" step="0.01" required>

    <label>Kredit:</label>
    <input type="number" name="kredit" value="<?= $result['kredit'] ?>" step="0.01" required>

    <button type="submit">Simpan</button>
</form>
</body>
</html>
