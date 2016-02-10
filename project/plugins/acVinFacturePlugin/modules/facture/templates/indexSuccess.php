<p id="fil_ariane"><strong>Page d'accueil</strong></p>
<div class="row">
    <div id="contenu_etape" class="col-xs-12">
        <?php include_component('facture', 'chooseSociete'); ?>
        <div class="row row-margin">
            <div class="col-xs-12">
                <a href="<?php echo url_for('facture_mouvements'); ?>" class="btn btn-md btn-default pull-right">Facturation libre</a>
            </div>
        </div>
        <br/>
        <div class="row row-margin">
            <div class="col-xs-12">
                <a href="<?php echo url_for('comptabilite_edition'); ?>" class="btn btn-md btn-default pull-right">Gérer les identifiants analytiques</a>
            </div>
        </div>
         <br/>
        <div class="row row-margin">
            <div class="col-xs-12">
                <a href="<?php echo url_for('facture_generation'); ?>" class="btn btn-md btn-default pull-right">Génération de facture</a>
            </div>
        </div>
        <?php include_partial('historiqueGeneration', array('generations' => $generations)); ?>
        <?php include_component('facture','generationMasse'); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <a href="<?php echo url_for('facture_mouvements'); ?>" class="btn btn-default btn-xs">Mouvements de Facture</a>
    </div>
</div>
