<?php

session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-customer.php";

$title = "Edit Customer - Jhonsi Bengkel Motor";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

// jalankan fungsi update data
if (isset($_POST['update'])) {
    if (update($_POST)) {
        // die(); //untuk memberhentikan program kalo ada error tapi cepat hilang
        echo "<script> 
        document.location.href = 'data-customer.php?msg=updated';
        </script>";
    }
}


$id = $_GET['id'];
$sqlEdit = "SELECT * FROM tbl_customer WHERE ID_CUSTOMER = $id ";
$customer = getData($sqlEdit)[0]; //untuk ambil data [0]= dan dimulai indeks ke 0

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Customer</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>customer/data-customer.php">Customer</a></li>
                        <li class="breadcrumb-item active">Edit Customer</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="" method="post">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-plus fa-sm"></i> Edit Customer</h3>
                        <button type="submit" name="update" class="btn btn-primary btn-sm float-right"><i class="fas fa-save"></i> Update</button>
                        <button type="reset" class="btn btn-danger btn-sm float-right mr-1"><i class="fas fa-times"></i> Reset</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="id" value="<?= $customer['ID_CUSTOMER'] ?>">
                            <div class="col-lg-8 mb-3">
                                <div class="form-group">
                                    <label for="name">Nama Supplier</label>
                                    <input type="text" name="nama" class="form-control" id="nama" placeholder="Masukkan Nama / Plat BK " autocomplete="off" value="<?= $customer['NAMA'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="telepon">Telepon</label>
                                    <input type="text" name="telepon" class="form-control" id="telepon" placeholder="no telepon supplier" pattern="[0-9]{5,}" title="minimal 5 angka" value="<?= $customer['TELEPON'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Deskripsi</label>
                                    <textarea name="keterangan" id="keterangan" rows="3" class="form-control" placeholder="Keterangan/Alamat/Catatan"> <?= $customer['DESKRIPSI'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>


    <?php
    require "../template/footer.php";

    ?>