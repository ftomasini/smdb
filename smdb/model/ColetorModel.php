<?php


class ColetorModel extends DbConection
{

    public function __construct()
    {
    }

    public function insert_stat_sgbd_versao( $data )
    {
        $this->openDb();
        $result = pg_query("INSERT INTO stat_sgbd_versao
                                        (usuario,
                                         data_coleta,
                                         versao)
                   VALUES (bdValor($data->usuario),
                           bdValor($data->data_coleta),
                           bdValor($data->versao))");
        $this->closeDb();


        $teste = "INSERT INTO stat_sgbd_versao
                                        (usuario,
                                         data_coleta,
                                         versao)
                   VALUES ({$this->bdValor($data->usuario)},
                           {$this->bdValor($data->data_coleta)},
                           {$this->bdValor($data->versao)})";

        if ( !$result )
        {
            $error = pg_last_error($this->dbcon);
            throw new Exception($error);
        }


        $f = fopen('/tmp/teste.log', 'a+');
        fwrite($f, $teste);


        return $result;
    }


    public function bdValor($valor)
    {
        $result = ($valor != NULL)?"'".pg_escape_string($valor)."'":'NULL';
        return $result;
    }
}

?>
