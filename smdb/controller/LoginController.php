
<?php

require_once 'model/LoginModel.php';
require_once 'core/Controller.php';

class LoginController extends Controller
{

    private $loginModel = NULL;


    public function __construct()
    {
        $this->loginModel = new LoginModel();
    }

	public function handleRequest()
	{
		$op = isset($_GET['op'])?$_GET['op']:NULL;
		try
		{
			if ( $op == 'check' )
			{
				$this->login();
			}
			else if ( !is_null($op) )
			{
				$this->showError("Page not found", "Page for operation ".$op." was not found!");
			}
			else
			{
				return;
			}
		}
		catch ( Exception $e )
		{
			$this->showError("Erro", $e->getMessage());
		}
	}



    public function login()
    {
		$errors = array();
        $this->loginModel = new LoginModel();

		$usuario = isset($_POST['usuario']) ? $_POST['usuario']:NULL;
		$senha = isset($_POST['senha']) ? $_POST['senha']:NULL;

		$retorno = $this->validateLogin($usuario, $senha);


		try
		{
			if (!$retorno)
			{
				throw new Exception('Usuário e senha inválido', 1);
			}
			else
			{
                $core = new Core();
				if (!isset($_SESSION) && !($core->isLoged()))
				{
					session_start();
				}
                if(!($core->isLoged()))
                {
					// Salva os dados encontrados na sess�o
				$_SESSION['UsuarioID'] = $usuario;//$resultado['id'];
				$_SESSION['UsuarioNome'] = $usuario;//$resultado['nome'];
                }

                    //var_dump($_SESSION);die();
			$this->redirect('view/handler/handlerContacts.php');
			}
		}
		catch (Exception $e)
		{
			$this->showError("", $e->getMessage());
		}

	}


	private function validateLogin( $usuario, $senha)
    {
		$return = false;
		$return = $this->loginModel->validade($usuario, $senha);
        if ( !empty($usuario) && !empty($senha) && $return)
        {
			$return = true;
        }

		return $return;
	}

}
?>
