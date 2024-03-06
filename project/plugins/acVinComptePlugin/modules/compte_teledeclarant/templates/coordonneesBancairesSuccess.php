<div id="principal" >
  <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
    <h4 class="titre_principal">Mon compte > Coordonnées bancaires</h4>
  </div>
   <div class="panel-body">
    <br/>

        <div id="modification_compte" class="col-xs-12">
        	<p>Pour vous inscrire au prélèvement automatique, merci de bien vouloir renseigner vos coordonnées bancaires dans le formulaire ci-dessous.</p>

          <div class="col-sm-12">&nbsp;<br/></div>

          <form action="<?php echo url_for('compte_teledeclarant_coordonnees_bancaires') ?>" method="post" class="form-horizontal">
            <?php echo $form->renderHiddenFields(); ?>
            <?php echo $form->renderGlobalErrors(); ?>

            <div class="form-group">
              <label class="col-sm-3">Référence Unique de Mandat :</label>
              <div class="col-sm-7">
                <?php echo $form->getObject()->identifiant_rum ?>
              </div>
            </div>

            <div class="form-group<?php if ($form['nom']->renderError()): ?> has-error<?php endif; ?>">
              <?php echo $form['nom']->renderLabel(null, array('class' => 'col-sm-3')) ?>
              <div class="col-sm-6">
                <?php echo $form['nom']->render() ?>
              </div>
              <div class="col-sm-3 text-danger">
                <?php echo $form['nom']->renderError(); ?>
              </div>
            </div>

            <div class="form-group<?php if ($form['adresse']->renderError()): ?> has-error<?php endif; ?>">
              <?php echo $form['adresse']->renderLabel(null, array('class' => 'col-sm-3')) ?>
              <div class="col-sm-6">
                <?php echo $form['adresse']->render() ?>
              </div>
              <div class="col-sm-3 text-danger">
                <?php echo $form['adresse']->renderError(); ?>
              </div>
            </div>

            <div class="form-group<?php if ($form['code_postal']->renderError()): ?> has-error<?php endif; ?>">
              <?php echo $form['code_postal']->renderLabel(null, array('class' => 'col-sm-3')) ?>
              <div class="col-sm-6">
                <?php echo $form['code_postal']->render() ?>
              </div>
              <div class="col-sm-3 text-danger">
                <?php echo $form['code_postal']->renderError(); ?>
              </div>
            </div>

            <div class="form-group<?php if ($form['commune']->renderError()): ?> has-error<?php endif; ?>">
              <?php echo $form['commune']->renderLabel(null, array('class' => 'col-sm-3')) ?>
              <div class="col-sm-6">
                <?php echo $form['commune']->render() ?>
              </div>
              <div class="col-sm-3 text-danger">
                <?php echo $form['commune']->renderError(); ?>
              </div>
            </div>

            <div class="form-group<?php if ($form['iban']->renderError()): ?> has-error<?php endif; ?>">
              <?php echo $form['iban']->renderLabel(null, array('class' => 'col-sm-3')) ?>
              <div class="col-sm-6">
                <?php echo $form['iban']->render() ?>
              </div>
              <div class="col-sm-3 text-danger">
                <?php echo $form['iban']->renderError(); ?>
              </div>
            </div>

            <div class="form-group<?php if ($form['bic']->renderError()): ?> has-error<?php endif; ?>">
              <?php echo $form['bic']->renderLabel(null, array('class' => 'col-sm-3')) ?>
              <div class="col-sm-6">
                <?php echo $form['bic']->render() ?>
              </div>
              <div class="col-sm-3 text-danger">
                <?php echo $form['bic']->renderError(); ?>
              </div>
            </div>

            <p>Une fois vos coordonnées bancaires saisies, vous devrez télécharger le document PDF du mandat de prélèvement SEPA, l'imprimer, le signer et nous le retourner par voie postale à l'adresse indiqué sur ledit document.</p>

            <div class="col-sm-12">&nbsp;<br/></div>

            <div class="col-sm-12">
                  <a href="<?php echo url_for('compte_teledeclarant_modification'); ?>" class=" btn btn-default " alt="Retour" style="cursor: pointer;">Retour</a>
                  <input type="submit" class="btn btn-success" style="cursor: pointer; float: right;" value="Enregistrer" />
            </div>
          </form>
        </div>


        </div>
</div>
