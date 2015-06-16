<?php

/**
 * Classe responsável por veririficar a identidade de um usuário
 * 
 */
class IdentidadeUsuario extends CUserIdentity
{

    /**
     * Autentica o usuário e senha informados conforme o método definido no
     * arquivo de configurações
     * 
     * @return Boolean TRUE se a validação foi bem-sucedida
     * 
     * @throws Exception Qualquer erro que possa ocorrer na autenticação
     */
    public function authenticate()
    {
        try
        {
            $autenticacao = Autenticacao::getAutenticacao(Yii::app()->params['app']['autenticacao']);

            $autenticacao->autentica($this->username, $this->password);

            return true;
        }
        catch ( Exception $e )
        {
            // Lança novamente a exceção para ser tratada pelo modelo
            throw new Exception($e->getMessage());
        }

    }

}
