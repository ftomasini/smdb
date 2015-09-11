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
}

?>
