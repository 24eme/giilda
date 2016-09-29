<!-- #principal -->
<div id="principal" class="clearfix mdp_oublie">
<div class="row">
  <div class="col-xs-12">
      <div class="panel panel-default">
          <div class="panel-heading">
    <form action="" class="ui-tabs" method="post">
        <h4 class="titre_principal">Mon compte - Mot de passe oublié</h4>
</div>
  <div class="panel panel-body">
        <p class="titre_section">Afin de récuperer votre mot de passe veuillez renseigner votre identifiant.</p>
        <br/>
        <div id="nouvelle_declaration" class="row">
            <?php echo $form->renderHiddenFields(); ?>
              <div class="ligne_form col-xs-12">
            <?php echo $form->renderGlobalErrors(); ?>
</div>
            <div class="ligne_form col-xs-12">
                <?php echo $form['login']->renderError() ?>
              </div>
              <div class="ligne_form col-xs-12">
                <div class="row">
                    <div class="col-xs-4 text-right">
                        <?php echo $form['login']->renderLabel() ?>
                    </div>
                    <div class="col-xs-8 text-left">
                      <?php echo $form['login']->render() ?>
                  </div>
                </div>
            <div class="col-xs-12 text-right">
              <button class="btn btn-warning" type="submit">Valider</button>
            </div>
        </div>
    </form>
  </div>
    </div>
</div>
</div>
</div>
</div>
