<?php
$server = "localhost";
$user = "root";
$password = "";
$nama_database = "db_back_end";

// Membuat koneksi
$connect = mysqli_connect($server, $user, $password, $nama_database);

if (!$connect) {
    die("Ada masalah koneksi ke database: " . mysqli_connect_error());
}
?>
