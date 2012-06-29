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
                    
                    <h2>Etat du contrat<h2>
                           <?php echo $vrac->valide->statut; ?>
                    
                    <?php include_partial('showContrat', array('vrac' => $vrac)); ?>

                    <div id="ligne_btn">
                        <div style="text-align: right;">
                        <a class="btn_majeur btn_gris" href="<?php echo url_for('vrac_nouveau') ?>"> Saisir un nouveau contrat</a>
                        </div>       
                    </div>
                </div>
            </div>
        </section>
        <aside id="colonne">
            <?php include_partial('actions_visu', array('vrac' => $vrac)); ?>
            <?php include_partial('contrat_aide'); ?>
            <?php //include_partial('contrat_campagne'); ?>
            <?php //include_partial('contrat_campagne'); ?>
            <?php include_partial('contrat_infos_contact',array('vrac' => $vrac)); ?>
        </aside>
    </div>
</div>