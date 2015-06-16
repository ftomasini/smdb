<?php

Yii::import('application.vendors.*');
require_once('PHPMailer/class.phpmailer.php');
require_once('PHPMailer/class.smtp.php');

class Email
{

    public $destino;
    public $mensagem;

    public function enviar()
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();  // Ativar SMTP
        $mail->SMTPDebug = 1;  // Debugar: 1 = erros e mensagens, 2 = mensagens apenas
        $mail->SMTPAuth = true;  // Autenticação ativada
        $mail->Host = Configuracao::getValorDaConfiguracao('hostServidorEmail');
        $mail->Port = Configuracao::getValorDaConfiguracao('portaServidorEmail');
        $mail->Username = Configuracao::getValorDaConfiguracao('usuarioAutenticacaoEmail');
        $mail->Password = Configuracao::getValorDaConfiguracao('senhaAutenticacaoEmail');

        $explode = explode(";", Configuracao::getValorDaConfiguracao('emailDe'));
        $mail->SetFrom($explode[0], $explode[1]);
        $mail->Subject = Configuracao::getValorDaConfiguracao('assuntoEmail');
        $mail->CharSet = 'UTF-8';
        $mail->MsgHTML($this->mensagem);
        $mail->AddAddress($this->destino);

        if ( !$mail->Send() )
        {
            Yii::trace('Erro ao enviar email: ' . $mail->ErrorInfo);

            return false;
        }

        return true;

    }

}
