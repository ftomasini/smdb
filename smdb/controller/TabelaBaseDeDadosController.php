<?php

class TabelaBaseDeDadosController extends Controller
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
        $estatisticas = new Estatistica();


        $tabelas = $estatisticas->tabelas($_SESSION['UsuarioID']);

        if( isset($_POST['tabela']) )
        {
            $usuario = $_SESSION['UsuarioID'];
            $tab = $_POST['tabela'];
        }
        include '../../view/tabelaBaseDeDados.php';
    }
}
?>
