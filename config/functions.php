<?php

//fungsi upload gambar
function uploadimg($url = null, $name = null) //$name = null (buat nama file gambar berdasarkan name yang dikasi atau kode yang dikasi)
{
    $Namafile = $_FILES['foto']['name'];
    $Ukuran = $_FILES['foto']['size'];
    $tmp = $_FILES['foto']['tmp_name'];

    // validasi file gambar yang boleh diupload
    $EkstensiGambarValid = ['jpg', 'jpeg', 'png', 'gif'];
    $EkstensiGambar = explode('.', $Namafile); //memecah file gambar
    $EkstensiGambar = strtolower(end($EkstensiGambar)); //keterangan : strlower berfungsi untuk membuat nama file huruf kecil dan end berfungsi mengambil data yang paling terakhir
    if (!in_array($EkstensiGambar, $EkstensiGambarValid)) { //apabila tipe gambar yang di upload user tidak sesuai yang kita buat maka kita tolak
        if ($url != null) {
            echo '<script>
            alert("file yang anda upload bukan gambar, Data gagal diupdate !");
            document.location.href = "' . $url . '";
            </script>';
            die();
        } else {
            echo '<script>
                    alert("file yang anda upload bukan gambar, Data gagal ditambahkan !");
            </script>';
            return false; //fungsi return false disini Jika file gambar tidak memenuhi syarat, maka program akan memberikan pesan kesalahan dan mengembalikan nilai false untuk membatalkan proses upload.
        }
    }
    //validasi ukuran gambar max 2 MB
    if ($Ukuran > 2000000) {
        if ($url != null) {
            echo '<script>
            alert("Ukuran gambar melebihi 2 MB, Data gagal diupdate !");
            document.location.href = "' . $url . '";
            </script>';
            die();
        } else {
            echo '<script>
        alert("Ukuran gambar tidak boleh melebihi 2 MB");
        </script>';
            return false; //fungsi return false disini Jika file gambar tidak memenuhi syarat, maka program akan memberikan pesan kesalahan dan mengembalikan nilai false untuk membatalkan proses upload.
        }
    }

    if ($name != null) { //jika nama file gambar tidak kosong
        $Namafilebaru = $name . '.' . $EkstensiGambar; //$name = nama file yang dikirim //nama file yang disimpan user digabungkan dengan angka acak, angka acak ditambahi di depan setelah nama 
    } else {
        $Namafilebaru = rand(10, 1000) . '-' . $Namafile; //angka random dari 10 sampai 1000 //nama file yang disimpan user digabungkan dengan angka acak, angka acak ditambahi di depan setelah nama 
    }



    move_uploaded_file($tmp, '../asset/imageuser/' . $Namafilebaru);
    return $Namafilebaru;
}


//ambil data
//ambil data
function getData($sql)
{
    global $koneksi;
    $result = mysqli_query($koneksi, $sql);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}


//login data
function userLogin()
{
    $userActive = $_SESSION["ssUserPOS"];
    $dataUser = getData("SELECT * FROM tbl_user WHERE USERNAME='$userActive'")[0]; //[0] = guna itu supaya mulai indeks nya dari 0
    return $dataUser;
}


//fungsi membagi bagian url
function userMenu()
{
    $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); //fungsi ini mengecek url yang lagi aktif atau kita lg akses url mana
    $uri_segment = explode('/', $uri_path); //memecah url dengan explode dengan pembatas tanda /
    $menu = $uri_segment[2]; // mengecek menu yang sedang aktif //$uri_segment[2] membagi secara 3 bagian dimulai dari index 0 sebagai contoh http://localhost:81/jhonsipos/dashboard.php == 0 utk localhost:81 1 utk jhonsipos 2 utk dashboard.php
    return $menu;
}

