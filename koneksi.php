<?php
$koneksi = mysqli_connect("localhost", "root", "", "osis_absensi");

if (!$koneksi) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

?>
