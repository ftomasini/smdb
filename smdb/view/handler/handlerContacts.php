<?php

$core = new Core();
$core->autoload();
$controller = new ContactsController();
$controller->handleRequest();
?>
