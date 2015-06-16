<?php

/**
 * Classe abstrata responsável por indicar os métodos que devem ser implementados
 * ao criar um novo "método de autenticação" com a controladora wifi
 * 
 */
abstract class ControladoraBase
{
    /**
     * Este método é chamado quando deve ser realizada a liberação de um dado usuário na controladora
     * 
     */
    abstract public function libera($usuario);
    
    /**
     * Este método é chamado antes de incializar o framework Yii
     * 
     */
    abstract public function setup();
}
