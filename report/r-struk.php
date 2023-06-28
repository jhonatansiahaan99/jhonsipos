<?php
session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";

$nota = $_GET['nota'];
$dataJual = getData("SELECT * FROM tbl_jual_head WHERE NO_JUAL = '$nota' ")[0];
$itemJual = getData("SELECT * FROM tbl_jual_detail WHERE NO_JUAL = '$nota' ");
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Belanja</title>
</head>

<body>

    <table style="border-bottom: solid 2px; text-align: center; font-size: 14px; width: 240px">
        <tr>
            <td><b>Bengkel Jhonsi Motor</b></td>
        </tr>
        <tr>
            <td><?= 'No Nota : ' . $nota ?></td>
        </tr>
        <tr>
            <td><?= date('d-m-Y H:i:s') ?></td>
        </tr>
        <tr>
            <td><?= userLogin()['USERNAME'] ?></td>
        </tr>
    </table>

    <table style="border-bottom: dotted 2px; font-size: 14px; width: 240px">
        <?php
        foreach ($itemJual as $item) {
        ?>

            <tr>
                <td colspan="6"><?= $item['NAMA_BRG'] ?></td>
            </tr>
            <tr>
                <td colspan="2" style="width: 70px;">Qty : </td>
                <td style="width: 10px; text-align: right;"><?= $item['QTY'] ?></td>
                <td style="width: 70px; text-align: right;">x <?= number_format($item['HARGA_JUAL'], 0, ',', '.') ?></td>
                <td style="width: 70px; text-align: right;" colspan="2"><?= number_format($item['JML_HARGA'], 0, ',', '.') ?></td>
            </tr>

        <?php
        }
        ?>
    </table>

    <table style="border-bottom: dotted 2px; font-size: 14px; width: 240px">
        <tr>
            <td colspan="3" style="width: 100px;"></td>
            <td style="width: 50px; text-align: right">Total</td>
            <td style="width: 70px; text-align: right" colspan="2"><b><?= number_format($dataJual['TOTAL'], 0, ',', '.') ?></b></td>
        </tr>
        <tr>
            <td colspan="3" style="width: 100px;"></td>
            <td style="width: 50px; text-align: right">Bayar</td>
            <td style="width: 70px; text-align: right" colspan="2"><b><?= number_format($dataJual['JML_BAYAR'], 0, ',', '.') ?></b></td>
        </tr>
    </table>

    <table style="border-bottom: solid 2px; font-size: 14px; width: 240px">
        <tr>
            <td colspan="3" style="width: 100px;"></td>
            <td style="width: 50px; text-align: right">Kembalian</td>
            <td style="width: 70px; text-align: right" colspan="2"><b><?= number_format($dataJual['KEMBALIAN'], 0, ',', '.') ?></b></td>
        </tr>
    </table>

    <table style="text-align: center; margin-top: 5px; font-size: 14px; width: 240px">
        <tr>
            <td>Terima kasih sudah berbelanja</td>
        </tr>
    </table>

    <script>
        setTimeout(function() { //setelah 5 detik baru tampil halaman cetak struk belanja nya
            window.print();
        }, 1000);
    </script>

</body>

</html>