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
            <section id="contenu_etape"> 
                <form id="vrac_recapitulatif" method="get" action="<?php echo url_for('vrac_nouveau') ?>">
                <h2>La saisie est terminée !</h2>
                <h2>N° d'enregistrement du contrat : <span><?php echo $vrac['numero_contrat']; ?></span></h2>
                <?php include_partial('showContrat', array('vrac' => $vrac)); ?>

                        <div id="btn_etape_dr">
                            <div class="btnValidation">
                            <span>&nbsp;</span>
                            <button class="btn_majeur btn_gris" type="submit"> Saisir un nouveau contrat</button>
                            </div>       
                        </div>
                </form>
            </section>
        </section>
    </div>
</div>