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
    
<form id="vrac_recapitulatif" method="get" action="<?php echo url_for('vrac_nouveau') ?>">
<h1>La saisie est terminée !</h1>
<h2>N° d'enregistrement deu contrat   <span><?php echo $vrac['numero_contrat']; ?></span></h2>

<section id="contenu">
    <h1>Récapitulatif de la saisie</h1>
    <ul>
        <li>
            <h2>
            Les soussignés
            </h2>
            <div class="btnModification">
                <a href="<?php echo url_for('vrac_soussigne',$vrac); ?>">Modifier</a>
            </div>
            <section id="soussigne_recapitulatif">
            <?php
            include_partial('soussigneRecapitulatif', array('vrac' => $vrac));
            ?>
            </section>            
        </li>
        <li>
            <h2>
            Le marché
            </h2>
            <div class="btnModification">
                <a href="<?php echo url_for('vrac_marche',$vrac); ?>">Modifier</a>
            </div>
            <section id="marche_recapitulatif">
            <?php
            include_partial('marcheRecapitulatif', array('vrac' => $vrac));
            ?>
            </section> 
        </li>
        <li>
            <h2>
            Les conditions
            </h2>
            <div class="btnModification">
                <a href="<?php echo url_for('vrac_condition',$vrac); ?>">Modifier</a>
            </div>
            <section id="conditions_recapitulatif">
            <?php
            include_partial('conditionsRecapitulatif', array('form' => $vrac));
            ?>
            </section> 
        </li>
    </ul>
</section>


<div id="btn_etape_dr">
        <a href="<?php echo url_for('vrac_validation', $vrac) ?>" class="btn_prec">
            <span>Précédent</span>
        </a> 
        <div class="btnValidation">
            <span>&nbsp;</span>
            <input class="btn_valider" type="submit" value="Saisir un nouveau contrat" />
        </div>
 </div>
</form>
</section>