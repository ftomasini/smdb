<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Hello!</title>
</head>

<body>

<?php
$webRoot = dirname(__FILE__);
global $webRoot;
require_once $webRoot .'/core/Core.php';
$core = new Core();
$core->autoload();
$controller = new LoginController();
$controller->handleRequest();

?>

<form method="post" action="index.php?op=check">
<fieldset>
<legend>Dados de Login</legend>
	<label for="txUsuario">Usu�rio</label>
	<input type="text" name="usuario" id="txUsuario" maxlength="25" />
	<label for="txSenha">Senha</label>
	<input type="password" name="senha" id="txSenha" />

    <input type="submit" value="Entrar" />
</fieldset>
</form>

</body>

</html>
