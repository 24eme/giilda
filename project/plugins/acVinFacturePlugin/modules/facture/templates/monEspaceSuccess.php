<?php
use_helper('Float');
?>

<ol class="breadcrumb">
    <li><a href="<?php echo url_for('facture') ?>">Factures</a></li>
    <li><a href="<?php echo url_for('facture_societe', $societe) ?>" class="active"><?php echo $societe->raison_sociale ?> (<?php echo $societe->identifiant ?>)</a></li>
</ol>

<div class="row">
    <div class="col-xs-12">
        <?php include_component('facture', 'chooseSociete', array('identifiant' => $societe->identifiant)); ?>
    </div>
    <div class="col-xs-12">
        <?php include_partial('historiqueFactures', array('societe' => $societe, 'factures' => $factures)); ?>
         <hr />
        <?php include_partial('facture/mouvements', array('mouvements' => $mouvements, 'societe' => $societe, 'form' => $form)) ?>    
    </div>
</div>

