<?php

/**
 * Controller base da aplicação
 * 
 */
class Controller extends CController
{

    /**
     * @var String Define o layout default da aplicação
     */
    public $layout = '//layouts/main';

    /**
     * @var Array Itens do menu de contexto. Atributo associado à {@link CMenu::items}.
     */
    public $menu = array();

    public function getModeloPopuladoComDadosDaRequisicao($modelo, $identificadorDosDados)
    {
        $dadosRequisicao = Yii::app()->request->getPost($identificadorDosDados);
        
        if ( $dadosRequisicao )
        {
            $modelo->setIsPopulated(true);
            $modelo->attributes = $dadosRequisicao;
        }

        return $modelo;

    }

}
