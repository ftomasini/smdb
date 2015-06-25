<?php

class Core
{

    private static $core;
    
	public static function getInstance()
	{
	    if ( !self::$core )
	    {
	    	$self::$core = new Core();
	    }
	    
	    return self::$core;
	}	
	
    public function __construct()
    {
    }

}


    public function isLogged()
    {
        if (!isset($_SESSION))
        {
            session_start();
        }
        
        return isset($_SESSION['UsuarioID']);
    }
}
?>
