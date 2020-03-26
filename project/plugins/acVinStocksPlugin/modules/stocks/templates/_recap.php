<?php if($etablissement->isViticulteur()): ?>
<h2>Stocks Suspendus (<?php echo $campagne ?>)</h2>
<?php include_component('drm', 'stocksRecap', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>
<?php endif; ?>

<?php if($etablissement->isNegociant()): ?>
<h2>Stocks SV12 (<?php echo $campagne ?>)</h2>
<?php include_component('stocks', 'recapNegociant', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>
<?php endif ?>
