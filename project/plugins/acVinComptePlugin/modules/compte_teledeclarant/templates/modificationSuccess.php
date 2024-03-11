<?php
$email_teledecl = null;
if($compte->getSociete()->isTransaction()){
   $email_teledecl = $compte->getSociete()->getEtablissementPrincipal()->getTeledeclarationEmail();
}else{
    $email_teledecl = $compte->getSociete()->getTeledeclarationEmail();
}

?>
<div id="principal" >
  <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="titre_principal">Mon compte</h4>
            </div>
            <div class="panel-body">
    <br/>

        <div id="modification_compte" class="col-xs-12">
        	<p>Sur cette page, si besoin, vous pouvez redéfinir votre mot de passe ou modifier vos informations de contact.</p>
            <div class="presentation row" <?php if ($form->hasErrors()) echo ' style="display:none;"'; ?> >
              <div class="col-xs-12">
                <h4>Vos identifiants de connexion : </h4>

                <?php if ($sf_user->hasFlash('maj')) : ?>
                    <p class="alert alert-success"><?php echo $sf_user->getFlash('maj'); ?></p>
                <?php endif; ?>
              </div>


                <div class="col-xs-8" >
                  <div class="row">
                    <div class="col-xs-6 text-right">
                        <label>Login :</label>
                    </div>
                    <div class="col-xs-6 text-left">
                        <?php echo $compte->login; ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-6 text-right">
                        <label>Mot de passe :</label>
                    </div>
                    <div class="col-xs-6 text-left">
                        ******
                    </div>
                  </div>
                </div>

                <div class="col-xs-12">
                	<h4>Vos informations de contact : </h4>
              	</div>


                <div class="col-xs-8" >
                  <div class="row">
                    <div class="col-xs-6 text-right">
                        <label>Email :</label>
                    </div>
                    <div class="col-xs-6 text-left">
                        <?php echo $email_teledecl; ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-6 text-right">
                        <label>Téléphone :</label>
                    </div>
                    <div class="col-xs-6 text-left">
                        <?php echo ($etablissementPrincipal) ? $etablissementPrincipal->telephone_bureau : $compte->telephone_bureau; ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-6 text-right">
                        <label>Mobile :</label>
                    </div>
                    <div class="col-xs-6 text-left">
                        <?php echo ($etablissementPrincipal) ? $etablissementPrincipal->telephone_mobile : $compte->telephone_mobile; ?>
                    </div>
                  </div>
                </div>

                <div class="col-xs-12">&nbsp;<br/><br/></div>
                <div class="col-xs-12">
                      <a href="#" class=" btn btn-warning modifier" style="cursor: pointer; float: right;">Modifier les informations</a>
                </div>
            </div>
            <div class="modification clearfix"<?php if (!$form->hasErrors()) echo ' style="display:none;"'; ?>>
              <div class="col-xs-12">
                <h4>Modification de vos identifiants de connexion :</h4>
              </div>

                <form method="post" action="">
                    <?php echo $form->renderHiddenFields(); ?>
                    <?php echo $form->renderGlobalErrors(); ?>

                    <div class="col-xs-8" >
                      <div class="row">
                        <div class="col-xs-6 text-right">
                            <label>Login :</label>
                        </div>
                        <div class="col-xs-6 text-left">
                    	<?php echo $compte->login; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12"><?php echo $form['mdp1']->renderError() ?></div>
                        <div class="col-xs-6 text-right">
                            <?php echo $form['mdp1']->renderLabel() ?>
                        </div>
                        <div class="col-xs-6 text-left">
                              <?php echo $form['mdp1']->render() ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12"><?php echo $form['mdp2']->renderError() ?></div>
                        <div class="col-xs-6 text-right">
                            <?php echo $form['mdp2']->renderLabel() ?>
                        </div>
                        <div class="col-xs-6 text-left">
                              <?php echo $form['mdp2']->render() ?>
                        </div>
                      </div>
                    </div>

                    <div class="col-xs-12">
		            	<h4>Modification de vos informations de contact :</h4>
		            </div>

                    <div class="col-xs-8" >
                      <div class="row">
                        <div class="col-xs-12"><?php echo $form['email']->renderError() ?></div>
                        <div class="col-xs-6 text-right">
                            <?php echo $form['email']->renderLabel() ?>
                        </div>
                        <div class="col-xs-6 text-left">
                              <?php echo $form['email']->render() ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12"><?php echo $form['telephone_bureau']->renderError() ?></div>
                        <div class="col-xs-6 text-right">
                            <?php echo $form['telephone_bureau']->renderLabel() ?>
                        </div>
                        <div class="col-xs-6 text-left">
                              <?php echo $form['telephone_bureau']->render() ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12"><?php echo $form['telephone_mobile']->renderError() ?></div>
                        <div class="col-xs-6 text-right">
                            <?php echo $form['telephone_mobile']->renderLabel() ?>
                        </div>
                        <div class="col-xs-6 text-left">
                              <?php echo $form['telephone_mobile']->render() ?>
                        </div>
                      </div>
                    </div>

                    <div class="col-xs-12">
                        <a class="btn btn-danger annuler" style="float: left;" href="#" >Annuler</a>
                        <button type="submit" class=" btn btn-success btn_valider modifier pull-right" style="cursor: pointer;" >Valider</button>
                    </div>

                </form>
            </div>


        </div>

