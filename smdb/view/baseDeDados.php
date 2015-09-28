<?php include 'principalInicio.php' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Estatísticas
            <small>Base de dados</small>
        </h1>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                    <!-- AREA CHART -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Area Chart</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>

                        <div class="box-body chart-responsive">


                            <?php
                            //require_once '../../core/Autoload.php';

                            function tipo1()
                            {
                            ?>
                            <div id="my-charts1"></div>
                            <?php
                            $morris = new MorrisLineCharts( 'my-charts1' );
                            $morris->xkey = array( 'date' );
                            $morris->resize = true;
                            $morris->hideHover = 'auto';
                            $morris->ykeys = array( 'value' );
                            $morris->labels = array( 'Money' );
                            $morris->data = array(
                                array( 'date' => '2010', 'value' => 88 ),
                                array( 'date' => '2011', 'value' => 18 ),
                                array( 'date' => '2012', 'value' => 28 ),
                                array( 'date' => '2013', 'value' => 48 ),
                            );
                            echo $morris->toJavascript();
                            }
                            tipo1();
                            ?>
                            <!--
                            <div class="chart" id="revenue-chart" style="height: 300px;"></div> -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <!-- DONUT CHART -->
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Donut Chart</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body chart-responsive">


                            <?php
                            //require_once '../../core/Autoload.php';

                            function tipo2()
                            {
                                ?>
                                <div id="my-charts2"></div>
                                <?php
                                $morris = new MorrisDonutCharts( 'my-charts2' );
                                $morris->resize = true;
                                $morris->data = array(
                                    array( 'label' => '2010', 'value' => 88 ),
                                    array( 'label' => '2011', 'value' => 18 ),
                                    array( 'label' => '2012', 'value' => 28 ),
                                    array( 'label' => '2013', 'value' => 48 ),
                                );
                                echo $morris->toJavascript();
                            }
                            tipo2();
                            ?>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <!-- LINE CHART -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Line Chart</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body chart-responsive">
                            <div class="chart" id="line-chart" style="height: 300px;"></div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <!-- BAR CHART -->
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Bar Chart</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body chart-responsive">
                            <div class="chart" id="bar-chart" style="height: 300px;"></div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

            </div><!-- /.row -->





<?php include 'principalFim.php' ?>
