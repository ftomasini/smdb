<?php
/**
 * Módulo coletor de estatísticas
 * para o SGBD PostgreSQL do
 * sistema SMBD (Sistema de monitoramento de base de dados)
 */

$config = array(
    //base de dados
    'host' => 'localhost',
    'port' => '5432',
    'user' => 'postgres',
    'password' => 'postgres',
    'dbname' => 'avaliacao',
    //smbd
    'url' => 'http://smbd.com.br',
    'sgbd > 9.2' => true,
    'usuario' => 'ftomasini.rs@gmail.com',
    //intervalo de coleta
    'tempo_coleta_sgbd_versao' => '200 minutes',
    'tempo_coleta_base_de_dados' => '200 minutes',
    'tempo_coleta_tabela' => '200 minutes',
    'tempo_coleta_indice' => '200 minutes',
    'tempo_coleta_configuracoes' => '200 minutes',
    'tempo_coleta_loadavg' => '200 minutes',
    'tempo_coleta_memoria' => '200 minutes',
    'tempo_coleta_processos' => '1 minutes',
    'tempo_coleta_bloqueios' => '200 minutes',
    );

//Arquivo de log de erros
$log = fopen('/var/log/smbd.log', 'a+');

//Instancia da classe coletora
$smbdColetor = new smbdColetor($config);
//Define configurações de coleta para versão do banco de dados
$coletaSgbdVersao = new Coleta($config['tempo_coleta_sgbd_versao']);
//Define configurações de coleta para estatísticas do banco de dados
$coletaBaseDeDados = new Coleta($config['tempo_coleta_base_de_dados']);
//Define configurações de coleta para tabelas do banco de dados
$coletaTabela = new Coleta($config['tempo_coleta_tabela']);
//Define configurações de coleta para índices do banco de dados
$coletaIndice = new Coleta($config['tempo_coleta_indice']);
//Define configurações de coleta para configurações da base de dados
$coletaConfiguracao = new Coleta($config['tempo_coleta_configuracoes']);
//Define configurações de coleta para loadavg
$coletaLoadavg = new Coleta($config['tempo_coleta_loadavg']);
//Define configurações de coleta para memória do servidor
$coletaMemoria = new Coleta($config['tempo_coleta_memoria']);
//Define configurações de coleta para processos em execução
$coletaProcessos = new Coleta($config['tempo_coleta_processos']);
//Define configurações de coleta para bloqueios
$coletaBloqueios = new Coleta($config['tempo_coleta_bloqueios']);


echo "[OK] Serviço inicializado com sucesso! \n";

//Execução
while(1)
{
    try
    {
        //Coleta da versão do banco de dados
        $smbdColetor->stat_sgbd_versao($coletaSgbdVersao);
        //Coleta de estatisticas do banco de dados
        $smbdColetor->stat_base_de_dados($coletaBaseDeDados);
        //Coleta de estatísticas das tabelas do banco de dados
        $smbdColetor->stat_tabela($coletaTabela);
        //Coleta de estatísticas dos índices do banco de dados
        $smbdColetor->stat_indice($coletaIndice);
        //Coleta de configurações do bando de dados
        $smbdColetor->stat_configuracao_base_de_dados($coletaConfiguracao);
        //Coleta do load do servidor
        $smbdColetor->stat_loadavg($coletaLoadavg);
        //Coleta do uso da memória do servidor
        $smbdColetor->stat_memoria($coletaMemoria);
        //Coleta dos processos em execução no servidor
        $smbdColetor->stat_processos($coletaProcessos);
        //Coleta dos bloqueios
        $smbdColetor->stat_bloqueios($coletaBloqueios);
    }
    catch ( Exception $e )
    {
        fwrite($log, "Erro na coleta de dados! : {$e->getMessage()} \n");
    }
}

// Fecha arquivo de log do coletor de dados
fclose($f);

/**
 * Class smbdColetor
 * Classe responsável por centralizar a logica das coletas.
 *
 */
class smbdColetor
{
    private $host;
    private $port;
    private $user;
    private $password;
    private $dbname;
    private $url;
    private $clientOptions = array();
    private $client;
    private $usuario;


