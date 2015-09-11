<?php
require_once '../../core/Autoload.php';
$controller = new RegisterController();

try
{
    $controller->handleRequest();
}
catch(Exception $e)
{
    $controller->showError("", $e->getMessage());
}

?>
