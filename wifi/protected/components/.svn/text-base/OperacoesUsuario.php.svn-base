<?php

class OperacoesUsuario
{

    /**
     * Usuário com o qual fazer as operações
     * 
     * @var Usuario
     */
    private $usuario;

    public function __construct($usuario)
    {
        $this->usuario = $usuario;

    }

    /**
     * Envia o SMS com os dados de acesso
     * 
     * @return Boolean TRUE se o envio foi bem sucedido
     */
    public function enviaSMSComDadosDeAcesso()
    {
        $sms = new SMS();

        $usuario = $this->usuario->nome;
        $senha = $this->usuario->senha;

        // Pega o padrão da mensagem definida em configuração e troca pelos valores
        $mensagem = str_replace(array("\${usuario}", "\${senha}"), array($usuario, $senha), Configuracao::getValorDaConfiguracao("templateSMS"));

        $sms->mensagem = $mensagem;
        $sms->numeroCelularDestino = $this->usuario->numero_celular;

        return $sms->enviar();

    }

    /**
     * Envia o email de confirmação de cadastro das informações
     * 
     * @return Boolean Se o email foi enviado
     */
    public function enviaEmailDeConfirmacao()
    {
        $usuario = $this->usuario->nome;
        $dataValidade = strval($this->usuario->obtemDataVencimentoCadastro());

        // Pega o padrão da mensagem definida em configuração e troca pelos valores
        $mensagem = str_replace(array("\${usuario}", "\${dataValidade}"), array($usuario, $dataValidade), Configuracao::getValorDaConfiguracao("templateEmail"));

        $email = new Email();
        
        Yii::trace($mensagem);
        
        $email->destino = $this->usuario->email;
        $email->mensagem = $mensagem;

        return $email->enviar();

    }

    /**
     * Gera uma senha randômica
     * 
     * @return Integer
     */
    public static function geraSenha()
    {
        $random = mt_rand(100000, 999999);

        return $random;

    }

}
