<?php

class Estatistica extends DbConection
{
    public function __construct()
    {
    }

    public static function tamanhoTabelaChart($usuario =null, $tabela= null)
    {
        $resultado = self::tamanhoTabela($usuario, $tabela);
        ?>
        <!-- DONUT CHART -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?php print htmlentities("Tamanho da tabela ". $tabela ." em relação a base de dados. "); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <p class="btn btn-box-tool" type="button" data-widget="" rel1 = "tamanhoTabelaChart" rel2 = <?php print htmlentities($usuario); ?> rel3 = <?php print htmlentities($tabela); ?> id="btn_publicar1"><i class="fa fa-dashboard"></i> Inserir / Remover do painel</p>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div id="<?php print htmlentities("tamanho".$tabela);?>"</div>
        <?php
        if (is_object($resultado))
        {
            $morris = new MorrisDonutCharts("tamanho".$tabela);
            $morris->resize = true;
            $morris->data = array(
                array('label' => $resultado->tamanho_formatado, 'value' => $resultado->percentual_tabela),
                array('label' => $resultado->tamanho_menos_tabela, 'value' => $resultado->percentual_restante),

            );

            $morris->formatter = 'REPLACE';

            echo $morris->toJavascript();
            ?>
            <?php
        }
        else
        {
            ?>
            <div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            <?php print htmlentities('Não foram encontrados dados para esse relatório' . '') ?>
            </div>
            <?php
        }
        ?>
        </div>
        </div>
            <?php echo "Data da coleta " .  $resultado->data_coleta_formatada; ?>
        </div>
        <?php

    }




    public static function informacoesTabelaChart($usuario, $tabela)
    {
        $resultado = self::informacoesTabela($usuario, $tabela);
        ?>

        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?php print htmlentities("Informações diversas da tabela " . $tabela . ". "); ?></h3>

                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <p class="btn btn-box-tool" type="button" data-widget="" rel1 = "informacoesTabelaChart" rel2 = <?php print htmlentities($usuario); ?> rel3 = <?php print htmlentities($tabela); ?> id="btn_publicar1"><i class="fa fa-dashboard"></i> Inserir / Remover do painel</p>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div class="box-header with-border">
                    <!-- info row -->
                    <div class="row invoice-info">
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <b>Vacuum</b><br>
                            <br>
                            <b>Última vez em que o vaccum foi executado manualmente:</b> <?php print htmlentities("$resultado->last_vacuum"); ?><br>
                            <b>Última vez em que o vaccum foi executado pelo autovacuum:</b> <?php print htmlentities("$resultado->last_autovacuum"); ?><br>
                            <b>Última vez em que o vaccum analyze foi executado manualmente:</b> <?php print htmlentities("$resultado->last_analyze"); ?><br>
                            <b>Última vez em que o vaccum analyze foi executado pelo autovacuum:</b> <?php print htmlentities("$resultado->last_autoanalyze"); ?><br>
                            <b>Número de vezes que o vaccum foi executado manualmente:</b> <?php print htmlentities("$resultado->vacuum_count"); ?><br>
                            <b>Número de vezes que o vaccum foi executado pelo autovacuum:</b> <?php print htmlentities("$resultado->autovacuum_count"); ?><br>
                            <b>Número de vezes que o vaccum analyze foi executado manualmente:</b> <?php print htmlentities("$resultado->analyze_count"); ?><br>
                            <b>Número de vezes que o vaccum analyze foi executado pelo autovacuum:</b> <?php print htmlentities("$resultado->autoanalyze_count"); ?><br>
                        </div>

                        <div class="col-sm-4 invoice-col">
                            <b>Registros</b><br>
                            <br>
                            <b>Número de linhas inseridas:</b> <?php print htmlentities("$resultado->n_tup_ins"); ?><br>
                            <b>Número de linhas atualizadas:</b> <?php print htmlentities("$resultado->n_tup_upd"); ?><br>
                            <b>Número de linhas excluídas:</b> <?php print htmlentities("$resultado->n_tup_del"); ?><br>
                            <b>Número de linhas vivas:</b> <?php print htmlentities("$resultado->n_dead_tup"); ?><br>
                            <b>Número de linhas mortas:</b> <?php print htmlentities("$resultado->n_dead_tup"); ?><br>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <b>Varreduras</b><br>
                            <br>
                            <b>Número de varreduras seqüenciais:</b> <?php print htmlentities("$resultado->seq_scan"); ?><br>
                            <b>Número de linhas ao vivas buscadas por varreduras seqüenciais:</b> <?php print htmlentities("$resultado->seq_tup_read"); ?><br>
                            <b>Número de varreduras indexadas:</b> <?php print htmlentities("$resultado->idx_scan"); ?><br>
                            <b>Número de linhas ao vivas buscadas por varreduras indexadas:</b> <?php print htmlentities("$resultado->idx_tup_fetch"); ?><br>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <?php echo "Data da coleta " .  $resultado->data_coleta_formatada; ?>
        </div>

        <?php
    }


