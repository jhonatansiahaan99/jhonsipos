<?php

session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: auth/login.php");
    exit();
}



require "config/config.php";
require "config/functions.php";
$title = "Dashoard - Jhonsi Pos";
require "template/header.php";
require "template/navbar.php";
require "template/sidebar.php";

$users = getData("SELECT * FROM tbl_user");
$userNum = count($users);

$suppliers = getData("SELECT * FROM tbl_supplier");
$supplierNum = count($suppliers);

$customers = getData("SELECT * FROM tbl_customer");
$customerNum = count($customers);

$barang = getData("SELECT * FROM tbl_barang");
$barangNum = count($barang);


// Mendefinisikan tanggal mulai dan tanggal akhir default
$startDateDefault = date("Y-m-d", strtotime("-7 days"));
$endDateDefault = date("Y-m-d");

// Mengambil data yang sudah di-submit (jika ada)
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : $startDateDefault;
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : $endDateDefault;

// Mendapatkan total omzet berdasarkan kriteria yang dipilih
if (isset($_POST['today'])) {
    $query = "SELECT SUM(TOTAL) AS omzet FROM tbl_jual_head WHERE DATE(TGL_JUAL) = CURDATE()";
} elseif (isset($_POST['this_month'])) {
    $query = "SELECT SUM(TOTAL) AS omzet FROM tbl_jual_head WHERE MONTH(TGL_JUAL) = MONTH(CURDATE())";
} else {
    $query = "SELECT SUM(TOTAL) AS omzet FROM tbl_jual_head WHERE DATE(TGL_JUAL) BETWEEN '$startDate' AND '$endDate'";
}

$totalOmzet = getTotalOmzet($query);

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?= $userNum ?></h3>

                                <p>Users</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="<?= $main_url ?>user" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?= $supplierNum ?></h3>
                                <p>Suplier</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-bus"></i>
                            </div>
                            <a href="<?= $main_url ?>supplier" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?= $customerNum ?></h3>

                                <p>Customer Service</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-stalker"></i>
                            </div>
                            <a href="<?= $main_url ?>customer" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?= $barangNum ?></h3>

                                <p>Item Barang</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-cart"></i>
                            </div>
                            <a href="<?= $main_url ?>barang" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card card-outline card-danger">
                            <div class="card-header text-info">
                                <h5 class="card-title">Info Stock Barang</h5>
                                <h5><a href="stock" class="float-right" title="laporan stock"><i class="fas fa-arrow-right"></i></a></h5>
                            </div>
                            <table class="table">
                                <tbody>
                                    <?php
                                    $stockMin = getData("SELECT * FROM tbl_barang WHERE STOCK < STOCK_MINIMAL");
                                    foreach ($stockMin as $min) {
                                    ?>
                                        <tr>
                                            <?php
                                            $namaBarang = $min['NAMA_BARANG']; // Ambil nama barang dari data
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
                                            <td class="text-primary">Sisa <?= $min['STOCK'] ?></td>
                                            <td class="text-danger">Stock Kurang</td>

                                        </tr>

                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="col-lg-6">
                        <div class="card card-outline card-success">
                            <div class="card-header text-info">
                                <h5>Omzet Penjualan</h5>
                                <div class="card-body text-primary">
                                    <h2><span class="h4">Rp</span><?= omzet() ?></h2>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <?php
                    if (userLogin()['USER_LEVEL'] != 3) { //KALO BUKAN 3(KASIR) MAKA TIDAK BISA BUKA HALAMAN MASTER
                    ?>
                        <div class="col-lg-6">
                            <div class="card card-outline card-success">
                                <div class="card-header text-info">
                                    <h5>Omzet Penjualan</h5>
                                    <div class="text-primary">
                                        <form method="post">
                                            <button type="submit" name="today">Today</button>
                                            <button type="submit" name="this_month">Month</button>
                                            Tgl Awal <input type="date" name="start_date" value="<?php echo $startDate; ?>">
                                            Tgl Akhir <input type="date" name="end_date" value="<?php echo $endDate; ?>">
                                            <button type="submit" name="custom_range">Tampil</button>

                                        </form>
                                        <h2>Total Omzet: Rp <?= number_format($totalOmzet, 0, ',', '.') ?></h2>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                </div>
                <?php
                // Bagian kode PHP untuk mengambil data penjualan dan menghitung total penjualan per bulan
                $jual = getData("SELECT * FROM tbl_jual_head");

                $totalPerBulan = array_fill(1, 12, 0); // Membuat array untuk menyimpan total penjualan per bulan

                foreach ($jual as $penjualan) {
                    $tglJual = $penjualan['TGL_JUAL'];
                    $bulan = date('n', strtotime($tglJual)); // Mendapatkan angka bulan (1-12) dari tanggal penjualan
                    $totalPerBulan[$bulan] += $penjualan['TOTAL']; // Menambahkan total penjualan ke array berdasarkan bulan
                }
                ?>
                <?php
                if (userLogin()['USER_LEVEL'] != 3) { //KALO BUKAN 3(KASIR) MAKA TIDAK BISA BUKA HALAMAN MASTER
                ?>
                    <div class="row">
                        <div class="col-12">
                            <!-- Bar chart -->
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="far fa-chart-bar"></i>
                                        Penghasilan
                                    </h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="bar-chart" style="height: 300px;"></div>
                                </div>
                                <!-- /.card-body-->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
    </div>
    <!-- /.content -->



    <?php
    require "template/footer.php";

    ?>