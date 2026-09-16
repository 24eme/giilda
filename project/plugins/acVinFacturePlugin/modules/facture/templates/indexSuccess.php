<?php include_partial('facture/preTemplate'); ?>

<ol class="breadcrumb">
    <li class="active"><a href="<?php echo url_for('facture') ?>">Factures</a></li>
</ol>

<div class="row">
    <div class="col-xs-12" id="formEtablissementChoice">
        <?php include_component('facture', 'chooseSociete'); ?>
    </div>
</div>

<?php if(isset($interpros) && count($interpros)): ?>
<ul class="nav nav-tabs">
<?php foreach($interpros as $keyInterpro => $interpro): ?>
  <li role="presentation" class="<?php echo ($keyInterpro == $interproFacturable) ? "active" : null ?>"><a href="<?php echo url_for('facture', ['interpro' => $keyInterpro]) ?>"><?php echo $interpro ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<div class="row">
    <div class="col-xs-12">
        <h2>Génération des factures</h2>
        <?php include_partial('historiqueGeneration', array('generations' => $generations, 'interpro' => $interproFacturable)); ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 facturemassive">
        <h3>Générer toutes les factures <small>(<a href="<?php echo url_for('facture_en_attente', ['interpro' => $interproFacturable]); ?>">mvts en attentes</a> | <a href="<?php echo url_for('facture_en_attente', ['only_versionnes_factures' =>  1, 'versionnes' => 1, 'interpro' => $interproFacturable]); ?>">mvts modifiés</a>)</small></h3>
        <?php include_partial('generationMasse', ['generationForm' => $generationForm, 'massive' => true, 'interpro' => $interproFacturable]); ?>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-xs-12">
        <h2>Facturation libre</h2>
        <a href="<?php echo url_for('facture_mouvements', ['interpro' => $interproFacturable]); ?>" class="btn btn-md btn-default">Créer des mouvements de facturation libre</a>
    </div>
</div>

<?php include_partial('facture/postTemplate'); ?>