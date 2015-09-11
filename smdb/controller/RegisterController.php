<?php


class RegisterController extends Controller
{

    private $registerModel = NULL;
    private $core = NULL;

    public function __construct()
    {

        $this->core = new Core();

        $this->registerModel = new RegisterModel();
    }


    public function save()
    {

        $title = 'Adicionar novo usuário';

        $nome = '';
        $email = '';

        $errors = array();

        if ( isset($_POST['form-submitted']) )
        {

            $nome = isset($_POST['nome']) ? $_POST['nome'] : NULL;
            $email = isset($_POST['email']) ? $_POST['email'] : NULL;

            try
            {
                $this->registerModel->createNewUser($nome, $email);
                //$this->redirect('../../index.php');
                $this->showSuccess('titulo', "Sua conta no SMBD foi criada com sucesso! Um e-mail foi enviado para ($nome, $email) com a senha de acesso. ");
                //return;
            }
            catch (Exception $e)
            {
                $this->showError("", $e->getMessage());
            }
        }
        include '../../view/register.php';
    }

    public function show()
    {

        include '../../view/register.php';
    }

    public function listar()
    {
        include '../../view/register.php';
    }
}
?>
