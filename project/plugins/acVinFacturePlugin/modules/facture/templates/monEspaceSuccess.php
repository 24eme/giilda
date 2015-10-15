<?php
use_helper('Float');
?>    

<p id="fil_ariane"><a href="<?php echo url_for('facture') ?>">Page d'accueil</a> &gt; <strong><?php echo $societe->raison_sociale ?></strong></p>
<div id="contenu_etape" class="col-xs-12">
    <?php include_component('facture', 'chooseSociete', array('identifiant' => $societe->identifiant)); ?>

    <div class="col-xs-12">
        <a href="<?php echo url_for('facture_creation', $societe); ?>" class="btn btn-md btn-info pull-right"><span class="glyphicon glyphicon-briefcase"></span>&nbsp;Création d'une factures</a>
    </div>
    <?php include_partial('historiqueFactures', array('societe' => $societe, 'factures' => $factures)); ?>

     <hr />    
    <br />
    <?php include_partial('facture/mouvements', array('mouvements' => $mouvements, 'societe' => $societe, 'form' => $form)) ?>
    
</div>
<!-- fin #principal -->

<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('facture'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>

