<?php
/* Fichier : recapitulatifSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/recapitulatif
 * Affichage des dernières information de la saisie : numero de contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 29-05-12
 */
?>
<section id="principal">      
    <div id="contenu_etape">
        <div id="vrac_visualisation"> 
            <div id="titre">
                <a id="btn_pdf_contrat" href="<?php echo url_for('vrac_pdf', $vrac); ?>">
                    <span class="style_label">N° d'enregistrement du contrat : <?php echo $vrac->numero_archive ?> (<?php echo $vrac->campagne ?>)</span>
                </a>
            </div>            
            <?php if (!$vrac->isTeledeclare() && !(isset($authenticated) || $authenticated)): ?>    
                <form id="vrac_condition" method="post" action="<?php echo url_for('vrac_visualisation', $vrac) ?>">  
                    <div id="ss_titre" class="legende"><span class="style_label">Etat du contrat</span>
                        <?php if ($vrac->valide->statut != VracClient::STATUS_CONTRAT_ANNULE) : ?>
                            <?php if ($vrac->valide->statut == VracClient::STATUS_CONTRAT_NONSOLDE) : ?>
                                <a href="<?php echo url_for('vrac_solder', $vrac) ?>" class="btn_majeur btn_vert f_right">Solder le contrat</a>
                            <?php endif; ?>
                            <?php if ($vrac->valide->statut == VracClient::STATUS_CONTRAT_SOLDE) : ?>
                                <a href="<?php echo url_for('vrac_nonsolder', $vrac) ?>" class="btn_majeur btn_orange f_right">Annuler le solde</a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div>
                            <?php
                            if ($vrac->valide->statut == VracClient::STATUS_CONTRAT_NONSOLDE) {
                                $class = 'statut_non-solde';
                            } elseif ($vrac->valide->statut == VracClient::STATUS_CONTRAT_ANNULE) {
                                $class = 'statut_annule';
                            } else {
                                $class = 'statut_solde';
                            }
                            ?>

                            <span class="statut <?php echo $class; ?>"></span><span class="legende_statut_texte"><?php echo $vrac->valide->statut; ?></span>
                        </div>                            
                    </div>
                    <div id="ligne_btn">
                        <?php
                        if (!is_null($vrac->valide->statut) && $vrac->valide->statut != VracClient::STATUS_CONTRAT_ANNULE && (is_null($vrac->volume_enleve) || ($vrac->volume_enleve == 0))):
                            ?>
                            <a id="btn_editer_contrat" href="<?php echo url_for('vrac_soussigne', $vrac); ?>"> Editer le contrat</a>
                            <button id="btn_annuler_contrat" type="submit">Annuler le contrat</button>            
    <?php endif; ?>                                 
                    </div>
                </form>
<?php endif; ?> 


<?php if ($signatureDemande): ?>
                    <a id="signature_popup_haut" href="#signature_popup_content" class="signature_popup btn_majeur btn_vert f_right">Signer le contrat</a> 
   
<?php endif; ?>

            <?php include_partial('showContrat', array('vrac' => $vrac, 'societe' => $societe, 'signatureDemande' => $signatureDemande)); ?>

            <?php
            if (!is_null($vrac->valide->statut) && $vrac->valide->statut != VracClient::STATUS_CONTRAT_ANNULE):
                ?>
                <a href="<?php echo url_for('vrac_pdf', $vrac) ?>" class="btn_majeur btn_orange f_right">Télécharger le PDF</a>  
            <?php endif; ?>
        </div>
    </div>
<?php if ($signatureDemande): ?>
        <a id="signature_popup_bas" href="#signature_popup_content" class="signature_popup btn_majeur btn_vert f_right">Signer le contrat</a>      
        <?php include_partial('signature_popup', array('vrac' => $vrac, 'societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal)); ?>
    <?php endif; ?>
    <?php if ($popupSignature): ?>
        <script type="text/javascript">
            $(document).ready(function()
            {
                triggerSignaturePopup();
            });
        </script>
<?php endif; ?>
</section>
    <?php
    if ($vrac->isTeledeclare() || (isset($authenticated) && $authenticated)):
        include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));
    else:
        slot('colButtons');
        include_partial('actions_visu', array('vrac' => $vrac));
        end_slot();

        slot('colApplications');
        include_partial('contrat_infos_contact', array('vrac' => $vrac));

        end_slot();
    endif;
    ?>

