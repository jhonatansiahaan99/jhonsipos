<?php

if (userLogin()['USER_LEVEL'] == 3) { //MENGECEK USER YANG AKTIF // ketika level user 3 yaitu kasir maka kita tolak untuk akses supplier
    header("location:" . $main_url . "error-page.php");
    exit();
}

function simpan($data)
{
    global $koneksi;
    $satuan        = strtoupper(mysqli_real_escape_string($koneksi, $data['satuan']));
    $cekSatuan     = mysqli_query($koneksi, "SELECT * FROM tbl_satuan WHERE SATUAN = '$satuan' ");
    if (mysqli_num_rows($cekSatuan)) {
        echo '<script>alert("Nama Satuan Sudah Ada, Nama Gagal DiTambahkan")</script>';
        return false;
    }

    mysqli_query($koneksi, "INSERT INTO tbl_satuan value (NULL,'$satuan')");
    return mysqli_affected_rows($koneksi);
}

function delete($idsatuan)
{
    global $koneksi;

    mysqli_query($koneksi, "DELETE FROM tbl_satuan WHERE ID_SATUAN = '$idsatuan'");
    return mysqli_affected_rows($koneksi);
}
