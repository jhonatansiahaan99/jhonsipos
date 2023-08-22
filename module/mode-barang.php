<?php

if (userLogin()['USER_LEVEL'] == 3) { //MENGECEK USER YANG AKTIF // ketika level user 3 yaitu kasir maka kita tolak untuk akses supplier
    header("location:" . $main_url . "error-page.php");
    exit();
}


function generateId()
{
    global $koneksi;

    date_default_timezone_set('Asia/Jakarta');
    // Mendapatkan tanggal saat ini
    $date = date("Ymd");

    $queryId    = mysqli_query($koneksi, "SELECT max(ID_BARANG) as maxid FROM tbl_barang"); //ngambil ID tertinggi
    $data       = mysqli_fetch_array($queryId);
    $maxId      = $data['maxid'];
    $noUrut     = (int)substr($maxId, 13, 5); //mengubah integer ke string //($maxId,13, 5) = maxId(diambil dari id tertinggi), 13(diambil dari index ke 13 dan mulai index itu 0), 5(panjang nya diambil 5 karakter terakhir)
    $noUrut++;

    $maxId      = "BRG-" . $date . "-" . sprintf("%05s", $noUrut);
    return $maxId;
}

function generateBarcode()
{
    global $koneksi;

    date_default_timezone_set('Asia/Jakarta');

    // Mendapatkan tanggal saat ini
    $date = date("Ymd");

    // Daftar huruf dan angka yang akan digunakan dalam barcode
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    // Panjang karakter acak untuk barcode
    $barcodeLength = 10;

    do {
        // Inisialisasi string untuk menyimpan karakter acak
        $randomString = '';

        // Loop untuk menghasilkan karakter acak sebanyak $barcodeLength kali
        for ($i = 0; $i < $barcodeLength; $i++) {
            // Mengambil karakter acak dari $characters dengan indeks acak
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }


        // Kombinasikan tanggal dengan karakter acak untuk mendapatkan barcode lengkap
        $barcode = $date . $randomString;

        // Cek apakah barcode sudah ada di database
        $query = "SELECT COUNT(*) as count FROM tbl_barang WHERE BARCODE = '$barcode'";
        $result = mysqli_query($koneksi, $query);
        $data = mysqli_fetch_assoc($result);
        $barcodeExists = $data['count'] > 0;
    } while ($barcodeExists); // Ulangi proses jika barcode sudah ada di database

    // Mengembalikan barcode yang unik
    return $barcode;
}



function insert($data)
{
    global $koneksi;

    $id             = mysqli_real_escape_string($koneksi, $data['kode']);
    $barcode        = mysqli_real_escape_string($koneksi, $data['barcode']);
    $nama           = mysqli_real_escape_string($koneksi, $data['nama_barang']);
    $kategori       = mysqli_real_escape_string($koneksi, $data['kategori']);
    $brand          = mysqli_real_escape_string($koneksi, $data['brand']);
    $type_motor     = mysqli_real_escape_string($koneksi, implode(',', $_POST['type_motor']));
    $satuan         = mysqli_real_escape_string($koneksi, $data['satuan']);
    $stock          = mysqli_real_escape_string($koneksi, $data['stock']);
    $stock_minimal  = mysqli_real_escape_string($koneksi, $data['stock_minimal']);
    $harga_beli     = mysqli_real_escape_string($koneksi, str_replace('.', '', $data['harga_beli']));
    $harga_barang   = mysqli_real_escape_string($koneksi, str_replace('.', '', $data['harga_barang']));
    $harga_pasang   = mysqli_real_escape_string($koneksi, str_replace('.', '', $data['harga_pasang']));
    $harga_mekanik  = mysqli_real_escape_string($koneksi, str_replace('.', '', $data['harga_mekanik']));
    $harga_tawar    = mysqli_real_escape_string($koneksi, str_replace('.', '', $data['harga_tawar']));
    $gambar         = mysqli_real_escape_string($koneksi, $_FILES['foto']['name']);

    $cekBarcode     = mysqli_query($koneksi, "SELECT * FROM tbl_barang WHERE BARCODE = '$barcode' ");
    if (mysqli_num_rows($cekBarcode)) {
        echo '<script>alert("Kode Barcode Sudah Ada, Barang Gagal DiTambahkan")</script>';
        return false;
    }

    //upload gambar barang
    if ($gambar != null) {
        $gambar = uploadimg(null, $id);
    } else {
        $gambar = 'default-brg.jpg';
    }

    //gambar tidak sesuai validasi
    if ($gambar == '') {
        return false;
    }


    date_default_timezone_set('Asia/Jakarta');
    // Mendapatkan tanggal saat ini
    $tanggal = date("Y-m-d");

    $SqlBarang = "INSERT INTO tbl_barang value ('$id','$barcode', '$nama','$kategori','$brand','$type_motor','$satuan','$stock','$stock_minimal','$harga_beli','$harga_barang','$harga_pasang','$harga_mekanik','$harga_tawar','$gambar') ";
    $SqlBarang_Waktu = "INSERT INTO tbl_barang_waktu value (NULL,'$id','$barcode', '$nama','$kategori','$brand','$type_motor','$satuan','$stock','$stock_minimal','$harga_beli','$harga_barang','$harga_pasang','$harga_mekanik','$harga_tawar','$tanggal') ";
    mysqli_query($koneksi, $SqlBarang);
    mysqli_query($koneksi, $SqlBarang_Waktu);
    return mysqli_affected_rows($koneksi); //mengembalikan atau menampilkan jumllah baris yang terkena pengaruh query seperti insert,select, update, dan delete

}


