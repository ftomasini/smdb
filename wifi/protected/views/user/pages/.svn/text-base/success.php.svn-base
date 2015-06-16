<?php
    $this->pageTitle = Yii::app()->name;

    $baseUrl = Yii::app()->baseUrl . '/index.php?r=user/index';

    echo "
            <script type='text/javascript'>
                setTimeout(function() { window.open('{$baseUrl}', '_self'); }, 10000); 

            </script>

        ";

    $info['recover'] = Yii::t('app', 'Um SMS será enviado com sua senha.');
    $info['confirm'] = '';

    $ref = $_GET['info'];
    $message = $info[$ref];
?>

<div class="form_title_center">
    <h3><?php echo Yii::t('app', 'Operação realizada com sucesso.') . '<br/>' . $message; ?></h3>

</div>
