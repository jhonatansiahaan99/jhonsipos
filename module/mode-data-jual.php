<?php
// function generateNo()
// {
//     global $koneksi;
//     date_default_timezone_set('Asia/Jakarta');
//     // Mendapatkan tanggal saat ini
//     $date = date("Ymd");

//     $queryNo = mysqli_query($koneksi, "SELECT max(NO_BELI) AS maxno FROM tbl_beli_head");
//     $row = mysqli_fetch_assoc($queryNo);
//     $maxno = $row["maxno"];

//     $noUrut = (int) substr($maxno, 2, 4); //ubah string jadi integer dari index ke 2 dan ambil 4 karakter
//     $noUrut++;
//     $maxno = 'PB' . sprintf("%04s", $noUrut);

//     return $maxno;
// }

function generateNo()
{
    global $koneksi;
    date_default_timezone_set('Asia/Jakarta');
    // Mendapatkan tanggal saat ini
    $today = date('Y-m-d');
    $date = date('Ymd');

    $queryNo = mysqli_query($koneksi, "SELECT max(NO_JUAL) AS maxno FROM tbl_jual_head");
    $row = mysqli_fetch_assoc($queryNo);
    $maxno = $row["maxno"];

    if (substr($maxno, 3, 8) == $date) { //NO BELI TANGGAL DIDATABASE SAMA DENGAN TANGGAL HARI INI
        $noUrut = (int) substr($maxno, 12, 5); //MAKA NO URUT SETELAH TANGGAL DI TAMBAH 1 SETELAH NOMOR SEBELUMNYA
        $noUrut++;
        $maxno = 'PJ-' . $date . "-" . sprintf("%05s", $noUrut);
    } else { //NO BELI TANGGAL DIDATABASE TIDAK SAMA DENGAN TANGGAL HARI INI
        $maxno = 'PJ-' . $date . "-" . '00001'; //MAKA NO URUT SETELAH TANGGAL DI TAMBAH 1
    }
    return $maxno;
}

function totalJual($noJual)
{
    global $koneksi;
    $totalJual  = mysqli_query($koneksi, "SELECT sum(JML_HARGA) AS total FROM tbl_jual_detail WHERE NO_JUAL = '$noJual' ");
    $data = mysqli_fetch_assoc($totalJual);
    $total = $data["total"];
    return $total;
}


function insert($data)
{
    global $koneksi;

    $no     = mysqli_real_escape_string($koneksi, $data['nojual']);
    $tgl     = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $kode     = mysqli_real_escape_string($koneksi, $data['barcode']);
    $nama     = mysqli_real_escape_string($koneksi, $data['namaBrg']);
    $qty     = mysqli_real_escape_string($koneksi, $data['qty']);
    $harga     = mysqli_real_escape_string($koneksi, $data['harga']);
    $jmlharga     = mysqli_real_escape_string($koneksi, $data['jmlHarga']);
    $stok     = mysqli_real_escape_string($koneksi, $data['stok']);


    //cek barang sudah di input atau belum
    $cekbrg     = mysqli_query($koneksi, "SELECT * FROM tbl_jual_detail WHERE NO_JUAL = '$no' AND BARCODE = '$kode' ");
    if (mysqli_num_rows($cekbrg)) {
        echo "<script>
                alert('Barang Sudah Ada, Anda harus menghapus nya dulu jika ingin mengubah qty nya..');
        </script>";
        return false;
    }

    //QTY barang tidak boleh kosong
    if (empty($qty)) {
        echo "<script>
                alert('Qty barang tidak boleh kosong');
        </script>";
        return false;
    } else if ($qty > $stok) {
        echo "<script>
                alert('Stok barang tidak mencukupi');
        </script>";
        return false;
    } else if ($stok === '0') {
        echo "<script>
                alert('Stok barang habis');
        </script>";
        return false;
    } else {
        $sqljual    = "INSERT INTO tbl_jual_detail VALUES (null,'$no','$tgl','$kode','$nama','$qty','$harga','$jmlharga')";
        mysqli_query($koneksi, $sqljual);
    }

    mysqli_query($koneksi, "UPDATE tbl_barang SET STOCK = STOCK - $qty WHERE BARCODE = '$kode'");
    return mysqli_affected_rows($koneksi);
}


