<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'dashboard.php';

// Dapatkan ID transaksi dari sub-transaksi
$sql = "SELECT id_trans FROM tb_transaksi_sub WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $id_trans = $row['id_trans'];

    // Hapus sub-transaksi
    $stmt = $conn->prepare("DELETE FROM tb_transaksi_sub WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Cek apakah transaksi utama masih memiliki sub-transaksi
    $sql = "SELECT COUNT(*) as total FROM tb_transaksi_sub WHERE id_trans=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_trans);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['total'];

    // Jika tidak ada sub-transaksi, hapus transaksi utama
    if ($count == 0) {
        $stmt = $conn->prepare("DELETE FROM tb_transaksi WHERE id_trans=?");
        $stmt->bind_param("i", $id_trans);
        $stmt->execute();
    }
}

// Redirect kembali ke laporan
header("Location: $redirect");
exit();
?>
