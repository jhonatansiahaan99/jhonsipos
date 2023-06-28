<?php

session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-barang.php";

$title = "Laporan Penjualan - Jhonsi Bengkel Motor";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

$penjualan = getData("SELECT * FROM tbl_jual_head");


?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Penjualan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Penjualan</li>
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
                    <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Data Penjualan</h3>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap" id="tblData">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Penjualan</th>
                                <th>Tgl Penjualan</th>
                                <th>Customer</th>
                                <th>Total Penjualan</th>
                                <th>Keterangan</th>
                                <th>Jumlah Bayar</th>
                                <th>Kembalian</th>
                                <th style="width : 10%;" class="text-center">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($penjualan as $jual) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $jual['NO_JUAL'] ?></td>
                                    <td><?= in_date($jual['TGL_JUAL'])  ?></td>
                                    <td><?= $jual['CUSTOMER'] ?></td>
                                    <td class="text-center"><?= number_format($jual['TOTAL'], 0, ",", ".") ?></td>
                                    <td><?= $jual['KETERANGAN'] ?></td>
                                    <td class="text-center"><?= number_format($jual['JML_BAYAR'], 0, ",", ".") ?></td>
                                    <td class="text-center"><?= number_format($jual['KEMBALIAN'], 0, ",", ".") ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-info" id="btnDetail" data-nojual="<?= $jual['NO_JUAL'] ?>" data-tgl="<?= $jual['TGL_JUAL'] ?>" title="rincian barang" onclick="ubah('<?= $jual['NO_JUAL'] ?>')">Detail</button>

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


    <!-- modal -->
    <div class="modal fade" id="mdlDetail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cetak Barcode</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="nmBrg" class="col-sm-3 col-form-label">Nama Barang</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nmBrg" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="barcode" class="col-sm-3 col-form-label">Barcode</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="barcode" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="jmlCetak" class="col-sm-3 col-form-label">Jumlah Cetak</label>
                        <div class="col-sm-9">
                            <input type="number" min="1" max="10" value="1" title="maximal 10" id="jmlCetak" class="form-control" id="barcode">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="preview"><i class="fas fa-print"></i> Cetak</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->






    <script>
        $(document).ready(function() {
            $(document).on("click", "#btnDetail", function() { //#btnCetakBarcode diambil dari tombol diatas //#btnCetakBarcode diklik maka kita jalani fungi untuk menampilkan modal
                $('#mdlDetail').modal('show'); //carikan elemen yang id nya #mdlCetakBarcode, ketika ketemu kemudian show(tampilkan)
                let barcode = $(this).data('barcode'); //data-barcode diambil dari tombol, bukan diambil dari id tapi data- dengan nama barcode
                let nama = $(this).data('nama');
                $('#nmBrg').val(nama); //cara baca nya biar mudah, jquery cari kan id #nmBrg, .(titik disebut kemudian jika ketemu),jika ketemu isi nya diambil nilai yaitu val // id nmBrg diambil dari id di form modal
                $('#barcode').val(barcode);
            })

        })
    </script>



    <?php
    require "../template/footer.php";
    ?>