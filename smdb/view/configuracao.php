<?php include 'principalInicio.php' ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Configurações do SGBD
        <small></small>
      </h1>
      <!-- Main content -->
      <section class="content">
        <div class="box">
          <div class="box-header">
          </div><!-- /.box-header -->
          <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Configuração</th>
                <th>Valor</th>
                <th>Descrição da configuração</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td>Trident</td>
                <td>Internet
                  Explorer 4.0</td>
                <td>Win 95+</td>
              </tr>

              </tbody>
              <tfoot>
              <tr>
                <th>Configuração</th>
                <th>Valor</th>
                <th>Descrição da configuração</th>
              </tr>
              </tfoot>
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </section><!-- /.content -->


      <!-- jQuery 2.1.4 -->

      <script src="../../html/plugins/datatables/jquery.dataTables.min.js"></script>
      <script src="../../html/plugins/datatables/dataTables.bootstrap.min.js"></script>
      <!-- SlimScroll -->


      <script>
        $(function () {
          $('#example1').DataTable({
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