<?php

/**
 * Classe abstrata que contém os métodos que devem ser implementados por um método
 * de autenticação
 * 
 */
abstract class AutenticacaoBase
{

    /**
     * Método abstrato
     *
     * Método que é chamado para autenticação
     * 
     */
    abstract public function autentica($nomeUsuario, $senhaUsuario);

}
