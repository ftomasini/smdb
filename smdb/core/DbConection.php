<?php


class DbConection
{

    public $dbcon;


    public function __construct()
    {
        $this->dbcon = $this->openDb();
    }

    protected function openDb()
    {
        if (! $con = pg_connect("host=localhost port=5432 user=postgres password=postgres dbname=smbd"))
        {
            throw new Exception("Connection to the database server failed!");
        }
        if (!pg_dbname($con))
        {
            throw new Exception("No mvc-crud database found on database server.");
        }
    }

    protected function closeDb()
    {
        pg_close();
    }
}
?>
