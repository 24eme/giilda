<?php use_helper('Vrac'); ?>

<?php include_partial('vrac/etapes', array('vrac' => $vrac, 'compte' => $compte, 'actif' => 3, 'urlsoussigne' => null, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>



<?php include_partial("vrac/recap", array('vrac' => $vrac, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>

<div class="row">
    <div class="col-xs-4 text-left">
        <a href="<?php echo url_for('vrac_condition', $vrac); ?>" class="btn btn-default">Etape précédente</a>
    </div>
    <div class="col-xs-4 text-center">
        <?php if ($isTeledeclarationMode && $vrac->isBrouillon()) : ?>
            <a class="btn btn-default" href="<?php echo url_for('vrac_supprimer_brouillon', $vrac); ?>" style="margin-left: 10px">Supprimer le brouillon
            </a>
        <?php endif; ?>
    </div>
    <div class="col-xs-4 text-right">
        <?php if ($validation->isValide()) : ?>
            <?php if ($isTeledeclarationMode): ?>
                <?php if ($signatureDemande): ?>
                    <a href="#signature_popup_content" class="btn btn-default">Signer le contrat</a> 
                    <?php include_partial('signature_popup', array('vrac' => $vrac, 'societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'validation' => true)); ?>
                <?php endif; ?>
            <?php else: ?>
                <a class="btn btn-default">Terminer la saisie</a>  
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>