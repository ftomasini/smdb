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
        $metodo = "Estatistica::{$_GET['func']}('{$_GET['usuario']}', '{$_GET['tabela']}');";

        $this->painelModel->insert($_GET['usuario'], $metodo);
        // insere no painel


?>

<script type="text/javascript">
    alert(2);
    </script>
<?php
    }


}
?>
