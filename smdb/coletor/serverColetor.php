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
        $this->coletorModel = ColetorModel();
        //chdir('../');

        //require_once 'core/Core.php';
    }


    /**
     *
     *
     */
    public function wsTeste($data, $tabela = null)
    {
        try
        {
            //$f = fopen('/tmp/resultWs.log', 'a+');

            foreach ($data as $registro)
            {
                if ($tabela == 'stat_sgbd_versao')
                {
                    $this->coletorModel->insert_stat_sgbd_versao($registro);
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
            $ok = false;
        }

        return $ok;
    }
}

?>
