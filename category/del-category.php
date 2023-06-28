<?php

session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-category.php";



$id = $_GET['id'];

if (delete($id)) { //jika ada data dihapus maka tampilkan alert berhasil 
    echo "
        <script>document.location.href = 'add-category.php?msg=deleted'</script>
    
    ";
} else { //jika ada data dihapus gagal
    echo "
        <script>document.location.href = 'add-category.php?msg=aborted'</script>
    
    ";
}
