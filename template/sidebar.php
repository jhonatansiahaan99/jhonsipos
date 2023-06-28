<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= $main_url ?>dashboard.php" class="brand-link">
        <img src="<?= $main_url ?>asset/image/logo.png" alt="Bengkel Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Jhonsi Bengkel Motor</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $main_url ?>asset/imageuser/<?= userLogin()['FOTO'] ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block" style="width: 200px; word-wrap: break-word;"><?= userLogin()['NAMA'] ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item ">
                    <a href="<?= $main_url ?>dashboard.php" class="nav-link <?= menuHome() ?>">
                        <i class="nav-icon fas fa-tachometer-alt text-sm"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <?php
                if (userLogin()['USER_LEVEL'] != 3) { //KALO BUKAN 3(KASIR) MAKA TIDAK BISA BUKA HALAMAN MASTER
                ?>
                    <li class="nav-item <?= menuMaster() ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-folder text-sm"></i>
                            <p>Master <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= $main_url ?>supplier/data-supplier.php" class="nav-link <?= menuSupplier() ?>">
                                    <i class="far fa-circle nav-icon text-sm"></i>
                                    <p>Supplier</p>
                                </a>
                            </li>
                            <li class="nav-item <?= menuMasterBarang() ?>">
                                <a href="#" class="nav-link ">
                                    <i class="nav-icon fas fa-archive text-sm"></i>
                                    <p>Master Barang <i class="fas fa-angle-left right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= $main_url ?>barang" class="nav-link <?= menuBarang() ?>">
                                            <i class="far fa-circle nav-icon text-sm"></i>
                                            <p>Barang</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= $main_url ?>category/add-category.php" class="nav-link <?= menuCategory() ?>">
                                            <i class="far fa-circle nav-icon text-sm"></i>
                                            <p>Category</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link ">
                                            <i class="far fa-circle nav-icon text-sm"></i>
                                            <p>Brand</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link ">
                                            <i class="far fa-circle nav-icon text-sm"></i>
                                            <p>Type Motor</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link ">
                                            <i class="far fa-circle nav-icon text-sm"></i>
                                            <p>Satuan</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                <?php
                }
                ?>
                <li class="nav-header">Transaksi</li>
                <li class="nav-item">
                    <a href="<?= $main_url ?>pembelian" class="nav-link <?= menuPembelian() ?>">
                        <i class="nav-icon fas fa-shopping-cart text-sm"></i>
                        <p>Pembelian</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $main_url ?>penjualan" class="nav-link <?= menuPenjualan() ?>">
                        <i class="nav-icon fas fa-file-invoice text-sm"></i>
                        <p>Penjualan</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="<?= $main_url ?>customer/data-customer.php" class="nav-link <?= menuCustomer() ?>">
                        <i class="nav-icon fas fa-address-book text-sm"></i>
                        <p>Customer</p>
                    </a>
                </li>
                <li class="nav-header">Report</li>
                <li class="nav-item">
                    <a href="<?= $main_url ?>laporan-pembelian" class="nav-link <?= menuLaporanPembelian() ?>">
                        <i class="nav-icon fas fa-chart-pie text-sm"></i>
                        <p>Laporan Pembelian</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $main_url ?>laporan-penjualan" class="nav-link <?= menuLaporanPenjualan() ?>">
                        <i class="nav-icon fas fa-chart-line text-sm"></i>
                        <p>Laporan Penjualan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-warehouse text-sm"></i>
                        <p>Laporan Stock</p>
                    </a>
                </li>
                <?php
                if (userLogin()['USER_LEVEL'] == 1) { //JIKA LEVEL USER 1(SUPERADMIN) BUKA BOLEH USER
                ?>
                    <li class="nav-header">Settings</li>
                    <li class="nav-item <?= menuSetting() ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog text-sm"></i>
                            <p>Pengaturan <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= $main_url ?>user/data-user.php" class="nav-link <?= menuUser() ?>">
                                    <i class="far fa-circle nav-icon text-sm"></i>
                                    <p>Users</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php
                }
                ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>