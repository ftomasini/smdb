<?php

/**
 * Componente que disponibiliza as linguages disponíveis da aplicação
 * 
 */
class LangOptions extends CWidget
{
    
    /**
     * Método chamado ao invocar o widget
     * 
     * Renderiza a visão langOptions
     */
    public function run()
    {
        $this->render('langOptions');

    }

}

?>
