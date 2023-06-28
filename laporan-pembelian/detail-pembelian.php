<?php

session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-barang.php";

$title = "Laporan - Jhonsi Bengkel Motor";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

$id         = $_GET['id'];
$tgl        = $_GET['tgl'];
$pembelian  = getData("SELECT * FROM tbl_beli_detail WHERE NO_BELI = '$id'");

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Pembelian</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>laporan-pembelian">Pembelian</a></li>
                        <li class="breadcrumb-item active">Detail</li>
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
                    <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Rincian Barang</h3>
                    <button type="button" class="btn btn-sm btn-success float-right"><?= $tgl ?></button>
                    <button type="button" class="btn btn-sm btn-warning float-right mr-1"><?= $id ?></button>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga Beli</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Jumlah Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($pembelian as $beli) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $beli['KODE_BRG'] ?></td>
                                    <td><?= $beli['NAMA_BRG'] ?></td>
                                    <td class="text-center"><?= number_format($beli['HARGA_BELI'], 0, ',', '.')  ?></td>
                                    <td class="text-center"><?= $beli['QTY'] ?></td>
                                    <td class="text-center"><?= number_format($beli['JML_HARGA'], 0, ',', '.')  ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>


    <?php
    require "../template/footer.php";
    ?>