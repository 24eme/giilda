<?php
use_helper('Float');
?>
<!-- #principal -->
<section id="principal" class="drm">
  <div id="sepa">
    <div class="sepa_panel" style="min-height: 50px;">
      <div class="title"><span class="text">MES COORDONNÉES BANCAIRES</span></div>
      <div class="panel">

        <form  method="POST" class="">
          <?php echo $form->renderHiddenFields(); ?>
          <?php echo $form->renderGlobalErrors(); ?>
          <div class="ligne_form">
            <label>Raison Sociale :</label>
            <strong><?php echo $societe->raison_sociale; ?></strong>
          </div>
          <div class="ligne_form">
            <?php echo $form['nom_bancaire']->renderError(); ?>
            <?php echo $form['nom_bancaire']->renderLabel(); ?>
            <?php echo $form['nom_bancaire']->render(array('class' => 'champ_long')); ?>
          </div>
          <div class="ligne_form">
            <?php echo $form['iban']->renderError(); ?>
            <?php echo $form['iban']->renderLabel(); ?>
            <?php echo $form['iban']->render(array('class' => 'champ_long')); ?>
          </div>
          <div class="ligne_form">
            <?php echo $form['bic']->renderError(); ?>
            <?php echo $form['bic']->renderLabel(); ?>
            <?php echo $form['bic']->render(); ?>
          </div>
          <div id="btn_etape_dr" >
            <a href="<?php echo url_for('facture_teledeclarant', array('identifiant' => $etablissementPrincipal->getIdentifiant())) ?>" class="btn_majeur btn_annuler" style="float: left;" id="drm_validation_societe_annuler_btn"><span>annuler</span></a>
            <button type="submit" class="btn_validation" id="drm_validation_societe_valider_btn" style="float: right;"><span>Continuer</span></button>
          </div>
        </form>

      </div>
    </div>
  </div>
</section>
<!-- fin #principal -->

<?php
include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'retour_espace' => true));
?>
