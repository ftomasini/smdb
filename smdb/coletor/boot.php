<?php
require_once '../core/Autoload.php';

while(true)
{
    $usuarios[] = 'fametro@fametro.com.br';
    $usuarios[] = 'unifor@unifor.com.br';
    $usuarios[] = 'smbd@smbd.com.br';

    foreach ($usuarios as $usuario)
    {
        if(isset($usuario))
        {
            Estatistica::informacoesBaseDeDados($usuario, '');
            Estatistica::tamanhoTabela($usuario, null, true);
            Estatistica::tamanhoTabelaComIndices($usuario, null, true);
            Estatistica::aproveitamentoCacheBaseDeDados($usuario, '');
            Estatistica::tabelasComPoucaPesquisaPorIndices($usuario, '');
            Estatistica::consultasLentas($usuario, '');
        }
    }
}
?>
