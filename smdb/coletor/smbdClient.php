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
            $returnWs1 = $smbdColetor->ws2();
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
    private $url = 'http://46.101.150.238';
    private $clientOptions = array();
    private $client;

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


    public function ws2()
    {

        $this->openDb();

        $dbres = pg_query("SELECT *
                             FROM ONLY basperson limit 10");

        $dados = $this->fetchObject($dbres);
        $this->closeDb();

        echo date("H:i");

        //echo date("H:i",strtotime("10:10 + 5 minutes"));
        $result = $this->client->wsTeste($dados);

    }

    protected function defineClientOptions()
    {
        $this->clientOptions["location"] = $this->url . "/tcc/smdb/webservice.php";
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




?>
