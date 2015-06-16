<?php
    /* @var $this UserController */
    /* @var $error array */

    $this->pageTitle = Yii::app()->name;

    $baseUrl = Yii::app()->baseUrl . '/index.php?r=user/index';

    echo "
        <script type='text/javascript'>
            setTimeout(function() { window.open('{$baseUrl}', '_self'); }, 10000); 
            
        </script>

    ";
?>

<div class="form_title_center">
    <h3><?php echo Yii::t('app', 'Erro'); ?></h3>

    <p style="font-size: 17px" class="error-message"><?php echo CHtml::encode($message) . ' <br> Redirecionando para a página inicial... Aguarde...'; ?></p>

</div>
