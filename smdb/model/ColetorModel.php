<?php


class ColetorModel extends DbConection
{

    public function __construct()
    {
    }

    public static function insert_stat_sgbd_versao( $data )
    {
        $this->openDb();
        $result = pg_query("INSERT INTO stat_sgbd_versao
                                        (usuario,
                                         data_coleta,
                                         versao)
                   VALUES ($this->bdValor($data->usuario),
                           $this->bdValor($data->data_coleta),
                           $this->bdValor($data->versao))");
        $this->closeDb();

        return $result;
    }


    public function bdValor($valor)
    {
        $result = ($valor != NULL)?"'".pg_escape_string($valor)."'":'NULL';
        return $result;
    }
}

?>