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


            if(isset($tab))
            {
                Estatistica::informacoesTabelaChart($usuario, $tab);
                Estatistica::indicesNaoUtilizadosChart($usuario, $tab);
                Estatistica::indicesUtilizadosChart($usuario, $tab);
                Estatistica::tamanhoTabelaChart($usuario, $tab);

            }
            ?>


        </section><!-- /.content -->



    </form>


<?php include 'principalFim.php' ?>
