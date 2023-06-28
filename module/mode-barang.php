<?php

if (userLogin()['USER_LEVEL'] == 3 || userLogin()['USER_LEVEL'] == 2) { //MENGECEK USER YANG AKTIF // ketika level user 3 yaitu kasir maka kita tolak untuk akses supplier
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
