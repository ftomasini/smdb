<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php echo Yii::app()->name; ?></title>

  	<!-- Bootstrap -->
  	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet">
  	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" rel="stylesheet">

    </head>

    <body>
        <!-- Fixed navbar -->
    	<div class="navbar navbar-inverse transparent" role="navigation">
	    <div class="container">
		<div class="navbar-header">
		    <div class="row">
           		<div class="col-xs-4 col-md-2">
			</div>

           	    	<div class="col-xs-4 col-md-8">

            	    	    <?php echo CHtml::link('<img src="'. Yii::app()->request->baseUrl . "/" . Yii::app()->params['tema']['logo'] . '"/>', array('user/index'), array('class' => 'logo')); ?>

           	    	</div>

           		<div class="col-xs-4 col-md-2">
           		</div>

          	    </div>
        	</div>
		<?php $this->widget('application.components.LangOptions'); ?>

	    </div>
	</div>
	
	<!-- Conteudo -->
	<div class="container">
	    <div class="row starter">
     	        <div class="col-md-12">
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/wifi.png" class="img-responsive"/>

       		</div>

            </div>

	    <?php echo $content; ?>
	    
	    <div class="row text">

      		    <div class="col-md-3">

      		    </div>
		    <div class="col-md-6">

       			<h4><?php echo Yii::t('license', 'Para se conectar à rede sem fio observe as dicas abaixo:'); ?></h4>
       			
			<ul>

			    <li>- <?php echo Yii::t('license', 'Mantenha o Firewall do sistema operacional ativado'); ?>;</li>

			    <li>- <?php echo Yii::t('license', 'Procure garantir que seu antivírus esteja atualizado e em pleno funcionamento'); ?>;</li>

			    <li>- <?php echo Yii::t('license', 'Desligue o compartilhamento de pastas e de arquivos com a rede'); ?>;</li>

			    <li>- <?php echo Yii::t('license', 'Evite o acesso a sites com conteúdos confidenciais, pois você estará utilizando uma rede pública e, portanto, estará exposto a ameaças. Se for necessário, dê preferência a serviços que usem criptografia'); ?>;</li>

			    <li>- <?php echo Yii::t('license', 'Habilite a rede sem fio somente quando for usá-la e desabilite ao desconectar'); ?>.</li>

			</ul>

      		    </div>

     		<div class="col-md-3">

      		</div>

     	     </div>

        </div>

	<!-- Js -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/script/jquery.min.js"></script>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/script/bootstrap.min.js"></script>

    </body>

</html>
