<?php

class Core
{

    public function autoload()
    {
        require_once $GLOBALS['webRoot'].'/core/Core.php';
        require_once $GLOBALS['webRoot'].'/core/Controller.php';
        require_once $GLOBALS['webRoot'].'/core/ValidationException.php';
        require_once $GLOBALS['webRoot'].'/core/DbConection.php';
        require_once $GLOBALS['webRoot'].'/controller/ContactsController.php';
        require_once $GLOBALS['webRoot'].'/controller/LoginController.php';
        require_once $GLOBALS['webRoot'].'/model/ContactsModel.php';
        require_once $GLOBALS['webRoot'].'/model/LoginModel.php';

    }
}
?>
