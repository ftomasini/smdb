<?php include 'principalInicio.php' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

    <!-- general form elements -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Perfil</h3>
        </div>
        <?php
        if (isset($_GET['m']))
        {
            $this->showSuccess('titulo', "Dados do usuário alterado com sucesso!");
        }
        ?>
    <div class=" form">
        <form class="cmxform form-horizontal " method="POST" action="handlerUsuario.php?op=new">
            <div class="box-body">
                <div class="form-group ">
                    <label for="cname" class="control-label col-lg-3">Nome</label>
                        <div class="col-lg-6">
                            <input class=" form-control" id="nome" name="nome" value="<?php print htmlentities($usuario->nome)  ?>" name="name" minlength="2" type="text" required />
                        </div>
                </div>
                <div class="form-group ">
                <label for="cname" class="control-label col-lg-3">E-mail</label>
                <div class="col-lg-6">
                    <input class=" form-control" id="email" nome="email" value="<?php print htmlentities($usuario->email) ?>" readonly name="email" minlength="2" type="text" required />
                </div>
            </div>
            <div class="form-group ">
                <label for="cname" class="control-label col-lg-3">Senha</label>
                <div class="col-lg-6">
                    <input class=" form-control" id="senhaNova" name="senhaNova" type="password" placeholder="Preencha esse campo se deseja alterar a senha" value=""/>

                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <div class="box-footer">
                    <input type="hidden" name="id" id="id" value=<?php print htmlentities($usuario->usuario) ?> />
                    <input type="hidden" name="form-submitted" value="1" />
                    <button class="btn btn-primary" type="submit">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>


<?php include 'principalFim.php' ?>