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
                <div id="vrac_recapitulatif"> 
                    <?php
                    $titre = '<div id="titre">
                                <span class="style_label">La saisie est terminée !</span>
                              </div>';
                  if($sf_user->hasFlash('postValidation')) echo $titre;
                  ?>  
                    
                    <div id="ss_titre">
                        <label>N° d'enregistrement du contrat : </label><span><?php echo $vrac['numero_contrat']; ?></span>
                    </div>
                    
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
        <?php include_partial('colonne', array('vrac' => $vrac)); ?>
        </aside>
    </div>
</div>