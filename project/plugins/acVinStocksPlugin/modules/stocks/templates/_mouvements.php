<h2 id="hamza_mouvement">Mouvements</h2>

<?php if($etablissement->isViticulteur()): ?>
<?php include_partial('drm/mouvements', array('mouvements' => $mouvements_viticulteur, 'hamza_style' => true, 'from_stock' => true)) ?>
<?php endif; ?>

<?php if($etablissement->isNegociant()): ?>
<?php include_partial('sv12/mouvements', array('mouvements' => $mouvements_negociant, 'hamza_style' => true, 'from_stock' => true)) ?>
<?php endif ?>