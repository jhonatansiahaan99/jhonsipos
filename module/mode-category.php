<?php

if (userLogin()['USER_LEVEL'] == 3) { //MENGECEK USER YANG AKTIF // ketika level user 3 yaitu kasir maka kita tolak untuk akses supplier
    header("location:" . $main_url . "error-page.php");
    exit();
}

//simpan supplier
function insert($data)
{
    global $koneksi;
    $Kategori = strtoupper(mysqli_real_escape_string($koneksi, $data['kategori']));

    $Cek = mysqli_query($koneksi, "SELECT CATEGORY FROM tbl_kategori where CATEGORY = '$Kategori' ");
    if (mysqli_num_rows($Cek) > 0) {
        echo "<script>
        alert('Kategori sudah terpakai, Kategori gagal ditambahkan !');
        </script>";
        return false;
    }

    $sqlCategory = "INSERT INTO tbl_kategori VALUES (null,'$Kategori')";
    mysqli_query($koneksi, $sqlCategory);
    return mysqli_affected_rows($koneksi);
}


//delete category
function delete($id)
{
    global $koneksi;
    $sqlDelete = "DELETE FROM tbl_kategori WHERE ID_CATEGORY = '$id' ";
    mysqli_query($koneksi, $sqlDelete);
    return mysqli_affected_rows($koneksi);
}
