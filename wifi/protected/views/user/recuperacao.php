<?php
/* @var $this UserController */
/* @var $model RecuperacaoForm */
/* @var $form CActiveForm */
?>

<div class="row pad-bot-20">
    <div class="col-md-4">

    </div>

    <div class="col-md-4">
        <div class="form_title_center">
            <h3><?php echo Yii::t('app', 'Recuperar senha'); ?></h3>

        </div>

        <!-- FORMULÁRIO -->
	<?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'userrecover-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        ));
        ?>
            <div class="form-group">
		<?php echo $form->labelEx($model, 'email'); ?>
                <?php echo $form->emailField($model, 'email', array('placeholder' => Yii::t('app', 'Seu email'), 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'email', array('class' => 'error-message')); ?>

            </div>

            <div class="form-group">
		<?php echo $form->labelEx($model,'ddi', array('class' => 'side-formfield-left')); ?>
                <?php echo $form->labelEx($model,'numeroCelular', array('class' => 'side-formfield-right')); ?>

                <div class="clear"></div>

                <?php echo $form->textField($model,'ddi', array('placeholder' => Yii::t('app', 'DDI'), 'class' => 'form-control side-formfield-left', 'pattern' => '^[0-9]{2}$', 'value' => '55')); ?>
                <?php echo $form->telField($model,'numeroCelular', array('placeholder' => Yii::t('app', 'DDD+Número'), 'class' => 'form-control side-formfield-right')); ?>
                <?php echo $form->error($model,'ddi', array('class' => 'side-formfield-left error-message')); ?>
                <?php echo $form->error($model,'numeroCelular', array('class' => 'side-formfield-right error-message')); ?>

                <div class="clear"></div>
	
            </div>

            <div class="form-group">
		<?php echo $form->labelEx($model, 'confirmacaoNumeroCelular'); ?>
                <?php echo $form->telField($model, 'confirmacaoNumeroCelular', array('placeholder' => Yii::t('app', 'DDD+Número'), 'class' => 'form-control')); ?>
	        <?php echo $form->error($model, 'confirmacaoNumeroCelular', array('class' => 'error-message')); ?>		

            </div>
	
	    <?php echo CHtml::submitButton(Yii::t('app', 'Enviar'), array('class' => 'btn btn-default')); ?>

        <!-- /FORMULÁRIO -->
	<?php $this->endWidget(); ?>

    </div>

    <div class="col-md-4">

    </div>

</div>
