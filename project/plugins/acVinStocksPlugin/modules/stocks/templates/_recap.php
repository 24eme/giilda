<h2>Stocks vins (DRMs <?php echo $campagne ?>)</h2>
<?php include_component('drm', 'stocksRecap', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>
<h2>Synthèse raisins et moûts (SV12s <?php echo $campagne ?>)</h2>
<?php include_component('sv12', 'stocksRecap', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>