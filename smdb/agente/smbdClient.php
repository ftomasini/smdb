<?php
/**
 * Módulo coletor de estatísticas
 * para o SGBD PostgreSQL do
 * sistema SMBD (Sistema de monitoramento de base de dados)
 */

$config = array(
    'host' => 'localhost',
    'port' => '5432',
    'user' => 'postgres',
    'password' => 'postgres',
    'dbname' => 'sagu_fametro',
    'url' => 'http://smbd.com.br',
    //tempo de coleta
    'tempo_coleta_sgbd_versao' => '1 minute',
    'tempo_coleta_base_de_dados' => '2 minutes',
    'tempo_coleta_tabela' => '2 minutes',
    'tempo_coleta_indice' => '2 minutes',
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
            $this->openDb();
            $dbres = pg_query("SELECT version()");
            $dados = $this->fetchObject($dbres);
            $this->closeDb();

            $result = $this->client->wsTeste($dados);

            $result = true;
            if ($result)
            {
                echo "Estatística versão do servidor coletada!! \n ";
                $coleta->proximaColeta = date('d-m-Y H:i:s', strtotime("+$coleta->tempoColeta"));
            }
            else
            {
                throw new Exception('Não foi possível obter a versão do banco de dados');
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
            $this->openDb();
            $bdNome = pg_escape_string($this->dbname);

            $dbres = pg_query("SELECT date_trunc('seconds', now()),
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
                                       WHERE (heap_blks_read + COALESCE(toast_blks_read, 0)) + heap_blks_hit + COALESCE(toast_blks_hit) > 0) as hit_ratio
                                 FROM pg_stat_database
                           INNER JOIN pg_stat_database_conflicts
                                   ON pg_stat_database.datname = pg_stat_database_conflicts.datname
                                WHERE pg_stat_database.datname= '$bdNome'");

            $dados = $this->fetchObject($dbres);
            $this->closeDb();

            $result = $this->client->wsTeste($dados);

            $result = true;
            if ($result)
            {
                echo "Estatísticas de base de dados coletadas!! \n ";
                $coleta->proximaColeta = date('d-m-Y H:i:s', strtotime("+$coleta->tempoColeta"));
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
            $this->openDb();

            $colunas = '';

            $dbres = pg_query("SELECT relid,
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
                                      pg_total_relation_size(relid)as tamanhocomindices,
                                      pg_size_pretty(pg_relation_size(relid)) as tamanhoformatado,
                                      pg_size_pretty(pg_total_relation_size(relid)) as tamanhocomindicesformatado,
                                      (SELECT round((heap_blks_hit + COALESCE(toast_blks_hit, 0))::numeric /
                                                   (heap_blks_read + COALESCE(toast_blks_read, 0) +
                                                    heap_blks_hit + COALESCE(toast_blks_hit,0))::numeric * 100, 3) as hit_ratio
                                        FROM pg_statio_user_tables
                                       WHERE pg_stat_user_tables.relname = pg_statio_user_tables.relname
                                         AND pg_stat_user_tables.schemaname = pg_statio_user_tables.schemaname
                                         AND pg_statio_user_tables.schemaname = 'public'
                                         AND (heap_blks_read + COALESCE(toast_blks_read, 0)) + heap_blks_hit + COALESCE(toast_blks_hit) > 0) as aproveitamento_cache
                                 FROM pg_stat_user_tables");

            $dados = $this->fetchObject($dbres);
            $this->closeDb();
            $result = $this->client->wsTeste($dados);
            $result = true;
            if ($result)
            {
                echo "Estatísticas de tabela coletada!! \n ";
                $coleta->proximaColeta = date('d-m-Y H:i:s', strtotime("+$coleta->tempoColeta"));
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
            $this->openDb();

            $dbres = pg_query("SELECT relid,
                                      indexrelid,
                                      schemaname,
                                      relname,
                                      indexrelname,
                                      idx_scan,
                                      idx_tup_read,
                                      idx_tup_fetch,
                                      (idx_scan > 0) as utilizado,
                                      pg_relation_size(relid) as tamanho,
                                      pg_total_relation_size(relid)as tamanhocomindices,
                                      pg_size_pretty(pg_relation_size(relid)) as tamanhoformatado,
                                      pg_size_pretty(pg_total_relation_size(relid)) as tamanhocomindicesformatado
                                 FROM pg_stat_user_indexes");
            $dados = $this->fetchObject($dbres);
            $this->closeDb();
            $result = $this->client->wsTeste($dados);
            $result = true;
            if ($result && $dados)
            {
                echo "Estatistica de índice coletada!! \n ";
                $coleta->proximaColeta = date('d-m-Y H:i:s', strtotime("+$coleta->tempoColeta"));
            }
            else
            {
                throw new Exception('Não foi possível obter estatatísticas de índices');
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
