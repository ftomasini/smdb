<?php

echo CHtml::form(); // Cria um formulário
$instrucao = Yii::app()->controller->id . '/' . Yii::app()->controller->action->id; // Cria a instrução a ser requisitada

?>
    <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
            <li><p class="navbar-text"><?php echo CHtml::link('Português', array($instrucao, 'idioma' => 'pt_br')); ?></p></li>
            <li><p class="navbar-text"><?php echo CHtml::link('English', array($instrucao, 'idioma' => 'en_us')); ?></p></li>
        </ul>
    </div>

<?php echo CHtml::endForm(); ?>
