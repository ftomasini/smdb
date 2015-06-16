<?php

/**
 * Classe que abstrai a importação de um dado método de autenticação
 * 
 */
class Autenticacao
{
    /**
     * Instância do método de autenticação
     * 
     * @var Objeto 
     */
    private static $autenticacao;
    
    /**
     * Importa a classe dada da autenticação se não importada ainda e retorna a
     * instância desta
     * 
     * @param String $nomeClasse Nome da classe referente ao método de autenticação
     * 
     * @return Objeto Instância do método de autenticação
     */
    public static function getAutenticacao($nomeClasse = null)
    {
        if( is_null(self::$autenticacao) )
        {
            if( !is_null($nomeClasse) )
            {
                require_once(dirname(__FILE__) . '/autenticacao/' . $nomeClasse . '.php');
                
                self::$autenticacao = new $nomeClasse();
            }
                    
        }
        
        return self::$autenticacao;
        
    }

}
