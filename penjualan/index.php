<?php
session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-jual.php";

$title = "Transaksi - Jhonsi Bengkel Motor";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";


if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

//jika barang di hapus
if ($msg == 'deleted') {
    $barcode = $_GET['barcode'];
    $idjual = $_GET['idjual'];
    $qty = $_GET['qty'];
    $tgl = $_GET['tgl'];
    delete($barcode, $idjual, $qty);
    echo "<script>
            setTimeout(function() {
                swal({
                    title: 'Data List Barang',
                    text: 'Berhasil Di Hapus',
                    type: 'success',
                    showConfirmButton: false,
                    timer: 1000
                }, function() {
                    window.location.href = '?tgl=$tgl';
                });
            }, 100);
        </script>";
}

//jika ada barcode yang dikirim
$kode   = @$_GET['barcode'] ? @$_GET['barcode'] : ''; //apbaila ada barcode yang dikirim kita ambil ya, kalo tidak ada maka kosongkan
if ($kode) {
    $tgl = $_GET['tgl'];
    $dataBrg = mysqli_query($koneksi, "SELECT * FROM tbl_barang WHERE BARCODE = '$kode' ");
    $selectBrg = mysqli_fetch_assoc($dataBrg);
    if (!mysqli_num_rows($dataBrg)) { //jika barcode yand di inputkan tidak ada di data barang maka ditolak
        echo "<script>
            setTimeout(function() {
                swal({
                    title: 'Barcode Tidak Ada',
                    text: 'Proses Input Dibatalkan',
                    type: 'error',
                    showConfirmButton: false,
                    timer: 2000
                }, function() {
                    window.location.href = '?tgl=$tgl';
                });
            }, 100);
        </script>";
    }
}

//jika tombol tambah barang ditekan
if (isset($_POST['addbrg'])) {
    $tgl = $_POST['tglNota'];
    if (insert($_POST)) {
        echo "<script>
                document.location  = '?tgl=$tgl';
        </script>";
    }
}

//jika tombol simpan ditekan
if (isset($_POST['simpan'])) {
    $nota = $_POST['nojual'];
    if (simpan($_POST)) {
        echo "<script>
            setTimeout(function() {
                swal({
                    title: 'Data Barang',
                    text: 'Berhasil Di Simpan',
                    type: 'success',
                    showConfirmButton: false,
                    timer: 2000
                }, window.onload = function(){
                    let win = window.open('../report/r-struk.php?nota=$nota','Struk Belanja','width=260,height=400,left=10,top=10','_blank');
                    if(win){
                        win.focus();
                        window.location = 'index.php';
                    }
                });
            }, 100);
        </script>";
    }
}

// echo "<script>
//             setTimeout(function() {
//                 swal({
//                     title: 'Data Barang',
//                     text: 'Berhasil Di Simpan',
//                     type: 'success',
//                     showConfirmButton: false,
//                     timer: 2000
//                 },);
//             }, 100);
//             window.onload = function(){
//                 let win = window.open('../report/r-struk.php?nota=$nota','Struk Belanja','width=260,height=400,left=10,top=10','_blank');
//                 if(win){
//                     win.focus();
//                     window.location = 'index.php';
//                 }
//             }
//         </script>";



// echo "<script>
//             setTimeout(function() {
//                 swal({
//                     title: 'Data Barang',
//                     text: 'Berhasil Di Simpan',
//                     type: 'success',
//                     showConfirmButton: false,
//                     timer: 2000
//                 },);
//             }, 100);


//             window.onload = function(){
//                 let win = window.open('../report/r-struk.php?nota=$nota','Struk Belanja','width=260,height=400,left=10,top=10','_blank');
//                 if(win){
//                     win.focus();
//                     window.location = 'index.php';
//                 }
//             }
//         </script>";

