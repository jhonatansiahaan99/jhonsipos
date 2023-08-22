<?php

session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";


$title = "Laporan Pembelian - Jhonsi Bengkel Motor";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

$stockBrg = getData("SELECT * FROM tbl_barang");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Stock Barang</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Stock</li>
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
                    <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Stock</h3>
                    <a href="<?= $main_url ?>report/r-stock.php" class="btn btn-sm btn-outline-primary float-right" target="_blank"><i class="fas fa-print"> Cetak</i></a>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap" id="tblData">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Jumlah Stock</th>
                                <th>Stock Minimal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($stockBrg as $stock) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $stock['ID_BARANG'] ?></td>
                                    <td><?= $stock['NAMA_BARANG'] ?></td>
                                    <td><?= $stock['SATUAN'] ?></td>
                                    <td class="text-center"><?= $stock['STOCK'] ?></td>
                                    <td class="text-center"><?= $stock['STOCK_MINIMAL'] ?></td>
                                    <td>

                                        <?php
                                        if ($stock['STOCK'] < $stock['STOCK_MINIMAL']) {
                                            echo '<span class="text-danger">Stock Kurang</span>';
                                        } else {
                                            echo 'Stock Cukup';
                                        }
                                        ?>
                                    </td>
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