<?php
/**
 * Created by PhpStorm.
 * User: ftomasini
 * Date: 11/09/15
 * Time: 19:39
 */

require_once 'core/Autoload.php';

class serverColetor
{
    public $coletorModel;

    public function __construct()
    {
        $this->coletorModel = new ColetorModel();
        //chdir('../');

        //require_once 'core/Core.php';
    }


    /**
     *
     *
     */
    public function ws($data, $tabela = null)
    {
        try
        {
            //$f = fopen('/tmp/resultWs.log', 'a+');

            foreach ($data as $registro)
            {
                $ok = true;
                if ($tabela == 'stat_sgbd_versao')
                {
                    $this->coletorModel->insert_stat_sgbd_versao($registro);
                }
                if ($tabela == 'stat_base_de_dados')
                {
                    $this->coletorModel->insert_stat_base_de_dados($registro);
                }
                if ($tabela == 'stat_stat_tabela')
                {
                    $this->coletorModel->insert_stat_tabela($registro);
                }
                if ($tabela == 'stat_stat_indice')
                {
                    $this->coletorModel->insert_stat_indice($registro);
                }
                if ($tabela == 'stat_stat_configuracao_base_de_dados')
                {
                    $this->coletorModel->insert_stat_configuracao_base_de_dados($registro);
                }
                if ($tabela == 'stat_stat_loadavg')
                {
                    $this->coletorModel->insert_stat_loadavg($registro);
                }
                if ($tabela == 'stat_stat_memoria')
                {
                    $this->coletorModel->insert_stat_memoria($registro);
                }
                if ($tabela == 'stat_stat_processos')
                {
                    $this->coletorModel->insert_stat_processos($registro);
                }
                if ($tabela == 'stat_stat_bloqueios')
                {
                    $this->coletorModel->insert_stat_bloqueios($registro);
                }

                //foreach($registro as $coluna=>$valor)
                //{
                //    fwrite($f, "{$coluna}: {$valor} \n");
                //}
            }

            $ok = true;
        }
        catch (Exception $e)
        {
            $ok = $e->getMessage();
        }

        return $ok;
    }
}

?>
