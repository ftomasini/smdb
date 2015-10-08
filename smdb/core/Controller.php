<?php

class Controller
{
    public function __construct()
    {
    }

    protected function redirect($location)
    {
        header('Location: '.$location);
    }

    public function handleRequest()
    {
        $op = isset($_GET['op'])?$_GET['op']:NULL;
        try
        {
            if ( !$op || $op == 'list' )
            {
                $this->listar();
            }
            elseif ( $op == 'new' )
            {
                $this->save();
            }
            elseif ( $op == 'delete' )
            {
                $this->delete();
            }
            elseif ( $op == 'show' )
            {
                $this->show();
            }
            elseif ( $op == 'publica' )
            {
                $this->publica();
            }
            else
            {
                $this->showError("Page not found", "Page for operation ".$op." was not found!");
            }
        }
        catch ( Exception $e )
        {
            // some unknown Exception got through here, use application error page to display it
            $this->showError("Application error", $e->getMessage());
        }
    }

    protected function showError($title, $message)
    {
        include file_exists('view/error.php') ? 'view/error.php' : '../../view/error.php';
    }

    protected function showSuccess($title, $message)
    {
        include file_exists('view/success.php') ? 'view/success.php' : '../../view/success.php';
    }
}
?>
