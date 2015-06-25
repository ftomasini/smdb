<?php include 'principal.php' ?>
<div class="row" style="margin-bottom:5px;">
        <div class="panel-body">
            <div class="adv-table editable-table ">
                <div class="clearfix">
                    <div class="btn-group">

                            <div class="btn btn-primary"><a  href="handlerContacts.php?op=new">Novo </a><i class="fa fa-plus"></i></div>
                        </button>
                    </div>
                    <div class="btn-group pull-right">
                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="#">Print</a></li>
                            <li><a href="#">Save as PDF</a></li>
                            <li><a href="#">Export to Excel</a></li>
                        </ul>
                    </div>
                </div>
                <div class="space15"></div>
                <table class="table table-striped table-hover table-bordered" id="editable-sample">
                    <thead>
                        <tr>
                            <th>Ações</th>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                            <th>Endereço</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                            <tr>
                                <td><a href="handlerContacts.php?op=edit&id=<?php print $contact->id; ?>">edit</a>
                                <a href="handlerContacts.php?op=delete&id=<?php print $contact->id; ?>">delete</a></td>
                                <td><a href="handlerContacts.php?op=show&id=<?php print $contact->id; ?>"><?php print htmlentities($contact->name); ?></a></td>
                                <td><?php print htmlentities($contact->phone); ?></td>
                                <td><?php print htmlentities($contact->email); ?></td>
                                <td><?php print htmlentities($contact->address); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php include 'principalFim.php' ?>
