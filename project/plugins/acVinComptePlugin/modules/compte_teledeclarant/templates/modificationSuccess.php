<?php
$email_teledecl = null;
if($compte->getSociete()->isTransaction()){
   $email_teledecl = $compte->getSociete()->getEtablissementPrincipal()->getEmailTeledeclaration();
}else{
    $email_teledecl = $compte->getSociete()->getEmailTeledeclaration();
}

if($compte->compte_type == CompteClient::TYPE_COMPTE_INTERLOCUTEUR) {
    $email_teledecl = $compte->email;
}

?>
<div id="principal" >
  <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
    <h4 class="titre_principal">Mon compte <span style="opacity: 0.5;" class="text-muted pull-right"><?php echo $compte->nom_a_afficher ?></span></h4>
  </div>
   <div class="panel-body">
     <?php if($compte->getStatutTeledeclarant() == CompteClient::STATUT_TELEDECLARANT_NOUVEAU): ?>
       <div class="alert alert-warning">Ce compte n'a pas encore été créé par le télédéclarant : <a href="<?php echo url_for('societe_visualisation', $compte->getSociete()) ?>">Voir la société</a></div>
     <?php elseif($compte->getStatutTeledeclarant() == CompteClient::STATUT_TELEDECLARANT_OUBLIE): ?>
       <div class="alert alert-info">Ce compte en est cours de mot de passe oublié : <a href="<?php echo url_for('societe_visualisation', $compte->getSociete()) ?>">Voir la société</a></div>
     <?php elseif($compte->getStatutTeledeclarant() == CompteClient::STATUT_TELEDECLARANT_INACTIF): ?>
       <div class="alert alert-danger">Ce compte est suspendu : <a href="<?php echo url_for('societe_visualisation', $compte->getSociete()) ?>">Voir la société</a></div>
     <?php endif; ?>

    <?php if(isset($form)): ?>
    <div class="col-xs-12 well">Pour changer des informations supplémentaire, veuillez passer par votre interprofession.</div>
        <div id="modification_compte" class="col-xs-12">
            <div class="presentation row" <?php if ($form->hasErrors()) echo ' style="display:none;"'; ?> >
              <div class="col-xs-12">
                <h4>Vos identifiants de connexion : </h4>
              </div>

                <?php if ($sf_user->hasFlash('maj')) : ?>
                    <p class="col-xs-12 flash_message text-info"><?php echo $sf_user->getFlash('maj'); ?></p>
                <?php endif; ?>
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
                        <label>Mot de passe :</label>
                    </div>
                    <div class="col-xs-6 text-left">
                        ******
                    </div>
                  </div>
                </div>
                <div class="col-xs-12">&nbsp;<br/><br/></div>
                <div class="col-xs-12">
                      <a href="<?php echo url_for('common_homepage'); ?>" class=" btn btn-default " alt="Retour" style="cursor: pointer;">Retour</a>
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
                        <div class="col-xs-12"><?php echo $form['email']->renderError() ?></div>
                        <div class="col-xs-6 text-right">
                            <?php echo $form['email']->renderLabel() ?>
                        </div>
                        <div class="col-xs-6 text-left">
                              <?php echo $form['email']->render() ?>
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
                        <a class="btn btn-danger annuler" style="float: left;" href="#" >Annuler</a>
                        <button type="submit" class=" btn btn-success btn_valider modifier pull-right" style="cursor: pointer;" >Valider</button>
                    </div>

                </form>
            </div>


        </div>
    <?php endif; ?>
</div>
</div>
</div>
</div>
</div>
