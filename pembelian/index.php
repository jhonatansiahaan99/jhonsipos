<?php

session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-beli.php";

$title = "Transaksi - Jhonsi Bengkel Motor";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";


if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

if ($msg == 'deleted') {
    $idbrg = $_GET['idbrg'];
    $idbeli = $_GET['idbeli'];
    $qty = $_GET['qty'];
    $tgl = $_GET['tgl'];
    delete($idbrg, $idbeli, $qty);
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

$kode = @$_GET['pilihbrg'] ? @$_GET['pilihbrg'] : ''; //berfungsi mengambil kode barang yang dipilih user. || ketika user sudah memilih barang maka kita ngambil kode barang nya, dan apabila user tidak memilih maka kosong

date_default_timezone_set('Asia/Jakarta');
if ($kode) { //jika kode ada
    $selectBrg = getData("SELECT * FROM tbl_barang WHERE ID_BARANG = '$kode' ")[0]; //maka ambil data barang dari index ke 0
}

if (isset($_POST['addbrg'])) {
    $tgl = $_POST['tglNota'];
    if (insert($_POST)) {
        echo "<script>
                document.location  = '?tgl=$tgl';
        </script>";
    }
}


if (isset($_POST['simpan'])) {
    if (simpan($_POST)) {
        echo "<script>
            setTimeout(function() {
                swal({
                    title: 'Data Stok Barang',
                    text: 'Berhasil Di Tambahkan',
                    type: 'success',
                    showConfirmButton: false,
                    timer: 1000
                }, function() {
                    window.location.href = 'index.php?msg=sukses';
                });
            }, 100);
        </script>";
    }
}
// document.location  = 'index.php';
$noBeli = generateNo();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pembelian Barang</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <!-- operator ternary php (if satu baris) -->
                        <!-- Cara baca nya jika $msg tidak kosong/ada data get yang dikirim maka Edit Barang, apabila kosong maka Add Barang  -->
                        <li class="breadcrumb-item active">Tambah Pembelian</li>
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
                                    <input type="text" name="nobeli" class="form-control" id="noNota" value="<?= $noBeli ?>">
                                </div>
                                <label for="tglNota" class="col-sm-2 col-form-label">Tgl Nota</label>
                                <div class="col-sm-4">
                                    <input type="date" name="tglNota" class="form-control" id="tglNota" value="<?= @$_GET['tgl'] ? $_GET['tgl'] : date('Y-m-d')  ?>" required>
                                    <!-- @$_GET['tgl'] ? $_GET['tgl'] : date('Y-m-d') ==> ketika ada tanggal yang dikirim maka kita ambil tanggal nya, apabila tidak ada maka kita ambil tanggal hari ini. Dan tgl yang di kirim di ambil dari if (isset($_POST['addbrg'])) { -->
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="kodeBrg" class="col-sm-2 col-form-label ">SKU</label>
                                <div class="col-sm-10 form-group">
                                    <select name="kodeBrg" id="kodeBrg" class="form-control select2">
                                        <option value="">-- Pilih Kode Barang --</option>
                                        <?php

                                        $barang = getData("SELECT * FROM tbl_barang");
                                        foreach ($barang as $brg) { ?>
                                            <option value="?pilihbrg=<?= $brg['ID_BARANG'] ?> <?= @$_GET['pilihbrg'] == $brg['ID_BARANG'] ? 'selected' : null ?>"><?= $brg['ID_BARANG'] . " | " . $brg['NAMA_BARANG'] ?></option>
                                            <!-- value="?pilihbrg =$brg['ID_BARANG'] ==> ini berfungsi ketika user memilih barangnya maka kita ambil kode barangnya -->
                                            <!--  @$_GET['pilihbrg'] == $brg['ID_BARANG'] ? 'selected' : null" ==> ini sebagai operator tenary atau if satu baris yang berfungsi jika user memilih barang dan barang yang di pilih itu  sama dengan salah satu barang yang ada di data barang maka kita tampilkan kalo tidak ada maka kosongkan-->
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card card-outline card-danger pt-3 px-3 pb-2">
                            <h6 class="font-weight-bold text-right">Total Pembelian</h6>
                            <h1 class="font-weight-bold text-right" style="font-size:40pt;">
                                <input type="hidden" name="total" value="<?= totalBeli($noBeli) ?>">
                                <?= number_format(totalBeli($noBeli), 0, ',', '.') ?>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="card pt-1 pb-2 px-3">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="hidden" value="<?= @$_GET['pilihbrg'] ? $selectBrg['ID_BARANG'] : '' ?>" name="kodeBrg">
                                <!-- @$_GET['pilihbrg'] ? $selectBrg['ID_BARANG'] : '' ==> berfungsi jika ada barang yang dipilih user maka diambil ID_BARANG nya kalo tidak ada maka kosong -->
                                <label for="namaBrg">Nama Barang</label>
                                <input type="text" name="namaBrg" class="form-control form-control-sm" id="namaBrg" value="<?= @$_GET['pilihbrg'] ? $selectBrg['NAMA_BARANG'] : '' ?>" readonly>
                                <!-- @$_GET['pilihbrg'] ? $selectBrg['NAMA_BARANG'] : '' ==> berfungsi jika ada barang yang dipilih user maka diambil NAMA_BARANG nya kalo tidak ada maka kosong -->
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-group">
                                <label for="stok">Stok Barang</label>
                                <input type="number" name="stok" class="form-control form-control-sm" id="stock" value="<?= @$_GET['pilihbrg'] ? $selectBrg['STOCK'] : '' ?>" readonly>
                                <!-- @$_GET['pilihbrg'] ? $selectBrg['STOCK'] : '' ==> berfungsi jika ada barang yang dipilih user maka diambil STOCK nya kalo tidak ada maka kosong -->
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-group">
                                <label for="satuan">Satuan</label>
                                <input type="text" name="satuan" class="form-control form-control-sm" id="satuan" value="<?= @$_GET['pilihbrg'] ? $selectBrg['SATUAN'] : '' ?>" readonly>
                                <!-- @$_GET['pilihbrg'] ? $selectBrg['SATUAN'] : '' ==> berfungsi jika ada barang yang dipilih user maka diambil SATUAN nya kalo tidak ada maka kosong -->
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="number" name="harga" class="form-control form-control-sm" id="harga" value="<?= @$_GET['pilihbrg'] ? $selectBrg['HARGA_BARANG'] : '' ?>" readonly>
                                <!-- @$_GET['pilihbrg'] ? $selectBrg['HARGA_BARANG'] : '' ==> berfungsi jika ada barang yang dipilih user maka diambil HARGA_BARANG nya kalo tidak ada maka kosong -->
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <input type="number" name="qty" class="form-control form-control-sm" id="qty" value="<?= @$_GET['pilihbrg'] ? 1 : '' ?>">
                                <!-- @$_GET['pilihbrg'] ? 1 : '' ==> berfungsi jika ada barang yang dipilih user maka ditambah 1 kalo tidak ada maka kosong -->
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="jmlHarga">Jumlah Harga</label>
                                <input type="number" name="jmlHarga" class="form-control form-control-sm" id="jmlHarga" value="<?= @$_GET['pilihbrg'] ? $selectBrg['HARGA_BARANG'] : '' ?>" readonly>
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
                                <th>Kode Barang</th>
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
                            $brgDetail  = getData("SELECT * FROM tbl_beli_detail WHERE NO_BELI = '$noBeli' ");
                            foreach ($brgDetail as $detail) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $detail['KODE_BRG'] ?></td>
                                    <td><?= $detail['NAMA_BRG'] ?></td>
                                    <td class="text-right"><?= number_format($detail['HARGA_BELI'], 0, ',', '.') ?></td>
                                    <td class="text-right"><?= $detail['QTY'] ?></td>
                                    <td class="text-right"><?= number_format($detail['JML_HARGA'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <a href="?idbrg=<?= $detail['KODE_BRG'] ?>&idbeli=<?= $detail['NO_BELI'] ?>&qty=<?= $detail['QTY'] ?>&tgl=<?= $detail['TGL_BELI'] ?>&msg=deleted" class="btn btn-sm btn-danger" title="Hapus Barang" onclick="return confirm('Anda yakin akan menghapus barang ini ?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-6 p-2">
                        <div class="form-group row mb-2">
                            <label for="supplier" class="col-sm-3 col-form-label col-form-label-sm">Supplier</label>
                            <div class="col-sm-9">
                                <select name="supplier" id="supplier" class="form-control form-control-sm">
                                    <option value="">-- Pilih Supplier --</option>
                                    <?php
                                    $suppliers = getData("SELECT * FROM tbl_supplier");
                                    foreach ($suppliers as $supplier) { ?>
                                        <option value="<?= $supplier['NAMA'] ?>"><?= $supplier['NAMA'] ?></option>
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
                    <div class="col-lg-6 p-2">
                        <button type="submit" name="simpan" id="simpan" class="btn btn-primary btn-sm btn-block"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script type="text/javascript">
        //script yang dikomen ini bisa digunakan ya...
        // let pilihbrg = document.getElementById('kodeBrg'); //mengambil dari data combobox
        //let tgl = document.getElementById('tglNota'); //mengambil dari id tanggal
        // pilihbrg.addEventListener('change', function() {
        //     document.location.href = this.options[this.selectedIndex].value + '&tgl=' + tgl.value; //mengambil barang atau nilai yang dipilih oleh user
        // })

        let tgl = document.getElementById('tglNota'); //mengambil dari id tanggal
        $('#kodeBrg').on('change', function() {
            let selectedValue = $(this).val();
            window.location.href = selectedValue + '&tgl=' + tgl.value; //'&tgl=' itu dibuat sendiri hanya tambahan saja, biar data tanggal tidak berubah ketika pilih select, maka nya data nya di panggil
        });
        let qty = document.getElementById('qty');
        let jmlHarga = document.getElementById('jmlHarga');
        let harga = document.getElementById('harga');
        qty.addEventListener('input', function() {
            jmlHarga.value = qty.value * harga.value;
        })
    </script>

    <?php
    require "../template/footer.php";
    ?>