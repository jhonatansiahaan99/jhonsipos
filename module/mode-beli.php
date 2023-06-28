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
