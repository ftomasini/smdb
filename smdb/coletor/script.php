<?php
    
    $f = fopen('/tmp/logteste.txt', 'a+');
    $valor = 1;    
    while(1)
    {
        sleep(2);
        $valor ++;
        fwrite($f, "Valor: {$valor} \n");
        
    }
    
    fclose($f);
?>
