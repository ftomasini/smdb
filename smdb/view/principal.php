<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>SMDB</title>

    <!-- Placed js at the end of the document so the pages load faster -->


    <!--Core CSS -->
    <link href="../../html/bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../html/css/bootstrap-reset.css" rel="stylesheet">
    <link href="../../html/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="../../html/css/style.css" rel="stylesheet">
    <link href="../../html/css/style-responsive.css" rel="stylesheet" />

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<section id="container" >
<!--header start-->
<header class="header fixed-top clearfix">
<!--logo start-->
<div class="brand">

    <a href="../index.php" class="logo">
        <!--<img src="../../../html/images/logo.png" alt="">-->
    </a>
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars"></div>
    </div>
</div>
<!--logo end-->

<div class="nav notify-row" id="top_menu">
    <!--  notification start -->
    <ul class="nav top-menu">
        <!-- settings start -->
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <i class="fa fa-tasks"></i>
                <span class="badge bg-success">8</span>
            </a>
            <ul class="dropdown-menu extended tasks-bar">
                <li>
                    <p class="">You have 8 pending tasks</p>
                </li>
                <li>
                    <a href="#">
                        <div class="task-info clearfix">
                            <div class="desc pull-left">
                                <h5>Target Sell</h5>
                                <p>25% , Deadline  12 June’13</p>
                            </div>
                                    <span class="notification-pie-chart pull-right" data-percent="45">
                            <span class="percent"></span>
                            </span>
                        </div>
                    </a>
                </li>

                <li class="external">
                    <a href="#">See All Tasks</a>
                </li>
            </ul>
        </li>
        <!-- settings end -->
        <!-- inbox dropdown start-->

        <!-- inbox dropdown end -->
        <!-- notification dropdown start-->
        <li id="header_notification_bar" class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                <i class="fa fa-bell-o"></i>
                <span class="badge bg-warning">3</span>
            </a>
            <ul class="dropdown-menu extended notification">
                <li>
                    <p>Notifications</p>
                </li>
                <li>
                    <div class="alert alert-info clearfix">
                        <span class="alert-icon"><i class="fa fa-bolt"></i></span>
                        <div class="noti-info">
                            <a href="#"> Server #1 overloaded.</a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="alert alert-danger clearfix">
                        <span class="alert-icon"><i class="fa fa-bolt"></i></span>
                        <div class="noti-info">
                            <a href="#"> Server #2 overloaded.</a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="alert alert-success clearfix">
                        <span class="alert-icon"><i class="fa fa-bolt"></i></span>
                        <div class="noti-info">
                            <a href="#"> Server #3 overloaded.</a>
                        </div>
                    </div>
                </li>

            </ul>
        </li>
        <!-- notification dropdown end -->
    </ul>
    <!--  notification end -->
</div>
<div class="top-nav clearfix">
    <!--search & user info start-->
    <ul class="nav pull-right top-menu">
        <!--
        <li>
            <input type="text" class="form-control search" placeholder=" Search">
        </li>
        -->
        <!-- user login dropdown start-->
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <img alt="" src="../../html/images/avatar1_small.jpg">
                <span class="username">Fabiano Tomasini</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
                <li><a href="logout.php"><i class="fa fa-key"></i> Log Out</a></li>
            </ul>
        </li>
        <!-- user login dropdown end -->
    </ul>
    <!--search & user info end-->
</div>
</header>
<!--header end-->
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
           <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">

            <li>
                <a href="handlerContacts.php">

                    <span>Contatos</span>
                </a>
            </li>
            <li>
                <a href="index.html">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="javascript:;">
                    <i class="fa fa-laptop"></i>
                    <span>Layouts</span>
                    <span class="dcjq-icon"></span>
                </a>
                <ul class="sub">
                    <li><a href="boxed_page.html">Boxed Page</a></li>
                    <li><a href="horizontal_menu.html">Horizontal Menu</a></li>
                    <li><a href="language_switch.html">Language Switch Bar</a></li>
                </ul>
            </li>

        </ul>
        </div>
<!-- sidebar menu end-->
    </div>
</aside>
<section id="main-content">
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">

                <!--Core js-->
                <script src="../../html/js/jquery.js"></script>
                <script src="../../html/js/jquery-1.8.3.min.js"></script>

                <script src="../../html/bs3/js/bootstrap.min.js"></script>
                <script class="include" type="text/javascript" src="../../html/js/jquery.dcjqaccordion.2.7.js"></script>
                <script src="../../html/js/jquery.scrollTo.min.js"></script>
                <script src="../../html/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
                <script src="../../html/js/jquery.nicescroll.js"></script>
                <!--Easy Pie Chart-->
                <script src="../../html/js/easypiechart/jquery.easypiechart.js"></script>
                <!--Sparkline Chart-->
                <script src="../../html/js/sparkline/jquery.sparkline.js"></script>
                <!--jQuery Flot Chart-->
                <script src="../../html/js/flot-chart/jquery.flot.js"></script>
                <script src="../../html/js/flot-chart/jquery.flot.tooltip.min.js"></script>
                <script src="../../html/js/flot-chart/jquery.flot.resize.js"></script>
                <script src="../../html/js/flot-chart/jquery.flot.pie.resize.js"></script>
                <!--script for this page only-->
                <script src="../../html/js/table-editable.js"></script>
                <script src="../../../html/js/scripts.js"></script>


                <script type="text/javascript" src="../../html/js/data-tables/jquery.dataTables.js"></script>
                <script type="text/javascript" src="../../html/js/data-tables/DT_bootstrap.js"></script>

                <script type="text/javascript" src="../../html/js/jquery.validate.min.js"></script>

                <!--common script init for all pages-->
                <!--this page script-->
                <script src="../../html/js/validation-init.js"></script>


                <!-- END JAVASCRIPTS -->
                <script>
                    jQuery(document).ready(function() {
                        EditableTable.init();
                    });
                </script>


<!--common script init for all pages-->
