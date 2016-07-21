<div id="principal">
  <form action="" method="post" id="principal">
  <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h2 class="titre_principal">Création de votre compte</h2>
            </div>
            <div class="panel-body">
        <?php
        $libelle = "Merci d'indiquer votre e-mail, votre mot de passe";
        $libelle .= ($form->getTypeCompte() == SocieteClient::TYPE_COURTIER)? " et votre numéro de carte professionnelle" : "";
        $libelle .= ($form->getTypeCompte() == SocieteClient::TYPE_OPERATEUR)?
                    " et votre numéro de SIRET" : "";
        $libelle .= " :";
        ?>
          <div class="row">
              <div class="col-xs-12">
                <p class="well"><strong>Conseil :</strong> Utiliser un email connu par vos collaborateurs habilités à télé-déclarer sur vos différents établissements.</p>
              </div>
              <div class="col-xs-12">
                <p class="titre_section">&nbsp;&nbsp;<?php echo $libelle; ?></p>
              </div>
        <div id="creation_compte_teledeclaration" class="col-xs-12" >
            <div class="bloc_form bloc_form_condensed">
              <div class="row">
                  <div class="col-xs-12">
                    <?php echo $form->renderHiddenFields(); ?>
                    <?php echo $form->renderGlobalErrors(); ?>
                  </div>
                <div class="col-xs-12">
                    <div class="row">
                      <div class="col-xs-12">
                        <?php echo $form['email']->renderError() ?>
                      </div>
                    </div>
                      <div class="row">
                      <div class="col-xs-4 text-right">
                        <?php echo $form['email']->renderLabel() ?>
                      </div>
                      <div class="col-xs-4 col-xs-offset-2 text-left">
                          <?php echo $form['email']->render(array('class' => "form-control")) ?>
                      </div>
                    </div>
                    <br/>
                </div>
                <div class="col-xs-12">
                    <div class="row">
                      <div class="col-xs-12">
                        <?php echo $form['mdp1']->renderError() ?>
                      </div>
                    </div>
                      <div class="row">
                      <div class="col-xs-4 text-right">
                        <?php echo $form['mdp1']->renderLabel() ?>
                      </div>
                      <div class="col-xs-4 col-xs-offset-2 text-left">
                        <?php echo $form['mdp1']->render(array('class' => "form-control")) ?>
                      </div>
                    </div>
                    <br/>
                </div>
                <div class="col-xs-12">
                    <div class="row">
                      <div class="col-xs-12">
                        <?php echo $form['mdp2']->renderError() ?>
                      </div>
                    </div>
                      <div class="row">
                      <div class="col-xs-4 text-right">
                        <?php echo $form['mdp2']->renderLabel() ?>
                      </div>
                      <div class="col-xs-4 col-xs-offset-2 text-left">
                        <?php echo $form['mdp2']->render(array('class' => "form-control")) ?>
                      </div>
                    </div>
                    <br/>
                </div>
                <?php if ($form->getTypeCompte() == SocieteClient::TYPE_COURTIER): ?>
                  <div class="col-xs-12">
                      <div class="row">
                        <div class="col-xs-12">
                        <?php echo $form['carte_pro']->renderError() ?>
                      </div>
                    </div>
                      <div class="row">
                      <div class="col-xs-4 text-right">
                        <?php echo $form['carte_pro']->renderLabel() ?>
                      </div>
                      <div class="col-xs-4 col-xs-offset-2 text-left">
                        <?php echo $form['carte_pro']->render(array('class' => "form-control")) ?>
                      </div>
                    </div>
                    <br/>
                  </div>
                <?php endif; ?>
                <?php if ($form->getTypeCompte() == SocieteClient::TYPE_OPERATEUR): ?>
                  <div class="col-xs-12">
                      <div class="row">
                        <div class="col-xs-12">
                          <?php echo $form['siret']->renderError() ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-4 text-right">
                          <?php echo $form['siret']->renderLabel() ?>
                        </div>
                        <div class="col-xs-4 col-xs-offset-2 text-left">
                          <?php echo $form['siret']->render(array('class' => "form-control")) ?>
                        </div>
                      </div>
                      <br/>
                    </div>
                      <div class="col-xs-12">
                          <div class="row">
                            <div class="col-xs-12">
                              <?php echo $form['num_accises']->renderError() ?>
                            </div>
                          </div>
                            <div class="row">
                            <div class="col-xs-4 text-right">
                              <?php echo $form['num_accises']->renderLabel() ?>
                            </div>
                            <div class="col-xs-4 col-xs-offset-2 text-left">
                              <?php echo $form['num_accises']->render(array('class' => "form-control")) ?>
                            </div>
                          </div>
                          <br/>
                      </div>
                <?php endif; ?>
          <div class="col-xs-12 text-right">
            <button class="btn btn-success" type="submit">Valider</button>
          </div>
      </div>
    </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
    </form>
    </div>
