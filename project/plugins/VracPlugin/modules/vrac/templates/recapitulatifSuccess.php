<?php
/* Fichier : recapitulatifSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/recapitulatif
 * Affichage des dernières information de la saisie : numero de contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 29-05-12
 */
?>
<section id="contenu">
    
<form id="vrac_recapitulatif" method="post" action="<?php echo url_for('vrac_nouveau') ?>">
        
<h1>La saisie est terminée !</h1>
<h2>N° d'enregistrement deu contrat   <span><?php echo $vrac['numero_contrat']; ?></span></h2>

<div id="btn_etape_dr">
        <a href="<?php echo url_for('vrac_validation', $vrac) ?>" class="btn_prec">
            <span>Précédent</span>
        </a> 
        <div class="btnValidation">
            <span>&nbsp;</span>
            <input class="btn_valider" type="submit" value="Terminer la saisie" />
        </div>
 </div>
</form>
</section>