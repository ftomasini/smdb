<?php

/**
 * Essas constantes são utilizadas apenas para debug
 * 
 * Em ambiente de produção devem estar comentadas!
 */
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

$caminhoYii = dirname(__FILE__) . '/framework/yii.php';
$caminhoConfig = dirname(__FILE__) . '/protected/config/main.php';

// Faz a importação do Yii
require_once($caminhoYii);

// Importa a classe que abstrai a controladora
require_once(dirname(__FILE__) . '/protected/components/Controladora.php');

// Inicializa a sessão
session_start();

// Carrega as configurações para uso nesse script
$config = include($caminhoConfig);

// Instancia a controladora
$controladora = Controladora::getControladora($config['params']['app']['controladora']);

// Realiza o setup necessário
$controladora->setup();

// Inicializa a aplicação
Yii::createWebApplication($caminhoConfig)->run();
