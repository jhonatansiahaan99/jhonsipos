<?php

session_start();
if (!isset($_SESSION["ssLoginPOS"])) { //jika user coba masuk melalui url dan tidak login maka kita tolak
    header("location: ../auth/login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode</title>
</head>

<body>
    <?php
    $jmlCetak = $_GET['jmlCetak']; //diambil dari index.php di folder barang. windows open javascript
    for ($i = 1; $i <= $jmlCetak; $i++) { ?>
        <div style="text-align:center; width:150px; float:left; margin-right:20px; margin-bottom:30px;">
            <?php
            $barcode = $_GET['barcode']; //diambil dari index.php di folder barang. windows open javascript

            require '../asset/barcodeGenerator/vendor/autoload.php'; //panggil barcode di barcodeGenerator

            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
            echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($barcode, $generator::TYPE_CODE_128)) . '"width="150px">';

            ?>
            <div><?= $barcode  ?> </div>
        </div>
    <?php
    }
    ?>

    <script>
        window.print();
    </script>


</body>

</html>