<?php include_partial('facture/preTemplate'); ?>

<ol class="breadcrumb">
    <li class="active"><a href="<?php echo url_for('facture') ?>">Factures</a></li>
</ol>

<div class="row">
    <div class="col-xs-12" id="formEtablissementChoice">
        <?php include_component('facture', 'chooseSociete'); ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <h2>Génération des factures</h2>
        <?php include_partial('historiqueGeneration', array('generations' => $generations, 'interproFacturable' => $interproFacturable)); ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <h3>Générer toutes les factures <small>(<a href="<?php echo url_for('facture_en_attente'); ?>">mvts en attentes</a> | <a href="<?php echo url_for('facture_en_attente', ['only_versionnes_factures' =>  1, 'versionnes' => 1]); ?>">mvts modifiés</a>)</small></h3>
        <?php include_partial('generationMasse', ['generationForm' => $generationForm, 'massive' => true]); ?>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-xs-12">
        <h2>Facturation libre</h2>
        <a href="<?php echo url_for('facture_mouvements'); ?>" class="btn btn-md btn-default">Créer des mouvements de facturation libre</a>
    </div>
</div>

<?php include_partial('facture/postTemplate'); ?>