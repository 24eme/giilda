<?php
use_helper('Float');
?>    

<ol class="breadcrumb">
    <li><a href="<?php echo url_for('facture') ?>">Page d'accueil</a></li>
    <li><a href="<?php echo url_for('facture_societe', $societe) ?>"><?php echo $societe->raison_sociale ?> (<?php echo $societe->identifiant ?>)</a></li>
    <li><a href="<?php echo url_for('facture_creation', $societe) ?>" class="active">Génération de factures</a></li>
</ol>

<div class="row">
    <div class="col-xs-12">
        <?php include_component('facture', 'chooseSociete', array('identifiant' => $societe->identifiant)); ?>
    </div>
    <div class="col-xs-12">
        <h2>Génération de facture</h2>
        <?php if ($sf_user->hasFlash('notice')): ?>
            <div class="alert alert-success" role="alert"><?php echo $sf_user->getFlash('notice') ?></div>
        <?php endif; ?>

        <?php if ($sf_user->hasFlash('error')): ?>
            <div class="alert alert-danger" role="alert"><?php echo $sf_user->getFlash('error') ?></div>
        <?php endif; ?>
        
        <?php include_partial('facture/generationMasse', array('generationForm' => $form,'massive' => false)); ?>
    </div>
</div>