    public static function indicesNaoUtilizadosChart($usuario, $tabela)
    {
        $resultado = self::indicesNaoUtilizados($usuario, $tabela);
        ?>

        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?php print htmlentities("Índices não utilizados da tabela " . $tabela . ". "); ?></h3>

                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <p class="btn btn-box-tool" type="button" data-widget="" rel1 = "indicesNaoUtilizadosChart" rel2 = <?php print htmlentities($usuario); ?> rel3 = <?php print htmlentities($tabela); ?> id="btn_publicar1"><i class="fa fa-dashboard"></i> Inserir / Remover do painel</p>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div class="row">
                    <div class="col-xs-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Número de varreduras sequenciais</th>
                                <th>Número de linhas retornados por scans neste índice</th>
                                <th>Número de linhas vivas buscadas pelo índice</th>
                                <th>Tamanho do índice</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($resultado as $linha): ?>
                                <tr>
                                    <td><font size="2" color="red"><?php print htmlentities($linha->indexrelname); ?></font></td>
                                    <td><font size="2" color="red"><?php print htmlentities($linha->idx_scan); ?></font></td>
                                    <td><font size="2" color="red"><?php print htmlentities($linha->idx_tup_read); ?></font></td>
                                    <td><font size="2" color="red"><?php print htmlentities($linha->idx_tup_fetch); ?></font></td>
                                    <td><font size="2" color="red"><?php print htmlentities($linha->tamanho_formatado); ?></font></td>

                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.col
                </div>
                <!-- /.row -->
            </div>
        </div>
            <?php echo "Data da coleta " .  (isset($resultado[0]) ? $resultado[0]->data_coleta_formatada : '-'); ?>
        </div>
        <?php
    }

    public static function indicesUtilizadosChart($usuario, $tabela)
    {
        $resultado = self::indicesUtilizados($usuario, $tabela);
        ?>

        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?php print htmlentities("Índices utilizados da tabela " . $tabela . ". "); ?></h3>

                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <p class="btn btn-box-tool" type="button" data-widget="" rel1 = "indicesUtilizadosChart" rel2 = <?php print htmlentities($usuario); ?> rel3 = <?php print htmlentities($tabela); ?> id="btn_publicar1"><i class="fa fa-dashboard"></i> Inserir / Remover do painel</p>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div class="row">
                    <div class="col-xs-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Número de varreduras sequenciais</th>
                                <th>Número de linhas retornados por scans neste índice</th>
                                <th>Número de linhas vivas buscadas pelo índice</th>
                                <th>Tamanho do índice</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($resultado as $linha): ?>
                                <tr>
                                    <td><font size="2" color="blue"><?php print htmlentities($linha->indexrelname); ?></font></td>
                                    <td><font size="2" color="blue"><?php print htmlentities($linha->idx_scan); ?></font></td>
                                    <td><font size="2" color="blue"><?php print htmlentities($linha->idx_tup_read); ?></font></td>
                                    <td><font size="2" color="blue"><?php print htmlentities($linha->idx_tup_fetch); ?></font></td>
                                    <td><font size="2" color="blue"><?php print htmlentities($linha->tamanho_formatado); ?></font></td>

                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.col
                </div>
                <!-- /.row -->
                </div>
            </div>
            <?php echo "Data da coleta " .  (isset($resultado[0]) ? $resultado[0]->data_coleta_formatada : '-'); ?>
        </div>
            <?php
    }





