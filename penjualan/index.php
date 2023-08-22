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

    $dataBrg = mysqli_query($koneksi, "SELECT b.*, bb.ADD_BARCODE FROM tbl_barang b INNER JOIN tbl_barcode bb ON b.ID_BARANG = bb.ID_BARANG WHERE b.BARCODE = '$kode' OR bb.ADD_BARCODE = '$kode'");
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

if (isset($_POST['tambahcustomer'])) {
    $tgl = $_POST['tglNotaCustomer'];
    if (insertCustomer($_POST)) {
        echo "<script>
                document.location  = '?tgl=$tgl';
        </script>";
    }
}

if (isset($_POST['addMenu'])) {
    $tgl = $_POST['tglNota'];
    if (insertMenuPilihan($_POST)) {
        echo "<script>
                document.location  = '?tgl=$tgl';
        </script>";
    }
}

if (isset($_POST['UpdateDaftarBrg'])) {
    $tgl = $_POST['tglNota'];
    if (updateDaftarBarang($_POST)) {
        echo "<script>
                document.location  = '?tgl=$tgl';
        </script>";
    }
}




//jika tombol cetak ditekan maka tampil struk
if (isset($_POST['cetak'])) {
    $nota = $_POST['nojual'];
    if (cetak($_POST)) {
        echo "<script>
            setTimeout(function() {
                swal({
                    title: 'Data Barang',
                    text: 'Berhasil Di Cetak Struk',
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
//jika tombol simpan ditekan, maka data di simpan dan tidak mencetak struk
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
                        window.location = 'index.php';
                });
            }, 100);
        </script>";
    }
}

