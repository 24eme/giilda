<?php
/* Fichier : recapitulatifSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/recapitulatif
 * Affichage des dernières information de la saisie : numero de contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 29-05-12
 */
use_helper('Vrac');
use_helper('Date');
?>
<div class="row">      
        <div class="col-xs-12">
            <h2 class="titre_page">
                <?php if ($isTeledeclarationMode): ?>
                    <span>Visualisation du contrat</span>
                <?php endif; ?>
                <div class="statut_contrat">

                    <?php
                    if ($isTeledeclarationMode):
                        $classStatut = strtolower($vrac->valide->statut);
                        if (($vrac->valide->statut == VracClient::STATUS_CONTRAT_ATTENTE_SIGNATURE) && $vrac->isSocieteHasSigned($societe)) {
                            $classStatut = 'attente_signature_autres';
                        }
                        ?>
                        <span class="<?php echo $classStatut; ?>"><?php echo "Contrat de " . showType($vrac) . '&nbsp;-&nbsp;' . $vrac->getTeledeclarationStatutLabel(); ?></span>
                    <?php endif; ?>                           
                </div>
            </h2>
          </div>
          <div class="col-xs-4 text-left">
		        	<p>
		            	<span class="<?php echo typeToPictoCssClass($vrac->type_transaction) ?>" style="font-size: 24px;"><?php echo "&nbsp;Contrat de " . showType($vrac); ?></span>
		            </p>
           </div>
		   <div class="col-xs-4 text-center">	
            <?php if ($vrac->isVise()) : ?>
            <p style="font-size: 24px;">
 			N° <?php echo $vrac->numero_archive; ?> (<?php echo format_date($vrac->date_campagne, "dd/MM/yyyy", "fr_FR"); ?>) <span class="pull-right">
            </p>
           </div>
		   <div class="col-xs-3 text-right">	
            <p style="font-size:24px">
                            <span class="statut <?php echo getClassStatutPicto($vrac, $isTeledeclarationMode); ?>"></span><strong class="bg-success legende_statut_texte"><?php echo $vrac->getStatutLabel(); ?></strong></span>
 			</p>
           </div>
		   <div class="col-xs-1 text-right">	
            	<form id="vrac_condition" method="post" action="<?php echo url_for('vrac_visualisation', $vrac) ?>"> 
                <div id="ligne_btn">
                <?php if (!$isTeledeclarationMode): ?>
                    
                        <?php if ($vrac->valide->statut != VracClient::STATUS_CONTRAT_ANNULE) : ?>
                            <?php if ($vrac->valide->statut == VracClient::STATUS_CONTRAT_NONSOLDE) : ?>
                                <a href="<?php echo url_for('vrac_solder', $vrac) ?>" class="btn btn-default">Solder</a>
                            <?php endif; ?>
                            <?php if ($vrac->valide->statut == VracClient::STATUS_CONTRAT_SOLDE) : ?>
                                <a href="<?php echo url_for('vrac_nonsolder', $vrac) ?>" class="btn">Désolder</a>
                            <?php endif; ?>
                        <?php endif; ?>
                <?php endif; ?>
                    <?php
                    if (!is_null($vrac->valide->statut) && $vrac->valide->statut != VracClient::STATUS_CONTRAT_ANNULE && (is_null($vrac->volume_enleve) || ($vrac->volume_enleve == 0))):
                        if (!$isTeledeclarationMode):
                            ?>
                            <a id="btn_editer_contrat" href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn btn-warning">Modifier</a>
                        <?php endif; ?>
                        <?php if ($isTeledeclarationMode && $isTeledeclare && $isProprietaire && !$vrac->isVise() && $vrac->valide->statut != VracClient::STATUS_CONTRAT_VALIDE): ?>
                            <button id="btn_annuler_contrat" type="submit" class="btn">Annuler</button>  
                        <?php endif; ?>    
                        <?php if (!$isTeledeclarationMode): ?>
                            <button id="btn_annuler_contrat" type="submit" class="btn btn-danger">Annuler</button>  
                        <?php endif; ?>        
                    <?php endif; ?>                                 
                </div>
            </form>
                </div>
 			<?php endif; ?>

            <?php if ($signatureDemande): ?>
                <a id="signature_popup_haut" href="#signature_popup_content" class="signature_popup btn_majeur btn_vert f_right">Signer le contrat</a> 

            <?php endif; ?>

            
            <?php include_partial("vrac/recap", array('vrac' => $vrac, 'isTeledeclarationMode' => $isTeledeclarationMode, 'enlevements' => $enlevements)); ?>
            
            
            <div class="ligne_btn">
                <?php if ($vrac->isVise()): ?>
                    <div class="txt_centre text-center">
                        <a href="<?php echo url_for('vrac_pdf', $vrac) ?>" class="btn btn-success">Télécharger le PDF</a>  
                    </div>
                <?php endif; ?>
                <?php 
                if ($isTeledeclarationMode && !$vrac->isVise()): ?>
                    <a href="<?php echo url_for('vrac_societe', array('identifiant' => str_replace('COMPTE-', '', $societe->compte_societe))); ?>" class="btn_orange btn_majeur" style="float: left;">Retourner à l'espace contrats</a>
                <?php endif; ?>
            </div>
        </div>
    <?php include_partial('popup_notices'); ?> 
    <?php if ($signatureDemande): ?>
        <a id="signature_popup_bas" href="#signature_popup_content" class="signature_popup btn_majeur btn_vert f_right">Signer le contrat</a>      
        <?php include_partial('signature_popup', array('vrac' => $vrac, 'societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal)); ?>
    <?php endif; ?>
</div>
<?php
if ($sf_user->hasTeledeclaration()):
    include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'retour' => true));
else:
    slot('colButtons');
    include_partial('actions_visu', array('vrac' => $vrac));
    end_slot();

    slot('colApplications');
    include_partial('contrat_infos_contact', array('vrac' => $vrac));

    end_slot();
endif;
?>

