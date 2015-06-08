<?php use_helper('Vrac'); ?>

<?php include_partial('vrac/etapes', array('vrac' => $vrac, 'compte' => $compte, 'actif' => 4, 'urlsoussigne' => null, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>

<?php if ($isTeledeclarationMode): ?>
    <h2>Récapitulatif du contrat</h2>
<?php else: ?>
    <h2>Récapitulatif de la saisie</h2>
<?php endif; ?>

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

<script type="text/javascript">
    $(document).ready(function()
    {
<?php echo ($contratsSimilairesExist) ? 'initValidationWithPopup();' : 'initValidation();'; ?>
    });
</script>

<section id="principal" style="display: none">
    <?php include_partial('headerVrac', array('vrac' => $vrac, 'compte' => $compte, 'actif' => 4,'isTeledeclarationMode' => $isTeledeclarationMode)); ?>        
    <div id="contenu_etape"> 
        <form id="vrac_validation" method="post" action="<?php echo url_for('vrac_validation', $vrac) ?>">
            <?php include_partial('document_validation/validation', array('validation' => $validation)); ?>
            <?php if ($isTeledeclarationMode): ?>
                <div id="titre"><span class="style_label">Récapitulatif du contrat</span></div>
            <?php else: ?>
                <div id="titre"><span class="style_label">Récapitulatif de la saisie</span></div>
            <?php endif; ?>
            <?php include_partial('showContrat', array('vrac' => $vrac, 'societe' => $societe, 'template_validation' => true, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>

            <div class="btn_etape">
                <a href="<?php echo url_for('vrac_condition', $vrac); ?>" class="btn_etape_prec"><span>Etape précédente</span></a>
                <?php if ($isTeledeclarationMode && $vrac->isBrouillon()) : ?>
                    <a class="lien_contrat_supprimer_brouillon" href="<?php echo url_for('vrac_supprimer_brouillon', $vrac); ?>" style="margin-left: 10px">
                        <span>Supprimer Brouillon</span>
                    </a>
                <?php endif; ?>
                <?php if ($validation->isValide()) : ?>
                    <?php if ($isTeledeclarationMode): ?>
                        <?php if ($signatureDemande): ?>
                            <a id="signature_popup_haut" href="#signature_popup_content" class="btn_validation signature_popup"><span>Signer le contrat</span></a> 
                            <?php include_partial('signature_popup', array('vrac' => $vrac, 'societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'validation' => true)); ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <a id="btn_validation" class="btn_validation"><span>Terminer la saisie</span></a>  
                    <?php endif; ?>
                <?php endif; ?>
            </div> 
        </form>
    </div>
    <?php include_partial('popup_notices'); ?> 
</section>
<?php
if ($isTeledeclarationMode):
    include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'retour' => true));
else:
    slot('colApplications');
    /*
     * Inclusion du panel de progression d'édition du contrat
     */
    if (!$contratNonSolde):
        include_partial('contrat_progression', array('vrac' => $vrac));
    endif;
    /*
     * Inclusion du panel pour les contrats similaires
     */
    include_partial('contratsSimilaires', array('vrac' => $vrac));

    /*
     * Inclusion des Contacts
     */
    include_partial('contrat_infos_contact', array('vrac' => $vrac));

    end_slot();

    if ($contratsSimilairesExist):
        include_partial('contratsSimilaires_warning_popup', array('vrac' => $vrac));
    endif;
endif;
?>
