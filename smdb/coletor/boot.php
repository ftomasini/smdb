<?php
require_once 'core/Autoload.php';

while(true)
{
    $usuarios[] = 'fametro@fametro.com.br';
    $usuarios[] = 'unifor@unifor.com.br';
    $usuarios[] = 'smbd@smbd.com.br';

    foreach ($usuarios as $usuario)
    {
        if(isset($usuario))
        {
            Estatistica::informacoesBaseDeDadosChart($usuario, '');
            Estatistica::tamanhoBaseDeDadosChart($usuario, '');
            Estatistica::tamanhoBaseDeDadosComIndicesChart($usuario, '');
            Estatistica::aproveitamentoCacheBaseDeDadosChart($usuario, '');
            Estatistica::tabelasComPoucaPesquisaPorIndicesChart($usuario, '');
            Estatistica::consultasLentasChart($usuario, '');
        }
    }
}
?>
