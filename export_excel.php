<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Keuangan.xls");

$role = isset($_GET['role']) ? $_GET['role'] : 'user';
$id_user = ($role == 'admin' && $_SESSION['role'] == 'admin') ? "%" : $_SESSION['id_user'];

$sql = "SELECT tb_transaksi.id_trans, tb_transaksi.tgl_transaksi, tb_transaksi_sub.id AS id_sub, 
               tb_transaksi_sub.deskripsi, tb_transaksi_sub.debet, tb_transaksi_sub.kredit, tb_transaksi.id_user
        FROM tb_transaksi 
        JOIN tb_transaksi_sub ON tb_transaksi.id_trans = tb_transaksi_sub.id_trans
        WHERE tb_transaksi.id_user LIKE ? AND tb_transaksi.delete_at IS NULL
        ORDER BY tb_transaksi.tgl_transaksi ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$saldo = 0;

echo "Tanggal\tDeskripsi\tDebet\tKredit\tSaldo\n";

while ($row = $result->fetch_assoc()) {
    $saldo += $row['debet'] - $row['kredit']; // Hitung saldo otomatis
    echo "{$row['tgl_transaksi']}\t{$row['deskripsi']}\t{$row['debet']}\t{$row['kredit']}\t$saldo\n";
}
?>
