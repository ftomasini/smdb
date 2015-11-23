<?php include 'principalInicio.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        Alarmísticas
        <small></small>
    </h1>
    <!-- Main content -->
    <section class="content">

    <!-- general form elements -->
    <div class="box box-primary">

        <?php


        ?>
        <div class=" form">
            <form class="cmxform form-horizontal " method="POST" action="handlerUsuario.php?op=new">

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Melhor horário para manutenção</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            <!--<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                     -->
                        </div>
                    </div>
                    <div class="box-body">
                    De acordo com as informações coletadas no dia (<?php echo $alarmistica->data_coleta_formatada; ?>) o melhor horário para efetuar alguma manutenção é <?php echo $alarmistica->hora_coleta_formatada; ?>
                    Load avarege: <?php echo $alarmistica->load_ultimo_minuto; ?>
                    </div><!-- /.box-body -->
                    <div class="box-footer">

                    </div><!-- /.box-footer-->
                </div><!-- /.box -->

            </form>
        </div>
    </div>


<?php include 'principalFim.php' ?>