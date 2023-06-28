<?php

session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-barang.php";

$title = "Barang - Jhonsi Bengkel Motor";
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
    $id     = $_GET['id']; //diambil dari tombol hapus
    $gbr    = $_GET['gbr']; //diambil dari tombol hapus
    delete($id, $gbr); //function di mode-barang.php
    // $alert = "<script>
    // $(document).ready(function(){
    //     $(document).Toasts('create',{
    //         title   : 'Sukses',
    //         body    : 'Data Barang Berhasil Dihapus dari Database..',
    //         class   : 'bg-success',
    //         icon    : 'fas fa-check-circle',
    //    position : 'topRight',
    //    autohide : true,
    //    delay : 5000,//selama 5 detik 
    //     });
    // });
    //         </script>";

    $alert = '<script>
                setTimeout(function() {
                    swal({
                        title: "Data Sukses",
                        text: "Data Barang Berhasil Di Hapus",
                        type: "success",
                        showConfirmButton: false,
                        timer: 2000
                    },);
                }, 100);
            </script>';
}
if ($msg == 'updated') {

    $user = userLogin()['USERNAME'];
    $gbrUser = userLogin()['FOTO'];
    $alert = "<script>
                $(document).ready(function(){
                    $(document).Toasts('create',{
                        title   : '$user',
                        body    : 'Data Barang Berhasil Dihapus dari Database..',
                        class   : 'bg-success',
                        image    : '../asset/imageuser/$gbrUser',
                position : 'topRight',
                autohide : true,
                delay : 5000,//selama 5 detik 
                    });
                });
            </script>";


    // $alert = '<script>
    //             setTimeout(function() {
    //                 swal({
    //                     title: "Data Sukses",
    //                     text: "Data Barang Berhasil Di Perbarui",
    //                     type: "success",
    //                     showConfirmButton: false,
    //                     timer: 2000,
    //                 },);
    //             }, 100);
    //         </script>';
}

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Barang</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Add Barang</li>
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
                    <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Data Barang</h3>
                    <a href="<?= $main_url ?>barang/form-barang.php" class="mr-2 btn btn-sm btn-primary float-right"><i class="fas fa-plus fa-sm"></i> Add Barang</a>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap" id="tblData">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Id Barang</th>
                                <th>Nama Barang</th>
                                <th>Category</th>
                                <th>Type Motor</th>
                                <th>Stock</th>
                                <th>Harga Beli</th>
                                <th>Harga Barang</th>
                                <th style="width : 10%;" class="text-center">Operasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $barang = getData("SELECT * FROM tbl_barang");
                            foreach ($barang as $brg) : ?>
                                <tr>
                                    <td><img src="../asset/imageuser/<?= $brg['GAMBAR'] ?>" alt="gambar barang" class="rounded-circle" width="60px"></td>
                                    <td><?= $brg['ID_BARANG'] ?></td>
                                    <td><?= $brg['NAMA_BARANG'] ?></td>
                                    <td><?= $brg['CATEGORY'] ?></td>
                                    <td><?= $brg['TYPE_MOTOR'] ?></td>
                                    <td><?= $brg['STOCK'] ?></td>
                                    <td><?= number_format($brg['HARGA_BELI'], 0, ',', '.')  ?></td>
                                    <td><?= number_format($brg['HARGA_BARANG'], 0, ',', '.') ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-secondary" id="btnCetakBarcode" data-barcode="<?= $brg['BARCODE'] ?>" data-nama="<?= $brg['NAMA_BARANG'] ?>" title="cetak barcode"><i class="fas fa-barcode"></i></button>
                                        <a href="form-barang.php?id=<?= $brg['ID_BARANG'] ?>&msg=editing" class="btn btn-warning btn-sm" title="edit barang"><i class="fas fa-pen"></i></a>
                                        <a href="?id=<?= $brg['ID_BARANG'] ?>&gbr=<?= $brg['GAMBAR'] ?>&msg=deleted" class="btn btn-danger btn-sm tombol-hapus" title="hapus barang"><i class="fas fa-trash"></i></a>
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
    </section>
    <!-- modal -->
    <div class="modal fade" id="mdlCetakBarcode">
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
            $(document).on("click", "#btnCetakBarcode", function() { //#btnCetakBarcode diambil dari tombol diatas //#btnCetakBarcode diklik maka kita jalani fungi untuk menampilkan modal
                $('#mdlCetakBarcode').modal('show'); //carikan elemen yang id nya #mdlCetakBarcode, ketika ketemu kemudian show(tampilkan)
                let barcode = $(this).data('barcode'); //data-barcode diambil dari tombol, bukan diambil dari id tapi data- dengan nama barcode
                let nama = $(this).data('nama');
                $('#nmBrg').val(nama); //cara baca nya biar mudah, jquery cari kan id #nmBrg, .(titik disebut kemudian jika ketemu),jika ketemu isi nya diambil nilai yaitu val // id nmBrg diambil dari id di form modal
                $('#barcode').val(barcode);
            })

            $(document).on("click", "#preview", function() { //#preview diambil dari tombol diatas //#preview diklik maka kita jalani fungi untuk menampilkan preview print
                let barcode = $('#barcode').val(); //#barcode diambil dari id form modal
                let jmlCetak = $('#jmlCetak').val(); //#barcode diambil dari id form modal
                if (jmlCetak > 0 && jmlCetak <= 10) { //jika user mengisi jumlah cetak 1-10 maka tampilkan window.open
                    window.open("../report/r-barcode.php?barcode=" + barcode + "&jmlCetak=" + jmlCetak) //jmlCetak dikirim ke r-barcode.php
                }
            })
        })
    </script>

    <?php
    require "../template/footer.php";
    ?>