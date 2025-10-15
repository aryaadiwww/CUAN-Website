<?php
$host = "sql109.infinityfree.com";
$user = "if0_39691990";
$pass = "arya20031995";
$db   = "if0_39691990_dbcuan"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
  die("Koneksi gagal: " . mysqli_connect_error());
}
?>
