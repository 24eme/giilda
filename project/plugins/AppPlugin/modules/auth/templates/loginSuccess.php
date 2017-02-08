


<div class="col-xs-12">
    <div class="panel panel-default">
        <div class="panel-heading">
          <h2 class="panel-title" >Connexion à un compte</h2>
        </div>
        <div class="panel-body">

<form action="<?php echo url_for('auth_login_no_cas') ?>" method="post" class="form-horizontal">
<?php echo $form->renderHiddenFields(); ?>
<?php echo $form->renderGlobalErrors(array("class" => "bg-danger")); ?>
  <div class="form-group">
<?php echo $form['login']->renderError(null, array("class" => "bg-danger")); ?>
<?php echo $form['login']->renderLabel(null, array("class" => "col-sm-2 control-label")); ?>
    <div class="col-sm-10">
    <?php echo $form['login']->render(array("class"=> "form-control")); ?>
    </div>
  </div>
  <div class="form-group">
     <div class="col-sm-offset-2 col-sm-10">
        <a href="<?php echo url_for('compte_teledeclarant_mot_de_passe_oublie') ?>">Mot de passe oublié</a> | <a href="<?php echo url_for('compte_teledeclarant_code_creation') ?>">Creation de compte</a>
     </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10 text-right">
      <button type="submit" class="btn btn-primary">Valider</button>
    </div>
  </div>
</form>
</div>

</div>

</div>
