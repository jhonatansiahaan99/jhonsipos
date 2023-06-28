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
    } else {
        $sqljual    = "INSERT INTO tbl_jual_detail VALUES (null,'$no','$tgl','$kode','$nama','$qty','$harga','$jmlharga')";
        mysqli_query($koneksi, $sqljual);
    }

    mysqli_query($koneksi, "UPDATE tbl_barang SET STOCK = STOCK - $qty WHERE BARCODE = '$kode'");
    return mysqli_affected_rows($koneksi);
}


function delete($barcode, $idjual, $qty)
{
    global $koneksi;

    $sqlDel = "DELETE FROM tbl_jual_detail WHERE BARCODE = '$barcode' AND NO_JUAL = '$idjual' ";
    mysqli_query($koneksi, $sqlDel);

    mysqli_query($koneksi, "UPDATE tbl_barang SET STOCK = STOCK + $qty WHERE BARCODE = '$barcode'");
    return mysqli_affected_rows($koneksi);
}



function simpan($data)
{
    global $koneksi;
    $nojual     = mysqli_real_escape_string($koneksi, $data['nojual']);
    $tgl        = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $total      = mysqli_real_escape_string($koneksi, $data['total']);
    $customer   = mysqli_real_escape_string($koneksi, $data['customer']);
    $keterangan = mysqli_real_escape_string($koneksi, $data['keterangan']);
    $bayar      = mysqli_real_escape_string($koneksi, $data['bayar']);
    $kembalian      = mysqli_real_escape_string($koneksi, $data['kembalian']);

    $sqljual = "INSERT INTO tbl_jual_head VALUES ('$nojual','$tgl','$customer',$total,'$keterangan','$bayar','$kembalian')";

    mysqli_query($koneksi, $sqljual);
    return mysqli_affected_rows($koneksi);
}
