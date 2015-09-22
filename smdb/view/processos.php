<?php include 'principalInicio.php' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Processos em execução
            <small></small>
        </h1>
            <!-- Main content -->
        <section class="content">
          <div class="box">
              <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Rendering engine</th>
                      <th>Browser</th>
                      <th>Platform(s)</th>
                      <th>Engine version</th>
                      <th>CSS grade</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Trident</td>
                      <td>Internet
                        Explorer 4.0</td>
                      <td>Win 95+</td>
                      <td> 4</td>
                      <td>X</td>
                    </tr>

                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Rendering engine</th>
                      <th>Browser</th>
                      <th>Platform(s)</th>
                      <th>Engine version</th>
                      <th>CSS grade</th>
                    </tr>
                  </tfoot>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
        </section><!-- /.content -->


        <!-- jQuery 2.1.4 -->
        <script src="../../html/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="../../html/bootstrap/js/bootstrap.min.js"></script>
        <!-- DataTables -->
        <script src="../../html/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="../../html/plugins/datatables/dataTables.bootstrap.min.js"></script>
        <!-- SlimScroll -->
        <script src="../../html/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="../../html/plugins/fastclick/fastclick.min.js"></script>
        <!-- AdminLTE App -->
        <script src="../../html/dist/js/app.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../../html/dist/js/demo.js"></script>
        <!-- page script -->
        <script>
            $(function () {
                $('#example2').DataTable({
                    "paging": false,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": true
                });
            });
        </script>

<?php include 'principalFim.php' ?>