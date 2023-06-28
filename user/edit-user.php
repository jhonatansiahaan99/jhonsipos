<?php
session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}


require "../config/config.php";
require "../config/functions.php";
require "../module/mode-user.php";

$title = "Update User - Jhonsi Bengkel Motor";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

$id = $_GET['id'];

$sqlEdit = "SELECT * FROM tbl_user WHERE ID_USER = $id";
$User = getData($sqlEdit)[0];
$Level = $User['USER_LEVEL'];
$Status = $User['STATUS_USER'];

if (isset($_POST['koreksi'])) {
    if (update($_POST)) {
        echo '<script>
            alert("Data user berhasil diupdate..");
            document.location.href = "data-user.php";
            </script>';
    }
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>user/data-user.php">Users</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-pen fa-sm"></i> Edit User</h3>
                        <button type="submit" name="koreksi" class="btn btn-primary btn-sm float-right"><i class="fas fa-save"></i> Koreksi</button>
                        <button type="reset" class="btn btn-danger btn-sm float-right mr-1"><i class="fas fa-times"></i> Reset</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" value="<?= $User['ID_USER'] ?>" name="id">
                            <div class="col-lg-8 mb-3">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" class="form-control" id="username" placeholder="masukkan username" autocomplete="off" value="<?= $User['USERNAME'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="fullname">Nama</label>
                                    <input type="text" name="fullname" class="form-control" id="fullname" placeholder="masukkan nama" value="<?= $User['NAMA'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="level">Level</label>
                                    <select name="leveluser" id="level" class="form-control">
                                        <option value="">-- Level User --</option>
                                        <option value="1" <?= selectUser1($Level) ?>>SuperAdmin</option>
                                        <option value="2" <?= selectUser2($Level) ?>>Admin</option>
                                        <option value="3" <?= selectUser3($Level) ?>>Kasir</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Status Pengguna</label>
                                    <div class="form-check">
                                        <input name="status_user" class="form-check-input" type="radio" value="Aktif" <?= radioUser1($Status) ?>>
                                        <label class="form-check-label">Aktif</label>
                                    </div>
                                    <div class="form-check">
                                        <input name="status_user" class="form-check-input" type="radio" value="Tidak Aktif" <?= radioUser2($Status) ?>>
                                        <label class="form-check-label">Tidak Aktif</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 text-center">
                                <input type="hidden" name="oldImg" value="<?= $User['FOTO'] ?>">
                                <img src="<?= $main_url ?>asset/imageuser/<?= $User['FOTO'] ?>" class="profile-user-img img-circle mb-3" alt="">
                                <input type="file" class="form-control" name="foto">
                                <span class="text-sm">Type file gambar JPG | PNG | GIF | JPEG</span><br>
                                <span class="text-sm">Width = Height</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php
    require "../template/footer.php";

    ?>