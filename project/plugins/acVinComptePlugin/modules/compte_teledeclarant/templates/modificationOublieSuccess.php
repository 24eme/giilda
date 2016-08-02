<form action="" method="post" id="principal">
<div class="row">
  <div class="col-xs-12">
      <div class="panel panel-default">
          <div class="panel-heading">
    <h2 class="titre_principal">Modification de votre mot de passe</h2>
  </div>
    <div class="panel-body">
    <div class="row" id="application_dr">

        <!-- #nouvelle_declaration -->
        <div id="nouvelle_declaration" class="col-xs-12">
            <h3 class="titre_section" style="margin: 10px 0;">Connexion</h3>
          </div>
            <div class="contenu_section col-xs-12 well">
                <p class="intro">Merci d'indiquer un nouveau mot de passe: </p>
              </div>
                <div class="col-xs-12">
                    <?php echo $form->renderHiddenFields(); ?>
                    <?php echo $form->renderGlobalErrors(); ?>
                </div>
                <div class="col-xs-12">
                    <?php echo $form['mdp1']->renderError() ?>
                  </div>
                  <div class="col-xs-12">
                    <div class="row">
                      <div class="col-xs-4 text-right">
                      <?php echo $form['mdp1']->renderLabel() ?>
                    </div>
                      <div class="col-xs-8 text-left">
                      <?php echo $form['mdp1']->render() ?>
                      </div>
                    </div>
                    </div>
                <div class="col-xs-12">
                    <?php echo $form['mdp2']->renderError() ?>
                  </div>
                   <div class="col-xs-12">
                     <div class="row">
                         <div class="col-xs-4 text-right">
                    <?php echo $form['mdp2']->renderLabel() ?>
                    </div>
                      <div class="col-xs-8 text-left">
                    <?php echo $form['mdp2']->render() ?>
                    </div>
                  </div>
                  </div>
                  <div class="col-xs-12 text-right">
                     <button class="btn btn-success" type="submit">Valider</button>
                </div>
            </div>
        </div>
    </div>
      </div>
        </div>
</form>
