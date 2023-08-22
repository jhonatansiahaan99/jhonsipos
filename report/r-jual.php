<?php

session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
$tgl1       = $_GET['tgl1'];
$tgl2       = $_GET['tgl2'];

$dataJual   = getData("SELECT * FROM tbl_jual_head WHERE TGL_JUAL BETWEEN '$tgl1' AND '$tgl2' ");
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
</head>

<body>
    <div style="text-align: center;">
        <h2 style="margin-bottom: -15px;">Rekap Laporan Penjualan</h2>
        <h2 style="margin-bottom: 15px;">Bengkel Jhonsi Motor</h2>
    </div>


    <table>
        <thead>
            <tr>
                <td colspan="5" style="height: 5px;">
                    <hr style="margin-bottom: 2px; margin-left: -5px;" , size="3" , color="grey">
                </td>
            </tr>
            <tr>
                <th>No</th>
                <th style="width: 120px;">Tgl Penjualan </th>
                <th style="width: 120px;">No Penjualan </th>
                <th style="width: 120px;">Customer </th>
                <th>Jumlah Harga</th>
            </tr>
            <tr>
                <td colspan="5" style="height: 5px;">
                    <hr style="margin-bottom: 2px; margin-left: -5px; margin-top: 1px;" , size="3" , color="grey">
                </td>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($dataJual as $data) {
                $total += $data['TOTAL'];
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td align="center"><?= in_date($data['TGL_JUAL']) ?></td>
                    <!-- fungsi in_date itu fungsi yang kita buat di functions -->
                    <td align="center"><?= $data['NO_JUAL'] ?></td>
                    <td align="center"><?= $data['CUSTOMER'] ?></td>
                    <td align="right"><?= number_format($data['TOTAL'], 0, ',', '.') ?></td>
                </tr>
            <?php

            }
            ?>
            <tr>
                <td colspan="5" style="height: 5px;">
                    <hr style="margin-bottom: 2px; margin-left: -5px;" , size="3" , color="grey">
                </td>
            </tr>
            <tr>
                <td colspan="5" align="right"><b>Total Rp <?= number_format($total, 0, ',', '.') ?></b></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="height: 5px;">
                    <hr style="margin-bottom: 2px; margin-left: -5px; margin-top: 1px;" , size="3" , color="grey">
                </td>

            </tr>
        </tfoot>
    </table>


    <script>
        window.print();
    </script>

</body>

</html>