function delete($id, $gbr)
{
    global $koneksi;

    $sqlDel = "DELETE FROM tbl_barang WHERE ID_BARANG = '$id' ";
    mysqli_query($koneksi, $sqlDel);
    if ($gbr != 'default-brg.jpg') {
        unlink('../asset/imageuser/' . $gbr);
    }
    return mysqli_affected_rows($koneksi);
}


function update($data)
{
    global $koneksi;

    $id             = mysqli_real_escape_string($koneksi, $data['kode']);
    $barcode        = mysqli_real_escape_string($koneksi, $data['barcode']);
    $nama           = mysqli_real_escape_string($koneksi, $data['nama_barang']);
    $kategori       = mysqli_real_escape_string($koneksi, $data['kategori']);
    $brand          = mysqli_real_escape_string($koneksi, $data['brand']);
    $type_motor     = mysqli_real_escape_string($koneksi, implode(',', $_POST['type_motor']));
    $satuan         = mysqli_real_escape_string($koneksi, $data['satuan']);
    $stock          = mysqli_real_escape_string($koneksi, $data['stock']);
    $stock_minimal  = mysqli_real_escape_string($koneksi, $data['stock_minimal']);
    $harga_beli     = mysqli_real_escape_string($koneksi, str_replace('.', '', $data['harga_beli']));
    $harga_barang   = mysqli_real_escape_string($koneksi, str_replace('.', '', $data['harga_barang']));
    $harga_pasang   = mysqli_real_escape_string($koneksi, str_replace('.', '', $data['harga_pasang']));
    $harga_mekanik  = mysqli_real_escape_string($koneksi, str_replace('.', '', $data['harga_mekanik']));
    $harga_tawar    = mysqli_real_escape_string($koneksi, str_replace('.', '', $data['harga_tawar']));
    $gbrLama        = mysqli_real_escape_string($koneksi, $data['oldImg']);
    $gambar         = mysqli_real_escape_string($koneksi, $_FILES['foto']['name']);

    //cek barcode lama
    $queryBarcode = mysqli_query($koneksi, "SELECT * FROM tbl_barang WHERE ID_BARANG = '$id' ");
    $dataBrg      = mysqli_fetch_assoc($queryBarcode);
    $curBarcode   = $dataBrg['BARCODE']; //BARCODE LAMA YANG DI DATABASE

    //barcode baru
    $cekBarcode     = mysqli_query($koneksi, "SELECT BARCODE FROM tbl_barang WHERE BARCODE = '$barcode' ");

    //Jika barcode diganti
    if ($barcode !== $curBarcode) { //mengecek jika barcode saat ini(barcode yang di input user) tidak sama dengan barcode sekarang(yang didatabase)
        if (mysqli_num_rows($cekBarcode)) { //jiika barcode sudah ada
            echo '<script>alert("Kode Barcode Sudah Ada, Barang Gagal Di Perbarui")</script>';
            return false;
        }
    }

    date_default_timezone_set('Asia/Jakarta');
    // Mendapatkan tanggal saat ini
    $date = date("Ymd");

    //cek gambar
    //upload gambar barang
    if ($gambar != null) {
        $url = "index.php"; //alamat data barang kita
        if ($gbrLama == 'default-brg.jpg') { //gambar lama sama dengan gambar default-brg.jpg(atau pun user tidak masukin gambar)
            $nmgbr = $id; //nama gambar dari ID
        } else { //kalau bukan gambar default
            $nmgbr = $id . '-' . $date . '-' . rand(10, 1000);
        }
        $imgBrg = uploadimg($url, $nmgbr);
        if ($gbrLama != 'default-brg.jpg') {
            @unlink('../asset/imageuser' . $gbrLama);
        }
    } else {
        $imgBrg = $gbrLama;
    }

    mysqli_query($koneksi, "UPDATE tbl_barang SET
                           BARCODE          = '$barcode',
                           NAMA_BARANG      = '$nama',
                           CATEGORY         = '$kategori',
                           BRAND            = '$brand',
                           TYPE_MOTOR       = '$type_motor',
                           SATUAN           = '$satuan',
                           STOCK            = '$stock',
                           STOCK_MINIMAL    = '$stock_minimal',
                           HARGA_BELI       = '$harga_beli',
                           HARGA_BARANG     = '$harga_barang',
                           HARGA_PASANG     = '$harga_pasang',
                           HARGA_MEKANIK    = '$harga_mekanik',
                           HARGA_TAWAR      = '$harga_tawar',
                           GAMBAR           = '$imgBrg'
                           WHERE ID_BARANG  = '$id'    
    ");
    return mysqli_affected_rows($koneksi);
}

function simpan_barcode($data)
{
    global $koneksi;

    $id             = mysqli_real_escape_string($koneksi, $data['id_barang']);
    $barcode        = mysqli_real_escape_string($koneksi, $data['addbarcode']);
    $cekBarcode     = mysqli_query($koneksi, "SELECT * FROM tbl_barcode WHERE ADD_BARCODE = '$barcode' ");
    if (mysqli_num_rows($cekBarcode)) {
        echo '<script>alert("Kode Barcode Sudah Ada, Barang Gagal DiTambahkan")</script>';
        return false;
    }
    if ($id == '' || $barcode == '') {
        echo '<script>alert("Gagal Disimpan!! ID dan Barcode Tidak Ada...")</script>';
        echo "
            <script>document.location.href = 'index.php'</script>
         ";
        return false;
    }

    mysqli_query($koneksi, "INSERT INTO tbl_barcode value (NULL,'$id','$barcode')");
    return mysqli_affected_rows($koneksi);
}

function delete_barcode($idbarcode)
{
    global $koneksi;

    mysqli_query($koneksi, "DELETE FROM tbl_barcode WHERE ID_BARCODE = '$idbarcode'");
    return mysqli_affected_rows($koneksi);
}
