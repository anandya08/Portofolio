<?php
$host = "localhost";
$user = "root"; // Sesuaikan dengan username MySQL
$pass = ""; // Sesuaikan dengan password MySQL
$db = "catatan_keuangan";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