$nojual = generateNo();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Penjualan Barang</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <!-- operator ternary php (if satu baris) -->
                        <!-- Cara baca nya jika $msg tidak kosong/ada data get yang dikirim maka Edit Barang, apabila kosong maka Add Barang  -->
                        <li class="breadcrumb-item active">Tambah Penjualan</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section>
        <div class="container-fluid">
            <form action="" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card card-outline card-warning p-3">
                            <div class="form-group row mb-2">
                                <label for="noNota" class="col-sm-2 col-form-label">No Nota</label>
                                <div class="col-sm-4">
                                    <input type="text" name="nojual" class="form-control" id="noNota" value="<?= $nojual ?>">
                                </div>
                                <label for="tglNota" class="col-sm-2 col-form-label">Tgl Nota</label>
                                <div class="col-sm-4">
                                    <input type="date" name="tglNota" class="form-control" id="tglNota" value="<?= @$_GET['tgl'] ? $_GET['tgl'] : date('Y-m-d')  ?>" required>
                                    <!-- @$_GET['tgl'] ? $_GET['tgl'] : date('Y-m-d') ==> ketika ada tanggal yang dikirim maka kita ambil tanggal nya, apabila tidak ada maka kita ambil tanggal hari ini. Dan tgl yang di kirim di ambil dari if (isset($_POST['addbrg'])) { -->
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="barcode" class="col-sm-2 col-form-label ">Barcode</label>
                                <div class="col-sm-10 input-group">
                                    <input type="text" name="barcode" id="barcode" class="form-control" placeholder="masukkan barcode barang" value="<?= @$_GET['barcode'] ? $_GET['barcode'] : ''  ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="icon-barcode">
                                            <i class="fas fa-barcode"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card card-outline card-danger pt-3 px-3 pb-2">
                            <h6 class="font-weight-bold text-right">Total Penjualan</h6>
                            <h1 class="font-weight-bold text-right" style="font-size:40pt;">
                                <input type="hidden" name="total" id="total" value="<?= totalJual($nojual) ?>"><?= number_format(totalJual($nojual), 0, ',', '.') ?>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="card pt-1 pb-2 px-3">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="hidden" value="<?= @$_GET['barcode'] ? $selectBrg['BARCODE'] : '' ?>" name="barcode">
                                <!-- @$_GET['pilihbrg'] ? $selectBrg['ID_BARANG'] : '' ==> berfungsi jika ada barang yang dipilih user maka diambil ID_BARANG nya kalo tidak ada maka kosong -->
                                <label for="namaBrg">Nama Barang</label>
                                <input type="text" name="namaBrg" class="form-control form-control-sm" id="namaBrg" value="<?= @$_GET['barcode'] ? $selectBrg['NAMA_BARANG'] : '' ?>" readonly>
                                <!-- @$_GET['pilihbrg'] ? $selectBrg['NAMA_BARANG'] : '' ==> berfungsi jika ada barang yang dipilih user maka diambil NAMA_BARANG nya kalo tidak ada maka kosong -->
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-group">
                                <label for="stok">Stok Barang</label>
                                <input type="number" name="stok" class="form-control form-control-sm" id="stock" value="<?= @$_GET['barcode'] ? $selectBrg['STOCK'] : '' ?>" readonly>
                                <!-- @$_GET['pilihbrg'] ? $selectBrg['STOCK'] : '' ==> berfungsi jika ada barang yang dipilih user maka diambil STOCK nya kalo tidak ada maka kosong -->
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-group">
                                <label for="satuan">Satuan</label>
                                <input type="text" name="satuan" class="form-control form-control-sm" id="satuan" value="<?= @$_GET['barcode'] ? $selectBrg['SATUAN'] : '' ?>" readonly>
                                <!-- @$_GET['pilihbrg'] ? $selectBrg['SATUAN'] : '' ==> berfungsi jika ada barang yang dipilih user maka diambil SATUAN nya kalo tidak ada maka kosong -->
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="number" name="harga" class="form-control form-control-sm" id="harga" value="<?= @$_GET['barcode'] ? $selectBrg['HARGA_BARANG'] : '' ?>" readonly>
                                <!-- @$_GET['pilihbrg'] ? $selectBrg['HARGA_BARANG'] : '' ==> berfungsi jika ada barang yang dipilih user maka diambil HARGA_BARANG nya kalo tidak ada maka kosong -->
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <input type="number" name="qty" class="form-control form-control-sm" id="qty" value="<?= @$_GET['barcode'] ? 1 : '' ?>">
                                <!-- @$_GET['pilihbrg'] ? 1 : '' ==> berfungsi jika ada barang yang dipilih user maka ditambah 1 kalo tidak ada maka kosong -->
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="jmlHarga">Jumlah Harga</label>
                                <input type="number" name="jmlHarga" class="form-control form-control-sm" id="jmlHarga" value="<?= @$_GET['barcode'] ? $selectBrg['HARGA_BARANG'] : '' ?>" readonly>
                                <!-- @$_GET['pilihbrg'] ? $selectBrg['HARGA_BARANG'] : '' ==> berfungsi jika ada barang yang dipilih user maka diambil HARGA_BARANG nya kalo tidak ada maka kosong -->
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-info btn-block" name="addbrg"><i class="fas fa-cart-plus fa-sm"></i> Tambah Barang</button>
                </div>
                <div class="card card-outline card-success table-responsive px-2">
                    <table class="table table-sm table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Barcode</th>
                                <th>Nama Barang</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Jumlah Harga</th>
                                <th class="text-center" width="10%">Operasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $brgDetail  = getData("SELECT * FROM tbl_jual_detail WHERE NO_JUAL = '$nojual' ");
                            foreach ($brgDetail as $detail) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $detail['BARCODE'] ?></td>
                                    <td><?= $detail['NAMA_BRG'] ?></td>
                                    <td class="text-right"><?= number_format($detail['HARGA_JUAL'], 0, ',', '.') ?></td>
                                    <td class="text-right"><?= $detail['QTY'] ?></td>
                                    <td class="text-right"><?= number_format($detail['JML_HARGA'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <a href="?barcode=<?= $detail['BARCODE'] ?>&idjual=<?= $detail['NO_JUAL'] ?>&qty=<?= $detail['QTY'] ?>&tgl=<?= $detail['TGL_JUAL'] ?>&msg=deleted" class="btn btn-sm btn-danger" title="Hapus Barang" onclick="return confirm('Anda yakin akan menghapus barang ini ?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-4 p-2">
                        <div class="form-group row mb-2">
                            <label for="customer" class="col-sm-3 col-form-label col-form-label-sm">Customer</label>
                            <div class="col-sm-9">
                                <select name="customer" id="customer" class="form-control form-control-sm select2">
                                    <option value="">-- Pilih Customer --</option>
                                    <?php
                                    $customers = getData("SELECT * FROM tbl_customer");
                                    foreach ($customers as $customer) { ?>
                                        <option value="<?= $customer['NAMA'] ?>"><?= $customer['NAMA'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row bm-2">
                            <label for="ktr" class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-9">
                                <textarea name="keterangan" id="keterangan" class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 py-2 px-3">
                        <div class="form-group row mb-2">
                            <label for="bayar" class="col-sm-3 col-form-label">Bayar</label>
                            <div class="col-sm-9">
                                <input type="number" name="bayar" class="form-control form-control-sm text-right" id="bayar">
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="kembalian" class="col-sm-3 col-form-label">Kembalian</label>
                            <div class="col-sm-9">
                                <input type="number" name="kembalian" class="form-control form-control-sm text-right" id="kembalian" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 p-2">
                        <button type="submit" name="simpan" id="simpan" class="btn btn-primary btn-sm btn-block"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script>
        let barcode = document.getElementById('barcode');
        let tgl = document.getElementById('tglNota');
        let qty = document.getElementById('qty');
        let harga = document.getElementById('harga');
        let jmlHarga = document.getElementById('jmlHarga');
        let bayar = document.getElementById('bayar');
        let kembalian = document.getElementById('kembalian');
        let total = document.getElementById('total');

        barcode.addEventListener('change', function() {
            document.location.href = '?barcode=' + barcode.value + '&tgl=' + tgl.value;
        })


        qty.addEventListener('input', function() {
            jmlHarga.value = qty.value * harga.value;
        })

        bayar.addEventListener('input', function() {
            kembalian.value = bayar.value - total.value;
        })
    </script>

    <?php
    require "../template/footer.php";
    ?>