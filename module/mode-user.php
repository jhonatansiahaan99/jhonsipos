<?php

if (userLogin()['USER_LEVEL'] != 1) { // jika user
    header("location:" . $main_url . "error-page.php");
    exit();
}



function insert($data)
{
    global $koneksi;

    $Username = strtolower(mysqli_real_escape_string($koneksi, $data['username'])); //strlower = ketika user mengetik nama maka otomatis diubah huruf kecil semua untuk masuk kedatabase
    $Nama = mysqli_real_escape_string($koneksi, $data['fullname']);
    $Password = mysqli_real_escape_string($koneksi, $data['password']);
    $Password2 = mysqli_real_escape_string($koneksi, $data['konfirmasipassword']);
    $Level = mysqli_real_escape_string($koneksi, $data['leveluser']);
    $Status = mysqli_real_escape_string($koneksi, $data['status_user']);
    $Gambar = mysqli_real_escape_string($koneksi, $_FILES['foto']['name']);

    if ($Password !== $Password2) {
        echo "<script>
        alert('Konfirmasi password tidak sesuai, user baru gagal diregistrasi !');
        </script>";
        return false; //fungsi return false disini Jika password tidak memenuhi syarat, maka program akan memberikan pesan kesalahan dan mengembalikan nilai false untuk membatalkan proses password.
    }
    $Pass = password_hash($Password, PASSWORD_DEFAULT);

    $CekUsername = mysqli_query($koneksi, "SELECT USERNAME FROM tbl_user where USERNAME = '$Username' ");
    if (mysqli_num_rows($CekUsername) > 0) {
        echo "<script>
        alert('Username sudah terpakai, user baru gagal diregistrasi !');
        </script>";
        return false;
    }

    if ($Gambar != null) {
        $Gambar = uploadimg();
    } else {
        $Gambar = 'default.png';
    }

    //gambar tidak sesuai validasi
    if ($Gambar == '') {
        return false;
    }

    $SqlUser = "INSERT INTO tbl_user value (null,'$Nama','$Username', '$Pass','$Level','$Gambar','$Status') ";
    mysqli_query($koneksi, $SqlUser);
    return mysqli_affected_rows($koneksi); //mengembalikan atau menampilkan jumllah baris yang terkena pengaruh query seperti insert,select, update, dan delete

}


function delete($id, $foto)
{
    global $koneksi;
    $sqlDel = "DELETE FROM tbl_user WHERE ID_USER = $id";
    mysqli_query($koneksi, $sqlDel);
    if ($foto != 'default.png') { //file gambar tidak gambar default.png maka hapus gambar
        unlink('../asset/imageuser/' . $foto);
    }

    return mysqli_affected_rows($koneksi);
}



function selectUser1($level)
{
    $result = null;
    if ($level == 1) {
        $result = "selected";
    }
    return $result;
}

function selectUser2($level)
{
    $result = null;
    if ($level == 2) {
        $result = "selected";
    }
    return $result;
}

function selectUser3($level)
{
    $result = null;
    if ($level == 3) {
        $result = "selected";
    }
    return $result;
}

function radioUser1($status)
{
    $result = null;
    if ($status == "Aktif") {
        $result = "checked";
    }
    return $result;
}

function radioUser2($status)
{
    $result = null;
    if ($status == "Tidak Aktif") {
        $result = "checked";
    }
    return $result;
}



function update($data)
{
    global $koneksi;

    $Iduser = mysqli_real_escape_string($koneksi, $data['id']);
    $Username = strtolower(mysqli_real_escape_string($koneksi, $data['username'])); //strlower = ketika user mengetik nama maka otomatis diubah huruf kecil semua untuk masuk kedatabase
    $Nama = mysqli_real_escape_string($koneksi, $data['fullname']);
    $Level = mysqli_real_escape_string($koneksi, $data['leveluser']);
    $Status = mysqli_real_escape_string($koneksi, $data['status_user']);
    $Gambar = mysqli_real_escape_string($koneksi, $_FILES['foto']['name']);
    $Fotolama = mysqli_real_escape_string($koneksi, $data['oldImg']);

    //cek username sekarang
    $QueryUsername = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE ID_USER = $Iduser");
    $DataUsername = mysqli_fetch_assoc($QueryUsername);
    $CurUsername = $DataUsername['USERNAME'];

    //cek username baru
    $NewUsername = mysqli_query($koneksi, "SELECT USERNAME FROM tbl_user WHERE USERNAME = '$Username' ");

    if ($Username !== $CurUsername) {
        if (mysqli_num_rows($NewUsername)) { //apabila nama username sudah ada di database maka kita tolak
            echo "<script>
                    alert('Username sudah terpakai, update data user gagal !');
                </script>";
            return false;
        }
    }

    //cek gambar
    if ($Gambar != null) {
        $Url = "data-user.php";
        $ImgUser = uploadimg($Url);
        if ($Fotolama != 'default.png') {
            @unlink('../asset/imageuser' . $Fotolama);
        }
    } else {
        $ImgUser = $Fotolama;
    }

    mysqli_query($koneksi, "UPDATE tbl_user SET USERNAME = '$Username', NAMA = '$Nama', USER_LEVEL = '$Level', FOTO = '$ImgUser', STATUS_USER = '$Status' WHERE ID_USER = $Iduser ");

    return mysqli_affected_rows($koneksi); //mengembalikan atau menampilkan jumllah baris yang terkena pengaruh query seperti insert,select, update, dan delete

}
