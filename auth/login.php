<?php


session_start();

if (isset($_SESSION["ssLoginPOS"])) { //jika session sudah ada maka user sudah masuk ke aplikasi tetapi dia masuk ke halaman login lagi, itu tidak boleh maka kita arahkan ke dashboard
    header("location: ../dashboard.php");
    exit();
}


require "../config/config.php";

if (isset($_POST['login'])) {
    $Username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $Password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $QueryLogin = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE USERNAME ='$Username' ");
    if (mysqli_num_rows($QueryLogin) === 1) { // mengecek username
        $Row = mysqli_fetch_assoc($QueryLogin);
        if (password_verify($Password, $Row['PASSWORD'])) { //kita mencocok kan password yang di input user dengan password di database
            //setelah user berhasil memasukkan username dan password dengan benar maka kita buat variabel session
            //set session
            if ($Row['STATUS_USER'] == 'Aktif') {
                $_SESSION["ssLoginPOS"] = true;
                $_SESSION["ssUserPOS"] = $Username;
                header("location: ../dashboard.php");
                exit();
            } else {
                echo "<script>alert('Status Anda tidak aktif, Mohon Laporkan Ke Admin');</script>";
            }
        } else {
            echo "<script>alert('Password Salah...');</script>";
        }
    } else {
        echo "<script>alert('Username Tidak Terdaftar...');</script>";
    }
}


// if (isset($_POST['login'])) {
//     $Username = mysqli_real_escape_string($koneksi, $_POST['username']);
//     $Password = mysqli_real_escape_string($koneksi, $_POST['password']);

//     $QueryLogin = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE USERNAME ='$Username' AND PASSWORD ='$Password' ");
//     if (mysqli_num_rows($QueryLogin) > 0) { // mengecek username
//         header("location: ../dashboard.php");
//         exit();
//     }
// }



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Jhonsi Bengkel Motor</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/dist/css/adminlte.min.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= $main_url ?>asset/image/icon_pos.png" type="image/x-icon">
    <!-- css -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="hold-transition login-page" id="bg-login">
    <!-- class : slide down berfungsi dibuat di css untuk animasi  -->
    <div class="login-box slide-down" style="margin-top: -70px;">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>JHONSI</b>Bengkel Motor</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form action="" method="post">
                    <div class="input-group mb-4">
                        <input type="text" name="username" class="form-control" placeholder="username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-4">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="mb-4">
                        <button type="submit" name="login" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </form>
                <p class="my-3 text-center">
                    <strong>Copyright &copy; 2023 <span class="text-info">Jhonsi Bengkel Motor</span></strong>
                </p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= $main_url ?>asset/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
</body>

</html>