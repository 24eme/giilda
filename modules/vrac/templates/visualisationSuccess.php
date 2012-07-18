<?php
/* Fichier : recapitulatifSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/recapitulatif
 * Affichage des dernières information de la saisie : numero de contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 29-05-12
 */
?>
<div id="contenu">
    <div id="rub_contrats">
        <section id="principal">      
            <div id="contenu_etape">
                <div id="vrac_visualisation"> 
                    <div id="titre">
   <span class="style_label">N° d'enregistrement du contrat : <?php echo preg_replace('/(\d{8})(\d+)/', '\1 \2', $vrac['numero_contrat']); ?></span>
                    </div>
                    <form id="vrac_condition" method="post" action="<?php echo url_for('vrac_visualisation',$vrac) ?>">  
                        <div id="ss_titre" class="legende"><span class="style_label">Etat du contrat</span>
                            <?php if($vrac->valide->statut!= VracClient::STATUS_CONTRAT_ANNULE) : ?>
                                <?php if($vrac->valide->statut== VracClient::STATUS_CONTRAT_NONSOLDE) : ?>
                                    <a href="<?php echo url_for('vrac_solder',$vrac) ?>" class="btn_majeur btn_vert f_right">Solder le contrat</a>
                                <?php endif; ?>
                                <?php if($vrac->valide->statut== VracClient::STATUS_CONTRAT_SOLDE) : ?>
                                    <a href="<?php echo url_for('vrac_nonsolder',$vrac) ?>" class="btn_majeur btn_orange f_right">Annuler le solde</a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div>
                            <?php 

                            if($vrac->valide->statut == VracClient::STATUS_CONTRAT_NONSOLDE){ 
                                $class = 'statut_non-solde';
                            }elseif($vrac->valide->statut == VracClient::STATUS_CONTRAT_ANNULE){ 
                                $class = 'statut_annule';
                            }else{ 
                                $class = 'statut_solde';
                            } ?>

                            <span class="statut <?php echo $class; ?>"></span><span class="legende_statut_texte"><?php echo $vrac->valide->statut; ?></span>
                            </div>                            
                        </div>
                        <div id="ligne_btn">
                            <?php if(!is_null($vrac->valide->statut) 
                                    && $vrac->valide->statut != VracClient::STATUS_CONTRAT_ANNULE 
                                    && (is_null($vrac->volume_enleve) || ($vrac->volume_enleve==0))): ?>
                                <a id="btn_editer_contrat" href="<?php echo url_for('vrac_soussigne', $vrac); ?>"> Editer le contrat</a>
                            <?php endif; ?>

							<?php if($vrac->valide->statut != VracClient::STATUS_CONTRAT_ANNULE): ?>
                            	<button id="btn_annuler_contrat" type="submit">Annuler le contrat</button>
                        	<?php endif; ?>
                                 
                        </div>
                    </form>
                    <?php include_partial('showContrat', array('vrac' => $vrac)); ?>
                </div>
            </div>
        </section>
        <aside id="colonne">
            <?php include_partial('actions_visu', array('vrac' => $vrac)); ?>
            <?php include_partial('contrat_aide'); ?>
            <?php include_partial('contrat_campagne',array('vrac' => $vrac, 'visualisation' => true)); ?>
            <?php include_partial('contrat_infos_contact',array('vrac' => $vrac)); ?>
        </aside>
    </div>
</div>