<?php
session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-user.php";


$id = $_GET['id'];
$foto = $_GET['foto'];

if (delete($id, $foto)) {
    echo "
        <script>
            alert('User Berhasil Dihapus..');
            document.location.href = 'data-user.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('User Gagal Dihapus..');
            document.location.href = 'data-user.php';
        </script>
    ";
}
