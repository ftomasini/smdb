<?php include 'principalInicio.php' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

    <div class="callout callout-warning">
        <h4>Atenção!</h4>
        <p>Clique em <b>Download</b> para obter o agente coletor que deve ser instalado no servidor da base de dados que será monitorada.</p>
    </div>
 <form class="cmxform form-horizontal " method="POST" action="handler/handlerUsuario.php?op=new">
     <div>
         <center>
    <button class="btn btn-app" type="submit">
        <i class="glyphicon glyphicon-download-alt"></i> Download
    </button>
             </center>
     </div>
 </form>



<?php include 'principalFim.php' ?>