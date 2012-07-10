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
                            <span class="style_label">N° d'enregistrement du contrat : <?php echo $vrac['numero_contrat']; ?></span>
                    </div>
                    <form id="vrac_condition" method="post" action="<?php echo url_for('vrac_visualisation',$vrac) ?>">  
                        <div id="ss_titre" class="legende"><span class="style_label">Etat du contrat</span>
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
                            <?php if(!is_null($vrac->valide->statut) && $vrac->valide->statut != VracClient::STATUS_CONTRAT_ANNULE): ?>
                                <a id="btn_editer_contrat" href="<?php echo url_for('vrac_soussigne', $vrac); ?>"> Editer le contrat</a>
                            <?php endif; ?>
                                
<!--                            <a id="btn_voir_contrat" href="#"> Voir le contrat</a>       -->
                            <button id="btn_annuler_contrat" type="submit">Annuler le contrat</button>
                                 
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