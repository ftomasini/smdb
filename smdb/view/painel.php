<?php include 'principalInicio.php' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Painel principal
            <small>Estatísticas que estão sendo monitoradas</small>
        </h1>

        <div class="pad margin no-print">
            <div class="callout callout-info" style="margin-bottom: 0!important;">
                <h4><i class="fa fa-info"></i> Atenção!</h4>
                Para adicionar ou remover gráficos do painel clique em <b>Inserir/remover do painel</b> no canto direito do gráfico desejado.
            </div>
        </div>

            <!-- Main content -->
            <section class="content">
                <?php

                $charts = PainelModel::selectAll($_SESSION['UsuarioID']);

                foreach($charts as $chart)
                {
                    eval($chart->metodo);
                }
                ?>

            </section><!-- /.content -->




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
