<?php

if (userLogin()['USER_LEVEL'] == 3) { //MENGECEK USER YANG AKTIF // ketika level user 3 yaitu kasir maka kita tolak untuk akses supplier
    header("location:" . $main_url . "error-page.php");
    exit();
}

function simpan($data)
{
    global $koneksi;
    $typeMotor        = strtoupper(mysqli_real_escape_string($koneksi, $data['typemotor']));
    $cekBarcode     = mysqli_query($koneksi, "SELECT * FROM tbl_type_motor WHERE TYPE_MOTOR = '$typeMotor' ");
    if (mysqli_num_rows($cekBarcode)) {
        echo '<script>alert("Nama Type Motor Sudah Ada, Nama Gagal DiTambahkan")</script>';
        return false;
    }

    mysqli_query($koneksi, "INSERT INTO tbl_type_motor value (NULL,'$typeMotor')");
    return mysqli_affected_rows($koneksi);
}

function delete($idmotor)
{
    global $koneksi;

    mysqli_query($koneksi, "DELETE FROM tbl_type_motor WHERE ID_MOTOR = '$idmotor'");
    return mysqli_affected_rows($koneksi);
}
