<?php

if (userLogin()['USER_LEVEL'] == 3) { //MENGECEK USER YANG AKTIF // ketika level user 3 yaitu kasir maka kita tolak untuk akses supplier
    header("location:" . $main_url . "error-page.php");
    exit();
}

//simpan supplier
function insert($data)
{
    global $koneksi;

    $Nama = mysqli_real_escape_string($koneksi, $data['nama']);
    $Telepon = mysqli_real_escape_string($koneksi, $data['telepon']);
    $Deskripsi = mysqli_real_escape_string($koneksi, $data['keterangan']);
    $Alamat = mysqli_real_escape_string($koneksi, $data['alamat']);

    $sqlSupplier = "INSERT INTO tbl_supplier VALUES (null,'$Nama','$Telepon','$Deskripsi','$Alamat')";
    mysqli_query($koneksi, $sqlSupplier);
    return mysqli_affected_rows($koneksi);
}

//delete supplier
function delete($id)
{
    global $koneksi;
    $sqlDelete = "DELETE FROM tbl_supplier WHERE ID_SUPPLIER = '$id' ";
    mysqli_query($koneksi, $sqlDelete);
    return mysqli_affected_rows($koneksi);
}


function update($data)
{
    global $koneksi;

    $Id = mysqli_real_escape_string($koneksi, $data['id']);
    $Nama = mysqli_real_escape_string($koneksi, $data['nama']);
    $Telepon = mysqli_real_escape_string($koneksi, $data['telepon']);
    $Deskripsi = mysqli_real_escape_string($koneksi, $data['keterangan']);
    $Alamat = mysqli_real_escape_string($koneksi, $data['alamat']);

    $sqlSupplier = "UPDATE tbl_supplier SET NAMA = '$Nama', TELEPON = '$Telepon', DESKRIPSI = '$Deskripsi', ALAMAT = '$Alamat' WHERE ID_SUPPLIER = $Id ";
    mysqli_query($koneksi, $sqlSupplier);
    return mysqli_affected_rows($koneksi);
}
