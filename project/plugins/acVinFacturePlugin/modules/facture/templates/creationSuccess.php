<?php
use_helper('Float');
?>    

<p id="fil_ariane"><a href="<?php echo url_for('facture') ?>">Page d'accueil</a> &gt; <strong><?php echo $societe->raison_sociale ?></strong></p>
<div id="contenu_etape" class="col-xs-12">
    <?php include_component('facture', 'chooseSociete', array('identifiant' => $societe->identifiant)); ?> 
</div>
<div class="col-xs-12">
    <h2>Génération de facture</h2>
</div>

<?php if ($sf_user->hasFlash('notice')): ?>
    <div class="alert alert-success" role="alert"><?php echo $sf_user->getFlash('notice') ?></div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('error')): ?>
    <div class="alert alert-danger" role="alert"><?php echo $sf_user->getFlash('error') ?></div>
<?php endif; ?>
<div class="col-xs-12">
    <?php include_partial('facture/generationMasse', array('generationForm' => $form,'massive' => false)); ?>
</div>