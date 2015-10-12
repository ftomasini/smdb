<?php include 'principalInicio.php' ?>
<meta http-equiv="refresh" content="30">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Bloqueios
            <small></small>
        </h1>

        <!-- Main content -->
        <section class="content">
                <a href="handlerBloqueios.php?op=show" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span></a>
          <div class="box">




              <div class="box-header">
              </div><!-- /.box-header -->
              <div class="box-body table-responsive">

                  <table id="example2" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                          <th>Data coleta</th>
                          <!--<th>Base de dados</th>-->
                          <th>Usuário</th>
                          <th>Código processo</th>
                          <!--<th>Prioridade processo</th>-->
                          <th>Memória utilizada</th>
                          <th>Estado</th>
                          <th>Modo</th>
                          <th>Consulta</th>
                          <!--<th>Início execução</th>-->
                          <th>Tempo de execução</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($bloqueios as $bloqueio): ?>
                          <tr>
                              <td><font size="2" color="black"><?php print htmlentities($bloqueio->data_coleta_formatada); ?></font></td>
                              <!--<td><font size="2" color="#191970"><?php print htmlentities($bloqueio->datname); ?></font></td>-->
                              <td><font size="2" color="#ff4500"><?php print htmlentities($bloqueio->usename); ?></font></td>
                              <td><font size="2" color="green"><?php print htmlentities($bloqueio->pid); ?></font></td>
                              <!--<td><font size="2" color="#8b4513"><?php print htmlentities($bloqueio->priority); ?></font></td>-->
                              <td><font size="2" color="#8b4513"><?php print htmlentities($bloqueio->memoria); ?></font></td>
                              <td>
                                  <font size="2" color="#8b4513">
                                      <?php

                                            if ($bloqueio->state == 'R')
                                            {?><span class="label label-success">
                                                <?php print htmlentities("R - executável");?>
                                                </span><?php
                                            }
                                            elseif ($bloqueio->state == 'D')
                                            {?><span class="label label-primary">
                                                <?php print htmlentities("D - em espera no disco");?>
                                                </span><?php
                                            }
                                            elseif ($bloqueio->state == 'S')
                                            {?><span class="label label-warning">
                                                <?php print htmlentities("S - Suspenso");?>
                                                </span><?php
                                            }
                                            elseif ($bloqueio->state == 'T')
                                            {?><span class="label label-danger">
                                                <?php print htmlentities("T - interrompido");?>
                                                </span><?php
                                            }
                                            elseif ($bloqueio->state == 'Z')
                                            {?><span class="label label-danger">
                                                <?php print htmlentities("Z - Zumbi");?>
                                                </span><?php
                                            }
                                            else
                                            {?><span class="label label-danger">
                                                <?php print htmlentities($bloqueio->state);?>
                                                </span><?php
                                            }
                                      ?>
                                  </font></td>
                              <td><font size="2" color="#8b4513"><?php print htmlentities($bloqueio->mode); ?></font></td>
                              <td><font size="2" color="#8b4513"><?php print htmlentities($bloqueio->query); ?></font></td>
                              <!--<td><font size="2" color="#8b4513"><?php print htmlentities($bloqueio->inicio_processo); ?></font></td>-->
                              <td><font size="2" color="#8b4513"><?php print htmlentities($bloqueio->tempo_execussao); ?></font></td>

                          </tr>
                      <?php endforeach; ?>
                      </tbody>
                  </table>



              </div><!-- /.box-body -->

          </div><!-- /.box -->
        </section><!-- /.content -->


        <!-- jQuery 2.1.4 -->

        <!-- DataTables -->
        <script src="../../html/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="../../html/plugins/datatables/dataTables.bootstrap.min.js"></script>
        <!-- SlimScroll -->


        <script>
            $(function () {
                $('#example2').DataTable({
                    "paging": false,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": false,
                    "columns": [
                        { "width": "5%" },
                        { "width": "5%" },
                        { "width": "5%" },
                        { "width": "5%" },
                        { "width": "5%" },
                        { "width": "5%" },
                        { "width": "30%" },
                        { "width": "5%" }
                    ]
                });
            });
        </script><!-- page script-->






<?php include 'principalFim.php' ?>

        <!--

                <tr>
                    <td>183</td>
                    <td>John Doe</td>
                    <td>11-7-2014</td>
                    <td><span class="label label-success">Approved</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                </tr>
                <tr>
                    <td>219</td>
                    <td>Alexander Pierce</td>
                    <td>11-7-2014</td>
                    <td><span class="label label-warning">Pending</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                </tr>
                <tr>
                    <td>657</td>
                    <td>Bob Doe</td>
                    <td>11-7-2014</td>
                    <td><span class="label label-primary">Approved</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                </tr>
                <tr>
                    <td>175</td>
                    <td>Mike Doe</td>
                    <td>11-7-2014</td>
                    <td><span class="label label-danger">Denied</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                </tr>
                -->