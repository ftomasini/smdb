<?php

// Importa a classe base das controladoras
require_once(dirname(__FILE__) . '/controladora/base/ControladoraBase.php');

/**
 * Classe que abstrai a importação de uma dada controladora
 * 
 */
class Controladora
{

    /**
     * Instância da classe da controladora
     * 
     * @var Objeto 
     */
    private static $controladora;

    /**
     * Importa a classe dada da controladora se não importada e retorna a 
     * instância desta
     * 
     * @param String $nomeClasse Nome da classe específica da controladora
     * 
     * @return Objeto Instância da classe da controladora
     */
    public static function getControladora($nomeClasse = null)
    {
        if ( is_null(self::$controladora) )
        {
            if ( !is_null($nomeClasse) )
            {
                require_once(dirname(__FILE__) . '/controladora/' . $nomeClasse . '.php');

                self::$controladora = new $nomeClasse();
            }
        }

        return self::$controladora;

    }

}
