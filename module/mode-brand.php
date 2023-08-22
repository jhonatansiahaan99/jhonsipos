<?php

if (userLogin()['USER_LEVEL'] == 3) { //MENGECEK USER YANG AKTIF // ketika level user 3 yaitu kasir maka kita tolak untuk akses supplier
    header("location:" . $main_url . "error-page.php");
    exit();
}

function simpan($data)
{
    global $koneksi;

    $brand = strtoupper(mysqli_real_escape_string($koneksi, $data['brand'])); //strtoupper = ketika user mengetik nama maka otomatis diubah huruf besar semua untuk masuk kedatabase
    $cekBrand     = mysqli_query($koneksi, "SELECT * FROM tbl_brand WHERE BRAND = '$brand' ");
    if (mysqli_num_rows($cekBrand)) {
        echo '<script>alert("Nama Brand Sudah Ada, Nama Gagal DiTambahkan")</script>';
        return false;
    }

    mysqli_query($koneksi, "INSERT INTO tbl_brand value (NULL,'$brand')");
    return mysqli_affected_rows($koneksi);
}

function delete($idbrand)
{
    global $koneksi;

    mysqli_query($koneksi, "DELETE FROM tbl_brand WHERE ID_BRAND = '$idbrand'");
    return mysqli_affected_rows($koneksi);
}
