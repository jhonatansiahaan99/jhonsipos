<?php

//update atau ganti password didatabase
function update($data)
{
    global $koneksi;
    $curPass = trim(mysqli_real_escape_string($koneksi, $_POST['curPass']));
    $newPass = trim(mysqli_real_escape_string($koneksi, $_POST['newPass']));
    $confPass = trim(mysqli_real_escape_string($koneksi, $_POST['confPass']));

    $userActive = userLogin()['USERNAME'];
    if ($newPass !== $confPass) { //password baru tidak sama dengan password konfirmasi maka kita tolak
        echo "<script>
        alert('Password gagal diperbarui..');
        document.location='?msg=err1';
        </script>";
        return false;
    }
    if (!password_verify($curPass, userLogin()['PASSWORD'])) //mengecek password yang di input user sama dengan password sekarang yang sedang login .
    {
        echo "<script>
        alert('Password gagal diperbarui..');
        document.location='?msg=err2';
        </script>";
        return false;
    } else {
        $pass = password_hash($newPass, PASSWORD_DEFAULT);
        mysqli_query($koneksi, "UPDATE tbl_user SET PASSWORD = '$pass' WHERE USERNAME = '$userActive' ");
        return mysqli_affected_rows($koneksi);
    }
}