    /**
     * Construtor da classe
     */
    public function __construct($config)
    {

        $this->host = $config['host'];
        $this->port = $config['port'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->dbname = $config['dbname'];
        $this->url = $config['url'];
        $this->usuario = $config['usuario'];
        $this->defineClientOptions();
        $this->client = new SoapClient(NULL, $this->getClientOptions());
    }


    /**
     * Obtém a versão da base de dados
     * que está sendo analisada
     *
     *
     * @throws Exception
     */
    public function stat_sgbd_versao($coleta)
    {
        if( $coleta->verificaColeta() )
        {
            $coleta->proximaColeta = date('d-m-Y H:i:s', strtotime("+$coleta->tempoColeta"));
            $coletaAtual = date('d-m-Y H:i:s');

            $this->openDb();
            $dbres = pg_query("SELECT '{$coletaAtual}' as data_coleta,
                                      '{$this->usuario}' as usuario,
                                      version() as versao");

            $dados = array();
            if(count($dbres)>0)
            {
                $dados = $this->fetchObject($dbres);
            }
            $this->closeDb();

            $result = $this->client->ws($dados, 'stat_sgbd_versao');

            if ($result)
            {
                echo "Estatística versão do servidor coletada!! \n";
            }
            else
            {
                throw new Exception('Não foi possível obter a versão do banco de dados ' . $result);
            }
        }
    }


    /**
     * Obtém estatísticas da base de dados.
     * @throws Exception
     */
    public function stat_base_de_dados($coleta)
    {
        if( $coleta->verificaColeta() )
        {
            $coleta->proximaColeta = date('d-m-Y H:i:s', strtotime("+$coleta->tempoColeta"));
            $coletaAtual = date('d-m-Y H:i:s');
            $this->openDb();
            $bdNome = pg_escape_string($this->dbname);

            $dbres = pg_query("SELECT '{$coletaAtual}' as data_coleta,
                                      '{$this->usuario}' as usuario,
                                      pg_stat_database.datid,
                                      pg_stat_database.datname,
                                      pg_stat_database.numbackends,
                                      pg_stat_database.xact_commit,
                                      pg_stat_database.xact_rollback,
                                      pg_stat_database.blks_read,
                                      pg_stat_database.blks_hit,
                                      pg_stat_database.tup_returned,
                                      pg_stat_database.tup_fetched,
                                      pg_stat_database.tup_inserted,
                                      pg_stat_database.tup_updated,
                                      pg_stat_database.tup_deleted,
                                      pg_stat_database.conflicts,
                                      date_trunc('seconds', pg_stat_database.stats_reset) AS stats_reset,
                                      --conflitos
                                      pg_stat_database_conflicts.confl_tablespace,
                                      pg_stat_database_conflicts.confl_lock,
                                      pg_stat_database_conflicts.confl_snapshot,
                                      pg_stat_database_conflicts.confl_bufferpin,
                                      pg_stat_database_conflicts.confl_deadlock,
                                      --cache
                                      (SELECT round(AVG(
                                             (heap_blks_hit + COALESCE(toast_blks_hit, 0))::numeric /
                                             (heap_blks_read + COALESCE(toast_blks_read, 0) +
                                             heap_blks_hit + COALESCE(toast_blks_hit,0))::numeric * 100), 3) as hit_ratio
                                        FROM pg_statio_user_tables
                                       WHERE (heap_blks_read + COALESCE(toast_blks_read, 0)) + heap_blks_hit + COALESCE(toast_blks_hit) > 0) as hit_ratio,
                                       pg_size_pretty(pg_database_size(pg_stat_database.datname)) as tamanho_base_de_dados_formatado,
                                       pg_database_size(pg_stat_database.datname) as tamanho_base_de_dados
                                 FROM pg_stat_database
                           INNER JOIN pg_stat_database_conflicts
                                   ON pg_stat_database.datname = pg_stat_database_conflicts.datname
                                WHERE pg_stat_database.datname= '$bdNome'");

            $dados = array();
            if(count($dbres)>0)
            {
                $dados = $this->fetchObject($dbres);
            }
            $this->closeDb();

            $result = $this->client->ws($dados, 'stat_base_de_dados');

            if ($result)
            {
                echo "Estatísticas de base de dados coletadas!! \n";
            }
            else
            {
                throw new Exception('Não foi possível obter as estatísticas do banco de dados');
            }
        }

    }

    /**
     * Obtém estatísticas das tabelas do
     * banco de dados que está sendo analisado
     *
     * @throws Exception
     */
    public function stat_tabela($coleta)
    {
        if( $coleta->verificaColeta() )
        {
            $coleta->proximaColeta = date('d-m-Y H:i:s', strtotime("+$coleta->tempoColeta"));
            $coletaAtual = date('d-m-Y H:i:s');
            $this->openDb();

            $dbres = pg_query("SELECT '{$coletaAtual}' as data_coleta,
                                      '{$this->usuario}' as usuario,
                                      relid,
                                      schemaname,
                                      relname,
                                      seq_scan,
                                      seq_tup_read,
                                      idx_scan,
                                      idx_tup_fetch,
                                      n_tup_ins,
                                      n_tup_upd,
                                      n_tup_del,
                                      n_tup_hot_upd,
                                      n_live_tup,
                                      n_dead_tup,
                                      last_vacuum,
                                      last_autovacuum,
                                      last_analyze,
                                      last_autoanalyze,
                                      vacuum_count,
                                      autovacuum_count,
                                      analyze_count,
                                      autoanalyze_count,
                                      pg_relation_size(relid) as tamanho,
                                      pg_total_relation_size(relid)as tamanho_com_indices,
                                      pg_size_pretty(pg_relation_size(relid)) as tamanho_formatado,
                                      pg_size_pretty(pg_total_relation_size(relid)) as tamanho_com_indices_formatado,
                                      (SELECT round((heap_blks_hit + COALESCE(toast_blks_hit, 0))::numeric /
                                                   (heap_blks_read + COALESCE(toast_blks_read, 0) +
                                                    heap_blks_hit + COALESCE(toast_blks_hit,0))::numeric * 100, 3) as hit_ratio
                                        FROM pg_statio_user_tables
                                       WHERE pg_stat_user_tables.relname = pg_statio_user_tables.relname
                                         AND pg_stat_user_tables.schemaname = pg_statio_user_tables.schemaname
                                         AND pg_statio_user_tables.schemaname = 'public'
                                         AND (heap_blks_read + COALESCE(toast_blks_read, 0)) + heap_blks_hit + COALESCE(toast_blks_hit) > 0) as aproveitamento_cache
                                 FROM pg_stat_user_tables");

            $dados = array();
            if(count($dbres)>0)
            {
                $dados = $this->fetchObject($dbres);
            }
            $this->closeDb();
            $result = $this->client->ws($dados, 'stat_tabela');

            if ($result)
            {
                echo "Estatísticas de tabela coletada!! \n";
            }
            else
            {
                throw new Exception('Não foi possível obter estatísticas das tabelas do banco de dados');
            }
        }
    }

    /**
     * Obtém estatísticas dos índices
     * da base de dados que está sendo analisada
     *
     * @throws Exception
     */
    public function stat_indice($coleta)
    {
        if( $coleta->verificaColeta() )
        {
            $coleta->proximaColeta = date('d-m-Y H:i:s', strtotime("+$coleta->tempoColeta"));
            $coletaAtual = date('d-m-Y H:i:s');
            $this->openDb();

            $dbres = pg_query("SELECT '{$coletaAtual}' as data_coleta,
                                      '{$this->usuario}' as usuario,
                                      relid,
                                      indexrelid,
                                      schemaname,
                                      relname,
                                      indexrelname,
                                      idx_scan,
                                      idx_tup_read,
                                      idx_tup_fetch,
                                      (idx_scan > 0) as utilizado,
                                      pg_relation_size(indexrelid) as tamanho,
                                      pg_total_relation_size(relid)as tamanho_com_indices,
                                      pg_size_pretty(pg_relation_size(indexrelid)) as tamanho_formatado,
                                      pg_size_pretty(pg_total_relation_size(relid)) as tamanho_com_indices_formatado
                                 FROM pg_stat_user_indexes");
            $dados = array();
            if(count($dbres)>0)
            {
                $dados = $this->fetchObject($dbres);
            }
            $this->closeDb();
            $result = $this->client->ws($dados, 'stat_indice');

            if ($result)
            {
                echo "Estatistica de índice coletada!! \n";
            }
            else
            {
                throw new Exception('Não foi possível obter estatísticas de índices');
            }
        }
    }

    /**
     * Obtém configurações
     * da base de dados que está sendo analisada
     *
     * @throws Exception
     */
    public function stat_configuracao_base_de_dados($coleta)
    {
        if( $coleta->verificaColeta() )
        {
            $coleta->proximaColeta = date('d-m-Y H:i:s', strtotime("+$coleta->tempoColeta"));
            $coletaAtual = date('d-m-Y H:i:s');
            $this->openDb();

            $dbres = pg_query(" SELECT '{$coletaAtual}' as data_coleta,
                                       '{$this->usuario}' as usuario,
                                       name,
                                       setting,
                                       unit,
                                       category,
                                       short_desc,
                                       extra_desc,
                                       context,
                                       vartype,
                                       source,
                                       min_val,
                                       max_val,
                                       enumvals,
                                       boot_val,
                                       reset_val,
                                       sourcefile,
                                       sourceline
                                  FROM pg_settings");
            $dados = array();
            if(count($dbres)>0)
            {
                $dados = $this->fetchObject($dbres);
            }
            $this->closeDb();
            $result = $this->client->ws($dados, 'stat_configuracao_base_de_dados');

            if ($result)
            {
                echo "Configurações da base de dados coletadas!! \n";
            }
            else
            {
                throw new Exception('Não foi possível as configurações da base de dados');
            }
        }
    }

    /**
     * Obtém loadavg do servidor
     * da base de dados que está sendo analisada
     *
     * @throws Exception
     */
    public function stat_loadavg($coleta)
    {
        if( $coleta->verificaColeta() )
        {
            $coleta->proximaColeta = date('d-m-Y H:i:s', strtotime("+$coleta->tempoColeta"));
            $coletaAtual = date('d-m-Y H:i:s');

            $this->openDb();

            $dbres = pg_query("SELECT '{$coletaAtual}' as data_coleta,
                                      '{$this->usuario}' as usuario,
                                      load1 as load_ultimo_minuto,
                                      load5 as load_ultimos_5_minutos,
                                      load15 as load_ultimos_15_minutos
                                 FROM pg_loadavg();");
            $dados = array();
            if(count($dbres)>0)
            {
                $dados = $this->fetchObject($dbres);
            }
            $this->closeDb();
            $result = $this->client->ws($dados, 'stat_loadavg');

            if ($result && $dados)
            {
                echo "Loadavg do servidor coletado!! \n";
            }
            else
            {
                throw new Exception('Não foi possível obter o load do servidor');
            }
        }
    }

    /**
     * Obtém memoria do servidor
     * da base de dados que está sendo analisada
     *
     * @throws Exception
     */
    public function stat_memoria($coleta)
    {
        if( $coleta->verificaColeta() )
        {
            $coleta->proximaColeta = date('d-m-Y H:i:s', strtotime("+$coleta->tempoColeta"));
            $coletaAtual = date('d-m-Y H:i:s');

            $this->openDb();

            $dbres = pg_query("SELECT '{$coletaAtual}' as data_coleta,
                                      '{$this->usuario}' as usuario,
                                      pg_size_pretty(memused * 1024) as format_memused,
                                      pg_size_pretty(memfree * 1024) as format_memfree,
                                      pg_size_pretty(memshared * 1024) as format_memshared,
                                      pg_size_pretty(membuffers * 1024) as format_membuffers,
                                      pg_size_pretty(memcached * 1024) as format_memcached,
                                      pg_size_pretty(swapused * 1024) as format_swapused,
                                      pg_size_pretty(swapfree * 1024) as format_swapfree,
                                      pg_size_pretty(swapcached * 1024) as format_swapcached,
                                      memused,
                                      memfree,
                                      memshared,
                                      membuffers,
                                      memcached,
                                      swapused,
                                      swapfree,
                                      swapcached
                                 FROM pg_memusage();");
            $dados = array();
            if(count($dbres)>0)
            {
                $dados = $this->fetchObject($dbres);
            }

            $this->closeDb();
            $result = $this->client->ws($dados, 'stat_memoria');

            if ($result)
            {
                echo "Memória do servidor coletada coletadas!! \n";
            }
            else
            {
                throw new Exception('Não foi possível obter status da memória do servidor');
            }
        }
    }

    /**
     * Obtém processos em exucução na base de dados
     * da base de dados que está sendo analisada
     *
     * @throws Exception
     */
    public function stat_processos($coleta)
    {
        if( $coleta->verificaColeta() )
        {
            $coleta->proximaColeta = date('d-m-Y H:i:s', strtotime("+$coleta->tempoColeta"));
            $coletaAtual = date('d-m-Y H:i:s');

            $this->openDb();
            $bdNome = pg_escape_string($this->dbname);

            $dbres = pg_query(" SELECT '{$coletaAtual}' as data_coleta,
                                       '{$this->usuario}' as usuario,
                                       pg_stat_activity.datname,
                                       pg_stat_activity.usename,
                                       pg_stat_activity.pid,
                                       priority,
                                       pg_size_pretty(B.rss * 1024) as memoria,
                                       B.state,
                                       pg_stat_activity.query,
                                       'PROC_SMBD_COLETOR' as identificador,
                                       date_trunc('seconds', pg_stat_activity.backend_start) AS inicio_processo,
                                       date_trunc('seconds', now()) AS hora_coleta,
                                       date_trunc('seconds',SUM(now() - pg_stat_activity.backend_start)) as tempo_execussao
                                  FROM pg_stat_activity
                            INNER JOIN pg_proctab() B
                                    ON pg_stat_activity.pid = B.pid
                                   AND pg_stat_activity.query not ilike '%PROC_SMBD_COLETOR%'
                                 WHERE datname = '$bdNome'
                              GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12; ");
            $dados = array();
            if(count($dbres)>0)
            {
                $dados = $this->fetchObject($dbres);
            }
            $this->closeDb();
            $result = $this->client->ws($dados, 'stat_processos');

            if ($result)
            {
                echo "Processos em execução coletados \n";
            }
            else
            {
                throw new Exception('Não foi possível obter os processos em execução');
            }
        }
    }


    /**
     * Obtém bloqueios da base de dados
     *
     * @throws Exception
     */
    public function stat_bloqueios($coleta)
    {
        if( $coleta->verificaColeta() )
        {
            $coleta->proximaColeta = date('d-m-Y H:i:s', strtotime("+$coleta->tempoColeta"));
            $coletaAtual = date('d-m-Y H:i:s');

            $this->openDb();

            $bdNome = pg_escape_string($this->dbname);

            $dbres = pg_query(" SELECT '{$coletaAtual}' as data_coleta,
                                       '{$this->usuario}' as usuario,
                                       pg_stat_activity.datname,
                                       pg_stat_activity.usename,
                                       pg_stat_activity.pid,
                                       priority,
                                       pg_size_pretty(B.rss * 1024) as memoria,
                                       B.state,
                                       pg_stat_activity.query,
                                       C.mode,
                                       C.granted,
                                       'PROC_SMBD_COLETOR' as identificador,
                                       date_trunc('seconds', pg_stat_activity.backend_start) AS inicio_processo,
                                       date_trunc('seconds', now()) AS hora_coleta,
                                       date_trunc('seconds',SUM(now() - pg_stat_activity.backend_start)) as tempo_execussao
                                  FROM pg_stat_activity
                            INNER JOIN pg_proctab() B
                                    ON pg_stat_activity.pid = B.pid
                            INNER JOIN pg_locks C
                                    ON pg_stat_activity.pid = C.pid
                                   AND pg_stat_activity.query not ilike '%PROC_SMBD_COLETOR%'
                                 WHERE datname = '$bdNome'
                              GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14; ");
            $dados = array();
            if(count($dbres)>0)
            {
                $dados = $this->fetchObject($dbres);
            }
            $this->closeDb();
            $result = $this->client->ws($dados, 'stat_bloqueios');
            $result = true;

            if ($result)
            {
                echo "Bloqueios coletados \n";
            }
            else
            {
                throw new Exception('Não foi possível obter os processos em execução');
            }
        }
    }

    /**
     * Define parâmetros de conexão
     */
    protected function defineClientOptions()
    {
        $this->clientOptions["location"] = $this->url . "/webservice.php";
        $this->clientOptions["uri"] = "$this->url";
        $this->clientOptions["encoding"] = "UTF-8";
    }

    /**
     * Obtém configurações de conexão com sistema externo
     * @return array
     */
    protected function getClientOptions()
    {
        return $this->clientOptions;
    }

    /**
     * Abre conexão com o banco de dados
     * @throws Exception
     */
    protected function openDb()
    {
        if (!$con = pg_connect("host={$this->host}
                                port={$this->port}
                                user={$this->user}
                                password={$this->password}
                                dbname={$this->dbname}"))
        {
            throw new Exception("Conexão com a base de dados falhou!");
        }

        if (!pg_dbname($con))
        {
            throw new Exception("A base de dados {$this->dbname} não foi encontrada no servidor.");
        }

        return $con;
    }

    /**
     * Fecha conexão com
     * o banco de dados
     */
    protected function closeDb()
    {
        pg_close();
    }

    /**
     * Transforma o array de dados obtido pelo banco
     * em um array de objetos stdclass
     *
     * @param $data
     * @return array
     */
    protected function fetchObject($data)
    {
        $returnData = array();
        while ( ($obj = pg_fetch_object($data)) != NULL )
        {
            $returnData[] = $obj;
        }
        return $returnData;
    }

}

class Coleta
{
    public $tempoColeta;
    public $proximaColeta;

    /**
     * Construtor da classe
     */
    public function __construct($tempoColeta)
    {
        $this->tempoColeta = $tempoColeta;
    }

    /**
     * Verifica se está na hora de efetuar a coleta
     * @return bool
     */
    public function verificaColeta()
    {
        $retorno = false;

        if((!$this->proximaColeta) || strtotime($this->proximaColeta) < strtotime(date('d-m-Y H:i:s')) )
        {
            $retorno = true;
        }

        return $retorno;
    }
}

?>