    public static function informacoesTabela($usuario, $tabela)
    {
        self::openDb();

        $dbUsuario = pg_escape_string($usuario);
        $dbTabela = pg_escape_string($tabela);

        $dbres = pg_query(" SELECT seq_scan,
                                   seq_tup_read,
                                   idx_scan,
                                   idx_tup_fetch,
                                   n_tup_ins,
                                   n_tup_upd,
                                   n_tup_del,
                                   n_tup_hot_upd,
                                   n_live_tup,
                                   n_dead_tup,
                                   TO_CHAR(last_vacuum::timestamp, 'dd/mm/yyyy hh24:mi') as last_vacuum,
                                   TO_CHAR(last_autovacuum::timestamp, 'dd/mm/yyyy hh24:mi') as last_autovacuum,
                                   TO_CHAR(last_analyze::timestamp, 'dd/mm/yyyy hh24:mi') as last_analyze,
                                   TO_CHAR(last_autoanalyze::timestamp, 'dd/mm/yyyy hh24:mi') as last_autoanalyze,
                                   vacuum_count,
                                   autovacuum_count,
                                   analyze_count,
                                   autoanalyze_count,
                                   data_coleta,
                                   relname,
                                   TO_CHAR(data_coleta, 'dd/mm/yyyy hh24:mi') as data_coleta_formatada
                              FROM stat_tabela A
                           WHERE A.usuario = '$dbUsuario'
                             AND schemaname || '-' || relname = '$dbTabela'
                             AND data_coleta = (select obtemultimacoleta('stat_tabela', '$dbUsuario'))
                        ");

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }

        return $result[0];

    }


    public static function indicesUtilizados($usuario, $tabela)
    {
        self::openDb();

        $dbUsuario = pg_escape_string($usuario);
        $dbTabela = pg_escape_string($tabela);

        $dbres = pg_query(" SELECT relname,
                                   schemaname,
                                   indexrelname,
                                   idx_scan,
                                   idx_tup_read,
                                   idx_tup_fetch,
                                   utilizado,
                                   tamanho_formatado,
                                   TO_CHAR(data_coleta, 'dd/mm/yyyy hh24:mi') as data_coleta_formatada
                              FROM stat_indice a
                             WHERE utilizado = 't'
                               AND A.usuario = '$dbUsuario'
                               AND schemaname || '-' || relname = '$dbTabela'
                               AND data_coleta = (select obtemultimacoleta('stat_indice', '$dbUsuario'))");

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }

        return $result;
    }

    public static function indicesNaoUtilizados($usuario, $tabela)
    {
        self::openDb();

        $dbUsuario = pg_escape_string($usuario);
        $dbTabela = pg_escape_string($tabela);

        $dbres = pg_query(" SELECT relname,
                                   schemaname,
                                   indexrelname,
                                   idx_scan,
                                   idx_tup_read,
                                   idx_tup_fetch,
                                   utilizado,
                                   tamanho_formatado,
                                   TO_CHAR(data_coleta, 'dd/mm/yyyy hh24:mi') as data_coleta_formatada
                              FROM stat_indice a
                             WHERE utilizado = 'f'
                               AND A.usuario = '$dbUsuario'
                               AND schemaname || '-' || relname = '$dbTabela'
                               AND data_coleta = (select obtemultimacoleta('stat_indice', '$dbUsuario'))");

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }

        return $result;

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
                                   TO_CHAR(data_coleta, 'dd/mm/yyyy hh24:mi') as data_coleta_formatada
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
