<?php
/**
 * Handle webservices requests.
 *
 * @author Fabiano Tomasini [ftomasini.rs@gmail.com]
 */

$time = date('d/m/Y-G:i:s');


// Temporary log file
$tmpDir  = sys_get_temp_dir();
$logFile = $tmpDir . '/webservices.log';

ob_start();

$uri = 'http://smbd.com.br/';

//echo "Requested Data:\n";

//$file = "../coletor/serverColetor.php";
 //$a =
//echo $a;

//Instanciate a new soapserver
$server = new SoapServer( NULL, array( 'uri' => $uri ) );

$file = "coletor/serverColetor.php";
if (file_exists($file))
{
    require_once $file;

    $server->setClass('serverColetor');

    echo "Methods: ";
    $funcs = $server->getFunctions();

    foreach($funcs as $f)
    {
        echo "$f, ";
    }

    echo "\n";

    $out = ob_get_contents();
    error_log("$out \n", 3, $logFile);
    ob_end_clean();

    $server->handle();
}
else
{
    echo "\nERROR! File not found: $path \n";

    $out = ob_get_contents();
    error_log("$out \n", 3, $logFile);
    ob_end_clean();

    $server->fault('171', 'Sorry, webservices not found!');
}

?>