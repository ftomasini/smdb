<?php

/**
 * Modelo do formulário de registro
 * 
 */
class RegistroForm extends ModelForm
{

    public $nome;
    public $email;
    public $ddi;
    public $numeroCelular;
    public $confirmacaoNumeroCelular;
    public $documento;

    /**
     * Código de verificação ao dar submit no formulário (captcha)
     * 
     * @var String
     */
    public $codigoVerificacao;

    /**
     * Declara as regras de validação dos campos do formulário
     * 
     */
    public function rules()
    {
        return array(
            array('nome, email, numeroCelular, confirmacaoNumeroCelular, documento, ddi', 'required', 'message' => Yii::t('app', 'Campo requerido')),
            array('email', 'email'),
            array('numeroCelular, confirmacaoNumeroCelular, ddi', 'numerical'),
            array('confirmacaoNumeroCelular', 'compare', 'compareAttribute' => 'numeroCelular', 'message' => Yii::t('app', 'O nº de celular deve ser exatamente igual')),
            array('codigoVerificacao', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements()),
        );

    }

    /**
     * Declara as labels dos atributos
     * 
     */
    public function attributeLabels()
    {
        return array(
            'nome' => Yii::t('app', 'Nome'),
            'email' => 'E-mail',
            'numeroCelular' => Yii::t('app', 'Nº de celular'),
            'confirmacaoNumeroCelular' => Yii::t('app', 'Confirme seu celular'),
            'documento' => Yii::t('app', 'Documento'),
            'ddi' => 'DDI',
        );

    }

    /**
     * Salva o usuário com as informações do modelo
     * 
     * @return Boolean Se o usuário foi cadastrado
     * 
     * @throws CHttpException Caso ocorra algum erro ao tentar salvar o usuário
     */
    public function salvaUsuario()
    {
        try
        {
            if ( !$this->hasErrors() )
            {
                return $this->tentaSalvarUsuario();
            }
        }
        catch ( Exception $e )
        {
            throw new CHttpException(0, $e->getMessage());
        }

    }

    /**
     * Tenta salvar o usuário com as informações do formulário
     * 
     */
    private function tentaSalvarUsuario()
    {
        try
        {
            $usuario = Usuario::procuraInformacoesUsuarioPorLogin($this->email);
        }
        catch ( EUsuarioInvalido $e )
        {
            $usuario = new Usuario;
            Yii::trace($e->getMessage());
        }

        $usuario->email = $this->email;
        $usuario->numero_celular = $this->ddi . $this->numeroCelular;
        $usuario->nome = $this->nome;
        $usuario->documento = $this->documento;
        $usuario->senha = OperacoesUsuario::geraSenha();
        $usuario->ativado = FALSE;

        if ( $usuario->save() )
        {
            $this->enviaDadosDeConfirmacaoParaUsuario($usuario);
        }

        return true;

    }

    /**
     * Envia os dados 
     * 
     * @param type $usuario
     * 
     * @throws Exception
     */
    private function enviaDadosDeConfirmacaoParaUsuario($usuario)
    {
        $operacoesUsuario = new OperacoesUsuario($usuario);

        /*if ( $operacoesUsuario->enviaSMSComDadosDeAcesso() )
        {
            LogSistema::registraOperacaoRealizadaPeloUsuarioCadastrado(LogSistema::ACAO_SMS_ENVIADO, $this->email);
        }
        else
        {
            throw new Exception(Yii::t('app', 'Erro ao enviar dados de confirmação. Tente novamente o envio na seção de "Recuperar Senha"'));
        }*/

        if ( $operacoesUsuario->enviaEmailDeConfirmacao() )
        {
            LogSistema::registraOperacaoRealizadaPeloUsuarioCadastrado(LogSistema::ACAO_EMAIL_ENVIADO, $this->email);
        }

    }

}
