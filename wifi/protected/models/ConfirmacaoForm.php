<?php

/**
 * Modelo do de confirmação de cadastro
 * 
 */
class ConfirmacaoForm extends ModelForm
{

    public $email;
    public $senha;

    /**
     * Declara as regras de validação do formulário
     * 
     */
    public function rules()
    {
        return array(
            array('senha, email', 'required', 'message' => Yii::t('app', 'Campo requerido')),
            array('email', 'email'),
            array('senha', 'validaInformacoes')
        );

    }

    /**
     * Declara as labels dos atributos
     * 
     */
    public function attributeLabels()
    {
        return array(
            'senha' => Yii::t('app', 'Senha')
        );

    }

    /**
     * Valida as informações do formulário
     * 
     * @return Boolean
     */
    public function validaInformacoes()
    {
        try
        {
            if ( !$this->hasErrors() )
            {
                $usuario = Usuario::procuraUsuarioComDadosDeAcesso($this->email, $this->senha);

                $this->validaUsuario($usuario);

                return true;
            }

            return false;
        }
        catch ( Exception $e )
        {
            $this->addError('senha', $e->getMessage());

            return false;
        }

    }

    /**
     * Valida um dado usuário
     * 
     */
    private function validaUsuario($usuario)
    {
        if ( $usuario->ativado === TRUE )
        {
            throw new Exception(Yii::t('app', 'Usuário já validado.'));
        }
        if ( $usuario->bloqueado === TRUE )
        {
            throw new Exception(Yii::t('app', 'Seu usuário está bloqueado. Por favor, contate a administração da instituição'));
        }

    }

    /**
     * Confirma o usuário
     * 
     */
    public function confirmaUsuario()
    {
        try
        {
            $usuario = Usuario::procuraUsuarioComDadosDeAcesso($this->email, $this->senha);

            if ( !$this->hasErrors() )
            {
                $usuario->ativado = TRUE;

                return $usuario->save();
            }
        }
        catch ( CDbException $e )
        {
            throw new CHttpException(Yii::t('Não foi possível completar a sua solicitação. Arquivo: ' . $e->getFile()));
        }
        catch ( EUsuarioInvalido $e )
        {
            $this->addError('senha', $e->getMessage());
        }

        return false;

    }

}
