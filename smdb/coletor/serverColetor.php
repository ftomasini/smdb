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
    }


    /**
     * Função genérica que recebe os dados enviados via webservices e
     * salva na base de dados da aplicação.
     *
     * @param $data
     * @param null $tabela
     * @return bool|string
     */
    public function ws($data, $tabela = null)
    {
        try
        {
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
                if ($tabela == 'stat_tabela')
                {
                    $this->coletorModel->insert_stat_tabela($registro);
                }
                if ($tabela == 'stat_indice')
                {
                    $this->coletorModel->insert_stat_indice($registro);
                }
                if ($tabela == 'stat_configuracao_base_de_dados')
                {
                    $this->coletorModel->insert_stat_configuracao_base_de_dados($registro);
                }
                if ($tabela == 'stat_loadavg')
                {
                    $this->coletorModel->insert_stat_loadavg($registro);
                }
                if ($tabela == 'stat_memoria')
                {
                    $this->coletorModel->insert_stat_memoria($registro);
                }
                if ($tabela == 'stat_processos')
                {
                    $this->coletorModel->insert_stat_processos($registro);
                }
                if ($tabela == 'stat_bloqueios')
                {
                    $this->coletorModel->insert_stat_bloqueios($registro);
                }
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
