<?php
session_start();
include 'koneksi.php';

// Cek login & role
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Hapus kegiatan
mysqli_query($koneksi, "DELETE FROM kegiatan WHERE id = $id");

header("Location: kegiatan_list.php");
exit;
?>