function insertMenuPilihan($data)
{
    global $koneksi;

    $no         = mysqli_real_escape_string($koneksi, $data['nojual']);
    $tgl        = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $barcode       = mysqli_real_escape_string($koneksi, $_POST['addMenu']);
    // $nama     = mysqli_real_escape_string($koneksi, $data['menunamabarang']);
    // $qty     = mysqli_real_escape_string($koneksi, $data['menuqty']);
    // $harga     = mysqli_real_escape_string($koneksi, $data['menuhargabarang']);
    // $jmlharga     = mysqli_real_escape_string($koneksi, $data['menujmlhhargabarang']);

    // Dapatkan data yang dipilih berdasarkan barcode barang
    $sqlBarang      = mysqli_query($koneksi, "SELECT * FROM tbl_barang WHERE BARCODE = '$barcode'");
    $dataBrg        = mysqli_fetch_assoc($sqlBarang);
    $nama           = $dataBrg['NAMA_BARANG'];
    $qty            = 1;
    $harga_beli     = $dataBrg['HARGA_BELI'];
    $harga          = $dataBrg['HARGA_BARANG'];
    $jmlharga       = $dataBrg['HARGA_BARANG'];


    if ($dataBrg['STOCK'] == 0) {
        echo "<script>
                alert('Stok habis');
              </script>";
        return false;
    }

    $cekbrg         = mysqli_query($koneksi, "SELECT * FROM tbl_jual_detail WHERE NO_JUAL = '$no' AND BARCODE = '$barcode' ");
    if (mysqli_num_rows($cekbrg) > 0) { // JIKA ADA BARANG DI TBL JUAL DETAIL MAKA DI TAMBAH 1 DENGAN QTY SEKARANG
        $sqlCekbrg    = "UPDATE tbl_jual_detail SET QTY = QTY + 1 WHERE NO_JUAL = '$no' AND BARCODE = '$barcode'";
        mysqli_query($koneksi, $sqlCekbrg);
        $sqlCekbrg    = "UPDATE tbl_jual_detail SET JML_HARGA = JML_HARGA + $harga WHERE NO_JUAL = '$no' AND BARCODE = '$barcode'";
        mysqli_query($koneksi, $sqlCekbrg);
        mysqli_query($koneksi, "UPDATE tbl_barang SET STOCK = STOCK - $qty WHERE BARCODE = '$barcode'");
        return mysqli_affected_rows($koneksi);
    } else { // APABILA TIDAK ADA OTOMATIS JADI 1 KARNA DATA BELUM ADA 
        $sqlbeli    = "INSERT INTO tbl_jual_detail VALUES (null,'$no','$tgl','$barcode','$nama','$qty','$harga_beli','$harga','$jmlharga')";
        mysqli_query($koneksi, $sqlbeli);
        mysqli_query($koneksi, "UPDATE tbl_barang SET STOCK = STOCK - $qty WHERE BARCODE = '$barcode'");
        return mysqli_affected_rows($koneksi);
    }
}


function delete($barcode, $idjual, $qty)
{
    global $koneksi;

    $sqlDel = "DELETE FROM tbl_jual_detail WHERE BARCODE = '$barcode' AND NO_JUAL = '$idjual' ";
    mysqli_query($koneksi, $sqlDel);

    mysqli_query($koneksi, "UPDATE tbl_barang SET STOCK = STOCK + $qty WHERE BARCODE = '$barcode'");
    return mysqli_affected_rows($koneksi);
}



