
<?php

// Require: use your own path
require_once '../../core/morris/morris.php';
require_once '../../core/morris/morris-charts.php';

// Optional: include chart line
require_once '../../core/morris/morris-line-charts.php';
require_once '../../core/morris/morris-donut-charts.php';
require_once '../../core/morris/morris-bar-charts.php';
require_once '../../core/morris/morris-area-charts.php';

?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <link rel="shortcut icon" href="../../html/images/favicon.ico">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SMBD - Sistema de monitoramento de base de dados</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../../html/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../html/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../../html/plugins/datatables/dataTables.bootstrap.css">

    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="../../html/dist/css/skins/skin-blue.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- jQuery 2.1.4 -->
    <script src="../../html/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../../html/bootstrap/js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../html/dist/js/app.min.js"></script>
    <script src="../../html/plugins/morris/morris.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>


    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <!-- MORRIS
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
-->
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>


</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="handlerPainel.php?op=show" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini glyphicon glyphicon-cloud"></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg glyphicon glyphicon-cloud">SM<b>BD</b></span>
        </a>
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->

                    <!-- Notifications Menu
                    <li class="dropdown notifications-menu">
                        <!-- Menu toggle button
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">10</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 10 notifications</li>
                            <li>
                                <!-- Inner Menu: contains the notifications
                                <ul class="menu">
                                    <li><!-- start notification
                                        <a href="#">
                                            <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                        </a>
                                    </li><!-- end notification
                                </ul>
                            </li>
                            <li class="footer"><a href="#">View all</a></li>
                        </ul>
                    </li> -->
                    <!-- Tasks Menu -->
                    <!-- User Account Menu -->
                    <li class="dropdown">

                            <a href="handlerUsuario.php?op=show" class="glyphicon glyphicon-user" > Perfil </a>

                    </li>
                    <li class="dropdown">
                        <a href="logout.php" class="glyphicon glyphicon-off"> Sair </a>
                    </li>

                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">MENU</li>
                <li><a href="handlerPainel.php?op=show"><i class="fa fa-dashboard"></i> <span>Painel principal</span></a></li>
                <li><a href="handlerConfiguracaoSGBD.php?op=show"><i class="fa fa-edit"></i> <span>Configurações do SGBD</span></a></li>



                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-pie-chart"></i>
                        <span>Estatísticas</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="handlerBaseDeDados.php?op=show"><i class="fa fa-circle-o"></i> Base de dados</a></li>
                        <li><a href="handlerTabelaBaseDeDados.php?op=show"><i class="fa fa-circle-o"></i> Tabela da base de dados</a></li>
                    </ul>
                </li>
                <li>
                    <a href="handlerAlarmistica.php?op=show">
                        <i class="fa fa-envelope"></i> <span>Alarmísticas</span>
                        <small class="label pull-right bg-yellow">1</small>
                    </a>
                </li>
                <li><a href="handlerProcessos.php?op=show"><i class="fa fa-laptop"></i> <span>Processos em execução</span></a></li>
                <li><a href="handlerBloqueios.php?op=show"><i class="glyphicon glyphicon-ban-circle"></i> <span>Bloqueios</span></a></li>
                <li><a href="handlerServidor.php?op=show"><i class="glyphicon glyphicon-scale"></i> <span>Servidor</span></a></li>
                <!--

                <li><a href="handlerConsultas.php?op=show"><i class="glyphicon glyphicon-search"></i> <span>Consultas</span></a></li>

                <!--
                <li><a href="handlerPainel.php?op=show"><i class="glyphicon glyphicon-calendar"></i> <span>Agendamento de tarefas</span></a></li>
                -->
                <li><a href="handlerColetor.php?op=show"><i class="glyphicon glyphicon-globe"></i> <span>Coletor de dados</span></a></li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    </section>

        <!-- Main content -->
        <section class="content">
            <div id="divresponse"></div>
