<?php
    
    $f = fopen('/var/log/smbd.log', 'a+');
    $valor = 1;

    $tempoDeColeta = 2;


    while(1)
    {
        sleep($tempoDeColeta);

        $returnWs1 = ws1();

    }
    
    fclose($f);

    function ws1()
    {

        $dados = new stdClass();
        $dados->tabela = 'testetabela';
        $dados->outrosdados = 'dados';

        $url = 'http://192.168.1.102';
        $clientOptions["location"] = $url . "/tcc/smdb/webservice.php";
        $clientOptions["uri"] = "$url";
        $clientOptions["encoding"] = "UTF-8";

        $client = new SoapClient(NULL, $clientOptions);

        $result = $client->wsTeste($dados);


    }


?>
