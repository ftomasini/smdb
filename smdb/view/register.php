
<!DOCTYPE html>
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
    <!-- iCheck -->
    <link rel="stylesheet" href="../../html/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition register-page">
    <div class="register-box">
      <div class="login-logo">
        <a href="index.php"><span class="glyphicon glyphicon-cloud" </span>SM<b>BD</b></a>
      </div><!-- /.login-logo -->
      <p class="text-center">Sistema de monitoramento de base de dados</p>

      <div class="register-box-body">
        <p class="login-box-msg">Registrar um novo usuário</p>
        <form action="handlerRegister.php?op=new" method="post">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" name="nome" id="nome" value="<?php isset($_POST['nome']) ? print $_POST['nome'] : '' ?>" placeholder="Nome completo">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="email" class="form-control" name="email" id="email" value="<?php isset($_POST['email'])? print $_POST['email']: '' ?>" placeholder="E-mail">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <input type="hidden" name="form-submitted" value="1" />

          <!--
          <div class="form-group has-feedback">
            <input type="password" class="form-control"  id="senha" placeholder="Senha">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control"  id="confirmaSenha" placeholder="Repita a senha">
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div> -->
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <!--<label>
                  <input type="checkbox"> Estou de acordo com os termos <a href="#">terms</a>
                </label> -->
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>
            </div><!-- /.col -->
          </div>
        </form>

        <div class="social-auth-links text-center">
          <!--<p>- OR -</p>
          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign up using Facebook</a>
          <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign up using Google+</a>
        </div>-->

        <a href="../../index.php" class="text-center">Eu já tenho um cadastro</a>
      </div><!-- /.form-box -->
    </div><!-- /.register-box -->

    <!-- jQuery 2.1.4 -->
    <script src="../../html/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../../html/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="../../html/plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>
