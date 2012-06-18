<?php
/* Fichier : validationSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/validation
 * Formulaire d'enregistrement de la partie validation d'un contrat donnant le récapitulatif
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
?>
<div id="contenu">
    <div id="rub_contrats">
        <section id="principal">
        <?php include_partial('headerVrac', array('vrac' => $vrac,'actif' => 4)); ?>        
            <section id="contenu_etape"> 
                <form id="vrac_validation" method="post" action="<?php echo url_for('vrac_validation',$vrac) ?>">

                    <h2>Récapitulatif de la saisie</h2>

                    <?php include_partial('showContrat', array('vrac' => $vrac)); ?>
                    <div class="btnValidation">
                        <span>&nbsp;</span>
                        <button class="btn_majeur btn_gris" type="submit">Saisir un nouveau contrat</button>                            
                    </div>

                </form>
            </section>
        </section>
        <?php include_partial('colonne', array('vrac' => $vrac)); ?>
    </div>
</div>