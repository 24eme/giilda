<?php
/* Fichier : validationSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/validation
 * Formulaire d'enregistrement de la partie validation d'un contrat donnant le récapitulatif
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0
 * Derniere date de modification : 28-05-12
 */
use_helper('Vrac');
?>
<script type="text/javascript">
    $(document).ready(function()
    {
<?php echo ($contratsSimilairesExist) ? 'initValidationWithPopup();' : 'initValidation();'; ?>
    });
</script>

<section id="principal">
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
                      <?php if($vrac->isBio() && $etablissementPrincipal->isNegociant()): ?>
                        <div style="display:none">
                          <input name="vrac_validation_bio_ecocert" id="vrac_validation_bio_ecocert" type="checkbox">
                        </div>
                      <?php endif; ?>
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
