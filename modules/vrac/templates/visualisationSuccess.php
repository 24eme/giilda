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
                    <div id="ss_titre">
                        <label>N° d'enregistrement du contrat : </label><span><?php echo $vrac['numero_contrat']; ?></span>
                    </div>
                    <form id="vrac_condition" method="post" action="<?php echo url_for('vrac_visualisation',$vrac) ?>">  
                        <h2>Etat du contrat</h2>
                        <div id="vrac_visualisation_statut">
                            <?php echo $vrac->valide->statut; ?>
                        </div>
                        <div id="ligne_btn">
                            <?php 
                            if(!is_null($vrac->valide->statut) && $vrac->valide->statut != VracClient::STATUS_CONTRAT_ANNULE):
                            ?>
                                <div style="text-align: left;">
                                <a class="btn_majeur btn_orange" href="<?php echo url_for('vrac_soussigne', $vrac); ?>"> Editer le contrat</a>
                                </div>   
                            <?php 
                            endif;
                            ?>
                            <div style="text-align: right;">
                            <a class="btn_majeur btn_gris" href="#"> Voir le contrat</a>
                            </div>   

                            <div style="text-align: right;">
                                <button class="btn_majeur btn_rouge" type="submit">Annuler le contrat</button>
                            </div>       
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