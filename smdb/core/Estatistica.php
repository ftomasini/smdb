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

    public function tabelas($usuario)
    {
        $this->openDb();

        $dbUsuario = pg_escape_string($usuario);
        $dbres = pg_query("SELECT schemaname || '-' || relname as tabela
                             FROM stat_tabela
                            WHERE data_coleta = (select obtemultimacoleta('stat_tabela', '$dbUsuario'))
                            ORDER BY tabela");

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }

        return $result;

    }


    public static function tamanhoTabelaChart($usuario =null, $tabela= null)
    {



        $resultado = self::tamanhoTabela($usuario, $tabela);
        ?>
        <!-- DONUT CHART -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Donut Chart</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove2"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div id="my-charts2"></div>
        <?php
        $morris = new MorrisDonutCharts( 'my-charts2' );
        $morris->resize = true;
        $morris->data = array(
            array( 'label' => $resultado->tamanho_formatado, 'value' => $resultado->percentual_tabela ),
            array( 'label' => $resultado->tamanho_menos_tabela, 'value' => $resultado->percentual_restante),

        );
        $morris->formatter = 'REPLACE';

        ?>
        </div>
</div>
<?php
        echo $morris->toJavascript();
    }



    public static function tamanhoTabela($usuario, $tabela)
    {
        self::openDb();

        $dbUsuario = pg_escape_string($usuario);
        $dbTabela = pg_escape_string($tabela);
        $dbres = pg_query(" SELECT tamanho_formatado,
                                   round((tamanho::numeric * 100) / tamanho_base_de_dados::numeric,2) as percentual_tabela,
                                   round(100 - ((tamanho::numeric * 100) / tamanho_base_de_dados::numeric),2) as percentual_restante,
                                   pg_size_pretty((tamanho_base_de_dados::numeric - tamanho::numeric)) as tamanho_menos_tabela,
                                   data_coleta,
                                   relname,
                                   TO_CHAR(MAX(data_coleta), 'dd/mm/yyyy hh24:mi') as data_coleta_formatada
                              FROM stat_tabela A
                        INNER JOIN (SELECT usuario,
                                           tamanho_base_de_dados_formatado,
                                           tamanho_base_de_dados
                                      FROM stat_base_de_dados
                                     WHERE data_coleta = (select obtemultimacoleta('stat_base_de_dados', '$dbUsuario')::timestamp)) B
                                ON A.usuario = B.usuario
                           WHERE A.usuario = '$dbUsuario'
                             AND schemaname || '-' || relname = '$dbTabela'
                             AND data_coleta = (select obtemultimacoleta('stat_tabela', '$dbUsuario'))
                        GROUP BY 1,2,3,4,5,6");

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }

        return $result[0];

    }




}
?>
