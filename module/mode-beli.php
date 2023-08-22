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

    $queryNo = mysqli_query($koneksi, "SELECT max(NO_BELI) AS maxno FROM tbl_beli_head");
    $row = mysqli_fetch_assoc($queryNo);
    $maxno = $row["maxno"];

    if (substr($maxno, 3, 8) == $date) { //NO BELI TANGGAL DIDATABASE SAMA DENGAN TANGGAL HARI INI
        $noUrut = (int) substr($maxno, 12, 5); //MAKA NO URUT SETELAH TANGGAL DI TAMBAH 1 SETELAH NOMOR SEBELUMNYA
        $noUrut++;
        $maxno = 'PB-' . $date . "-" . sprintf("%05s", $noUrut);
    } else { //NO BELI TANGGAL DIDATABASE TIDAK SAMA DENGAN TANGGAL HARI INI
        $maxno = 'PB-' . $date . "-" . '00001'; //MAKA NO URUT SETELAH TANGGAL DI TAMBAH 1
    }
    return $maxno;
}


function totalBeli($nobeli)
{
    global $koneksi;
    $totalBeli  = mysqli_query($koneksi, "SELECT sum(JML_HARGA) AS total FROM tbl_beli_detail WHERE NO_BELI = '$nobeli' ");
    $data = mysqli_fetch_assoc($totalBeli);
    $total = $data["total"];
    return $total;
}


function insert($data)
{
    global $koneksi;

    $no     = mysqli_real_escape_string($koneksi, $data['nobeli']);
    $tgl     = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $kode     = mysqli_real_escape_string($koneksi, $data['kodeBrg']);
    $nama     = mysqli_real_escape_string($koneksi, $data['namaBrg']);
    $qty     = mysqli_real_escape_string($koneksi, $data['qty']);
    $harga     = mysqli_real_escape_string($koneksi, $data['harga']);
    $jmlharga     = mysqli_real_escape_string($koneksi, $data['jmlHarga']);

    $cekbrg     = mysqli_query($koneksi, "SELECT * FROM tbl_beli_detail WHERE NO_BELI = '$no' AND KODE_BRG = '$kode' ");
    if (mysqli_num_rows($cekbrg)) {
        echo "<script>
                alert('Barang Sudah Ada, Anda harus menghapus nya dulu jika ingin mengubah qty nya..');
        </script>";
        return false;
    }

    if (empty($qty)) {
        echo "<script>
                alert('Qty barang tidak boleh kosong');
        </script>";
        return false;
    } else {
        $sqlbeli    = "INSERT INTO tbl_beli_detail VALUES (null,'$no','$tgl','$kode','$nama','$qty','$harga','$jmlharga')";
        mysqli_query($koneksi, $sqlbeli);
    }

    mysqli_query($koneksi, "UPDATE tbl_barang SET STOCK = STOCK + $qty WHERE ID_BARANG = '$kode'");
    return mysqli_affected_rows($koneksi);
}


function insertMenuPilihan($data)
{
    global $koneksi;

    $no         = mysqli_real_escape_string($koneksi, $data['nobeli']);
    $tgl        = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $kode       = mysqli_real_escape_string($koneksi, $_POST['addMenu']);
    // $nama     = mysqli_real_escape_string($koneksi, $data['menunamabarang']);
    // $qty     = mysqli_real_escape_string($koneksi, $data['menuqty']);
    // $harga     = mysqli_real_escape_string($koneksi, $data['menuhargabarang']);
    // $jmlharga     = mysqli_real_escape_string($koneksi, $data['menujmlhhargabarang']);

    // Dapatkan data yang dipilih berdasarkan kode barang
    $sqlBarang      = mysqli_query($koneksi, "SELECT * FROM tbl_barang WHERE ID_BARANG = '$kode'");
    $dataBrg        = mysqli_fetch_assoc($sqlBarang);
    $nama           = $dataBrg['NAMA_BARANG'];
    $qty            = 1;
    $harga          = $dataBrg['HARGA_BARANG'];
    $jmlharga       = $dataBrg['HARGA_BARANG'];


    $cekbrg     = mysqli_query($koneksi, "SELECT * FROM tbl_beli_detail WHERE NO_BELI = '$no' AND KODE_BRG = '$kode' ");
    if (mysqli_num_rows($cekbrg) > 0) {
        $sqlCekbrg    = "UPDATE tbl_beli_detail SET QTY = QTY + 1 WHERE NO_BELI = '$no' AND KODE_BRG = '$kode'";
        mysqli_query($koneksi, $sqlCekbrg);
        $sqlCekbrg    = "UPDATE tbl_beli_detail SET JML_HARGA = JML_HARGA + $harga WHERE NO_BELI = '$no' AND KODE_BRG = '$kode'";
        mysqli_query($koneksi, $sqlCekbrg);
        mysqli_query($koneksi, "UPDATE tbl_barang SET STOCK = STOCK + $qty WHERE ID_BARANG = '$kode'");
        return mysqli_affected_rows($koneksi);
    } else {
        $sqlbeli    = "INSERT INTO tbl_beli_detail VALUES (null,'$no','$tgl','$kode','$nama','$qty','$harga','$jmlharga')";
        mysqli_query($koneksi, $sqlbeli);
        mysqli_query($koneksi, "UPDATE tbl_barang SET STOCK = STOCK + $qty WHERE ID_BARANG = '$kode'");
        return mysqli_affected_rows($koneksi);
    }
}



