<?php
$this->title = 'Contact';
?>
<h1>Contact</h1>
<?php
use App\Services\Form\Form;
use App\Services\Form\TextAreaField;
?>
<?php $form = Form::begin('', 'post') ;?>
<?php echo $form->field($model, 'subject'); ?>
<?php echo $form->field($model, 'email'); ?>
<?php echo new TextAreaField($model, 'body'); ?>
<button class="btn btn-primary">Submit</button>
<?php Form::end(); ?>