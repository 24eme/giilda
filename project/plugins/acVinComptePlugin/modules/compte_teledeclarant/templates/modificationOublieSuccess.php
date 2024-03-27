<form action="" class="form-horizontal" method="post" id="principal">
  <div class="row">
    <div class="col-xs-10 col-xs-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="titre_principal">Modification de votre mot de passe</h4>
        </div>
        <div class="panel-body">
          <p style="margin-bottom: 30px;">Merci d'indiquer un nouveau mot de passe.</p>
          <?php echo $form->renderHiddenFields(); ?>
          <?php echo $form->renderGlobalErrors(); ?>
          <?php echo $form['mdp1']->renderError() ?>
          <div class="form-group">
            <?php echo $form['mdp1']->renderLabel(null, ['class' => 'col-sm-4 control-label']) ?>
            <div class="col-sm-4">
              <?php echo $form['mdp1']->render(['autofocus' => 'autofocus']) ?>
              </div>
            </div>
            <?php echo $form['mdp2']->renderError() ?>
            <div class="form-group">
              <?php echo $form['mdp2']->renderLabel(null, ['class' => 'col-sm-4 control-label']) ?>
              <div class="col-sm-4">
                <?php echo $form['mdp2']->render() ?>
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