function cetak($data)
{
    global $koneksi;
    $nojual     = mysqli_real_escape_string($koneksi, $data['nojual']);
    $tgl        = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $DataAsli_Total = $data['total'];
    $Hapus_MataUang_Total = str_replace('.', '', $DataAsli_Total);
    $Total = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Total);
    $customer   = mysqli_real_escape_string($koneksi, $data['customer']);
    $keterangan = mysqli_real_escape_string($koneksi, $data['keterangan']);
    $DataAsli_Bayar = trim($data['bayar']);
    $Hapus_MataUang_Bayar = str_replace('.', '', $DataAsli_Bayar);
    $Bayar = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Bayar);
    $DataAsli_Kembalian = $data['kembalian'];
    $Hapus_MataUang_Kembalian = str_replace('.', '', $DataAsli_Kembalian);
    $Kembalian = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Kembalian);

    if ($DataAsli_Bayar == '' || $DataAsli_Bayar == 0) {
        echo "<script>
            alert('Masukkan Nominal Bayar!!');
            window.location.href = 'edit-data-penjualan.php?id=' + encodeURIComponent('$nojual') + '&tgl=' + encodeURIComponent('$tgl');
        </script>";
        return false;
        //Fungsi encodeURIComponent digunakan untuk mengkodekan tanggal secara aman dalam URL, menghindari masalah jika tanggal mengandung karakter khusus.
        //Penggunaan encodeURIComponent penting saat Anda ingin menyisipkan nilai yang berisi karakter khusus dalam URL, seperti tanda & (ampersand) atau tanda = (sama dengan).
        //Jika tanggal atau nomor jual mengandung karakter khusus dan Anda tidak menggunakan encodeURIComponent, maka URL yang dihasilkan mungkin tidak valid, dan pengguna dapat mengalami masalah saat mengakses halaman yang diarahkan.
    } else {
        mysqli_query($koneksi, "UPDATE tbl_jual_head SET NO_JUAL = '$nojual', TGL_JUAL = '$tgl', CUSTOMER = '$customer', TOTAL = '$Total', KETERANGAN = '$keterangan', JML_BAYAR = '$Bayar', KEMBALIAN = '$Kembalian' WHERE NO_JUAL = '$nojual' AND TGL_JUAL = '$tgl' ");
        return mysqli_affected_rows($koneksi);
    }
}

function simpan($data)
{
    global $koneksi;
    $nojual     = mysqli_real_escape_string($koneksi, $data['nojual']);
    $tgl        = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $DataAsli_Total = $data['total'];
    $Hapus_MataUang_Total = str_replace('.', '', $DataAsli_Total);
    $Total = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Total);
    $customer   = mysqli_real_escape_string($koneksi, $data['customer']);
    $keterangan = mysqli_real_escape_string($koneksi, $data['keterangan']);
    $DataAsli_Bayar = $data['bayar'];
    $Hapus_MataUang_Bayar = str_replace('.', '', $DataAsli_Bayar);
    $Bayar = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Bayar);
    $DataAsli_Kembalian = $data['kembalian'];
    $Hapus_MataUang_Kembalian = str_replace('.', '', $DataAsli_Kembalian);
    $Kembalian = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Kembalian);

    if ($DataAsli_Bayar == '' || $DataAsli_Bayar == 0) {
        echo "<script>
            alert('Masukkan Nominal Bayar!!');
            window.location.href = 'edit-data-penjualan.php?id=' + encodeURIComponent('$nojual') + '&tgl=' + encodeURIComponent('$tgl');
        </script>";
        return false;
        //Fungsi encodeURIComponent digunakan untuk mengkodekan tanggal secara aman dalam URL, menghindari masalah jika tanggal mengandung karakter khusus.
        //Penggunaan encodeURIComponent penting saat Anda ingin menyisipkan nilai yang berisi karakter khusus dalam URL, seperti tanda & (ampersand) atau tanda = (sama dengan).
        //Jika tanggal atau nomor jual mengandung karakter khusus dan Anda tidak menggunakan encodeURIComponent, maka URL yang dihasilkan mungkin tidak valid, dan pengguna dapat mengalami masalah saat mengakses halaman yang diarahkan.
    }
    mysqli_query($koneksi, "UPDATE tbl_jual_head SET NO_JUAL = '$nojual', TGL_JUAL = '$tgl', CUSTOMER = '$customer', TOTAL = '$Total', KETERANGAN = '$keterangan', JML_BAYAR = '$Bayar', KEMBALIAN = '$Kembalian' WHERE NO_JUAL = '$nojual' AND TGL_JUAL = '$tgl' ");
    return mysqli_affected_rows($koneksi);
}

