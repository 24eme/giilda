
<?php if($etablissement->isViticulteur()): ?>
  <h2 id="hamza_mouvement">Mouvements en droits suspendus</h2>
  <fieldset id="fieldset_SUSPENDU" class="validation_drm_tables">
    <div id="drm_visualisation_stock_SUSPENDU" class="section_label_maj">
  <?php include_partial('mouvementsDrm', array('mouvementsByProduit' => $mouvements_viticulteur, 'isTeledeclarationMode' => false, 'visualisation' => true, 'hamza_style' => true, 'no_link' => false,'typeKey' => DRMClient::TYPE_DRM_SUSPENDU, 'typeDetailKey' => DRM::DETAILS_KEY_SUSPENDU,'identifiant' => $etablissement->identifiant)) ?>
    <div>
  </fieldset>
<?php endif; ?>

<?php if($etablissement->isNegociant()): ?>
<?php include_partial('sv12/mouvements', array('mouvements' => $mouvements_negociant, 'hamza_style' => true, 'from_stock' => true)) ?>
<?php endif ?>
