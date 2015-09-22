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
        if (isset($_GET['m']))
        {
            $this->showSuccess('titulo', "Alerta excluido com sucesso!");
        }
        ?>
        <div class=" form">
            <form class="cmxform form-horizontal " method="POST" action="handlerUsuario.php?op=new">

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Title</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        Start creating your amazing application!
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        Footer
                    </div><!-- /.box-footer-->
                </div><!-- /.box -->


                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Title</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        Start creating your amazing application!
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        Footer
                    </div><!-- /.box-footer-->
                </div><!-- /.box -->

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Title</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        Start creating your amazing application!
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        Footer
                    </div><!-- /.box-footer-->
                </div><!-- /.box -->


            </form>
        </div>
    </div>


<?php include 'principalFim.php' ?>