function delete($idbrg, $idbeli, $qty)
{
    global $koneksi;

    $sqlDel = "DELETE FROM tbl_beli_detail WHERE KODE_BRG = '$idbrg' AND NO_BELI = '$idbeli' ";
    mysqli_query($koneksi, $sqlDel);

    mysqli_query($koneksi, "UPDATE tbl_barang SET STOCK = STOCK - $qty WHERE ID_BARANG = '$idbrg'");
    return mysqli_affected_rows($koneksi);
}


function simpan($data)
{
    global $koneksi;
    $nobeli     = mysqli_real_escape_string($koneksi, $data['nobeli']);
    $tgl        = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $total      = mysqli_real_escape_string($koneksi, $data['total']);
    $supplier   = mysqli_real_escape_string($koneksi, $data['supplier']);
    $keterangan = mysqli_real_escape_string($koneksi, $data['keterangan']);

    $sqlbeli = "INSERT INTO tbl_beli_head VALUES ('$nobeli','$tgl','$supplier',$total,'$keterangan')";

    mysqli_query($koneksi, $sqlbeli);
    return mysqli_affected_rows($koneksi);
}

function update($data)
{
    global $koneksi;
    $Id = mysqli_real_escape_string($koneksi, $data['id']);
    $Nama = strtoupper(mysqli_real_escape_string($koneksi, $data['nama'])); //strtoupper = ketika user mengetik nama maka otomatis diubah huruf besar semua untuk masuk kedatabase
    $Telepon = mysqli_real_escape_string($koneksi, $data['telepon']);
    $Deskripsi = mysqli_real_escape_string($koneksi, $data['keterangan']);


    //cek Nama sekarang
    $QueryNama = mysqli_query($koneksi, "SELECT * FROM tbl_customer WHERE ID_CUSTOMER = $Id");
    $Data = mysqli_fetch_assoc($QueryNama);
    $CurNama = $Data['NAMA'];

    //cek Nama baru
    $NewNama = mysqli_query($koneksi, "SELECT NAMA FROM tbl_customer WHERE NAMA = '$Nama' ");


    if ($Nama !== $CurNama) {
        if (mysqli_num_rows($NewNama)) { //apabila nama nama sudah ada di database maka kita tolak
            echo "<script>
                    alert('Nama sudah terpakai, update data customer gagal !');
                </script>";
            return false;
        }
    }

    mysqli_query($koneksi, "UPDATE tbl_customer SET NAMA = '$Nama', TELEPON = '$Telepon', DESKRIPSI = '$Deskripsi' WHERE ID_CUSTOMER = $Id ");
    return mysqli_affected_rows($koneksi);
}





