<?php

//Arquivo de log de erros
$log = fopen('/var/log/smbd.log', 'a+');
$contadorWs1 = 1;

//Instancia da classe coletora
$smbdColetor = new smbdColetor();

//Execução
while(1)
{
    try
    {
        if($contadorWs1 <= 1)
        {
            $smbdColetor->stat_sgbd_versao();
            //$returnWs1 = $smbdColetor->stat_base_de_dados();
            $contadorWs1++;
        }
    }
    catch ( Exception $e )
    {
        fwrite($log, "Erro na coleta de dados! : {$e->getMessage()} \n");
    }
}

fclose($f);


class smbdColetor
{

    private $host     ='localhost';
    private $port     ='5432';
    private $user     ='postgres';
    private $password ='postgres';
    private $dbname   ='avaliacao';
    private $url = 'http://smbd.com.br';
    private $clientOptions = array();
    private $client;
    private $sgbdVersao = '9.3';


    public function __construct()
    {
        $this->defineClientOptions();
        $this->client = new SoapClient(NULL, $this->getClientOptions());

    }


    public function ws1()
    {
        $dados = new stdClass();
        $dados->tabela = 'testetabela';
        $dados->outrosdados = 'dados';

        $result = $this->client->wsTeste($dados);
    }



    public function stat_sgbd_versao()
    {
        $this->openDb();
        $dbres = pg_query("SELECT version()");

        $dados = $this->fetchObject($dbres);
        $this->closeDb();

        $result = $this->client->wsTeste($dados);
    }


    public function stat_base_de_dados()
    {

        $this->openDb();

        $colunas = '';
        if ($this->sgbdVersao >= '8.3')
        {
            $colunas .= ", tup_returned, tup_fetched, tup_inserted, tup_updated, tup_deleted";
        }
        if ($this->sgbdVersao >= '9.1')
        {
            $colunas .= "conflicts, date_trunc('seconds', stats_reset) AS stats_reset";
        }
        if ($this->sgbdVersao >= '9.2')
        {
            $colunas .= ", temp_files, temp_bytes, deadlocks, blk_read_time, blk_write_time";
        }

        $dbres = pg_query("SELECT date_trunc('seconds', now()),
                                  datid,
                                  datname,
                                  numbackends,
                                  xact_commit,
                                  xact_rollback,
                                  blks_read,
                                  blks_hit ".
                                  {$colunas} .
                            "FROM pg_stat_database");

        $dados = $this->fetchObject($dbres);
        $this->closeDb();

        $result = $this->client->wsTeste($dados);

    }




    protected function defineClientOptions()
    {
        $this->clientOptions["location"] = $this->url . "/webservice.php";
        $this->clientOptions["uri"] = "$this->url";
        $this->clientOptions["encoding"] = "UTF-8";
    }

    protected function getClientOptions()
    {
        return $this->clientOptions;
    }

    protected function openDb()
    {
        if (!$con = pg_connect("host={$this->host}
                                port={$this->port}
                                user={$this->user}
                                password={$this->password}
                                dbname={$this->dbname}"))
        {
            throw new Exception("Connection to the database server failed!");
        }
        if (!pg_dbname($con)) {
            throw new Exception("No mvc-crud database found on database server.");
        }
    }

    protected function closeDb()
    {
        pg_close();
    }

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

/*

$horafixa = strtotime("12:00:00");
$horaatual = strtotime("now");

strtotime("+1 minute",$suaData)


if($horaatual > $horafixa){
print("Agora a hora é maior\n");
print($horaatual);
}



$novaHora1 = $turno1_inicio;

for ($i=0; $i <= $turno1_fim; $i++){

    $horaNova = strtotime("$novaHora1 + $duracao minutes");
    $horaFormatada = date("H:i",$horaNova);

    echo "Nova Hora :".$horaFormatada."<br>";
    $novaHora1 = $horaFormatada;
}


//hora inicial
$horaInicial = new DateTime('07:00');

//hora final
$horaFinal = new DateTime('08:00');

echo 'Hora Inicial: '.$horaInicial->format('H:i')."<br />";

//O valor é somado dentro do while, para evitar que repita a hora final
while($horaInicial->add(new DateInterval('PT20M')) < $horaFinal) {
    echo 'Hora intermediária: '.$horaInicial->format('H:i')."<br />";
}
echo 'Hora Final: '.$horaFinal->format('H:i')."<br />";



*/



?>
