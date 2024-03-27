<form action="" method="post" class="form-horizontal" name="firstConnection">
  <div class="row">
    <div class="col-xs-10 col-xs-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="titre_principal">Premiere connexion</h4>
        </div>
        <div class="panel-body">
          <p style="margin-bottom: 30px;">Afin d'accèder à la plateforme de télédéclaration, veuillez remplir les champs suivants :</p>

          <?php echo $form->renderHiddenFields(); ?>
          <?php echo $form->renderGlobalErrors(); ?>

          <?php echo $form['login']->renderError() ?>
          <div class="form-group">
            <?php echo $form['login']->renderLabel(null, ['class' => 'col-sm-4 control-label']) ?>
            <div class="col-sm-4">
              <?php echo $form['login']->render(['autofocus' => 'autofocus']) ?>
            </div>
          </div>
          <?php echo $form['mdp']->renderError() ?>
          <div class="form-group">
            <?php echo $form['mdp']->renderLabel(null, ['class' => 'col-sm-4 control-label']) ?>
            <div class="col-sm-4">
              <?php echo $form['mdp']->render() ?>
            </div>
          </div>
          <div class="text-right" style="margin-top: 30px;">
            <button class="btn btn-primary" type="submit">Valider</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
