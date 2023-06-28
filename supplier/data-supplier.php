<?php

session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-supplier.php";

$title = "Data Supplier - Jhonsi Bengkel Motor";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

$alert = '';
if ($msg == 'deleted') {
    $alert = '<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h5><i class="icon fas fa-check"></i> Alert!</h5>
    Supplier Berhasil Di Hapus..
  </div>';
}
if ($msg == 'aborted') {
    $alert = '<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
    Supplier Gagal Di Hapus..
  </div>';
}
if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h5><i class="icon fas fa-check-circle"></i> Alert!</h5>
    Supplier Berhasil Di Perbarui..
  </div>';
}

?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Supplier</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Data Supplier</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <?php
                if ($alert != '') {
                    echo $alert;
                }
                ?>
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Data Supplier</h3>
                    <a href="<?= $main_url ?>supplier/add-supplier.php" class="btn btn-sm btn-primary float-right">
                        <i class="fas fa-plus fa-sm"></i> Add Supplier
                    </a>
                    <div class="card-body table-responsive p-3">
                        <table class="table table-hover text-nowrap" id="tblData">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Telpon</th>
                                    <th>ALamat</th>
                                    <th>Deskripsi</th>
                                    <th style="width: 10%;">Operasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $suppliers = getData("SELECT * FROM tbl_supplier");
                                foreach ($suppliers as $supplier) :
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $supplier['NAMA'] ?></td>
                                        <td><?= $supplier['TELEPON'] ?></td>
                                        <td><?= $supplier['ALAMAT'] ?></td>
                                        <td><?= $supplier['DESKRIPSI'] ?></td>
                                        <td>
                                            <a href="edit-supplier.php?id=<?= $supplier['ID_SUPPLIER'] ?>" class="btn btn-sm btn-warning" title="edit supplier"><i class="fas fa-pen"></i></a>
                                            <a href="del-supplier.php?id=<?= $supplier['ID_SUPPLIER'] ?>" class="btn btn-sm btn-danger" title="hapus supplier" onclick="return confirm('Anda Yakin Akan Menghapus Supplier Ini ?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>

                                <?php
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>








    <?php
    require "../template/footer.php";
    ?>