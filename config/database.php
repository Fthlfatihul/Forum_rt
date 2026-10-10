<?php

date_default_timezone_set('Asia/Jakarta');

$host     = "localhost";
$username = "root";
$password = "";
$database = "forum_rt";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi database gagal : " . mysqli_connect_error());
}
?>