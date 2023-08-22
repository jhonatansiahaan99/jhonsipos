<?php

session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-barang.php";

$title = "Tambah Barcode - Jhonsi Bengkel Motor";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

$id = isset($_GET['id']) ? $_GET['id'] : '';


if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}


//jika barcode di hapus
if ($msg == 'deleted') {
    $idBarcode = $_GET['idbarcode'];
    $idBarang = $_GET['idbarang'];
    delete_barcode($idBarcode);
    echo "<script>
            setTimeout(function() {
                swal({
                    title: 'Data List Barcode',
                    text: 'Berhasil Di Hapus',
                    type: 'success',
                    showConfirmButton: false,
                    timer: 1000
                }, function() {
                    window.location.href = 'add-barcode.php?id=$idBarang';
                });
            }, 100);
        </script>";
}

if (isset($_POST['simpan'])) {
    if (simpan_barcode($_POST)) {
        echo "<script>
            setTimeout(function() {
                swal({
                    title: 'Data Barcode',
                    text: 'Berhasil Di Simpan',
                    type: 'success',
                    showConfirmButton: false,
                    timer: 2000
                }, window.onload = function(){                        
                        window.location = 'add-barcode.php?id=$id';
                });
            }, 100);
        </script>";
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
                    <h1 class="m-0">Tambah Barcode</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Tambah Barcode</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="card">
                        <form action="" method="post">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-plus fa-sm"></i> Add Barcode</h3>
                                <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right"><i class="fas fa-save"></i> Simpan</button>
                                <button type="reset" class="btn btn-danger btn-sm float-right mr-1"><i class="fas fa-times"></i> Reset</button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="category">Barcode</label>
                                            <input type="hidden" name="id_barang" id="id_barang" class="form-control" value="<?= $id ?>" required>
                                            <input type="text" name="addbarcode" id="addbarcode" class="form-control" placeholder="Masukkan Barcode" autocomplete="off" autofocus required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Data Barcode</h3>
                        </div>
                        <div class="card-body table-responsive p-3">
                            <table class="table table-hover text-nowrap" id="tblData">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Barcode</th>
                                        <th style="width: 10%;">Operasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $sqlBarcode = getData("SELECT * FROM tbl_barcode WHERE ID_BARANG = '$id' ORDER BY ID_BARCODE DESC");
                                    foreach ($sqlBarcode as $data_barcode) :
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $data_barcode['ADD_BARCODE'] ?></td>
                                            <td>
                                                <a href="?idbarcode=<?= $data_barcode['ID_BARCODE'] ?>&idbarang=<?= $id ?>&msg=deleted" class="btn btn-sm btn-danger" title="Hapus Barcode" onclick="return confirm('Anda yakin akan menghapus barcode ini ?')"><i class="fas fa-trash"></i></a>
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
        </div>
    </section>






    <?php
    require "../template/footer.php";
    ?>