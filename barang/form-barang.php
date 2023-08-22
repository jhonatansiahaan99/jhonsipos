<?php

session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-barang.php";

$title = "Form Barang - Jhonsi Bengkel Motor";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";


if (isset($_GET['msg'])) { //jika ada pesan dikirim dari tombol url edit
    $msg        = $_GET['msg'];
    $id         = $_GET['id'];
    $sqlEdit    = "SELECT * FROM tbl_barang WHERE ID_BARANG = '$id' ";
    $barang     = getData($sqlEdit)[0];
} else { //jika tidak ada pesan dikirim maka kosong
    $msg = "";
}

$alert = '';

if (isset($_POST['simpan'])) {
    if ($msg != '') {
        if (update($_POST)) {
            // die();
            echo "
                <script>document.location.href = 'index.php?msg=updated'; </script>
            ";
        } else {
            // die();
            echo "
                <script>document.location.href = 'index.php'; </script>
            ";
        }
    } else {
        if (insert($_POST)) {
            $alert = '<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            Barang Berhasil Di Tambahkan..
        </div>';
        }
    }
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
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>barang">Barang</a></li>
                        <!-- operator ternary php (if satu baris) -->
                        <!-- Cara baca nya jika $msg tidak kosong/ada data get yang dikirim maka Edit Barang, apabila kosong maka Add Barang  -->
                        <li class="breadcrumb-item active"><?= $msg != '' ? 'Edit Barang' : 'Add Barang' ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="" method="post" enctype="multipart/form-data">
                    <?php
                    if ($alert != '') {
                        echo $alert;
                    }
                    ?>
                    <div class="card-header">
                        <!-- operator ternary php (if satu baris) -->
                        <!-- Cara baca nya jika $msg tidak kosong/ada data get yang dikirim maka Edit Barang, apabila kosong maka Input Barang  -->
                        <h3 class="card-title"><i class="fas fa-pen fa-sm"></i> <?= $msg != '' ? 'Edit Barang' : 'Input Barang' ?></h3>
                        <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right"><i class="fas fa-save"></i> Simpan</button>
                        <button type="reset" class="btn btn-danger btn-sm float-right mr-1"><i class="fas fa-times"></i> Reset</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8 mb-3 pr-3">
                                <div class="form-group">
                                    <label for="kode">Kode Barang</label>
                                    <input type="text" name="kode" class="form-control" id="kode" value="<?= $msg != '' ? $barang['ID_BARANG'] : generateId() ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="barcode">Barcode *</label>
                                    <input type="text" name="barcode" class="form-control" id="barcode" value="<?= $msg != '' ? $barang['BARCODE'] : generateBarcode() ?>" placeholder="barcode" autocomplete="off" autofocus required>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Barang*</label>
                                    <input type="text" name="nama_barang" class="form-control" id="name" placeholder="nama barang" value="<?= $msg != '' ? $barang['NAMA_BARANG'] : null ?>" autocomplete="off" autofocus required>
                                </div>

                                <div class="form-group">
                                    <label for="category">Category *</label>
                                    <select name="kategori" id="category" class="form-control select2" required>
                                        <option value="">-- Pilih Category --</option>
                                        <?php
                                        if ($msg != "") {
                                            $id_category = $barang['CATEGORY'];
                                            $sql_category = getData("SELECT * FROM tbl_kategori");
                                            foreach ($sql_category as $data_kategori) :
                                                $id_data_category = $data_kategori['CATEGORY'];
                                                //Data akan terseleksi (selected) jika variabel $kode_kelas sama dengan $kode_data_kelas.
                                                if ($id_category == $id_data_category) {
                                                    $cek = "selected";
                                                } else {
                                                    $cek = "";
                                                }
                                                echo "<option value='$id_data_category' $cek>" . $data_kategori['CATEGORY'] . "</option>";
                                            endforeach;
                                        } else { ?>
                                            <?php
                                            $sql_category = getData("SELECT * FROM tbl_kategori");
                                            foreach ($sql_category as $data_kategori) :
                                            ?>
                                                <option value="<?= $data_kategori['CATEGORY'] ?>"><?= $data_kategori['CATEGORY'] ?></option>
                                        <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="brand">Brand *</label>
                                    <select name="brand" id="brand" class="form-control select2" style="width: 100%;" required>
                                        <option value="">-- Pilih Brand/Merek --</option>
                                        <?php
                                        if ($msg != "") {
                                            $id_brand = $barang['BRAND'];
                                            $sql_brand = getData("SELECT * FROM tbl_brand");
                                            foreach ($sql_brand as $data_brand) :
                                                $id_data_brand = $data_brand['BRAND'];
                                                //Data akan terseleksi (selected) jika variabel $kode_kelas sama dengan $kode_data_kelas.
                                                if ($id_brand == $id_data_brand) {
                                                    $cek = "selected";
                                                } else {
                                                    $cek = "";
                                                }
                                                echo "<option value='$id_data_brand' $cek>" . $data_brand['BRAND'] . "</option>";
                                            endforeach;
                                        } else { ?>
                                            <?php
                                            $sql_brand = getData("SELECT * FROM tbl_brand");
                                            foreach ($sql_brand as $data_brand) :
                                            ?>
                                                <option value="<?= $data_brand['BRAND'] ?>"><?= $data_brand['BRAND'] ?></option>
                                        <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Type Motor *</label>
                                    <div class="select2-blue">
                                        <select class="select2" name="type_motor[]" multiple="multiple" data-placeholder="Pilih Type Motor  " style="width: 100%;" required>
                                            <?php
                                            if ($msg != "") {
                                                $pisahkoma = explode(',', $barang['TYPE_MOTOR']);
                                                $database_type_motor = getData("SELECT * FROM tbl_type_motor ");
                                                foreach ($database_type_motor as $data_type_motor) :
                                                    $selected = in_array($data_type_motor['TYPE_MOTOR'], $pisahkoma) ? 'selected' : '';
                                                    echo "<option value='" . $data_type_motor["TYPE_MOTOR"] . "' " . $selected . ">" . $data_type_motor["TYPE_MOTOR"] . "</option>";
                                                endforeach;
                                            } else {
                                                $database_type_motor = $koneksi->query("SELECT * FROM tbl_type_motor ") or die(mysqli_error($koneksi));
                                                while ($data_type_motor = $database_type_motor->fetch_assoc()) {
                                                    echo '<option value="' . $data_type_motor['TYPE_MOTOR'] . '">' . $data_type_motor['TYPE_MOTOR'] . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="satuan">Satuan *</label>
                                    <select name="satuan" id="satuan" class="form-control select2" required>
                                        <option value="">-- Pilih Satuan --</option>

                                        <?php
                                        if ($msg != "") {
                                            $id_satuan = $barang['SATUAN'];
                                            $sql_satuan = getData("SELECT * FROM tbl_satuan");
                                            foreach ($sql_satuan as $data_satuan) :
                                                $id_data_satuan = $data_satuan['SATUAN'];
                                                //Data akan terseleksi (selected) jika variabel $kode_kelas sama dengan $kode_data_kelas.
                                                if ($id_satuan == $id_data_satuan) {
                                                    $cek = "selected";
                                                } else {
                                                    $cek = "";
                                                }
                                                echo "<option value='$id_data_satuan' $cek>" . $data_satuan['SATUAN'] . "</option>";
                                            endforeach;
                                        } else { ?>
                                            <?php
                                            $sql_satuan = getData("SELECT * FROM tbl_satuan");
                                            foreach ($sql_satuan as $data_satuan) :
                                            ?>
                                                <option value="<?= $data_satuan['SATUAN'] ?>"><?= $data_satuan['SATUAN'] ?></option>
                                        <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="stock">Stock *</label>
                                    <input type="number" name="stock" class="form-control" id="stock" placeholder="0" value="<?= $msg != '' ? $barang['STOCK'] : null ?>" autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <label for="stock_minimal">Stock Minimal *</label>
                                    <input type="number" name="stock_minimal" class="form-control" id="stock_minimal" placeholder="0" value="<?= $msg != '' ? $barang['STOCK_MINIMAL'] : null ?>" autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <label for="harga_beli">Harga Beli *</label>
                                    <input type="text" name="harga_beli" class="form-control maskingrupiahhargabelitambah" placeholder="Rp 0" value="<?= $msg != '' ? $barang['HARGA_BELI'] : null ?>" autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <label for="harga_barang">Harga Barang *</label>
                                    <input type="text" name="harga_barang" class="form-control maskingrupiahhargabarangtambah" placeholder="Rp 0" value="<?= $msg != '' ? $barang['HARGA_BARANG'] : null ?>" autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <label for="harga_pasang">Harga Pasang *</label>
                                    <input type="text" name="harga_pasang" class="form-control maskingrupiahhargapasangtambah" placeholder="Rp 0" value="<?= $msg != '' ? $barang['HARGA_PASANG'] : null ?>" autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <label for="harga_mekanik">Harga Mekanik *</label>
                                    <input type="text" name="harga_mekanik" class="form-control maskingrupiahhargamekaniktambah" placeholder="Rp 0" value="<?= $msg != '' ? $barang['HARGA_MEKANIK'] : null ?>" autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <label for="harga_tawar">Harga Tawar *</label>
                                    <input type="text" name="harga_tawar" class="form-control maskingrupiahhargatawartambah" placeholder="Rp 0" value="<?= $msg != '' ? $barang['HARGA_TAWAR'] : null ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-lg-4 text-center px-3">
                                <input type="hidden" name="oldImg" value="<?= $msg != '' ? $barang['GAMBAR'] : null ?>">
                                <img src="<?= $main_url ?>asset/imageuser/<?= $msg != '' ? $barang['GAMBAR'] : 'default-brg.jpg' ?>" alt="" class="profile-user-img mb-3 mt-4">
                                <input type="file" name="foto" class="form-control">
                                <span class="text-sm">Type file gambar JPG | PNG | GIF | JPEG</span>
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