//keterangan  : memiliki kelemahan, kalo diakses melalui url masih bisa maka solusi tambahkan bawah ini ke mode-user.php
//if (userLogin()['USER_LEVEL'] != 1) { // jika user 
//     header("location:" . $main_url . "error-page.php");
//     exit();
// }
//
function menuHome()
{
    if (userMenu() == 'dashboard.php') { // jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

//keterangan  : memiliki kelemahan, kalo diakses melalui url masih bisa maka solusi tambahkan bawah ini ke mode-user.php
//if (userLogin()['USER_LEVEL'] != 1) { // jika user 
//     header("location:" . $main_url . "error-page.php");
//     exit();
// }
//
function menuSetting()
{
    if (userMenu() == 'user') {
        $result = 'menu-is-opening menu-open'; // menu-is-opening menu-open itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

//keterangan  : memiliki kelemahan, kalo diakses melalui url masih bisa maka solusi tambahkan bawah ini ke mode-user.php
//if (userLogin()['USER_LEVEL'] != 1) { // jika user 
//     header("location:" . $main_url . "error-page.php");
//     exit();
// }
//
function menuUser()
{
    if (userMenu() == 'user') { //url //jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}


function menuSupplier()
{
    if (userMenu() == 'supplier') { //url // jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

function menuMaster()
{
    if (userMenu() == 'supplier' || userMenu() == 'category' or userMenu() == 'barang' || userMenu() == 'brand' || userMenu() == 'type-motor' || userMenu() == 'satuan') { //jika user membuka menu supplier untuk membuka master
        $result = 'menu-is-opening menu-open'; // menu-is-opening menu-open itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

function menuMasterPenjualan()
{
    if (userMenu() == 'penjualan' || userMenu() == 'data-penjualan') { //jika user membuka menu supplier untuk membuka master
        $result = 'menu-is-opening menu-open'; // menu-is-opening menu-open itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}



function menuMasterBarang()
{
    if (userMenu() == 'category' or userMenu() == 'barang' || userMenu() == 'brand' || userMenu() == 'type-motor' || userMenu() == 'satuan') { //jika user membuka menu supplier untuk membuka master
        $result = 'menu-is-opening menu-open'; // menu-is-opening menu-open itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

function menuCustomer()
{
    if (userMenu() == 'customer') { //url // jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

function menuPembelian()
{
    if (userMenu() == 'pembelian') { //url // jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

function menuPenjualan()
{
    if (userMenu() == 'penjualan') { //url // jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

function menuDataPenjualan()
{
    if (userMenu() == 'data-penjualan') { //url // jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

function menuCategory()
{
    if (userMenu() == 'category') { //url // jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

function menuBarang()
{
    if (userMenu() == 'barang') { //url // jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

function laporanStock()
{
    if (userMenu() == 'stock') { //url // jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

function in_date($tgl)
{ //fungsi mengubah tanggal versi indonesia
    $tg     = substr($tgl, 8, 2); // substr($tgl, 8, 2); ==> mengubah ke string //$tgl ==> mengambil tanggal, 8 ==> mengambil data indeks ke 8, 2 ==> sebanyak 2 karakter 
    $bln     = substr($tgl, 5, 2); // substr($tgl, 5, 2); ==> mengubah ke string //$bln ==> mengambil bln, 5 ==> mengambil data indeks ke 5, 2 ==> sebanyak 2 karakter 
    $thn     = substr($tgl, 0, 4); // substr($tgl, 0, 4); ==> mengubah ke string //$bln ==> mengambil tahun, 0 ==> mengambil data indeks ke 0, 5 ==> sebanyak 4 karakter 

    return $tg . "-" . $bln . "-" . $thn;
}

function menuLaporanPembelian()
{
    if (userMenu() == 'laporan-pembelian') { //url // jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

function menuLaporanPenjualan()
{
    if (userMenu() == 'laporan-penjualan') { //url // jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

function omzet()
{
    global $koneksi;
    $queryOmzet = mysqli_query($koneksi, "SELECT sum(TOTAL) AS omzet FROM tbl_jual_head");
    $data       = mysqli_fetch_assoc($queryOmzet);
    $omzet      = number_format($data['omzet'], 0, ',' . '.');
    return $omzet;
}


function menuBrand()
{
    if (userMenu() == 'brand') { //url // jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}

function menuTypeMotor()
{
    if (userMenu() == 'type-motor') { //url // jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}
function menuSatuan()
{
    if (userMenu() == 'satuan') { //url // jika user lagi buka menu dashboard.php maka yang di sorot menu dashboard di sidebar
        $result = 'active'; //active itu bagian dari html AdminLTE
    } else {
        $result = null;
    }
    return $result;
}


function getTotalOmzet($query)
{
    global $koneksi;
    $queryOmzet = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($queryOmzet);
    return $data['omzet'];
}
