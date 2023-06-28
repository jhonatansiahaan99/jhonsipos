<?php

//simpan customer
function insert($data)
{
    global $koneksi;

    $Nama = strtoupper(mysqli_real_escape_string($koneksi, $data['nama'])); //strtoupper = ketika user mengetik nama maka otomatis diubah huruf besar semua untuk masuk kedatabase
    $Telepon = mysqli_real_escape_string($koneksi, $data['telepon']);
    $Deskripsi = mysqli_real_escape_string($koneksi, $data['keterangan']);


    $CekNama = mysqli_query($koneksi, "SELECT NAMA FROM tbl_customer where NAMA = '$Nama' ");
    if (mysqli_num_rows($CekNama) > 0) {
        echo "<script>
        alert('Nama Customer Sudah Ada, Customer Baru Gagal DiTambahkan !');
        </script>";
        return false;
    }

    $sqlCustomer = "INSERT INTO tbl_customer VALUES (null,'$Nama','$Telepon','$Deskripsi')";
    mysqli_query($koneksi, $sqlCustomer);
    return mysqli_affected_rows($koneksi);
}


//delete customer
function delete($id)
{
    global $koneksi;
    $sqlDelete = "DELETE FROM tbl_customer WHERE ID_CUSTOMER = '$id' ";
    mysqli_query($koneksi, $sqlDelete);
    return mysqli_affected_rows($koneksi);
}


function update($data)
{
    global $koneksi;
    $Id = mysqli_real_escape_string($koneksi, $data['id']);
    $Nama = strtoupper(mysqli_real_escape_string($koneksi, $data['nama'])); //strtoupper = ketika user mengetik nama maka otomatis diubah huruf besar semua untuk masuk kedatabase
    $Telepon = mysqli_real_escape_string($koneksi, $data['telepon']);
    $Deskripsi = mysqli_real_escape_string($koneksi, $data['keterangan']);


    //cek Nama sekarang
    $QueryNama = mysqli_query($koneksi, "SELECT * FROM tbl_customer WHERE ID_CUSTOMER = $Id");
    $Data = mysqli_fetch_assoc($QueryNama);
    $CurNama = $Data['NAMA'];

    //cek Nama baru
    $NewNama = mysqli_query($koneksi, "SELECT NAMA FROM tbl_customer WHERE NAMA = '$Nama' ");


    if ($Nama !== $CurNama) {
        if (mysqli_num_rows($NewNama)) { //apabila nama nama sudah ada di database maka kita tolak
            echo "<script>
                    alert('Nama sudah terpakai, update data customer gagal !');
                </script>";
            return false;
        }
    }

    mysqli_query($koneksi, "UPDATE tbl_customer SET NAMA = '$Nama', TELEPON = '$Telepon', DESKRIPSI = '$Deskripsi' WHERE ID_CUSTOMER = $Id ");
    return mysqli_affected_rows($koneksi);
}
