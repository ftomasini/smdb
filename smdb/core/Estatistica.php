<?php

class Estatistica extends DbConection
{
    public function __construct()
    {


    }


    public static function usoCacheBancoDeDados()
    {

    }

    public static function usoCacheTabela()
    {

    }

    public function configuracoesDoSgbd($usuario)
    {
        $this->openDb();

        $dbUsuario = pg_escape_string($usuario);
        $dbres = pg_query("SELECT usuario,
                                  data_coleta,
                                  TO_CHAR(data_coleta, 'dd/mm/yyyy hh24:mi') as data_coleta_formatada,
                                  name,
                                  setting as valor,
                                  boot_val as valor_original,
                                  category as categoria,
                                  short_desc as descricao_resumida,
                                  extra_desc as descricao
                             FROM stat_configuracao_base_de_dados
                            WHERE data_coleta = (select obtemultimacoleta('stat_configuracao_base_de_dados', '$dbUsuario'))");

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }

        return $result;

    }

    public function processosEmExecucao($usuario)
    {
        $this->openDb();

        $dbUsuario = pg_escape_string($usuario);
        $dbres = pg_query("SELECT usuario,
                                  data_coleta,
                                  TO_CHAR(data_coleta, 'dd/mm/yyyy hh24:mi') as data_coleta_formatada,
                                  datname,
                                  usename,
                                  pid,
                                  priority,
                                  memoria,
                                  state,
                                  query,
                                  inicio_processo,
                                  tempo_execussao

                             FROM stat_processos
                            WHERE data_coleta = (select obtemultimacoleta('stat_processos', '$dbUsuario'))");

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }

        return $result;

    }






}
?>
