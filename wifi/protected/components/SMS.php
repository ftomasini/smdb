<?php

Yii::import('application.vendors.*');
require_once('Zenvia/human_gateway_client_api/HumanClientMain.php');
require_once('Zenvia/human_gateway_client_api/bean/HumanSimpleMessage.php');
require_once('Zenvia/human_gateway_client_api/service/HumanSimpleSend.php');

class SMS
{

    public $numeroCelularDestino;
    public $mensagem;

    public function enviar()
    {
        $smsSimples = new HumanSimpleMessage(utf8_decode($this->mensagem), $this->numeroCelularDestino);

        $envioSimples = new HumanSimpleSend(Configuracao::getValorDaConfiguracao('usuarioZenvia'), Configuracao::getValorDaConfiguracao('senhaZenvia'));

        $resposta = $envioSimples->sendMessage($smsSimples);

        if ( $resposta->getCode() !== 000 )
        {
            Yii::log('SMS não enviado. Cód. Erro: ' . $resposta->getCode() . ': ' . $resposta->getMessage(), 'error');

            return false;
        }

        return true;

    }

}
