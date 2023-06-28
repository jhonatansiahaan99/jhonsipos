<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title  ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/dist/css/adminlte.min.css">
  <!-- jQuery -->
  <script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- SweetAlert2 -->
  <script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/sweetalert2/sweetalert2.min.js"></script>
  <!-- Kenapa jquery, sweetalert2.js dan sweetalert2.css dibuat diatas karna biar duluan eksekusi karna kalo dibawa dia gak akan mmuncul, dan harus diatas datable juga biar duluan di eksekusi jquery dari pada datable -->
  <!-- Icon  -->
  <link rel="shortcut icon" href="<?= $main_url ?>asset/image/icon_pos.png" type="image/x-icon">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Sweetalert -->
  <link href="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/sweetalert/sweetalert.css" rel="stylesheet" />

</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->