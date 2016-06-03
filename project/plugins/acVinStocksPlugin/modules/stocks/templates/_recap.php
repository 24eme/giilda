<h2>Stocks (<?php echo $campagne ?>)</h2>

<?php if($etablissement->isViticulteur()): ?>
<?php include_component('drm', 'stocksRecap', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>
<?php endif; ?>

<?php if($etablissement->isNegociant()): ?>
<?php include_component('stocks', 'recapNegociant', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>
<?php endif ?>
