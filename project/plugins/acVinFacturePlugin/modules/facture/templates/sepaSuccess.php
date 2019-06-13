<?php
use_helper('Float');
?>
<!-- #principal -->
<section id="principal" class="drm">
    <div id="application_drm">
        <h2>informations bancaires liées à votre société</h2>

        <form  method="POST" class="drm_validation_societe_form">
            <?php echo $form->renderHiddenFields(); ?>
            <?php echo $form->renderGlobalErrors(); ?>
            <div class="ligne_form">
                <label>Raison Sociale :</label>
                <?php echo $societe->raison_sociale; ?>
            </div>
            <div class="ligne_form">
                <?php echo $form['nom_bancaire']->renderError(); ?>
                <?php echo $form['nom_bancaire']->renderLabel(); ?>
                <?php echo $form['nom_bancaire']->render(); ?>
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
            <div class="ligne_form">
                <?php echo $form['engagement']->renderError(); ?>
                <?php echo $form['engagement']->renderLabel(); ?>
                <?php echo $form['engagement']->render(array('required' => 'true')); ?>
            </div>
            <div id="btn_etape_dr">
                <a href="<?php echo url_for('facture_teledeclarant', array('identifiant' => $etablissementPrincipal->getIdentifiant())) ?>" class="btn_majeur btn_annuler" style="float: left;" id="drm_validation_societe_annuler_btn"><span>annuler</span></a>
                <button type="submit" class="btn_majeur btn_valider" id="drm_validation_societe_valider_btn" style="float: right;"><span>Valider</span></button>
            </div>
        </form>
    </div>
</section>
  <!-- fin #principal -->

  <?php
  include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'retour_espace' => true));
  ?>
