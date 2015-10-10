<?php include 'principalInicio.php' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Estatísticas
            <small>Base de dados</small>
        </h1>

            <!-- Main content -->
            <section class="content">



                <?php

                if(isset($usuario))
                {
                    Estatistica::informacoesBaseDeDadosChart($usuario, '');
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
