<?php

/* @var $this UserController */
/* @var $model RegistroForm */
/* @var $form CActiveForm */

?>

<div class="row pad-top-20 pad-bot-20">
    <div class="col-md-4">

    </div>

    <div class="col-md-4">

        <div class="form_title_center">

            <h3><?php echo Yii::t('app', 'Cadastro'); ?></h3>

        </div>

        <!-- FORMULÁRIO -->
        <?php
         $form = $this->beginWidget('CActiveForm', array(
            'id' => 'userinfo-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        ));

        ?>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'nome'); ?>
                <?php echo $form->textField($model, 'nome', array('placeholder' => Yii::t('app', 'Seu nome'), 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'nome', array('class' => 'error-message')); ?>

            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'email'); ?>
                <?php echo $form->emailField($model, 'email', array('placeholder' => Yii::t('app', 'Seu email'), 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'email', array('class' => 'error-message')); ?>

            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'ddi', array('class' => 'side-formfield-left')); ?>
                <?php echo $form->labelEx($model, 'numeroCelular', array('class' => 'side-formfield-right')); ?>

                <div class="clear"></div>
                    <?php echo $form->textField($model, 'ddi', array('placeholder' => Yii::t('app', 'DDI'), 'class' => 'form-control side-formfield-left', 'pattern' => '^[0-9]{2}$', 'value' => '55')); ?>
                    <?php echo $form->telField($model, 'numeroCelular', array('placeholder' => Yii::t('app', 'DDD+Número'), 'class' => 'form-control side-formfield-right')); ?>
                    <?php echo $form->error($model, 'ddi', array('class' => 'side-formfield-left error-message')); ?>
                    <?php echo $form->error($model, 'numeroCelular', array('class' => 'side-formfield-right error-message')); ?>

                <div class="clear"></div>

            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'confirmacaoNumeroCelular'); ?>
                <?php echo $form->telField($model, 'confirmacaoNumeroCelular', array('placeholder' => Yii::t('app', 'DDD+Número'), 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'confirmacaoNumeroCelular', array('class' => 'error-message')); ?>

            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'documento'); ?>
                <?php echo $form->textField($model, 'documento', array('placeholder' => Yii::t('app', 'CPF,Identidade ou Passaporte'), 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'documento', array('class' => 'error-message')); ?>

            </div>

            <div class="form_text">
                <p><?php echo Yii::t('app', 'Preencha o campo com as letras apresentadas na figura.'); ?></p>

            </div>

            <?php if ( CCaptcha::checkRequirements() ): ?>
            <div class="form-group">
                <?php $this->widget('CCaptcha', array('buttonOptions' => array('style' => 'display:block; color: #fff; margin-top: -15px;'))); ?>
                <?php echo $form->textField($model, 'codigoVerificacao', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'codigoVerificacao', array('class' => 'error-message')); ?>

            </div>
            <?php endif; ?>

            <?php echo CHtml::submitButton(Yii::t('app', 'Enviar'), array('class' => 'btn btn-default')); ?>

        <?php $this->endWidget(); ?> <!-- /FORMULÁRIO -->

    </div>

    <div class="col-md-4">

    </div>

</div>
