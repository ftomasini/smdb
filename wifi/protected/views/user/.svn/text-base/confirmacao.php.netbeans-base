<?php

/* @var $this UserController */
/* @var $model ConfirmacaoForm */
/* @var $form CActiveForm */

?>

<div class="row pad-bot-20">
    <div class="col-md-4">

    </div>

    <div class="col-md-4">
        <div class="form_title_center">

            <h3><?php echo Yii::t('app', 'Confirmação'); ?></h3>
       	    <p><?php echo Yii::t('app', 'Preencha o campo \'Senha\' com o código recebido no seu celular.'); ?></p>

        </div>

        <!-- FORMULÁRIO -->
        <?php

        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'userconfirm-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        ));

        ?>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'email'); ?>
                <?php

                echo $form->emailField($model, 'email', array(
                    'placeholder' => Yii::t('app', 'Seu email'),
                    'class' => 'form-control',
                    'value' => isset(Yii::app()->session['userEmail']) ? Yii::app()->session['userEmail'] : ''));

                ?>
                <?php echo $form->error($model, 'email', array('class' => 'error-message')); ?>

            </div>            

            <div class="form-group">
                <?php echo $form->labelEx($model, 'senha'); ?>
                <?php echo $form->textField($model, 'senha', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'senha', array('class' => 'error-message')); ?>	

            </div> 

            <?php echo CHtml::submitButton(Yii::t('app', 'Enviar'), array('class' => 'btn btn-default')); ?>

        <!-- /FORMULÁRIO -->
        <?php $this->endWidget(); ?>	

    </div>

    <div class="col-md-4">

    </div>

</div>
