<!DOCTYPE html>
<html>
<?php include 'principal.php' ?>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>
        <?php print htmlentities($title) ?>
        </title>
    </head>
    <body>
        <?php
        if ( $errors ) {
            print '<ul class="errors">';
            foreach ( $errors as $field => $error ) {
                print '<li>'.htmlentities($error).'</li>';
            }
            print '</ul>';
        }
        ?>



            <section class="panel">
                <header class="panel-heading">
                    Contato
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                        <a class="fa fa-cog" href="javascript:;"></a>
                        <a class="fa fa-times" href="javascript:;"></a>
                     </span>
                </header>
                <div class="panel-body">
                    <div class=" form">

                            <form class="cmxform form-horizontal " method="POST" action="handlerContacts.php?op=new">

                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-3">Nome</label>
                                <div class="col-lg-6">
                                    <input class=" form-control" id="name" value="<?php print htmlentities($name) ?>" name="name" minlength="2" type="text" required />
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-3">Telefone</label>
                                <div class="col-lg-6">
                                    <input class=" form-control" id="phone" value="<?php print htmlentities($phone) ?>" name="phone" minlength="2" type="text" required />
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-3">E-mail</label>
                                <div class="col-lg-6">
                                    <input class=" form-control" id="email" value="<?php print htmlentities($email) ?>"  name="email" minlength="2" type="text" required />
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-3">Name (required)</label>
                                <div class="col-lg-6">
                                    <textarea class=" form-control" id="address" name="address" minlength="2" type="text" required /><?php print htmlentities($address) ?>
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-offset-3 col-lg-6">
                                    <input type="hidden" name="form-submitted" value="1" />
                                    <button class="btn btn-primary" type="submit">Salvar</button>
                                    <button class="btn btn-default" action="handlerContacts.php" type="button">Cancelar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </form>
    </body>
</html>
