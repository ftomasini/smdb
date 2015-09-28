<?php

class UsuarioModel extends DbConection
{
  private $table;

  public function __construct()
  {
      $this->table = 'smbd_usuario';
  }

    public function update( $name, $senha, $id )
    {
        $this->openDb();

        $dbId = ($id != NULL)?"'".pg_escape_string($id)."'":'NULL';
        $dbName = ($name != NULL)?"'".pg_escape_string($name)."'":'NULL';

        if (strlen($senha)>0)
        {
            $dbSenha = ($senha != NULL) ? "'" . pg_escape_string($senha) . "'" : 'NULL';
        }

        $stringQuery = "UPDATE smbd_usuario ";

        if (strlen($senha)>0)
        {
            $stringQuery .= "SET nome = $dbName,
                                senha = md5($dbSenha) ";
        }
        else
        {
            $stringQuery .= "SET nome = $dbName ";
        }

        $stringQuery .= "WHERE usuario = $dbId";
        pg_query($stringQuery);
        return true;
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
