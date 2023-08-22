<?php

session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-satuan.php";

$title = "Satuan - Jhonsi Bengkel Motor";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}


//jika barcode di hapus
if ($msg == 'deleted') {
    $idSatuan = $_GET['idsatuan'];
    delete($idSatuan);
    echo "<script>
            setTimeout(function() {
                swal({
                    title: 'Nama List Satuan',
                    text: 'Berhasil Di Hapus',
                    type: 'success',
                    showConfirmButton: false,
                    timer: 1000
                }, function() {
                    window.location.href = 'index.php';
                });
            }, 100);
        </script>";
}

if (isset($_POST['simpan'])) {
    if (simpan($_POST)) {
        echo "<script>
            setTimeout(function() {
                swal({
                    title: 'Data Nama Satuan',
                    text: 'Berhasil Di Simpan',
                    type: 'success',
                    showConfirmButton: false,
                    timer: 2000
                }, window.onload = function(){                        
                        window.location = 'index.php';
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
                    <h1 class="m-0">Satuan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Satuan</li>
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
                                <h3 class="card-title"><i class="fas fa-plus fa-sm"></i> Satuan</h3>
                                <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right"><i class="fas fa-save"></i> Simpan</button>
                                <button type="reset" class="btn btn-danger btn-sm float-right mr-1"><i class="fas fa-times"></i> Reset</button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="satuan">Nama Satuan</label>
                                            <input type="text" name="satuan" id="satuan" class="form-control" placeholder="Masukkan Nama Satuan" autocomplete="off" autofocus required>
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
                            <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Nama-Nama Satuan</h3>
                        </div>
                        <div class="card-body table-responsive p-3">
                            <table class="table table-hover text-nowrap" id="tblData">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Satuan</th>
                                        <th style="width: 10%;">Operasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $sqlSatuan = getData("SELECT * FROM tbl_satuan ORDER BY ID_SATUAN DESC");
                                    foreach ($sqlSatuan as $data_satuan) :
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $data_satuan['SATUAN'] ?></td>
                                            <td>
                                                <a href="?idsatuan=<?= $data_satuan['ID_SATUAN'] ?>&msg=deleted" class="btn btn-sm btn-danger" title="Hapus Nama Satuan" onclick="return confirm('Anda yakin akan menghapus Nama Satuan ini ?')"><i class="fas fa-trash"></i></a>
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