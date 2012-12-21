<?php if($etablissement->famille == EtablissementFamilles::FAMILLE_PRODUCTEUR): ?>
<h2>Stocks vins (DRMs <?php echo $campagne ?>)</h2>
<?php include_component('drm', 'stocksRecap', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>
<?php endif; ?>

<?php if($etablissement->famille == EtablissementFamilles::FAMILLE_NEGOCIANT): ?>
<h2>Synthèse raisins et moûts (SV12s <?php echo $campagne ?>)</h2>
<?php include_component('sv12', 'stocksRecap', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>
<?php endif ?>