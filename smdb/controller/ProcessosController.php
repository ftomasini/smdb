<?php

class ProcessosController extends Controller
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

        $load = Estatistica::loadAvg($_SESSION['UsuarioID'], true);
        $memoria = Estatistica::memoria($_SESSION['UsuarioID'], true);
        $processos = $estatisticas->processosEmExecucao($_SESSION['UsuarioID']);
        include '../../view/processos.php';
    }
}
?>
