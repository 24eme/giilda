<ol class="breadcrumb">
    <li><a href="<?php echo url_for('facture') ?>">Page d'accueil</a></li>
</ol>

<div class="row">
    <div class="col-xs-12">
        <?php include_component('facture', 'chooseSociete'); ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <h2>Génération des factures</h3>
        <div class="row">
            <div class="col-xs-12">
                <?php include_partial('historiqueGeneration', array('generations' => $generations)); ?>
            </div>
            <div class="col-xs-8">
                <?php include_component('facture','generationMasse'); ?>
            </div>
        </div>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-xs-12">
        <h2>Facturation libre</h3>
        <a href="<?php echo url_for('facture_mouvements'); ?>" class="btn btn-md btn-default">Créer des mouvements de facturation libre</a>
         <a href="<?php echo url_for('comptabilite_edition'); ?>" class="btn btn-md btn-default">Gérer les identifiants analytiques</a>
    </div>
</div>
