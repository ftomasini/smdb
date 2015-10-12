<?php

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

    public function publica()
    {
        //&func="+id1+"&usuario="+id2+"&tabela="+id3,

        $tabela =  isset($_GET['tabela']) ? $_GET['tabela'] : '';
        $metodo = "Estatistica::{$_GET['func']}('{$_GET['usuario']}', '{$tabela}');";


        $metodos = $this->painelModel->selectAll($_GET['usuario']);

        $encontrou = false;
        foreach ($metodos as $l)
        {
            if ($l->metodo == $metodo)
            {
                $encontrou = true;
            }
        }

        if ($encontrou)
        {
            $this->painelModel->delete($_GET['usuario'], $metodo);
            ?>
            <script>
                location.reload();
            </script><?php
        }
        else
        {
            $this->painelModel->insert($_GET['usuario'], $metodo);
        }

        // insere no painel

    }


}
?>
