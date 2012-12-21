<?php if($etablissement->famille == EtablissementFamilles::FAMILLE_PRODUCTEUR): ?>
<h2>Mouvements DRM</h2>
<?php include_partial('drm/mouvements', array('mouvements' => $mouvements_drm, 'hamza_style' => true)) ?>
<?php endif; ?>

<?php if($etablissement->famille == EtablissementFamilles::FAMILLE_NEGOCIANT): ?>
<h2>Mouvements SV12</h2>
<?php include_partial('sv12/mouvements', array('mouvements' => $mouvements_sv12, 'hamza_style' => true)) ?>
<?php endif ?>