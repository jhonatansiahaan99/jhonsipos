<?php
session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}


require "../config/config.php";
require "../config/functions.php";
require "../module/mode-user.php";

$title = "Users - Jhonsi Bengkel Motor";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";
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
                        <li class="breadcrumb-item active">User</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->



    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Data User</h3>
                    <div class="card-tools">
                        <a href="<?= $main_url ?>user/add-user.php" class="btn btn-sm btn-primary"><i class="fas fa-plus fa-sm"></i> Add User</a>
                    </div>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Level User</th>
                                <th>Status</th>
                                <th style="width: 10%;">Operasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $Users = getData("SELECT * FROM tbl_user");
                            foreach ($Users as $User) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><img src="../asset/imageuser/<?= $User['FOTO'] ?>" class="rounded-circle" alt="" width="60px"></td>
                                    <td><?= $User['USERNAME'] ?></td>
                                    <td><?= $User['NAMA'] ?></td>
                                    <td>
                                        <?php
                                        if ($User['USER_LEVEL'] == 1) {
                                            echo "SuperAdmin";
                                        } elseif ($User['USER_LEVEL'] == 2) {
                                            echo "Admin";
                                        } else {
                                            echo "Kasir";
                                        }
                                        ?>
                                    </td>
                                    <td><?= $User['STATUS_USER'] ?></td>
                                    <td>
                                        <a href="edit-user.php?id=<?= $User['ID_USER'] ?>" class="btn btn-sm btn-warning" title="edit user"><i class="fas fa-user-edit"></i></a>
                                        <a href="del-user.php?id=<?= $User['ID_USER'] ?>&foto=<?= $User['FOTO'] ?>" class="btn btn-sm btn-danger" title="hapus user" onclick="return confirm('Anda yakin akan menghapus user ini ?')"><i class="fas fa-user-times"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>




    <?php
    require "../template/footer.php";
    ?>