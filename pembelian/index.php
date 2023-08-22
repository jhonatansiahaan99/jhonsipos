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
            <!-- Form -->
            <form method="post">
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
                            <div class="form-group row mb-2">
                                <div class="col form-group">
                                    <button type="button" class="btn btn-sm btn-primary" id="btnMenuBrg">Menu Barang</i></button>
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
                            $brgDetail = getData("SELECT * FROM tbl_beli_detail WHERE NO_BELI = '$noBeli' ");
                            foreach ($brgDetail as $detail) {
                                $total = $detail['QTY'] * $detail['HARGA_BELI'];

                                // Mengambil data harga barang, harga pasang, dan harga mekanik dari tbl_barang
                                $Kode_beli_barang = $detail['KODE_BRG'];
                                $sql_Brg = "SELECT HARGA_BARANG, HARGA_PASANG, HARGA_MEKANIK, HARGA_TAWAR FROM tbl_barang WHERE ID_BARANG = '$Kode_beli_barang'";
                                $barangData = getData($sql_Brg);
                                if (count($barangData) > 0) {
                                    $barang = $barangData[0];
                                } else {
                                    // Barang tidak ditemukan dalam tabel tbl_barang
                                    $barang = array(
                                        'HARGA_BARANG' => 0,
                                        'HARGA_PASANG' => 0,
                                        'HARGA_MEKANIK' => 0,
                                        'HARGA_TAWAR' => 0
                                    );
                                }
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $detail['KODE_BRG'] ?></td>
                                    <td><?= $detail['NAMA_BRG'] ?></td>
                                    <td class="text-right"><?= number_format($detail['HARGA_BELI'], 0, ',', '.') ?></td>
                                    <td class="text-right"><?= $detail['QTY'] ?></td>
                                    <td class="text-right"><?= number_format($total, 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalubah<?= $detail['KODE_BRG'] ?>" data-list="listharga<?= $detail['KODE_BRG'] ?>" title="Edit barang"><i class="fas fa-pen"></i></button>
                                        <a href="?idbrg=<?= $detail['KODE_BRG'] ?>&idbeli=<?= $detail['NO_BELI'] ?>&qty=<?= $detail['QTY'] ?>&tgl=<?= $detail['TGL_BELI'] ?>&msg=deleted" class="btn btn-sm btn-danger" title="Hapus Barang" onclick="return confirm('Anda yakin akan menghapus barang ini ?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <!-- Modal Edit Daftar Barang  -->
                                <div class="modal fade" id="modalubah<?= $detail['KODE_BRG'] ?>">
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
                                                $No_beli            = $detail['NO_BELI'];
                                                $Kode_beli_barang   = $detail['KODE_BRG'];
                                                $Tgl_beli           = $detail['TGL_BELI'];
                                                ?>

                                                <div class="form-group row">
                                                    <label for="tglBrg" class="col-sm-3 col-form-label">Tanggal</label>
                                                    <div class="col-sm-9">
                                                        <input type="hidden" class="form-control" name="updateNobeli" value="<?= $detail['NO_BELI'] ?>" readonly>
                                                        <input type="text" class="form-control" name="updateTglbeli" value="<?= $detail['TGL_BELI'] ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="kdBrg" class="col-sm-3 col-form-label">Kode Barang</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="kdBrg" name="updateKodebrg" value="<?= $detail['KODE_BRG'] ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="nmBrg" class="col-sm-3 col-form-label">Nama Barang</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="nmBrg" name="updateNamabrg" value="<?= $detail['NAMA_BRG'] ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="hrgBrg<?= $detail['KODE_BRG'] ?>" class="col-sm-3 col-form-label">Harga Beli</label>
                                                    <div class="col-sm-9">
                                                        <input name="updateHrgbrg" class="form-control FormatUang hargaBarang" data-id="<?= $detail['KODE_BRG'] ?>" list="listharga<?= $detail['KODE_BRG'] ?>" value="<?= $detail['HARGA_BELI'] ?>" id="hrgbrg<?= $detail['KODE_BRG'] ?>" autocomplete="off">
                                                        <datalist id="listharga<?= $detail['KODE_BRG'] ?>">
                                                            <option value="<?= $barang['HARGA_BARANG'] ?>">Harga Barang Rp <?= number_format($barang['HARGA_BARANG'], 0, ',', '.') ?></option>
                                                            <option value="<?= $barang['HARGA_PASANG'] ?>">Harga Pasang Rp <?= number_format($barang['HARGA_PASANG'], 0, ',', '.') ?></option>
                                                            <option value="<?= $barang['HARGA_MEKANIK'] ?>">Harga Mekanik Rp <?= number_format($barang['HARGA_MEKANIK'], 0, ',', '.') ?></option>
                                                            <option value="<?= $barang['HARGA_TAWAR'] ?>">Harga Tawar Rp <?= number_format($barang['HARGA_TAWAR'], 0, ',', '.') ?></option>
                                                        </datalist>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="hrgBrg<?= $detail['KODE_BRG'] ?>" class="col-sm-3 col-form-label">Harga Barang</label>
                                                    <div class="col-sm-9">
                                                        <input name="updateHrgbarang" class="form-control FormatUang" value="<?= $barang['HARGA_BARANG'] ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="hrgBrg<?= $detail['KODE_BRG'] ?>" class="col-sm-3 col-form-label">Harga Pasang</label>
                                                    <div class="col-sm-9">
                                                        <input name="updateHrgpasang" class="form-control FormatUang " value="<?= $barang['HARGA_PASANG'] ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="hrgBrg<?= $detail['KODE_BRG'] ?>" class="col-sm-3 col-form-label">Harga Mekanik</label>
                                                    <div class="col-sm-9">
                                                        <input name="updateHrgmekanik" class="form-control FormatUang" value="<?= $barang['HARGA_MEKANIK'] ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="hrgBrg<?= $detail['KODE_BRG'] ?>" class="col-sm-3 col-form-label">Harga Tawar</label>
                                                    <div class="col-sm-9">
                                                        <input name="updateHrgtawar" type="text" class="form-control FormatUang" value="<?= $barang['HARGA_TAWAR'] ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="qtyBrg" class="col-sm-3 col-form-label">Qty Barang</label>
                                                    <div class="col-sm-9">
                                                        <input type="number" class="form-control FormatUang qtyBarang" id="qtyBrg<?= $detail['KODE_BRG'] ?>" name="qty" value="<?= $detail['QTY'] ?>" data-id="<?= $detail['KODE_BRG'] ?>">
                                                        <input type="hidden" class="form-control" name="qtylama" value="<?= $detail['QTY'] ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="jmlBrg" class="col-sm-3 col-form-label">Jumlah Harga</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control FormatUang jmlHarga" id="jmlhrg<?= $detail['KODE_BRG'] ?>" name="jmlharga" value="<?= $detail['JML_HARGA'] ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.modal-body -->
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-sm btn-primary" name="UpdateDaftarBrg" title="pilih barang"><i class="fa fa-save"></i> Ubah</button>
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
                                                <td><button type="submit" class="btn btn-sm btn-primary" name="addMenu" title="pilih barang" value="<?= $brg['ID_BARANG'] ?>"><i class="fas fa-cart-plus fa-sm"></i></button></td>
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
            <!-- /.form -->
        </div>
    </section>

    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on("click", "#btnMenuBrg", function() {
                $('#mdlMenuBrg').modal('show');
            })
        })

        $(document).ready(function() {
            $('.FormatUang').mask('000.000.000.000', { // di ambil dari class
                reverse: true
            });

            $('.hargaBarang').on('input', function() { // di ambil dari class
                let id = $(this).data('id');
                calculateTotal($(this).data('id')); //kita menambahkan fungsi calculateTotal() yang akan dipanggil saat input harga barang atau qty barang berubah:
            });

            $('.qtyBarang').on('input', function() { // di ambil dari class
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
    </script>



    <script type="text/javascript">
        $(document).ready(function() {
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

            let harga_barang_lihat = document.getElementById('harga_barang');
            console.log(harga_barang_lihat);

        })
    </script>



    <?php
    require "../template/footer.php";
    ?>