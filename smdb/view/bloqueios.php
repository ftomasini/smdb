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
                <a href="handlerProcessos.php?op=show" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span></a>
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

                  <b>Modos de bloqueio</b>
                  <br>
                  <br>
                  ACCESS SHARE<br>
                  O comando SELECT obtém um bloqueio neste modo nas tabelas referenciadas. Em geral, qualquer comando que apenas lê a tabela e não modificá-la obtém este modo de bloqueio.
                  <br>
                  <br>
                  ROW SHARE<br>
                  O comando UPDATE, DELETE, e INSERT adquirir este modo de bloqueio na tabela de destino (além de ACCESS SHARE bloqueios em outras tabelas referenciadas). Em geral, este modo de bloqueio é obtido por qualquer comando que modifica os dados em uma tabela.
                  <br>
                  <br>
                  ROW EXCLUSIVE<br>
                  Conflicts with the SHARE, SHARE ROW EXCLUSIVE, EXCLUSIVE, and ACCESS EXCLUSIVE lock modes.

                  The commands UPDATE, DELETE, and INSERT acquire this lock mode on the target table (in addition to ACCESS SHARE locks on any other referenced tables). In general, this lock mode will be acquired by any command that modifies data in a table.
                  <br>
                  <br>
                  SHARE UPDATE EXCLUSIVE<br>
                  Conflicts with the SHARE UPDATE EXCLUSIVE, SHARE, SHARE ROW EXCLUSIVE, EXCLUSIVE, and ACCESS EXCLUSIVE lock modes. This mode protects a table against concurrent schema changes and VACUUM runs.

                  Acquired by VACUUM (without FULL), ANALYZE, CREATE INDEX CONCURRENTLY, and some forms of ALTER TABLE.
                  <br>
                  <br>
                  SHARE<br>
                  Conflicts with the ROW EXCLUSIVE, SHARE UPDATE EXCLUSIVE, SHARE ROW EXCLUSIVE, EXCLUSIVE, and ACCESS EXCLUSIVE lock modes. This mode protects a table against concurrent data changes.

                  Acquired by CREATE INDEX (without CONCURRENTLY).
                  <br>
                  <br>
                  SHARE ROW EXCLUSIVE<br>
                  Conflicts with the ROW EXCLUSIVE, SHARE UPDATE EXCLUSIVE, SHARE, SHARE ROW EXCLUSIVE, EXCLUSIVE, and ACCESS EXCLUSIVE lock modes. This mode protects a table against concurrent data changes, and is self-exclusive so that only one session can hold it at a time.

                  This lock mode is not automatically acquired by any PostgreSQL command.
                  <br>
                  <br>
                  EXCLUSIVE<br>
                  Conflicts with the ROW SHARE, ROW EXCLUSIVE, SHARE UPDATE EXCLUSIVE, SHARE, SHARE ROW EXCLUSIVE, EXCLUSIVE, and ACCESS EXCLUSIVE lock modes. This mode allows only concurrent ACCESS SHARE locks, i.e., only reads from the table can proceed in parallel with a transaction holding this lock mode.

                  This lock mode is not automatically acquired on tables by any PostgreSQL command.
                  <br>
                  <br>
                  ACCESS EXCLUSIVE<br>
                  Conflicts with locks of all modes (ACCESS SHARE, ROW SHARE, ROW EXCLUSIVE, SHARE UPDATE EXCLUSIVE, SHARE, SHARE ROW EXCLUSIVE, EXCLUSIVE, and ACCESS EXCLUSIVE). This mode guarantees that the holder is the only transaction accessing the table in any way.



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