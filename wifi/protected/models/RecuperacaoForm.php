<?php

/**
 * Modelo do formulário de recuperação de senha
 * 
 */
class RecuperacaoForm extends ModelForm
{

    public $email;
    public $ddi;
    public $numeroCelular;
    public $confirmacaoNumeroCelular;

    /**
     * Declara as regras de validação dos campos do formulário
     * 
     */
    public function rules()
    {
        return array(
            array('email, numeroCelular, confirmacaoNumeroCelular, ddi', 'required', 'message' => Yii::t('app', 'Campo requerido')),
            array('email', 'email'),
            array('numeroCelular, confirmacaoNumeroCelular, ddi', 'numerical'),
            array('confirmacaoNumeroCelular', 'compare', 'compareAttribute' => 'numeroCelular', 'message' => Yii::t('app', 'O nº de celular deve ser exatamente igual')),
        );

    }

    /**
     * Declara as labels dos atributos
     * 
     */
    public function attributeLabels()
    {
        return array(
            'email' => 'E-mail',
            'numeroCelular' => Yii::t('app', 'Nº de celular'),
            'confirmacaoNumeroCelular' => Yii::t('app', 'Confirme seu celular'),
            'ddi' => 'DDI',
            'confirmacaoNumeroCelular' => 'validaInformacoes'
        );

    }
    
    /**
     * Valida as informações desse formulário conforme o sistema
     * 
     * @return Boolean Se as informações estão validas
     */
    public function validaInformacoes()
    {
        try
        {
            $usuario = $this->procuraUsuarioPorLoginETelefone();

            if ( $usuario === null )
            {
                throw new Exception(Yii::t('app', 'Nenhum usuário registrado com essas credenciais.'));
            }
            if ( !$usuario->cadastroEstaNoPeriodoValido() )
            {
                throw new Exception(Yii::t('app', 'Seu cadastro expirou, você deve se cadastrar novamente.'));
            }
            
            return true;
            
        }
        catch ( Exception $e )
        {
            $this->addError("confirmacaoNumeroCelular", $e->getMessage());
            
            return false;
            
        }
        
    }
    
    /**
     * Envia os dados de recuperacao para o usuário
     * 
     * @throws CHttpException Caso não seja possível enviar os dados de recuperação
     */
    public function recuperaUsuario()
    {
        if( !$this->hasErrors() )
        {
            $usuario = $this->procuraUsuarioPorLoginETelefone();
        
            $operacoesUsuario = new OperacoesUsuario($usuario);

            if ( $operacoesUsuario->enviaSMSComDadosDeAcesso() )
            {
                LogSistema::registraOperacaoRealizadaPeloUsuarioCadastrado(LogSistema::ACAO_REENVIO_SENHA, $this->email);
            }
            else
            {
                throw new CHttpException(0, Yii::t('app', 'Erro ao enviar dados de recuperação. Tente novamente mais tarde.'));
            }
            
        }

    }

    /**
     * Procura pelo usuário com o email e numero de telofone informados nesse formulário
     *  
     * @return Usuario Resultado da pesquisa
     */
    private function procuraUsuarioPorLoginETelefone()
    {
        $usuario = new Usuario();
        $usuario->email = $this->email;
        $usuario->numero_celular = $this->ddi . $this->numeroCelular;

        $resultadoPesquisa = $usuario->search()->getData();

        return $resultadoPesquisa[0];

    }

}
