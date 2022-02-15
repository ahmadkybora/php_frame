<h1>Register</h1>
<?php $form = \App\Services\Form\Form::begin('', 'post'); ?>
<div class="row">
  <div class="col-md-4">
    <?= $form->field($model, 'first_name'); ?>
  </div>
  <div class="col-md-4">
    <?= $form->field($model, 'last_name'); ?>
  </div>
  <div class="col-md-8">
    <?= $form->field($model, 'email'); ?>
  </div>
  <div class="col-md-8">
    <?= $form->field($model, 'password')->passwordField(); ?>
  </div>
  <div class="col-md-8">
    <?= $form->field($model, 'password_confirm')->passwordField(); ?>
  </div>
  <div class="col-md-8">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</div>
<?php echo \App\Services\Form\Form::end(); ?>
