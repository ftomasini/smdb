<?php


//require_once 'model/ContactsModel.php';
//require_once 'core/Controller.php';

class UsuarioController extends Controller
{

    private $usuarioModel = NULL;
    private $core = NULL;

    public function __construct()
    {

        $this->core = new Core();
        if (!$this->core->isLogged())
        {
            $this->redirect('../../index.php');
        }
        $this->usuarioModel = new UsuarioModel();
    }


    public function save()
    {
       $errors = array();

        if ( isset($_POST['form-submitted']) )
        {
            $nome = isset($_POST['nome']) ? $_POST['nome'] : NULL;
            $senha = isset($_POST['senhaNova']) ? $_POST['senhaNova'] : NULL;
            $id = isset($_POST['id']) ? $_POST['id'] : NULL;

            try
            {
                $this->usuarioModel->update($nome, $senha,$id);

            }
            catch (ValidationException $e)
            {
                $errors = $e->getErrors();
            }
        }
        $this->redirect('handlerUsuario.php?op=show&m=true');
    }


    public function show()
    {
        $id = isset($_SESSION['UsuarioID']) ? $_SESSION['UsuarioID'] : NULL;
        if ( ! $id)
        {
            throw new Exception('Internal error.');
        }
        $usuario = $this->usuarioModel->selectByEmail($_SESSION['UsuarioID']);


        //var_dump($usuario);
        include '../../view/usuario.php';
    }
}
?>
