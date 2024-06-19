<?php
$server     = "localhost";
$user       = "root";
$password   = "";
$db         = "U_COMM";

$koneksi    = mysqli_connect($server, $user, $password, $db);
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database" . mysqli_connect_error());
}