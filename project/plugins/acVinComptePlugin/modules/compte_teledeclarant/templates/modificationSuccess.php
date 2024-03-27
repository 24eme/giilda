<?php
$email_teledecl = null;
if($compte->getSociete()->isTransaction()){
   $email_teledecl = $compte->getSociete()->getEtablissementPrincipal()->getTeledeclarationEmail();
}else{
    $email_teledecl = $compte->getSociete()->getTeledeclarationEmail();
}

?>
  <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="titre_principal">Mon compte <span style="opacity: 0.5;" class="text-muted pull-right"><?php echo $compte->nom_a_afficher ?></span></h4>
            </div>
            <div class="panel-body">
            <?php if ($sf_user->hasFlash('maj')) : ?>
                <p class="alert alert-success"><?php echo $sf_user->getFlash('maj'); ?></p>
            <?php endif; ?>
            <?php if($sf_user->isAdmin() && $compte->getStatutTeledeclarant() == CompteClient::STATUT_TELEDECLARANT_NOUVEAU): ?>
           <div class="alert alert-warning">Ce compte n'a pas encore été créé par le télédéclarant : <a href="<?php echo url_for('societe_visualisation', $compte->getSociete()) ?>">Voir la société</a></div>
         <?php elseif($sf_user->isAdmin() && $compte->getStatutTeledeclarant() == CompteClient::STATUT_TELEDECLARANT_OUBLIE): ?>
           <div class="alert alert-info">Ce compte est en procédure de mot de passe oublié : <a href="<?php echo url_for('societe_visualisation', $compte->getSociete()) ?>">Voir la société</a></div>
         <?php elseif($sf_user->isAdmin() && $compte->getStatutTeledeclarant() == CompteClient::STATUT_TELEDECLARANT_INACTIF): ?>
           <div class="alert alert-danger">Ce compte est suspendu : <a href="<?php echo url_for('societe_visualisation', $compte->getSociete()) ?>">Voir la société</a></div>
         <?php endif; ?>

         <?php if(isset($form)): ?>

        <div id="modification_compte">
        	<p style="margin-bottom: 30px;">Sur cette page, si besoin, vous pouvez redéfinir votre mot de passe ou modifier vos informations de contact.</p>
          <div class="presentation form-horizontal" <?php if ($form->hasErrors()) echo ' style="display:none;"'; ?> >

            <h4>Vos identifiants de connexion : </h4>

              <div class="form-group">
                <label class="col-sm-4 control-label">Login :</label>
                <div class="col-sm-4">
                  <p class="form-control-static"><?php echo $compte->login; ?></p>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">Mot de passe :</label>
                <div class="col-sm-4">
                  <p class="form-control-static">******</p>
                </div>
              </div>

              <div class="form-group"><label class="col-sm-4 control-label"></label><div class="col-sm-4"><p class="form-control-static"></p></div></div>

            	<h4>Vos informations de contact : </h4>

              <div class="form-group">
                <label class="col-sm-4 control-label">Email :</label>
                <div class="col-sm-4">
                  <p class="form-control-static"><?php echo $email_teledecl; ?></p>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">Téléphone :</label>
                <div class="col-sm-4">
                  <p class="form-control-static"><?php echo ($etablissementPrincipal) ? $etablissementPrincipal->telephone_bureau : $compte->telephone_bureau; ?></p>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">Mobile :</label>
                <div class="col-sm-4">
                  <p class="form-control-static"><?php echo ($etablissementPrincipal) ? $etablissementPrincipal->telephone_mobile : $compte->telephone_mobile; ?></p>
                </div>
              </div>

                <div>
                    <a href="<?php echo url_for('common_homepage'); ?>" class=" btn btn-default " alt="Retour" style="cursor: pointer;">Retour</a>
                      <a href="#" class=" btn btn-default modifier" style="cursor: pointer; float: right;">Modifier les informations</a>
                </div>
            </div>
            <div class="modification"<?php if (!$form->hasErrors()) echo ' style="display:none;"'; ?>>

                <form method="post" class="form-horizontal" action="">
                    <?php echo $form->renderHiddenFields(); ?>
                    <?php echo $form->renderGlobalErrors(); ?>

                    <h4>Modification de vos identifiants de connexion :</h4>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Login :</label>
                      <div class="col-sm-4">
                        <p class="form-control-static"><?php echo $compte->login; ?></p>
                      </div>
                    </div>

                    <?php echo $form['mdp1']->renderError() ?>
                    <div class="form-group">
                      <?php echo $form['mdp1']->renderLabel(null, ['class' => 'col-sm-4 control-label']) ?>
                      <div class="col-sm-4">
                        <?php echo $form['mdp1']->render() ?>
                      </div>
                    </div>

                    <?php echo $form['mdp2']->renderError() ?>
                    <div class="form-group">
                      <?php echo $form['mdp2']->renderLabel(null, ['class' => 'col-sm-4 control-label']) ?>
                      <div class="col-sm-4">
                        <?php echo $form['mdp2']->render() ?>
                      </div>
                    </div>

		            	  <h4>Modification de vos informations de contact :</h4>

                    <?php echo $form['email']->renderError() ?>
                    <div class="form-group">
                      <?php echo $form['email']->renderLabel(null, ['class' => 'col-sm-4 control-label']) ?>
                      <div class="col-sm-4">
                        <?php echo $form['email']->render() ?>
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

                    <div>
                        <a class="btn btn-default annuler" style="float: left;" href="#" >Annuler</a>
                        <button type="submit" class=" btn btn-primary btn_valider modifier pull-right" style="cursor: pointer;" >Valider</button>
                    </div>

                </form>
            </div>


        </div>
    <?php endif; ?>
</div>
</div>
</div>
</div>
