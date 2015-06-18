<?php

/**
 * Table data gateway.
 *
 *  OK I'm using old MySQL driver, so kill me ...
 *  This will do for simple apps but for serious apps you should use PDO.
 */

//require_once 'core/DbConection.php';

class ContactsModel extends DbConection
{
  private $table;

  public function __construct()
  {
      $this->table = 'contacts';
  }

    public function selectAll($order)
    {
        $this->openDb();
        if ( !isset($order) )
        {
            $order = "name";
        }

        $dbOrder = pg_escape_string($order);
        $dbres = pg_query("SELECT *
                             FROM contacts
                         ORDER BY $dbOrder ASC");

        $contacts = array();
        while ( ($obj = pg_fetch_object($dbres)) != NULL )
        {
            $contacts[] = $obj;
        }

        return $contacts;
    }

    public function selectById($id)
    {
        $dbId = pg_escape_string($id);

        $dbres = pg_query("SELECT * FROM contacts WHERE id=$dbId");

        return pg_fetch_object($dbres);

    }

    public function insert( $name, $phone, $email, $address )
    {
        $dbName = ($name != NULL)?"'".pg_escape_string($name)."'":'NULL';
        $dbPhone = ($phone != NULL)?"'".pg_escape_string($phone)."'":'NULL';
        $dbEmail = ($email != NULL)?"'".pg_escape_string($email)."'":'NULL';
        $dbAddress = ($address != NULL)?"'".pg_escape_string($address)."'":'NULL';

        pg_query("INSERT INTO contacts (name,
                                        phone,
                                        email,
                                        address)
                       VALUES ($dbName,
                               $dbPhone,
                               $dbEmail,
                               $dbAddress)");
        return true;
    }

    public function delete($id)
    {
        $dbId = pg_escape_string($id);

        pg_query("DELETE
                    FROM contacts
                   WHERE id=$dbId");
    }


    public function getAllContacts($order)
    {
        try
        {
            $res = $this->selectAll($order);
            return $res;
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    public function getContact($id)
    {
        try
        {
            $res = $this->contactsGateway->selectById($id);
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
        return $this->find($id);
    }

    private function validateContactParams( $name, $phone, $email, $address )
    {
        $errors = array();
        if ( !isset($name) || empty($name) )
        {
            $errors[] = 'Name is required';
        }
        if ( empty($errors) )
        {
            return;
        }
        throw new ValidationException($errors);
    }

    public function createNewContact( $name, $phone, $email, $address )
    {
        try
        {
            $this->openDb();
            $this->validateContactParams($name, $phone, $email, $address);
            $res = $this->insert($name, $phone, $email, $address);
            $this->closeDb();
            return $res;
        }
        catch (Exception $e)
        {
            $this->closeDb();
            throw $e;
        }
    }

    public function deleteContact( $id )
    {
        try
        {
            $this->openDb();
            $res = $this->delete($id);
            $this->closeDb();
        }
        catch (Exception $e)
        {
            $this->closeDb();
            throw $e;
        }
    }








}

?>
