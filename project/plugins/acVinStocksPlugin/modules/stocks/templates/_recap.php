<?php if($etablissement->famille == EtablissementFamilles::FAMILLE_PRODUCTEUR): ?>
<h2>Stocks (<?php echo $campagne ?>)</h2>
<?php include_component('drm', 'stocksRecap', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>
<?php endif; ?>

<?php if($etablissement->famille == EtablissementFamilles::FAMILLE_NEGOCIANT): ?>
<h2>Stocks (<?php echo $campagne ?>)</h2>
<?php include_component('stocks', 'recapNegociant', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>
<?php endif ?>