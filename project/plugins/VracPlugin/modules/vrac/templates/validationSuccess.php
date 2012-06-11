<?php
/* Fichier : validationSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/validation
 * Formulaire d'enregistrement de la partie validation d'un contrat donnant le récapitulatif
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
?>
<section id="contenu">
    <?php include_partial('etapes', array('vrac' => $vrac, 'actif' => 4)); ?>
<form id="vrac_validation" method="post" action="<?php echo url_for('vrac_validation',$vrac) ?>">
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
            include_partial('conditionsRecapitulatif', array('vrac' => $vrac));
            ?>
            </section> 
        </li>
    </ul>
    <div class="btnValidation">
    	<span>&nbsp;</span>
        <input class="btn_valider" type="submit" value="Valider la saisie" />
    </div>

</form>
</section>