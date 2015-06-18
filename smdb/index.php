<!DOCTYPE html>
<html>

<head>
  <title>Hello!</title>
  <meta charset="utf-8" />
</head>

<body>

<?php
$webRoot = dirname(__FILE__);
global $webRoot;
require_once $webRoot .'/core/Autoload.php';
$controller = new LoginController();
$controller->handleRequest();

?>

<form method="post" action="index.php?op=check">
<fieldset>
<legend>Dados de Login</legend>
	<label for="txUsuario">Usuário</label>
	<input type="text" name="usuario" id="txUsuario" maxlength="25" />
	<label for="txSenha">Senha</label>
	<input type="password" name="senha" id="txSenha" />

    <input type="submit" value="Entrar" />
</fieldset>
</form>

</body>

</html>
