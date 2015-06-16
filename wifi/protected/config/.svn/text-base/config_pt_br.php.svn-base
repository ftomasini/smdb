<?php

//$config = include('config.php');

$thisConfig = array(
    'templateSMS' => '${usuario}, sua senha para acesso na rede Wi-Fi ${senha}.',
    'assuntoEmail' => 'Confirmação de cadastro na rede Wi-fi',
    'templateEmail' => '
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Documento sem título</title>
</head>

<body>
<div>
  <p><br />
    Olá ${usuario}, <br />
    <br />
    Bem-vindo a rede Wi-Fi<br />
    Para se conectar à rede  utilize a senha enviada por SMS  para o  número de celular informado.<br />
    <br />
  A sua senha é válida até <strong>${dataValidade}</strong>, podendo ser renovada após esta data.</p>
  <p>Caso esqueça a senha, acesse a página de autenticação e solicite uma nova senha.<br />
  Para renovar a sua senha após o período de validade, faça um novo cadastro da página de autenticação;</p>
  <h4>Tempos de  autenticação</h4>
  <ul>
    <li>Quando  abrir a tela&nbsp;do navegador de internet para fazer a autenticação, essa será  válida por 15min. Se não for preenchida nesse tempo, deve-se atualizar a página para  fazer a autenticação.</li>
    <li>Se mantiver o equipamento ativado, mas ocioso, a sessão dura até 1h.  Depois disso, deve autenticar-se novamente (abrir página de autenticação no navegador  e preencher usuário e senha).</li>
    <li>Com o equipamento em uso, a sessão dura até 5h. Depois disso, deve autenticar-se  novamente (abrir página de autenticação no navegador e preencher usuário e  senha).</li>
  </ul>
  <h4>Para se conectar à rede sem fio observe as dicas abaixo:<br />
  </h4>
  <ul>
    <li>Mantenha o Firewall do sistema operacional ativado; </li>
    <li> Procure garantir que seu antivírus esteja atualizado e em pleno funcionamento;</li>
    <li> Desligue o compartilhamento de pastas e de arquivos com a rede.</li>
    <li> Evite o acesso a sites com conteúdos confidenciais, pois você estará utilizando uma rede pública e, portanto, estará exposto a ameaças. Se for necessário, dê preferência a serviços que usem criptografia. </li>
    <li> Habilite a rede sem fio somente quando for usá-la e desabilite ao desconectar. </li>
  </ul>
  <p>&nbsp;</p>
  <p><br />
  <small>Essa notificação foi enviada para o endereço cadastrado na tela de autenticação.
  <br />
  A mensagem foi gerada automaticamente. Por favor não responda este e-mail.</small>  </p>
</div>
</body>
</html>',
);

return $thisConfig;

?>
