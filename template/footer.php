</div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<!-- Main Footer -->
<footer class="main-footer">
    <strong>Copyright &copy; 2023 <span class="text-info">Jhonsi</span> </strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0
    </div>
</footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->



<!-- masking untuk angka rupiah -->
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/jquery/jquery.mask.min.js"></script>

<!-- Bootstrap -->
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<!-- AdminLTE -->
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/dist/js/adminlte.js"></script>
<!-- OPTIONAL SCRIPTS -->
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/chart.js/Chart.min.js"></script>
<!-- Select2 -->
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/select2/js/select2.full.min.js"></script>
<!-- SweetAlert -->
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/sweetalert/sweetalert.min.js"></script>


<script>
    //datatable
    $(function() {
        $('#tblData').DataTable();

        //Initialize Select2 Elements
        $('.select2').select2();

        //Awal Masking rupiah
        $('.maskingrupiahhargabelitambah').mask('000.000.000.000', {
            reverse: true
        });
        $('.maskingrupiahhargabarangtambah').mask('000.000.000.000', {
            reverse: true
        });
        $('.maskingrupiahhargapasangtambah').mask('000.000.000.000', {
            reverse: true
        });
        $('.maskingrupiahhargatawartambah').mask('000.000.000.000', {
            reverse: true
        });
        $('.maskingrupiahhargamekaniktambah').mask('000.000.000.000', {
            reverse: true
        });
        //Akhir Masking rupiah

        // Awal peringatan Tombol Hapus
        $('.tombol-hapus').on('click', function(e) {
            e.preventDefault(); // fungsi e.preventDefault() adalah aksi default nya di hentikan dan tidak akan jalan ke href nya yang di tuju.
            const href_hapus = $(this).attr('href'); //untuk mengambil href yang di tombol hapus

            swal({
                title: 'Apakah anda Yakin?',
                text: "Data ini akan di Hapus",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus Data!'
            }, function(result) {
                if (result) {
                    document.location.href = href_hapus;
                }
            })

        });
        // Akhir peringatan Tombol Hapus


    });
</script>
</body>

</html>