function service($data)
{
    global $koneksi;
    $nojual     = mysqli_real_escape_string($koneksi, $data['nojual']);
    $tgl        = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $DataAsli_Total = $data['total'];
    $Hapus_MataUang_Total = str_replace('.', '', $DataAsli_Total);
    $Total = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Total);
    $customer   = mysqli_real_escape_string($koneksi, $data['customer']);
    $keterangan = mysqli_real_escape_string($koneksi, $data['keterangan']);

    if ($customer == '') {
        echo "<script>
                    alert('Isi Customer Dulu');
                </script>";
        return false;
    } else {
        mysqli_query($koneksi, "UPDATE tbl_jual_head SET NO_JUAL = '$nojual', TGL_JUAL = '$tgl', CUSTOMER = '$customer', TOTAL = '$Total', KETERANGAN = '$keterangan' WHERE NO_JUAL = '$nojual' AND TGL_JUAL = '$tgl' ");
        return mysqli_affected_rows($koneksi);
    }
}



function updateDaftarBarang($data)
{
    global $koneksi;
    $Nojual = mysqli_real_escape_string($koneksi, $data['updateNojual']);
    $Tgl = mysqli_real_escape_string($koneksi, $data['updateTgljual']);
    $Barcode = mysqli_real_escape_string($koneksi, $data['updateBarcodebrg']);
    $Namabrg = mysqli_real_escape_string($koneksi, $data['updateNamabrg']);
    $DataAsli_Hargabrg = $data['updateHrgbrg'];
    $Hapus_MataUang_Hargabrg = str_replace('.', '', $DataAsli_Hargabrg);
    $Hargabrg = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Hargabrg);
    $Qtysekarang = mysqli_real_escape_string($koneksi, $data['qty']);
    $Qtylama = mysqli_real_escape_string($koneksi, $data['qtylama']);
    $DataAsli_Jumlahhrg = $data['jmlharga'];
    $Hapus_MataUang_Jumlahhrg = str_replace('.', '', $DataAsli_Jumlahhrg);
    $Jumlahhrg = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Jumlahhrg);

    // Cek stock barang sebelum melakukan perubahan
    $sql_Brg = "SELECT STOCK FROM tbl_barang WHERE BARCODE = '$Barcode'";
    $barangData = getData($sql_Brg);
    if (count($barangData) > 0) {
        $stockBarang = $barangData[0]['STOCK'];

        // Jika stock barang habis, batalkan perubahan dan tampilkan pesan
        if ($stockBarang <= 0) {
            echo "<script>alert('Stock barang habis. Tidak dapat mengubah data barang ini.');</script>";
            return false;
        }
    } else {
        // Barang tidak ditemukan dalam tabel tbl_barang
        echo "<script>alert('Data barang tidak ditemukan.');</script>";
        return false;
    }

    // Lakukan validasi data sebelum melakukan perubahan
    if (!is_numeric($Qtysekarang) || $Qtysekarang < 0) {
        echo "<script>alert('Qty barang tidak valid.');</script>";
        return false;
    }

    // Bandingkan qty baru dengan qty sebelumnya dan perbarui tbl_barang STOCK sesuai
    if ($Qtysekarang == $Qtylama) {
        // Jika qty baru sama dengan qty sebelumnya, tidak melakukan apa-apa (tidak ada perubahan STOCK).
        mysqli_query($koneksi, "UPDATE tbl_jual_detail SET NAMA_BRG = '$Namabrg', HARGA_JUAL = '$Hargabrg', JML_HARGA = '$Jumlahhrg' WHERE NO_JUAL = '$Nojual' AND TGL_JUAL = '$Tgl' AND BARCODE = '$Barcode' ");
        echo "<script>alert('BARANG BERHASIL DI UBAH');</script>";
        return mysqli_affected_rows($koneksi);
    } elseif ($Qtysekarang < $Qtylama) {
        // Jika qty baru lebih kecil dari qty sebelumnya, STOCK berkurang selisihnya.
        $qtyDifference = $Qtylama - $Qtysekarang;

        // Cek apakah stock mencukupi untuk mengurangi qty
        if ($stockBarang >= $qtyDifference) {
            mysqli_query($koneksi, "UPDATE tbl_barang SET STOCK = STOCK + $qtyDifference WHERE BARCODE = '$Barcode'");
            mysqli_query($koneksi, "UPDATE tbl_jual_detail SET NAMA_BRG = '$Namabrg', HARGA_JUAL = '$Hargabrg', QTY = '$Qtysekarang', JML_HARGA = '$Jumlahhrg' WHERE NO_JUAL = '$Nojual' AND TGL_JUAL = '$Tgl' AND BARCODE = '$Barcode' ");
            echo "<script>alert('BARANG BERHASIL DI UBAH');</script>";
            return mysqli_affected_rows($koneksi);
        } else {
            echo "<script>alert('Stock barang tidak mencukupi untuk mengurangi qty.');</script>";
            return false;
        }
    } elseif ($Qtysekarang > $Qtylama) {
        // Jika qty baru lebih besar dari qty sebelumnya, tambah STOCK dengan selisihnya.
        $qtyDifference = $Qtysekarang - $Qtylama;

        // Cek apakah stock mencukupi untuk menambah qty
        if ($stockBarang >= $qtyDifference) {
            mysqli_query($koneksi, "UPDATE tbl_barang SET STOCK = STOCK - $qtyDifference WHERE BARCODE = '$Barcode'");
            mysqli_query($koneksi, "UPDATE tbl_jual_detail SET NAMA_BRG = '$Namabrg', HARGA_JUAL = '$Hargabrg', QTY = '$Qtysekarang', JML_HARGA = '$Jumlahhrg' WHERE NO_JUAL = '$Nojual' AND TGL_JUAL = '$Tgl' AND BARCODE = '$Barcode' ");
            echo "<script>alert('BARANG BERHASIL DI UBAH');</script>";
            return mysqli_affected_rows($koneksi);
        } else {
            echo "<script>alert('Stock barang tidak mencukupi untuk menambah qty.');</script>";
            return false;
        }
    }

    echo "<script>alert('GAGAL MENGUBAH BARANG');</script>";
    return false;
}



function insertCustomer($data)
{
    global $koneksi;

    $Nama = strtoupper(mysqli_real_escape_string($koneksi, $data['nama'])); //strtoupper = ketika user mengetik nama maka otomatis diubah huruf besar semua untuk masuk kedatabase
    $Telepon = mysqli_real_escape_string($koneksi, $data['telepon']);
    $Deskripsi = mysqli_real_escape_string($koneksi, $data['keterangan']);


    $CekNama = mysqli_query($koneksi, "SELECT NAMA FROM tbl_customer where NAMA = '$Nama' ");
    if (mysqli_num_rows($CekNama) > 0) {
        echo "<script>
        alert('Nama Customer Sudah Ada, Customer Baru Gagal DiTambahkan !');
        </script>";
        return false;
    }

    $sqlCustomer = "INSERT INTO tbl_customer VALUES (null,'$Nama','$Telepon','$Deskripsi')";


    if (mysqli_query($koneksi, $sqlCustomer)) {
        return "Data Customer Berhasil Ditambahkan!";
    } else {
        return "Error: " . mysqli_error($koneksi);
    }
}
