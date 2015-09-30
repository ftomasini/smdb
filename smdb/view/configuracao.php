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
                <th>Data coleta</th>
                <th>Configuração</th>
                <th>Valor</th>
                <th>Valor padrão</th>
                <th>Descrição</th>
              </tr>
              </thead>
              <tbody>

              <?php foreach ($configuracoes as $configuracao): ?>
                <tr>
                    <td><font size="2" color="black"><?php print htmlentities($configuracao->data_coleta_formatada); ?></font></td>
                    <td><font size="2" color="#191970"><?php print htmlentities($configuracao->name); ?></font></td>
                    <td><font size="2" color="#ff4500"><?php print htmlentities($configuracao->valor); ?></font></td>
                    <td><font size="2" color="green"><?php print htmlentities($configuracao->valor_original); ?></font></td>
                    <td><font size="2" color="#8b4513"><?php print htmlentities($configuracao->descricao_resumida); ?></font></td>
                </tr>
            <?php endforeach; ?>



              </tbody>


              <tfoot>
              <th>Data coleta</th>
              <th>Configuração</th>
              <th>Valor</th>
              <th>Valor padrão</th>
              <th>Descrição</th>
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
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": true
          });
        });
      </script>
<?php include 'principalFim.php' ?>


      <!--<td><a href="index.php?op=edit&id=<?php print $contact->id; ?>">edit</a></td>
+                    <td><a href="index.php?op=delete&id=<?php print $contact->id; ?>">delete</a></td>
+                    <td><a href="index.php?op=show&id=<?php print $contact->id; ?>"><?php print htmlentities($contact->name); ?></a></td> -->
