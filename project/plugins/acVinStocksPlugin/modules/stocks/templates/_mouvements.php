<?php if($etablissement->famille == EtablissementFamilles::FAMILLE_PRODUCTEUR): ?>
<h2>Mouvements</h2>
<?php include_partial('drm/mouvements', array('mouvements' => $mouvements_viticulteur, 'hamza_style' => true)) ?>
<?php endif; ?>

<?php if($etablissement->famille == EtablissementFamilles::FAMILLE_NEGOCIANT): ?>
<h2>Mouvements</h2>
<?php include_partial('sv12/mouvements', array('mouvements' => $mouvements_negociant, 'hamza_style' => true)) ?>
<?php endif ?>