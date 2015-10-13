<?php

class Estatistica extends DbConection
{
    public function __construct()
    {
    }


    public static function tamanhoBaseDeDadosChart($usuario =null, $tabela= null)
    {
        $resultado = self::tamanhoTabela($usuario, null, true);
        ?>
        <!-- DONUT CHART -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?php print htmlentities("Tamanho de cada tabela da base de dados. "); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <p class="btn btn-box-tool" type="button" data-widget="" rel1 = "tamanhoBaseDeDadosChart" rel2 = <?php print htmlentities($usuario); ?> rel3 ="" id="btn_publicar1"><i class="fa fa-dashboard"></i> Inserir / Remover do painel</p>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div id="<?php print htmlentities("tamanhoBaseDeDadosChart".$tabela);?>"</div>
        <?php
        if (is_array($resultado))
        {
            $morris = new MorrisDonutCharts("tamanhoBaseDeDadosChart".$tabela);
            $morris->resize = true;

            $outros = 0;
            foreach ($resultado as $r)
            {
                if ( $r->percentual_tabela > 1)
                {
                $data['label'] = $r->tamanho_formatado . ' - '. $r->relname;
                $data['value'] = $r->percentual_tabela;
                $ret[] = $data;
                }
                else
                {
                    $outros = $outros + $r->percentual_tabela;
                }
            }
            $data['label'] = 'Outras tabelas';
            $data['value'] = $outros;
            $ret[] = $data;

            $morris->data = $ret;

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
            <?php echo "Data da coleta " .  $resultado[0]->data_coleta_formatada; ?>
        </div>
        <?php

    }

    public static function tamanhoBaseDeDadosComIndicesChart($usuario =null, $tabela= null)
    {
        $resultado = self::tamanhoTabelaComIndices($usuario, null, true);
        ?>
        <!-- DONUT CHART -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?php print htmlentities("Tamanho de cada tabela + indices da base de dados. "); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <p class="btn btn-box-tool" type="button" data-widget="" rel1 = "tamanhoBaseDeDadosComIndicesChart" rel2 = <?php print htmlentities($usuario); ?> rel3 ="" id="btn_publicar1"><i class="fa fa-dashboard"></i> Inserir / Remover do painel</p>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div id="<?php print htmlentities("tamanhoBaseDeDadosComIndicesChart".$tabela);?>"</div>
        <?php
        if (is_array($resultado))
        {
            $morris = new MorrisDonutCharts("tamanhoBaseDeDadosComIndicesChart".$tabela);
            $morris->resize = true;

            $outros = 0;
            foreach ($resultado as $r)
            {
                if ( $r->percentual_tabela > 1)
                {
                $data['label'] = $r->tamanho_com_indices_formatado . ' - '. $r->relname;
                $data['value'] = $r->percentual_tabela;
                $ret[] = $data;
                }
                else
                {
                    $outros = $outros + $r->percentual_tabela;
                }
            }
            $data['label'] = 'Outras tabelas';
            $data['value'] = $outros;
            $ret[] = $data;

            $morris->data = $ret;

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
            <?php echo "Data da coleta " .  $resultado[0]->data_coleta_formatada; ?>
        </div>
        <?php

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

    public static function loadChart($usuario =null, $tabela= null)
    {
        $resultado = self::loadAvg($usuario, false);

        ?>
        <!-- DONUT CHART -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?php print htmlentities("Load avarege no dia atual."); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <p class="btn btn-box-tool" type="button" data-widget="" rel1 = "loadChart" rel2 = <?php print htmlentities($usuario); ?> rel3 = "" id="btn_publicar1"><i class="fa fa-dashboard"></i> Inserir / Remover do painel</p>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div id="<?php print htmlentities("loadChart".$tabela);?>"</div>
        <?php


        if (is_array($resultado))
        {
            $morris = new MorrisLineCharts("loadChart");
            $morris->xkey = array( 'date' );
            $morris->resize = true;
            $morris->hideHover = 'auto';
            $morris->ykeys = array( 'value' );
            $morris->labels = array( 'Load average' );


            foreach ($resultado as $r)
            {
                $data['date'] = $r->data_coleta;
                $data['value'] = $r->load_ultimo_minuto;
                $ret[] = $data;
            }
            $morris->data = $ret;

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
            <?php echo "Data da coleta " .  $resultado[0]->data_coleta_formatada; ?>
        </div>
        <?php

    }


    public static function memoriaChart($usuario =null, $tabela= null)
    {
        $resultado = self::memoria($usuario, false);

        ?>
        <!-- DONUT CHART -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?php print htmlentities("Memória no dia atual."); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <p class="btn btn-box-tool" type="button" data-widget="" rel1 = "memoriaChart" rel2 = <?php print htmlentities($usuario); ?> rel3 = "" id="btn_publicar1"><i class="fa fa-dashboard"></i> Inserir / Remover do painel</p>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div id="<?php print htmlentities("memoriaChart".$tabela);?>"</div>
        <?php


        if (is_array($resultado))
        {
            $morris = new MorrisLineCharts("memoriaChart");
            $morris->xkey = array( 'date' );
            $morris->resize = true;
            $morris->hideHover = 'auto';
            $morris->ykeys = array( 'value' );
            $morris->labels = array( 'Memória usada' );


            foreach ($resultado as $r)
            {
                $data['date'] = $r->data_coleta;
                $data['value'] = $r->memused;
                $ret[] = $data;
            }
            $morris->data = $ret;

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
            <?php echo "Data da coleta " .  $resultado[0]->data_coleta_formatada; ?>
        </div>
        <?php

    }


    public static function tamanhoTabelaComIndicesChart($usuario =null, $tabela= null)
    {
        $resultado = self::tamanhoTabelaComIndices($usuario, $tabela);
        ?>
        <!-- DONUT CHART -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?php print htmlentities("Tamanho da tabela ". $tabela ." + indices em relação a base de dados. "); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <p class="btn btn-box-tool" type="button" data-widget="" rel1 = "tamanhoTabelaComIndicesChart" rel2 = <?php print htmlentities($usuario); ?> rel3 = <?php print htmlentities($tabela); ?> id="btn_publicar1"><i class="fa fa-dashboard"></i> Inserir / Remover do painel</p>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div id="<?php print htmlentities("tamanhoComIndices".$tabela);?>"</div>
        <?php
        if (is_object($resultado))
        {
            $morris = new MorrisDonutCharts("tamanhoComIndices".$tabela);
            $morris->resize = true;
            $morris->data = array(
                array('label' => $resultado->tamanho_com_indices_formatado, 'value' => $resultado->percentual_tabela),
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

public static function aproveitamentoCacheBaseDeDadosChart($usuario =null, $tabela= null)
    {
        $resultado = self::aproveitamentoCacheBaseDeDados($usuario);
        ?>
        <!-- DONUT CHART -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?php print htmlentities("Percentual de dados encontrados em cache em todas as consultas efetuadas na base de dados. "); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <p class="btn btn-box-tool" type="button" data-widget="" rel1 = "aproveitamentoCacheBaseDeDadosChart" rel2 = <?php print htmlentities($usuario); ?> rel3 = "" id="btn_publicar1"><i class="fa fa-dashboard"></i> Inserir / Remover do painel</p>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div id="<?php print htmlentities("aproveitamentoCacheBaseDeDadosChart".$tabela);?>"</div>
        <?php
        if (is_object($resultado))
        {
            $morris = new MorrisDonutCharts("aproveitamentoCacheBaseDeDadosChart".$tabela);
            $morris->resize = true;
            $morris->data = array(
                array('label' => 'Encontrados em cache', 'value' => $resultado->aproveitamento_cache_formatado),
                array('label' => 'Não encontrados em cache', 'value' => $resultado->nao_aproveitamento_cache_formatado),

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


    public static function aproveitamentoCacheTabelaChart($usuario =null, $tabela= null)
    {
        $resultado = self::aproveitamentoCacheTabela($usuario, $tabela);
        ?>
        <!-- DONUT CHART -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?php print htmlentities("Percentual de dados encontrados em cache em todas as consultas efetuadas na tabela ". $tabela .". "); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <p class="btn btn-box-tool" type="button" data-widget="" rel1 = "aproveitamentoCacheTabelaChart" rel2 = <?php print htmlentities($usuario); ?> rel3 = <?php print htmlentities($tabela); ?> id="btn_publicar1"><i class="fa fa-dashboard"></i> Inserir / Remover do painel</p>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div id="<?php print htmlentities("aproveitamentoCacheTabelaChart".$tabela);?>"</div>
        <?php
        if (is_object($resultado))
        {
            $morris = new MorrisDonutCharts("aproveitamentoCacheTabelaChart".$tabela);
            $morris->resize = true;
            $morris->data = array(
                array('label' => 'Encontrados em cache', 'value' => $resultado->aproveitamento_cache_formatado),
                array('label' => 'Não encontrados em cache', 'value' => $resultado->nao_aproveitamento_cache_formatado),

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
                            Último vaccum executado manualmente: <b><?php print htmlentities("$resultado->last_vacuum"); ?></b><br>
                            Último vaccum executado pelo autovacuum: <b><?php print htmlentities("$resultado->last_autovacuum"); ?></b><br>
                            Último vaccum analyze executado manualmente: <b><?php print htmlentities("$resultado->last_analyze"); ?></b><br>
                            Último vaccum analyze executado pelo autovacuum: <b><?php print htmlentities("$resultado->last_autoanalyze"); ?></b><br>
                            Nº de vezes que o vaccum foi executado manualmente: <b><?php print htmlentities("$resultado->vacuum_count"); ?></b><br>
                            Nº de vezes que o vaccum foi executado pelo autovacuum: <b><?php print htmlentities("$resultado->autovacuum_count"); ?></b><br>
                            Nº de vezes que o vaccum analyze foi executado manualmente: <b><?php print htmlentities("$resultado->analyze_count"); ?></b><br>
                            Nº de vezes que o vaccum analyze foi executado pelo autovacuum: <b><?php print htmlentities("$resultado->autoanalyze_count"); ?></b><br>
                        </div>

                        <div class="col-sm-4 invoice-col">
                            <b>Registros</b><br>
                            <br>
                            Número de linhas inseridas: <b><?php print htmlentities("$resultado->n_tup_ins"); ?></b><br>
                            Número de linhas atualizadas: <b><?php print htmlentities("$resultado->n_tup_upd"); ?></b><br>
                            Número de linhas excluídas: <b><?php print htmlentities("$resultado->n_tup_del"); ?></b><br>
                            Número de linhas vivas: <b><?php print htmlentities("$resultado->n_dead_tup"); ?></b><br>
                            Número de linhas mortas: <b><?php print htmlentities("$resultado->n_dead_tup"); ?></b><br>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <b>Varreduras</b><br>
                            <br>
                            Número de varreduras seqüenciais: <b><?php print htmlentities("$resultado->seq_scan"); ?></b><br>
                            Número de varreduras indexadas: <b><?php print htmlentities("$resultado->idx_scan"); ?></b><br>
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


    public static function consultasLentasChart($usuario)
    {
        $resultado = self::consultasLentas($usuario);
        ?>


        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?php print htmlentities("Comandos mais lentos do dia. "); ?></h3>

                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <p class="btn btn-box-tool" type="button" data-widget="" rel1 = "consultasLentasChart" rel2 = <?php print htmlentities($usuario); ?> rel3 = "" id="btn_publicar1"><i class="fa fa-dashboard"></i> Inserir / Remover do painel</p>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div class="row">
                    <div class="col-xs-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Data/hora coleta</th>
                                <th>Comando</th>
                                <th>Tempo de execução</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($resultado as $linha): ?>
                                <tr>
                                    <td><font size="2" color="red"><?php print htmlentities($linha->data_coleta_formatada); ?></font></td>
                                    <td><font size="2" color="red"><?php print htmlentities($linha->query); ?></font></td>
                                    <td><font size="2" color="red"><?php print htmlentities($linha->tempo_execussao); ?></font></td>

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



    public static function tabelasComPoucaPesquisaPorIndicesChart($usuario, $tabela)
    {
        $resultado = self::tabelasComPoucaPesquisaPorIndices($usuario);
        ?>

        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?php print htmlentities("Tabelas com poucas pesquisas que utilizaram índice." . $tabela . ". "); ?></h3>

                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <p class="btn btn-box-tool" type="button" data-widget="" rel1 = "tabelasComPoucaPesquisaPorIndicesChart" rel2 = <?php print htmlentities($usuario); ?> rel3 = "" id="btn_publicar1"><i class="fa fa-dashboard"></i> Inserir / Remover do painel</p>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div class="row">
                    <div class="col-xs-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Nome da tabela</th>
                                <th>Percentual de consultas que utilizaram índices</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($resultado as $linha): ?>
                                <tr>
                                    <td><font size="2" color="red"><?php print htmlentities($linha->tabela); ?></font></td>
                                    <td><font size="2" color="red"><?php print htmlentities($linha->idx_percent . '%'); ?></font></td>
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
                                <th>Número de varreduras nesse índice</th>
                                <th>Tamanho do índice</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($resultado as $linha): ?>
                                <tr>
                                    <td><font size="2" color="red"><?php print htmlentities($linha->indexrelname); ?></font></td>
                                    <td><font size="2" color="red"><?php print htmlentities($linha->idx_scan); ?></font></td>
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
                                <th>Número de varreduras nesse índice</th>
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


    public static function informacoesBaseDeDadosChart($usuario, $tabela)
    {
        $resultado = self::informacoesBaseDeDados($usuario, $tabela);
        ?>

        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?php print htmlentities("Informações diversas da base de dados."); ?></h3>

                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <p class="btn btn-box-tool" type="button" data-widget="" rel1 = "informacoesBaseDeDadosChart" rel2 = <?php print htmlentities($usuario); ?> rel3 = <?php print htmlentities($tabela); ?> id="btn_publicar1"><i class="fa fa-dashboard"></i> Inserir / Remover do painel</p>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div class="box-header with-border">
                    <!-- info row -->
                    <div class="row invoice-info">
                        <!-- /.col -->
                            <b>Transações</b><br>
                            <br>
                            Número de transações neste banco de dados que foram efetuadas com sucesso: <b><?php print htmlentities("$resultado->xact_commit"); ?></b><br>
                            Número de transações neste banco de dados que foram revertidas: <b><?php print htmlentities("$resultado->xact_rollback"); ?></b><br>
                            Número de blocos de disco lidos neste banco de dados: <b><?php print htmlentities("$resultado->blks_read"); ?></b><br>
                            Número de vezes que os blocos de disco já foram encontrados no cache de buffer: <b><?php print htmlentities("$resultado->blks_hit"); ?></b><br>
                            <br>
                            <br>

                            <b>Registros</b><br>
                            <br>
                            Número de linhas retornadas por consultas neste banco de dados: <b><?php print htmlentities("$resultado->tup_returned"); ?></b><br>
                           <br>
                           <br>

                            <b>Consultas</b><br>
                            <br>
                            Número de consultas canceladas devido a conflitos neste banco de dados: <b><?php print htmlentities("$resultado->conflicts"); ?></b><br>
                            Número de consultas neste banco de dados que tenham sido cancelada devido a tempo limite de bloqueio: <b><?php print htmlentities("$resultado->confl_lock"); ?></b><br>
                            Número de consultas neste banco de dados que foram cancelados devido a impasses: <b><?php print htmlentities("$resultado->confl_deadlock"); ?></b><br>
                            <br>
                            <br>

                            <b>Outros</b><br>
                            <br>
                            Aproveitamento do cache: <b><?php print htmlentities("$resultado->hit_ratio %"); ?></b><br>
                            Tamanho da base de dados: <b><?php print htmlentities("$resultado->tamanho_base_de_dados_formatado"); ?></b><br>
                            Data e hora em que essas estatísticas foram resetadas pelo PostgreSQL: <b><?php print htmlentities("$resultado->stats_reset"); ?></b><br>

                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <?php echo "Data da coleta " .  $resultado->data_coleta_formatada; ?>
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

    public static function informacoesBaseDeDados($usuario, $tabela)
    {
        self::openDb();

        $dbUsuario = pg_escape_string($usuario);

        $dbres = pg_query(" SELECT datid,
                                   datname,
                                   numbackends,
                                   xact_commit,
                                   xact_rollback,
                                   blks_read,
                                   blks_hit,
                                   tup_returned,
                                   tup_fetched,
                                   tup_inserted,
                                   tup_updated,
                                   tup_deleted,
                                   conflicts,
                                   TO_CHAR(stats_reset::timestamp, 'dd/mm/yyyy hh24:mi')  as stats_reset,
                                   confl_tablespace,
                                   confl_lock,
                                   confl_snapshot,
                                   confl_bufferpin,
                                   confl_deadlock,
                                   hit_ratio,
                                   tamanho_base_de_dados_formatado,
                                   tamanho_base_de_dados,
                                   TO_CHAR(data_coleta, 'dd/mm/yyyy hh24:mi') as data_coleta_formatada
                              FROM stat_base_de_dados A
                           WHERE A.usuario = '$dbUsuario'
                             AND data_coleta = (select obtemultimacoleta('stat_base_de_dados', '$dbUsuario'))
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

    public function bloqueios($usuario)
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
                                  mode,
                                  'PROC_SMBD_COLETOR' as identificador,
                                  inicio_processo,
                                  tempo_execussao
                             FROM stat_bloqueios
                            WHERE data_coleta = (select obtemultimacoleta('stat_bloqueios', '$dbUsuario'))");

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


    public static function tamanhoTabela($usuario, $tabela, $todas = false)
    {
        self::openDb();


        $dbUsuario = pg_escape_string($usuario);
        $dbTabela = pg_escape_string($tabela);
        $parte = "AND schemaname || '-' || relname = '$dbTabela'";
        if ($todas)
        {
            $parte = "AND 1=1";
        }

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
                             {$parte}
                             AND data_coleta = (select obtemultimacoleta('stat_tabela', '$dbUsuario'))
                        GROUP BY 1,2,3,4,5,6");

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }

        $return = $result;
        if (!$todas)
        {
            $return = $result[0];
        }
        return $return;
    }



    public static function tamanhoTabelaComIndices($usuario, $tabela, $todas = false)
    {
        self::openDb();
        $dbUsuario = pg_escape_string($usuario);
        $dbTabela = pg_escape_string($tabela);
        $parte = "AND schemaname || '-' || relname = '$dbTabela'";
        if ($todas)
        {
            $parte = "AND 1=1";
        }

        $dbUsuario = pg_escape_string($usuario);
        $dbTabela = pg_escape_string($tabela);
        $dbres = pg_query(" SELECT tamanho_com_indices_formatado,
                                   round((tamanho_com_indices::numeric * 100) / tamanho_base_de_dados::numeric,2) as percentual_tabela,
                                   round(100 - ((tamanho_com_indices::numeric * 100) / tamanho_base_de_dados::numeric),2) as percentual_restante,
                                   pg_size_pretty((tamanho_base_de_dados::numeric - tamanho_com_indices::numeric)) as tamanho_menos_tabela,
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
                             {$parte}
                             AND data_coleta = (select obtemultimacoleta('stat_tabela', '$dbUsuario'))
                        GROUP BY 1,2,3,4,5,6");

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }
        $return = $result;
        if (!$todas)
        {
            $return = $result[0];
        }
        return $return;

    }

    public static function aproveitamentoCacheBaseDeDados($usuario)
    {
        self::openDb();

        $dbUsuario = pg_escape_string($usuario);
        $dbres = pg_query("SELECT round(coalesce(hit_ratio::numeric,0.0),2) as aproveitamento_cache_formatado,
                                  round(100 - coalesce(hit_ratio::numeric,0.0),2) as nao_aproveitamento_cache_formatado,
                                  data_coleta,
                                  TO_CHAR(data_coleta, 'dd/mm/yyyy hh24:mi') as data_coleta_formatada
                              FROM stat_base_de_dados A
                             WHERE A.usuario = '$dbUsuario'
                               AND data_coleta = (select obtemultimacoleta('stat_base_de_dados', '$dbUsuario'))
                               ");

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }

        return $result[0];

    }



    public static function aproveitamentoCacheTabela($usuario, $tabela)
    {
        self::openDb();

        $dbUsuario = pg_escape_string($usuario);
        $dbTabela = pg_escape_string($tabela);
        $dbres = pg_query("SELECT round(coalesce(aproveitamento_cache::numeric,0.0),2) as aproveitamento_cache_formatado,
                                  round(100 - coalesce(aproveitamento_cache::numeric,0.0),2) as nao_aproveitamento_cache_formatado,
                                  data_coleta,
                                  relname,
                                  TO_CHAR(data_coleta, 'dd/mm/yyyy hh24:mi') as data_coleta_formatada
                              FROM stat_tabela A
                             WHERE A.usuario = '$dbUsuario'
                               AND data_coleta = (select obtemultimacoleta('stat_tabela', '$dbUsuario'))
                               AND schemaname || '-' || relname = '$dbTabela'");

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }

        return $result[0];

    }



    public static function loadAvg($usuario, $somenteUltimo = false)
    {
        self::openDb();

        $filtraData = '::date';
        if($somenteUltimo)
        {
            $filtraData = '';
        }
        $dbUsuario = pg_escape_string($usuario);

        $dbres = pg_query("SELECT usuario,
                                  data_coleta,
                                  TO_CHAR(data_coleta, 'dd/mm/yyyy hh24:mi') as data_coleta_formatada,
                                  load_ultimo_minuto,
                                  load_ultimos_5_minutos,
                                  load_ultimos_15_minutos
                             FROM stat_loadavg
                            WHERE usuario = '$dbUsuario'
                              AND data_coleta{$filtraData} = (select obtemultimacoleta('stat_loadavg', '$dbUsuario')){$filtraData} ");

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }

        $return = $result;
        if($somenteUltimo)
        {
            $return = $result[0];
        }

        return $return;
    }

    public static function memoria($usuario, $somenteUltimo = false)
    {
        self::openDb();

        $filtraData = '::date';
        if($somenteUltimo)
        {
            $filtraData = '';
        }
        $dbUsuario = pg_escape_string($usuario);

        $dbres = pg_query(" SELECT data_coleta,
                                   memused,
                                   format_memused,
                                   format_memfree,
                                   format_memshared,
                                   format_membuffers,
                                   format_memcached,
                                   format_swapused,
                                   format_swapfree,
                                   format_swapcached,
                                   TO_CHAR(data_coleta, 'dd/mm/yyyy hh24:mi') as data_coleta_formatada,
                                   round(((memused::int * 100) / (memused::int + memfree::int))) as percentual
                              FROM public.stat_memoria
                              WHERE usuario = '$dbUsuario'
                              AND data_coleta{$filtraData} = (select obtemultimacoleta('stat_memoria', '$dbUsuario')){$filtraData}");

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }

        $return = $result;
        if($somenteUltimo)
        {
            $return = $result[0];
        }

        return $return;
    }



    public static function tabelasComPoucaPesquisaPorIndices($usuario)
    {
        self::openDb();

        $dbUsuario = pg_escape_string($usuario);

        $dbres = pg_query(" SELECT data_coleta,
                                   schemaname || '-' || relname as tabela,
                                   round(idx_scan::numeric / (seq_scan::numeric + idx_scan::numeric)::numeric * 100,2) as idx_percent,
                                   TO_CHAR(data_coleta, 'dd/mm/yyyy hh24:mi') as data_coleta_formatada
                              FROM public.stat_tabela
                              WHERE usuario = '$dbUsuario'
                                AND seq_scan::numeric + idx_scan::numeric > 0
                              AND data_coleta = (select obtemultimacoleta('stat_tabela', '$dbUsuario'))
                              ORDER BY idx_percent, seq_scan DESC");

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }

        $return = $result;

        return $return;
    }


    public static function consultasLentas($usuario)
    {
        self::openDb();


        $dbUsuario = pg_escape_string($usuario);

        $dbres = pg_query("SELECT usuario,
                                  data_coleta,
                                  TO_CHAR(data_coleta, 'dd/mm/yyyy hh24:mi') as data_coleta_formatada,
                                  query,
                                  tempo_execussao
                             FROM stat_processos
                            WHERE usuario = '$dbUsuario'
                              AND data_coleta::date = (select obtemultimacoleta('stat_processos', '$dbUsuario'))::date
                              ORDER BY tempo_execussao LIMIT 50 ");
                              

        $result = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $result[] = $obj;
        }

        $return = $result;

        return $return;
    }

}
?>
