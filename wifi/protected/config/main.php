<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Portal wifi',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.components.*',
        'application.components.autenticacao.*',
        'application.components.autenticacao.base.*',
        'application.components.autenticacao.lib.*',
        'application.components.controladora.*',
        'application.components.controladora.base.*',
        'application.components.controladora.lib.*',
        'application.components.exceptions.*',
        'application.extensions.*',
        'application.models.*'
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'senha',
        ),
    ),
    'components' => array(
        'db' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'pgsql:host=127.0.0.1;port=5432;dbname=wifi',
            'username' => 'postgres',
            'password' => 'postgres',
        ),
        'errorHandler' => array(
            'errorAction' => 'user/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'trace, info, error, warning, vardump',
                ),
                // Mostra as mensagens no browser
                array(
                    'class' => 'CWebLogRoute',
                    'enabled' => YII_DEBUG,
                    'levels' => 'error, warning, trace, notice',
                    'categories' => 'application',
                    'showInFireBug' => false,
                ),
            ),
        )
    ),
    'sourceLanguage' => 'pt_br',
    'language' => 'pt_br',
    /**
     * Parâmetros configuráveis da aplicação
     * 
     * Para configurações de acesso à banco de dados, ver 'db'
     */
    'params' => array(
        'app' => array(
            'autenticacao' => 'AutenticacaoAutoCadastro', // Classe de autenticação
            'controladora' => 'ControladoraUnify' // Classe da controladora
        ),
        'tema' => array(
            'logo' => 'images/logo.png' // Logo do cliente (relativo a pasta raiz)
        ),
        'preferencias' => array(
            'autoCadastroHabilitado' => true
        ),
        'idiomasSuportados' => array('pt-br', 'en-us')
    ),
);