//jika tombol service ditekan, maka data di service dan tidak mencetak struk
if (isset($_POST['service'])) {
    $nota = $_POST['nojual'];
    if (service($_POST)) {
        echo "<script>
            setTimeout(function() {
                swal({
                    title: 'Data Barang',
                    text: 'Di Arah Kan Ke Service',
                    type: 'success',
                    showConfirmButton: false,
                    timer: 3000
                }, window.onload = function(){                        
                        window.location = 'index.php';
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
                                    <input type="date" name="tglNota" class="form-control" id="tglNota" value="<?= @$_GET['tgl'] ? $_GET['tgl'] : date('Y-m-d')  ?>" required onchange="updateTglNotaCustomer()">
                                    <!-- @$_GET['tgl'] ? $_GET['tgl'] : date('Y-m-d') ==> ketika ada tanggal yang dikirim maka kita ambil tanggal nya, apabila tidak ada maka kita ambil tanggal hari ini. Dan tgl yang di kirim di ambil dari if (isset($_POST['addbrg'])) { -->
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="barcode" class="col-sm-2 col-form-label ">Barcode</label>
                                <div class="col-sm-10 input-group">
                                    <input type="text" name="barcode" id="barcode" class="form-control" placeholder="masukkan barcode barang" value="<?= @$_GET['barcode'] ? $_GET['barcode'] : ''  ?>" autofocus>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="icon-barcode">
                                            <i class="fas fa-barcode"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <div class="col form-group">
                                    <button type="button" class="btn btn-sm btn-primary" id="btnMenuBrg">Menu Barang</i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card card-outline card-danger pt-3 px-3 pb-2">
                            <h6 class="font-weight-bold text-right">Total Penjualan</h6>
                            <h1 class="font-weight-bold text-right" style="font-size:70pt;">
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
                            foreach ($brgDetail as $detail) {
                                // Mengambil data harga barang, harga pasang, dan harga mekanik dari tbl_barang
                                $Barcode_jual = $detail['BARCODE'];

                                $sql_Brg = "SELECT HARGA_BARANG, HARGA_PASANG, HARGA_MEKANIK FROM tbl_barang WHERE BARCODE = '$Barcode_jual'";
                                $barangData = getData($sql_Brg);
                                if (count($barangData) > 0) {
                                    $barang = $barangData[0];
                                } else {
                                    // Barang tidak ditemukan dalam tabel tbl_barang
                                    $barang = array(
                                        'HARGA_BARANG' => 0,
                                        'HARGA_PASANG' => 0,
                                        'HARGA_MEKANIK' => 0
                                    );
                                }
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $detail['BARCODE'] ?></td>
                                    <td><?= $detail['NAMA_BRG'] ?></td>
                                    <td class="text-right"><?= number_format($detail['HARGA_JUAL'], 0, ',', '.') ?></td>
                                    <td class="text-right"><?= $detail['QTY'] ?></td>
                                    <td class="text-right"><?= number_format($detail['JML_HARGA'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalubah<?= $detail['BARCODE'] ?>" data-list="listharga<?= $detail['BARCODE'] ?>" title="Edit barang"><i class="fas fa-pen"></i></button>
                                        <a href="?barcode=<?= $detail['BARCODE'] ?>&idjual=<?= $detail['NO_JUAL'] ?>&qty=<?= $detail['QTY'] ?>&tgl=<?= $detail['TGL_JUAL'] ?>&msg=deleted" class="btn btn-sm btn-danger" title="Hapus Barang" onclick="return confirm('Anda yakin akan menghapus barang ini ?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <!-- Modal Edit Daftar Barang  -->
                                <div class="modal fade" id="modalubah<?= $detail['BARCODE'] ?>">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Edit Daftar Barang</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <!-- modal-body  -->
                                            <div class="modal-body">
                                                <?php
                                                $No_jual                = $detail['NO_JUAL'];
                                                $Barcode_jual_barang    = $detail['BARCODE'];
                                                $Tgl_jual               = $detail['TGL_JUAL'];
                                                $sql_Brg                = "SELECT tbl_barang.HARGA_BARANG, tbl_barang.HARGA_PASANG, tbl_barang.HARGA_MEKANIK FROM tbl_barang INNER JOIN tbl_jual_detail ON tbl_barang.ID_BARANG = tbl_jual_detail.BARCODE WHERE tbl_jual_detail.BARCODE = '$Barcode_jual_barang' AND tbl_jual_detail.JUAL = '$No_jual' AND tbl_jual_detail.TGL_JUAL = '$Tgl_jual' ";
                                                ?>
                                                <div class="form-group row">
                                                    <label for="tglBrg" class="col-sm-3 col-form-label">Tanggal</label>
                                                    <div class="col-sm-9">
                                                        <input type="hidden" class="form-control" name="updateNojual" value="<?= $detail['NO_JUAL'] ?>" readonly>
                                                        <input type="text" class="form-control" name="updateTgljual" value="<?= $detail['TGL_JUAL'] ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="kdBrg" class="col-sm-3 col-form-label">Barcode</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="kdBrg" name="updateBarcodebrg" value="<?= $detail['BARCODE'] ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="nmBrg" class="col-sm-3 col-form-label">Nama Barang</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="nmBrg" name="updateNamabrg" value="<?= $detail['NAMA_BRG'] ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="hrgBrg<?= $detail['BARCODE'] ?>" class="col-sm-3 col-form-label">Harga Barang</label>
                                                    <div class="col-sm-9">
                                                        <input name="updateHrgbrg" class="form-control FormatUang hargaBarang" data-id="<?= $detail['BARCODE'] ?>" list="listharga<?= $detail['BARCODE'] ?>" value="<?= $detail['HARGA_JUAL'] ?>" id="hrgbrg<?= $detail['BARCODE'] ?>" autocomplete="off">
                                                        <datalist id="listharga<?= $detail['BARCODE'] ?>">
                                                            <option value="<?= $barang['HARGA_BARANG'] ?>">Harga Barang Rp <?= number_format($barang['HARGA_BARANG'], 0, ',', '.') ?></option>
                                                            <option value="<?= $barang['HARGA_PASANG'] ?>">Harga Pasang Rp <?= number_format($barang['HARGA_PASANG'], 0, ',', '.') ?></option>
                                                            <option value="<?= $barang['HARGA_MEKANIK'] ?>">Harga Mekanik Rp <?= number_format($barang['HARGA_MEKANIK'], 0, ',', '.') ?></option>
                                                        </datalist>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="qtyBrg" class="col-sm-3 col-form-label">Qty Barang</label>
                                                    <div class="col-sm-9">
                                                        <input type="number" class="form-control FormatUang qtyBarang" id="qtyBrg<?= $detail['BARCODE'] ?>" name="qty" value="<?= $detail['QTY'] ?>" data-id="<?= $detail['BARCODE'] ?>">
                                                        <input type="hidden" class="form-control" name="qtylama" value="<?= $detail['QTY'] ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="jmlBrg" class="col-sm-3 col-form-label">Jumlah Harga</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control FormatUang jmlHarga" id="jmlhrg<?= $detail['BARCODE'] ?>" name="jmlharga" value="<?= $detail['JML_HARGA'] ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.modal-body -->
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-sm btn-primary" name="UpdateDaftarBrg" title="Ubah Data"><i class="fa fa-save"></i> Ubah</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.Modal Edit Daftar Barang -->

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

                                <button type="button" class="btn btn-sm mt-2 btn-primary btn-block" id="btnTambahCustomer">Tambah Customer</button>
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
                                <input type="text" name="bayar" class="form-control form-control-sm text-right FormatUang bayarpenjualan" autocomplete="off" id="bayar">
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="kembalian" class="col-sm-3 col-form-label">Kembalian</label>
                            <div class="col-sm-9">
                                <input type="text" name="kembalian" class="form-control form-control-sm text-right FormatUang kembalianpenjualan" id="kembalian" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 p-2">
                        <button type="submit" name="cetak" id="cetak" class="btn btn-primary btn-sm btn-block"><i class="fas fa-barcode"></i> Cetak Barcode</button>
                        <button type="submit" name="simpan" id="simpan" class="btn btn-success btn-sm btn-block"><i class="fa fa-save"></i> Simpan</button>
                        <button type="submit" name="service" id="service" class="btn btn-info btn-sm btn-block"><i class="fas fa-cogs"></i> Service</button>
                    </div>
                </div>

                <!-- Modal Menu Barang -->
                <div class="modal fade" id="mdlMenuBrg">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Menu Barang</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body table-responsive">
                                <table class="table table-hover text-nowrap" id="tblData">
                                    <thead>
                                        <tr>
                                            <th>Gambar</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Type Motor</th>
                                            <th>Stock</th>
                                            <th>List Harga</th>
                                            <th>OPSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $barang = getData("SELECT * FROM tbl_barang ORDER BY ID_BARANG DESC");
                                        foreach ($barang as $brg) : ?>
                                            <tr>
                                                <td><img src="../asset/imageuser/<?= $brg['GAMBAR'] ?>" alt="gambar barang" class="rounded-circle" width="60px"></td>
                                                <td><?= $brg['ID_BARANG'] ?></td>

                                                <?php
                                                $namaBarang = $brg['NAMA_BARANG']; // Ambil nama barang dari data
                                                // Memisahkan nama barang menjadi array berdasarkan spasi
                                                $nama = explode(' ', $namaBarang);
                                                // Memeriksa apakah nama barang memiliki lebih dari 4 kata
                                                if (count($nama) > 4) {
                                                    echo '<td>';
                                                    $counter = 0;
                                                    foreach ($nama as $namaBrg) {
                                                        echo $namaBrg . ' ';
                                                        $counter++;
                                                        if ($counter == 4) {
                                                            echo '<br>';
                                                            $counter = 0;
                                                        }
                                                    }
                                                    echo '</td>';
                                                } else {
                                                    // Menampilkan nama barang dalam satu baris
                                                    echo '<td>' . $namaBarang . '</td>';
                                                }
                                                ?>

                                                <?php
                                                $typeMotor = $brg['TYPE_MOTOR']; // Ambil tipe motor dari data
                                                // Memisahkan tipe motor menjadi array berdasarkan koma
                                                $types = explode(',', $typeMotor);
                                                // Memeriksa apakah tipe motor memiliki lebih dari 3 kata
                                                if (count($types) > 3) {
                                                    echo '<td>';
                                                    $counter = 0;
                                                    foreach ($types as $type) {
                                                        echo $type . ',';
                                                        $counter++;
                                                        if ($counter == 3) {
                                                            echo '<br>';
                                                            $counter = 0;
                                                        }
                                                    }
                                                    echo '</td>';
                                                } else {
                                                    // Menampilkan tipe motor dalam satu baris
                                                    echo '<td>' . $typeMotor . '</td>';
                                                }
                                                ?>
                                                <td><?= $brg['STOCK'] ?></td>
                                                <td><b>Harga Barang : Rp <?= number_format($brg['HARGA_BARANG'], 0, ',', '.')  ?> | <br> Harga Pasang : Rp <?= number_format($brg['HARGA_PASANG'], 0, ',', '.')  ?> | <br> Harga Mekanik : Rp <?= number_format($brg['HARGA_MEKANIK'], 0, ',', '.')  ?> </b></td>
                                                <td><button type="submit" class="btn btn-sm btn-primary" name="addMenu" title="pilih barang" value="<?= $brg['BARCODE'] ?>"><i class="fas fa-cart-plus fa-sm"></i></button></td>
                                            </tr>
                                        <?php
                                        endforeach;
                                        ?>
                                    </tbody>
                                </table>
                                <!-- </form> -->
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.Modal Menu Barang -->

            </form>
        </div>
    </section>

    <form method="post">
        <!-- Modal Tambah Customer -->
        <div class="modal fade" id="mdlTambahCustomer">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Customer</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="date" name="tglNotaCustomer" class="form-control" id="tglNotaCustomer" value="<?= @$_GET['tgl'] ? $_GET['tgl'] : date('Y-m-d')  ?>" required>
                            <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama / Plat BK " autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="telepon">Telpon</label>
                            <input type="text" name="telepon" id="telepon" class="form-control" pattern="[0-9]{5,}" title="minimal 5 angka" placeholder="No WA/Telp" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Deskripsi</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control" placeholder="Keterangan/Alamat/Catatan"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-primary" name="tambahcustomer" title="Tambah Data"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.Modal Tambah Customer -->

    </form>


    <script type="text/javascript">
        let barcode = document.getElementById('barcode');
        let tgl = document.getElementById('tglNota');
        let qty = document.getElementById('qty');
        let harga = document.getElementById('harga');
        let jmlHarga = document.getElementById('jmlHarga');
        let bayar = document.getElementById('bayar');
        let kembalian = document.getElementById('kembalian');
        let total = document.getElementById('total');

        // Simpan nilai tglNota saat halaman dimuat
        let previousTglNotaValue = document.getElementById('tglNota').value;

        function updateTglNotaCustomer() {
            // Dapatkan elemen input tglNota dan tglNotaCustomer
            let tglNotaInput = document.getElementById('tglNota');
            let tglNotaCustomerInput = document.getElementById('tglNotaCustomer');

            // Periksa apakah nilai tglNota berubah
            if (tglNotaInput.value !== previousTglNotaValue) {
                // Jika berubah, masukkan nilai baru ke tglNotaCustomerInput
                tglNotaCustomerInput.value = tglNotaInput.value;
                // Update nilai previousTglNotaValue agar menyimpan nilai terbaru
                previousTglNotaValue = tglNotaInput.value;
            } else {
                // Jika tidak berubah, tglNotaCustomer tetap menggunakan nilai tglNota sebelumnya
                tglNotaCustomerInput.value = previousTglNotaValue;
            }
        }

        barcode.addEventListener('change', function() {
            document.location.href = '?barcode=' + barcode.value + '&tgl=' + tgl.value;
        })


        qty.addEventListener('input', function() {
            jmlHarga.value = qty.value * harga.value;
        })

        bayar.addEventListener('input', function() {
            kembalian.value = bayar.value - total.value;
        })

        $(document).ready(function() {
            $(document).on("click", "#btnMenuBrg", function() {
                $('#mdlMenuBrg').modal('show');
            })
        })

        $(document).ready(function() {
            $(document).on("click", "#btnTambahCustomer", function() {
                $('#mdlTambahCustomer').modal('show');
            })
        })


        $(document).ready(function() {
            $('.FormatUang').mask('000.000.000.000', {
                reverse: true
            });

            $('.hargaBarang').on('input', function() {
                let id = $(this).data('id');
                calculateTotal($(this).data('id')); //kita menambahkan fungsi calculateTotal() yang akan dipanggil saat input harga barang atau qty barang berubah:
            });

            $('.qtyBarang').on('input', function() {
                let id = $(this).data('id');
                calculateTotal($(this).data('id')); //kita menambahkan fungsi calculateTotal() yang akan dipanggil saat input harga barang atau qty barang berubah:
            });

            //Di dalam fungsi calculateTotal(), kita mengambil nilai dari input harga barang dan qty barang, lalu menghitung jumlah harga:
            function calculateTotal(id) {
                let hargaEdit = $('#hrgbrg' + id);
                let qtyEdit = $('#qtyBrg' + id);
                let jmlHargaEdit = $('#jmlhrg' + id);

                let calculatedValue = qtyEdit.val() * hargaEdit.val().replace(/\./g, "").replace(',', '.'); //Ketika input berubah, kita menghitung nilai yang dihasilkan dengan mengalikan qtyEdit.value dengan hargaEdit.value setelah menghapus titik (.) dan mengganti koma (,) dengan titik (.) menggunakan metode replace().
                jmlHargaEdit.val(formatCurrency(calculatedValue)); //Nilai yang dihasilkan kemudian diformat menggunakan fungsi formatCurrency() dan ditetapkan sebagai nilai jmlHargaEdit.value.
            }

            //Di bagian terakhir JavaScript, terdapat penggunaan metode each() untuk melakukan iterasi pada semua elemen dengan kelas "jmlHarga", "hargaBarang", dan "qtyBarang", kemudian melakukan format currency saat halaman dimuat:
            $('.jmlHarga').each(function() {
                $(this).val(formatCurrency($(this).val().replace(/\./g, "").replace(',', '.')));
            });

            $('.hargaBarang').each(function() {
                $(this).val(formatCurrency($(this).val().replace(/\./g, "").replace(',', '.')));
            });

            //Terdapat juga fungsi formatCurrency() yang akan mengubah angka menjadi format mata uang:
            function formatCurrency(value) {
                let formattedValue = parseFloat(value).toLocaleString('id-ID', { //Kita mengubah nilai value menjadi tipe float menggunakan parseFloat() untuk memastikan bahwa nilai tersebut dapat diformat sebagai angka desimal. Kemudian, kita menggunakan metode toLocaleString() untuk menerapkan format dengan menggunakan opsi 'id-ID', yang mengacu pada bahasa Indonesia.
                    style: 'decimal', //Opsi style diatur ke 'decimal' untuk memastikan format angka desimal.
                    minimumFractionDigits: 0 //Opsi minimumFractionDigits diatur ke 0 agar tidak ada angka desimal yang ditampilkan.
                });

                formattedValue = formattedValue.replace(/\.00$/, ''); //untuk mengganti pola yang cocok dalam formattedValue. Pola yang digunakan adalah /\.00$/, yang berarti mencocokkan string yang diakhiri dengan .00. Kita menggantinya dengan string kosong ('') untuk menghapus pecahan desimal .00 jika ada. Ini memastikan bahwa nilai yang tidak memiliki desimal .00 tidak akan ditampilkan.
                return formattedValue; //mengembalikan formattedValue yang telah diformat sebagai hasil dari fungsi formatCurrency(). Nilai ini akan digunakan di bagian lain dari kode untuk menetapkan nilai yang diformat pada elemen yang relevan.
            }
        });


        $(document).ready(function() {

            // Fungsi untuk menghitung kembalian
            function calculateKembalian() {
                let totalPenjualan = parseFloat($('#total').val().replace(/\./g, '').replace(',', '.'));
                let bayar = parseFloat($('#bayar').val().replace(/\./g, '').replace(',', '.'));
                let kembalian = bayar - totalPenjualan;
                $('#kembalian').val(formatCurrency(kembalian));
            }

            // Jalankan fungsi calculateKembalian() saat input bayar berubah
            $('#bayar').on('input', calculateKembalian);

            // Fungsi untuk mengubah angka menjadi format mata uang
            function formatCurrency(value) {
                let formattedValue = parseFloat(value).toLocaleString('id-ID', {
                    style: 'decimal',
                    minimumFractionDigits: 0
                });

                formattedValue = formattedValue.replace(/\.00$/, '');
                return formattedValue;
            }

            // Jalankan fungsi calculateKembalian() saat halaman dimuat untuk menghitung kembali kembalian jika bayar sudah terisi sebelumnya
            $(document).ready(function() {
                calculateKembalian();
            });
        });
    </script>

    <?php
    require "../template/footer.php";
    ?>