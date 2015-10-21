<p id="fil_ariane"><strong>Page d'accueil</strong></p>
<div class="row">
    <div id="contenu_etape" class="col-xs-12">
        <?php include_component('facture', 'chooseSociete'); ?>
        <?php include_partial('historiqueGeneration', array('generations' => $generations)); ?>
        <?php //include_component('facture','generationMasse'); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <a href="<?php echo url_for('facture_mouvements'); ?>" class="btn btn-default btn-xs">Mouvements de Facture</a>
    </div>
</div>