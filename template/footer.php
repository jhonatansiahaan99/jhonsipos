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


<!-- FLOT CHARTS -->
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/flot/jquery.flot.js"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="<?= $main_url ?>asset/AdminLTE-3.2.0/plugins/flot/plugins/jquery.flot.resize.js"></script>


<script type="text/javascript">
    //datatable
    $(function() {

        let tema = sessionStorage.getItem('tema'); // ngambil key di sessionStorage
        if (tema) { // apabila ada
            $('body').addClass(tema); //jquer carikan elemen body, tambahkan class nya tema(dark-mode)
            $('#cekDark').prop('checked', true);
        }

        $(document).on('click', "#cekDark", function() { //ketika elemen id cekDark, menjalankan fungsi dibawah ini
            if ($('#cekDark').is(':checked')) { //cari elemen id cekDark itu di checked 
                $('body').addClass('dark-mode'); //maka cari elemen body yang class nya dark mode
                sessionStorage.setItem('tema', 'dark-mode');
            } else {
                $('body').removeClass('dark-mode'); //jika di uncek maka menghapus class dark mode
                sessionStorage.removeItem('tema');
            }
        })


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


<script type="text/javascript">
    <?php
    // Bagian kode PHP untuk mengambil data penjualan dan menghitung total penjualan per bulan
    $jual = getData("SELECT * FROM tbl_jual_head");

    $totalPerBulan = array_fill(1, 12, 0); // Membuat array untuk menyimpan total penjualan per bulan

    foreach ($jual as $penjualan) {
        $tglJual = $penjualan['TGL_JUAL'];
        $bulan = date('n', strtotime($tglJual)); // Mendapatkan angka bulan (1-12) dari tanggal penjualan
        $totalPerBulan[$bulan] += $penjualan['TOTAL']; // Menambahkan total penjualan ke array berdasarkan bulan
    }
    ?>


    var data = [];
    <?php
    for ($bulan = 1; $bulan <= 12; $bulan++) {
        echo "data.push([$bulan, " . $totalPerBulan[$bulan] . "]);"; // Membuat array data untuk grafik
    }
    ?>

    var bar_data = {
        data: data, // Menggunakan data yang telah dibuat di atas
        bars: {
            show: true
        }
    };

    $.plot('#bar-chart', [bar_data], {
        grid: {
            borderWidth: 1,
            borderColor: '#f3f3f3',
            tickColor: '#f3f3f3'
        },
        series: {
            bars: {
                show: true,
                barWidth: 0.5,
                align: 'center',
            },
        },
        colors: ['#3c8dbc'],
        xaxis: {
            ticks: [
                // Daftar bulan yang akan ditampilkan di sumbu X
                [1, 'January'],
                [2, 'February'],
                [3, 'March'],
                [4, 'April'],
                [5, 'May'],
                [6, 'June'],
                [7, 'July'],
                [8, 'August'],
                [9, 'September'],
                [10, 'October'],
                [11, 'November'],
                [12, 'December']
            ]
        }
    });
</script>

</body>

</html>