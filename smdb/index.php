
<?php
$webRoot = dirname(__FILE__);
global $webRoot;
require_once $webRoot .'/core/Autoload.php';
$controller = new LoginController();
try
{
    $controller->handleRequest();
}
catch(Exception $e)
{
?>

    <?php
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <link rel="shortcut icon" href="html/images/favicon.png">

    <title>Login</title>

    <!--Core CSS -->
    <link href="html/bs3/css/bootstrap.min.css" rel="stylesheet">

    <link href="html/custom/login.css" rel="stylesheet">

    <!-- Custom styles for this template

    <link href="html/css/bootstrap-reset.css" rel="stylesheet">
    <link href="html/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <link href="html/css/style.css" rel="stylesheet">
    <link href="html/css/style-responsive.css" rel="stylesheet" />-->

</head>

  <body class="login-body">

    <div class="container">

      <form method="post" class="form-signin" action="index.php?op=check">
        <h2 class="form-signin-heading">SMDB</h2>
        <div class="login-wrap">
            <div class="user-login-info">
                <input type="text" name="usuario" id="txUsuario" class="form-control" placeholder="Usuário" autofocus>
                <input type="password" name="senha" id="txSenha" class="form-control" placeholder="Senha">
            </div>

            <button class="btn btn-lg btn-login btn-block" type="submit">Entrar</button>

            <!--
            <div class="registration">
                Don't have an account yet?
                <a class="" href="registration.html">
                    Create an account
                </a>
            </div>

        </div>

          >
          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h4 class="modal-title">Forgot Password ?</h4>
                      </div>
                      <div class="modal-body">
                          <p>Enter your e-mail address below to reset your password.</p>
                          <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">

                      </div>
                      <div class="modal-footer">
                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                          <button class="btn btn-success" type="button">Submit</button>
                      </div>
                  </div>
              </div>
          </div>
          !-->
          <!-- modal -->
      </form>
    </div>
    <!-- Placed js at the end of the document so the pages load faster -->

    <!--Core js
    <script src="js/jquery.js"></script>
    <script src="bs3/js/bootstrap.min.js"></script>
-->
  </body>
</html>
