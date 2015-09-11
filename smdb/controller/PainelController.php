<?php


//require_once 'model/ContactsModel.php';
//require_once 'core/Controller.php';

class PainelController extends Controller
{

    private $painelModel = NULL;
    private $core = NULL;

    public function __construct()
    {

        $this->core = new Core();
        if (!$this->core->isLogged())
        {
            $this->redirect('../../index.php');
        }
        $this->painelModel = new PainelModel();
    }

    public function show()
    {
        include '../../view/painel.php';
    }
}
?>
