<?php

/**
 * Table data gateway.
 *
 *  OK I'm using old MySQL driver, so kill me ...
 *  This will do for simple apps but for serious apps you should use PDO.
 */

 require_once 'core/DbConection.php';

class LoginModel extends DbConection
{
    private $table;

    public function __construct()
    {
        $this->table = 'smbd_usuario';
    }

    public function validade( $usuario, $senha )
    {
        try
        {
            $this->openDb();

            $usuario = pg_escape_string($usuario);
            $senha = md5(pg_escape_string($senha));

            // Validação do usuário/senha digitados
            $sql = "SELECT usuario,
                           nome
                      FROM smbd_usuario
                     WHERE (email = '$usuario')
                       AND (senha = '$senha')
                       AND (ativo = true) LIMIT 1";

            $result = pg_query($sql);

            $ob = pg_fetch_object($result);

            $return = false;
            if (is_object($ob))
            {
                $return = true;
            }

            return $return;

        }
        catch (Exception $e)
        {
            $this->closeDb();
            throw $e;
        }
    }
}

?>
