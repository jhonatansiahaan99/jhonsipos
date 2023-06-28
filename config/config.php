<?php

date_default_timezone_set('Asia/Jakarta');

$Host = 'localhost';
$User = 'root';
$Pass = '';
$Dbname = 'db_bengkel';

$koneksi = mysqli_connect($Host, $User, $Pass, $Dbname);

// if (mysqli_connect_errno()) {
//     echo "Gagal Koneksi Ke database";
// } else {
//     echo "berhasil koneksi Ke database";
// }

$main_url = 'http://localhost:81/jhonsipos/';
