<?php

class ConfiguracaoController extends Controller
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
        $configuracoes = $estatisticas->configuracoesDoSgbd($_SESSION['UsuarioID']);
        include '../../view/configuracao.php';
    }
}
?>
