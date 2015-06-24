<?php

class Core
{

    public function isLogged()
    {
        if (!isset($_SESSION))
        {
            session_start();
        }
        
        return isset($_SESSION['UsuarioID']);
    }
}
?>
