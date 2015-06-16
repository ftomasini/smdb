<?php

/**
 * Classe de autenticação com o banco de dados
 * 
 */
class AutenticacaoAutoCadastro extends AutenticacaoBase
{

    /**
     * Método responsável pela autenticação
     * 
     * @param String $nomeUsuario Nome do usuário
     * @param String $senhaUsuario Senha do usuário
     * 
     * @return Boolean TRUE se a autenticação foi bem-sucedida
     * 
     * @throws Exception Qualquer erro que possa ocorrer na autenticação
     */
    public function autentica($nomeUsuario, $senhaUsuario)
    {
        try
        {
            $usuario = Usuario::procuraUsuarioComDadosDeAcesso($nomeUsuario, $senhaUsuario);
            
            $this->validaUsuario($usuario);

            return true;
        }
        catch ( Exception $e )
        {
            throw new Exception($e->getMessage());
        }

    }

    /**
     * Valida um dado usuário
     * 
     * @param Usuario $usuario
     * 
     * @throws Exception Erros de login
     */
    public function validaUsuario($usuario)
    {
        if ( !$usuario->cadastroEstaNoPeriodoValido() )
        {
            throw new Exception(Yii::t('app', 'Seu usuário está com o cadastro expirado. Por favor, cadastre-se novamente'));
        }

        // Se o usuário está ativo
        if ( $usuario->ativado !== TRUE )
        {
            throw new Exception(Yii::t('app', 'Seu usuário não está ativo. Por favor, confirme o seu cadastro na tela de confirmar cadastro'));
        }

        if ( $usuario->bloqueado === TRUE )
        {
            throw new Exception(Yii::t('app', 'Seu usuário está bloqueado. Por favor, contate a administração da instituição'));
        }

    }

}
