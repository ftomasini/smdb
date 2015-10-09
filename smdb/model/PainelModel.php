<?php

/**
 * Table data gateway.
 *
 *  OK I'm using old MySQL driver, so kill me ...
 *  This will do for simple apps but for serious apps you should use PDO.
 */

//require_once 'core/DbConection.php';

class PainelModel extends DbConection
{
    private $table;

    public function __construct()
    {
        $this->table = 'painel';
    }

    public static function selectAll($usuario)
    {
        self::openDb();

        $dbUsuario = ($usuario != NULL)?"'".pg_escape_string($usuario)."'":'NULL';
        $dbres = pg_query("SELECT usuario,
                                  metodo
                             FROM public.smbd_principal
                             WHERE usuario = $dbUsuario");

        $resultado = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $resultado[] = $obj;
        }

        return $resultado;
    }



    public function insert( $usuario, $metodo )
    {
        $this->openDb();
        $dbUsuario = ($usuario != NULL)?"'".pg_escape_string($usuario)."'":'NULL';
        $dbMetodo = ($metodo != NULL)?"'".pg_escape_string($metodo)."'":'NULL';

        pg_query("INSERT INTO public.smbd_principal
                              (usuario,
                               metodo)
                       VALUES ($dbUsuario,
                               $dbMetodo)");
        return true;
    }

    public function delete( $usuario, $metodo )
    {
        $this->openDb();
        $dbUsuario = ($usuario != NULL)?"'".pg_escape_string($usuario)."'":'NULL';
        $dbMetodo = ($metodo != NULL)?"'".pg_escape_string($metodo)."'":'NULL';

        pg_query("DELETE
                    FROM public.smbd_principal
                   WHERE usuario=$dbUsuario
                     AND metodo=$dbMetodo
                    ");
    }

}

?>
