<?php include 'principalInicio.php' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Estatísticas
            <small>Tabela da base de dados</small>
        </h1>

    <form method="POST" action="handlerTabelaBaseDeDados.php?op=show">
        <!-- Main content -->
        <section class="content">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <!--<h3 class="box-title">Perfil</h3> -->
                    <div class="form-group">
                        <label>Selecione a tabela</label>
                        <select name="tabela" class="form-control">
                            <option>--Selecione--</option>
                            <?php

                            foreach ($tabelas as $tabela)
                            {
                             ?> <option><?php print htmlentities($tabela->tabela);?></option>
                            <?php
                            }?>
                        </select>
                    </div>
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>

            </div>



            <?php


            if(isset($tab) && $tab != '--Selecione--')
            {
                Estatistica::informacoesTabelaChart($usuario, $tab);
                Estatistica::indicesNaoUtilizadosChart($usuario, $tab);
                Estatistica::indicesUtilizadosChart($usuario, $tab);
                Estatistica::tamanhoTabelaChart($usuario, $tab);
                Estatistica::tamanhoTabelaComIndicesChart($usuario, $tab);
                Estatistica::aproveitamentoCacheTabelaChart($usuario, $tab);
            }
            ?>


        </section><!-- /.content -->



    </form>

        <script type="text/javascript">

            //$("#btn_publicar1").click(function()
            $( "p" ).click(function()
            {
                //alert("Envia recado");
                var id1 = $(this).attr('rel1');
                var id2 = $(this).attr('rel2');
                var id3 = $(this).attr('rel3');

                $.ajax({
                    //Tipo de envio POST ou GET
                    type: "POST",
                    //Caminho do arquivo que processa o carrinho
                    url: "handlerPainel.php?op=publica&func="+id1+"&usuario="+id2+"&tabela="+id3,
                    //Arquvios passados via POST neste caso, segue o mesmo modelo para GET
                    //Se der tudo ok no envio...
                    success: function(resposta){
                        //Colocar a resposta do aqruivo na div de intens do carrinho
                        $("#divresponse").html(resposta);}

            });
            });

        </script>
<?php include 'principalFim.php' ?>