</div>
</div>
</div>
</div>

<?php if (MandatSepaConfiguration::getInstance()->isActive()): ?>
<div class="row">
  <div class="col-xs-12">
      <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="titre_principal">Coordonnées bancaires</h4>
          </div>
          <div class="panel-body">

          <div class="col-xs-12">
            <h4>Vos coordonnées bancaires : </h4>
          </div>
          <?php if ($mandatSepa): ?>
            <div class="col-xs-8">
              <div class="row">
                <div class="col-xs-6 text-right">
                    <label>IBAN :</label>
                </div>
                <div class="col-xs-6 text-left">
                    <?php echo $mandatSepa->debiteur->iban; ?>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-6 text-right">
                    <label>BIC :</label>
                </div>
                <div class="col-xs-6 text-left">
                    <?php echo $mandatSepa->debiteur->bic; ?>
                </div>
              </div>
              <div class="row">&nbsp;</div>
              <div class="row">
                <div class="col-xs-6 text-right">
                    <label>Mandat de prélèvement SEPA :</label>
                </div>
                <div class="col-xs-6 text-left">
                    <a href="<?php echo url_for('mandatsepa_pdf', $mandatSepa) ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-file"></span>&nbsp;Télécharger le document</a>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-6 text-right">
                    <label>Statut :</label>
                </div>
                <div class="col-xs-6 text-left<?php if(!$mandatSepa->is_signe): ?> text-danger<?php endif; ?>">
                    <?php echo $mandatSepa->getStatut(); ?>
                </div>
              </div>
            </div>
            <?php if (!$mandatSepa->is_telecharge): ?>
            <?php include_partial('mandatsepa/popupIncitationSignatureMandat', array('mandatSepa' => $mandatSepa)); ?>
            <?php endif; ?>
          <?php else: ?>
            <div class="col-xs-8">
              <div class="row">
                <div class="col-xs-6 text-right"></div>
                <div class="col-xs-6 text-left">
                  <p>Vous n'avez pas saisi de coordonnées bancaires</p>
                </div>
              </div>
            </div>
            <div class="col-xs-12">
                  <a href="<?php echo url_for('compte_teledeclarant_coordonnees_bancaires') ?>" class=" btn btn-warning modifier" style="cursor: pointer; float: right;">Saisir vos coordonnées bancaires</a>
            </div>
          <?php endif; ?>
          </div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="col-xs-12">
  <a href="<?php echo url_for('common_homepage'); ?>" class=" btn btn-default " alt="Retour" style="cursor: pointer;">Retour</a>
</div>

</div>
