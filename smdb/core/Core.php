<?php

class Core
{

    public function isLogged()
    {
        session_start();
        return isset($_SESSION['UsuarioID']);
    }
}
?>
