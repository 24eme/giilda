<form action="" class="form-horizontal" method="post" id="principal">
  <div class="row">
    <div class="col-xs-10 col-xs-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="titre_principal">Création de votre compte</h4>
            </div>
            <div class="panel-body">
                <?php
                $libelle = "Merci d'indiquer votre mot de passe";
                $libelle .= ($form->getTypeCompte() == SocieteClient::TYPE_COURTIER)? " et votre numéro de carte professionnelle" : "";
                $libelle_mail = "Merci de renseigner l'adresse email sur laquelle vous voulez recevoir les informations liées à vos déclarations :";
                $libelle .= " :";
                ?>
                <p class="well">Veuillez renseigner un mot de passe de <strong>8 caractères minimum.</strong></p>
                <p class="titre_section"><?php echo $libelle; ?></p>
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

                <p class="well"><strong>Conseil :</strong> Utiliser un <strong>email</strong> connu par vos collaborateurs habilités à télé-déclarer sur vos différents établissements.</p>

                <p class="titre_section">&nbsp;&nbsp;<?php echo $libelle_mail; ?></p>

                <?php echo $form['email']->renderError() ?>
                <div class="form-group">
                  <?php echo $form['email']->renderLabel(null, ['class' => 'col-sm-4 control-label']) ?>
                  <div class="col-sm-4">
                    <?php echo $form['email']->render() ?>
                  </div>
                </div>

                <?php if ($form->getTypeCompte() == SocieteClient::TYPE_COURTIER): ?>
                  <?php echo $form['carte_pro']->renderError() ?>
                  <div class="form-group">
                    <?php echo $form['carte_pro']->renderLabel(null, ['class' => 'col-sm-4 control-label']) ?>
                    <div class="col-sm-4">
                      <?php echo $form['carte_pro']->render() ?>
                    </div>
                  </div>
                <?php endif; ?>

                <?php if ($form->getTypeCompte() == SocieteClient::TYPE_OPERATEUR): ?>
                  <p class="well"><strong>Aidez-nous</strong> à améliorer les informations vous concernant en remplissant ou modifiant les éléments suivants&nbsp;:</p>
                  <?php echo $form['siret']->renderError() ?>
                  <div class="form-group">
                    <?php echo $form['siret']->renderLabel(null, ['class' => 'col-sm-4 control-label']) ?>
                    <div class="col-sm-4">
                      <?php echo $form['siret']->render() ?>
                    </div>
                  </div>
                  <?php echo $form['cvi']->renderError() ?>
                  <div class="form-group">
                    <?php echo $form['cvi']->renderLabel(null, ['class' => 'col-sm-4 control-label']) ?>
                    <div class="col-sm-4">
                      <?php echo $form['cvi']->render() ?>
                    </div>
                  </div>
                  <?php echo $form['ppm']->renderError() ?>
                  <div class="form-group">
                    <?php echo $form['ppm']->renderLabel(null, ['class' => 'col-sm-4 control-label']) ?>
                    <div class="col-sm-4">
                      <?php echo $form['ppm']->render() ?>
                    </div>
                  </div>
                  <?php echo $form['telephone_bureau']->renderError() ?>
                  <div class="form-group">
                    <?php echo $form['telephone_bureau']->renderLabel(null, ['class' => 'col-sm-4 control-label']) ?>
                    <div class="col-sm-4">
                      <?php echo $form['telephone_bureau']->render() ?>
                    </div>
                  </div>
                  <?php echo $form['telephone_mobile']->renderError() ?>
                  <div class="form-group">
                    <?php echo $form['telephone_mobile']->renderLabel(null, ['class' => 'col-sm-4 control-label']) ?>
                    <div class="col-sm-4">
                      <?php echo $form['telephone_mobile']->render() ?>
                    </div>
                  </div>
                <?php endif; ?>

              <div class="text-right">
                <button class="btn btn-primary" type="submit">Valider</button>
              </div>
            </div>
          </div>
        </div>
      </div>
</form>
