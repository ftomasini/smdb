<?php

/**
 * Table data gateway.
 *
 *  OK I'm using old MySQL driver, so kill me ...
 *  This will do for simple apps but for serious apps you should use PDO.
 */

//require_once 'core/DbConection.php';

class RegisterModel extends DbConection
{
  private $table;

  public function __construct()
  {
      $this->table = 'usuario';
  }


    public function insert( $name, $email )
    {
        $dbNome = ($name != NULL)?"'".pg_escape_string($name)."'":'NULL';
        $dbEmail = ($email != NULL)?"'".pg_escape_string($email)."'":'NULL';
        $senha = Core::createPassword();
        $dbSenha = ($senha != NULL)?"'".pg_escape_string($senha)."'":'NULL';

        $core = new Core();

        try
        {
            if (!$core->enviarEmail($email, $senha))
            {
                $error = "Não foi possível enviar um e-mail para $email. Verifique o endereço informado e tente novamente. :)";
                throw new Exception($error);
            }
            $this->openDb();

            $ok = pg_query("INSERT INTO smbd_usuario (nome,
                                          email,
                                          usuario,
                                          ativo,
                                          senha)
                           VALUES ($dbNome,
                                   $dbEmail,
                                   $dbEmail,
                                   true,
                                   md5($dbSenha))");
            if (!$ok)
            {
                throw new Exception($ok);
            }

            return true;
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }


    private function validateParams( $nome, $email )
    {
        try
        {
            $errors = array();
            if ( !isset($nome) || empty($nome) )
            {
                throw new Exception('Nome é requerido');
            }

            if ( !isset($email) || empty($email) )
            {
                throw new Exception('E-mail é requerido');
            }

            if(is_object($this->selectByEmail($email)))
            {
                throw new Exception( 'E-mail já utiizado em outro usuário');
            }

            return;
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    public function createNewUser( $nome, $email)
    {
        try
        {
            $this->openDb();
            $this->validateParams($nome, $email);
            $res = $this->insert($nome, $email);
            $this->closeDb();
            return $res;
        }
        catch (Exception $e)
        {
            $this->closeDb();
            throw $e;
        }
    }

    public function selectByEmail($email)
    {
        $email = pg_escape_string($email);
        $this->openDb();

        $dbres = pg_query("SELECT * FROM smbd_usuario WHERE email='$email'");

        return pg_fetch_object($dbres);

    }

}

?>
