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



              <div class="box">
                  <div class="box-header">
                  </div><!-- /.box-header -->
                  <div class="box-body">

                      <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                      <script type="text/javascript">
                          google.load("visualization", "1", {packages:["gauge"]});
                          google.setOnLoadCallback(drawChart);
                          function drawChart() {

                              var data = google.visualization.arrayToDataTable([
                                  ['Label', 'Value'],
                                  ['Memory', 80],
                                  ['CPU', 55],
                                  ['Network', 68]
                              ]);

                              var options = {
                                  width: 400, height: 120,
                                  redFrom: 90, redTo: 100,
                                  yellowFrom:75, yellowTo: 90,
                                  minorTicks: 5
                              };

                              var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

                              chart.draw(data, options);

                          }
                      </script>
                      <div id="chart_div"></div>
                  </div>
              </div>




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
                          <th>Consulta</th>
                          <!--<th>Início execução</th>-->
                          <th>Tempo de execução</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($processos as $processo): ?>
                          <tr>
                              <td><font size="2" color="black"><?php print htmlentities($processo->data_coleta_formatada); ?></font></td>
                              <!--<td><font size="2" color="#191970"><?php print htmlentities($processo->datname); ?></font></td>-->
                              <td><font size="2" color="#ff4500"><?php print htmlentities($processo->usename); ?></font></td>
                              <td><font size="2" color="green"><?php print htmlentities($processo->pid); ?></font></td>
                              <!--<td><font size="2" color="#8b4513"><?php print htmlentities($processo->priority); ?></font></td>-->
                              <td><font size="2" color="#8b4513"><?php print htmlentities($processo->memoria); ?></font></td>
                              <td>
                                  <font size="2" color="#8b4513">
                                      <?php

                                            if ($processo->state == 'R')
                                            {?><span class="label label-success">
                                                <?php print htmlentities("R - executável");?>
                                                </span><?php
                                            }
                                            elseif ($processo->state == 'D')
                                            {?><span class="label label-primary">
                                                <?php print htmlentities("D - em espera no disco");?>
                                                </span><?php
                                            }
                                            elseif ($processo->state == 'S')
                                            {?><span class="label label-warning">
                                                <?php print htmlentities("S - Suspenso");?>
                                                </span><?php
                                            }
                                            elseif ($processo->state == 'T')
                                            {?><span class="label label-danger">
                                                <?php print htmlentities("T - interrompido");?>
                                                </span><?php
                                            }
                                            elseif ($processo->state == 'Z')
                                            {?><span class="label label-danger">
                                                <?php print htmlentities("Z - Zumbi");?>
                                                </span><?php
                                            }
                                            else
                                            {?><span class="label label-danger">
                                                <?php print htmlentities($processo->state);?>
                                                </span><?php
                                            }
                                      ?>
                                  </font></td>
                              <td><font size="2" color="#8b4513"><?php print htmlentities($processo->query); ?></font></td>
                              <!--<td><font size="2" color="#8b4513"><?php print htmlentities($processo->inicio_processo); ?></font></td>-->
                              <td><font size="2" color="#8b4513"><?php print htmlentities($processo->tempo_execussao); ?></font></td>

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