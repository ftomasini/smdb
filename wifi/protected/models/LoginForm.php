<?php

/**
 * Modelo do formulário de Login
 * 
 */
class LoginForm extends ModelForm
{

    public $nomeUsuario;
    public $senhaUsuario;

    /**
     * Instância do componente IdentidadeUsuario
     * 
     * @var Objeto
     */
    private $_identidade;

    /**
     * Declara as regras de validação dos campos do formulário
     * 
     */
    public function rules()
    {
        $regras = array(
            array('nomeUsuario, senhaUsuario', 'required', 'message' => Yii::t('app', 'Campo requerido')),
            array('senhaUsuario', 'validaLogin')
        );

        // Se o auto-cadastro está habilitado acrescenta validação de login
        if ( Yii::app()->params['preferencias']['autoCadastroHabilitado'] )
        {
            $regras[] = array('nomeUsuario', 'email');
        }

        return $regras;

    }

    /**
     * Valida o usuário e senha informado
     * 
     */
    public function validaLogin()
    {
        // Se todos os campos estão ok
        if ( !$this->hasErrors() )
        {
            try
            {
                $this->_identidade = new IdentidadeUsuario($this->nomeUsuario, $this->senhaUsuario);

                $this->_identidade->authenticate();

                return true;
            }
            catch ( Exception $e )
            {
                $this->addError('senhaUsuario', $e->getMessage());

                LogSistema::registraOperacaoRealizadaGenerica(LogSistema::ACAO_LOGIN_INCORRETO, "Utilizando nome de usuário {$this->nomeUsuario}");

                return false;
            }
        }

    }

    /**
     * Loga o usuário no sistema
     * 
     * @return Boolean Se o login foi bem-sucedido
     */
    public function login()
    {
        try
        {
            if ( $this->_identidade === null )
            {
                $this->_identidade = new IdentidadeUsuario($this->nomeUsuario, $this->senhaUsuario);
                $this->_identidade->authenticate();
            }

            if ( !$this->hasErrors() )
            {
                Yii::app()->user->login($this->_identidade, null);

                LogSistema::registraOperacaoRealizadaPeloUsuarioCadastrado(LogSistema::ACAO_LOGIN, $this->nomeUsuario);

                return true;
            }

            return false;
        }
        catch ( Exception $ex )
        {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);

            return false;
        }

    }

}
