<?php


spl_autoload_register(function($class)
{
    $loc = __DIR__;
    $loc = str_replace('/core', "", $loc);
    $locations = array();
    //var_dump($loc);
    $locations[] = $loc . "/config/". str_replace('\\', '/', $class) . '.php';
    $locations[] = $loc . "/core/". str_replace('\\', '/', $class) . '.php';
    $locations[] = $loc . "/controller/". str_replace('\\', '/', $class) . '.php';
    $locations[] = $loc . "/model/". str_replace('\\', '/', $class) . '.php';
    $locations[] = $loc . "/core/PHPMailer-master". str_replace('\\', '/', $class) . '.php';

    foreach ($locations as $location)
    {

        //var_dump($location);
        if ( is_readable($location) )
        {
            //var_dump($location);
            require_once $location;
            # code...
        }


    }
}
);
?>