function updateDaftarBarang($data)
{
    global $koneksi;
    $Nobeli = mysqli_real_escape_string($koneksi, $data['updateNobeli']);
    $Tgl = mysqli_real_escape_string($koneksi, $data['updateTglbeli']);
    $Kodebrg = mysqli_real_escape_string($koneksi, $data['updateKodebrg']);
    $Namabrg = mysqli_real_escape_string($koneksi, $data['updateNamabrg']);
    $DataAsli_Hargabrg = $data['updateHrgbrg'];
    $Hapus_MataUang_Hargabrg = str_replace('.', '', $DataAsli_Hargabrg);
    $Hargabrg = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Hargabrg);

    $DataAsli_Hargabarang = $data['updateHrgbarang'];
    $Hapus_MataUang_Hargabarang = str_replace('.', '', $DataAsli_Hargabarang);
    $Hargabarang = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Hargabarang);

    $DataAsli_Hargapasang = $data['updateHrgpasang'];
    $Hapus_MataUang_Hargapasang = str_replace('.', '', $DataAsli_Hargapasang);
    $Hargapasang = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Hargapasang);

    $DataAsli_Hargamekanik = $data['updateHrgmekanik'];
    $Hapus_MataUang_Hargamekanik = str_replace('.', '', $DataAsli_Hargamekanik);
    $Hargamekanik = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Hargamekanik);

    $DataAsli_Hargatawar = $data['updateHrgtawar'];
    $Hapus_MataUang_Hargatawar = str_replace('.', '', $DataAsli_Hargatawar);
    $Hargatawar = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Hargatawar);


    $Qtysekarang = mysqli_real_escape_string($koneksi, $data['qty']);
    $Qtylama = mysqli_real_escape_string($koneksi, $data['qtylama']);
    $DataAsli_Jumlahhrg = $data['jmlharga'];
    $Hapus_MataUang_Jumlahhrg = str_replace('.', '', $DataAsli_Jumlahhrg);
    $Jumlahhrg = mysqli_real_escape_string($koneksi, $Hapus_MataUang_Jumlahhrg);

    // Bandingkan qty baru dengan qty sebelumnya dan perbarui tbl_barang STOCK sesuai
    if ($Qtysekarang == $Qtylama) {
        // Jika qty baru sama dengan qty sebelumnya, tidak melakukan apa-apa (tidak ada perubahan STOCK).
        mysqli_query($koneksi, "UPDATE tbl_beli_detail SET NAMA_BRG = '$Namabrg', HARGA_BELI = '$Hargabrg',HARGA_BELI = '$Hargabrg',HARGA_BARANG = '$Hargabarang',HARGA_PASANG = '$Hargapasang',HARGA_MEKANIK = '$Hargamekanik',HARGA_TAWWAR = '$Hargatawar' JML_HARGA = '$Jumlahhrg' WHERE NO_BELI = '$Nobeli' AND TGL_BELI = '$Tgl' AND KODE_BRG = '$Kodebrg' ");
        echo "<script>alert('BARANG BERHASIL DI UBAH');</script>";
        return mysqli_affected_rows($koneksi);
    } elseif ($Qtysekarang < $Qtylama) {
        // Jika qty baru lebih kecil dari qty sebelumnya, STOCK berkurang selisihnya.
        $qtyDifference = $Qtylama - $Qtysekarang;
        mysqli_query($koneksi, "UPDATE tbl_barang SET STOCK = STOCK - $qtyDifference,HARGA_BELI = '$Hargabrg',HARGA_BARANG = '$Hargabarang',HARGA_PASANG = '$Hargapasang',HARGA_MEKANIK = '$Hargamekanik',HARGA_TAWAR = '$Hargatawar' WHERE ID_BARANG = '$Kodebrg'");
        mysqli_query($koneksi, "UPDATE tbl_beli_detail SET NAMA_BRG = '$Namabrg', HARGA_BELI = '$Hargabrg', QTY = '$Qtysekarang', JML_HARGA = '$Jumlahhrg' WHERE NO_BELI = '$Nobeli' AND TGL_BELI = '$Tgl' AND KODE_BRG = '$Kodebrg' ");
        echo "<script>alert('BARANG BERHASIL DI UBAH');</script>";
        return mysqli_affected_rows($koneksi);
    } elseif ($Qtysekarang > $Qtylama) {
        // Jika qty baru lebih besar dari qty sebelumnya, tambah STOCK dengan selisihnya.
        $qtyDifference = $Qtysekarang - $Qtylama;
        mysqli_query($koneksi, "UPDATE tbl_barang SET STOCK = STOCK + $qtyDifference,HARGA_BELI = '$Hargabrg',HARGA_BARANG = '$Hargabarang',HARGA_PASANG = '$Hargapasang',HARGA_MEKANIK = '$Hargamekanik',HARGA_TAWAR = '$Hargatawar' WHERE ID_BARANG = '$Kodebrg'");
        mysqli_query($koneksi, "UPDATE tbl_beli_detail SET NAMA_BRG = '$Namabrg', HARGA_BELI = '$Hargabrg', QTY = '$Qtysekarang', JML_HARGA = '$Jumlahhrg' WHERE NO_BELI = '$Nobeli' AND TGL_BELI = '$Tgl' AND KODE_BRG = '$Kodebrg' ");
        echo "<script>alert('BARANG BERHASIL DI UBAH');</script>";
        return mysqli_affected_rows($koneksi);
    }

    echo "<script>alert('GAGAL MENGUBAH BARANG');</script>";
    return false;
}
