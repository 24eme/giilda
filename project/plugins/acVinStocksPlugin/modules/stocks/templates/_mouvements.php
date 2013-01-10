<h2>Mouvements</h2>

<?php if($etablissement->isViticulteur()): ?>
<?php include_partial('drm/mouvements', array('mouvements' => $mouvements_viticulteur, 'hamza_style' => true)) ?>
<?php endif; ?>

<?php if($etablissement->isNegociant()): ?>
<?php include_partial('sv12/mouvements', array('mouvements' => $mouvements_negociant, 'hamza_style' => true)) ?>
<?php endif ?>