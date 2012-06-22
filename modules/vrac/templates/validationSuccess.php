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
                    <div id="btn_etape_dr">
                        <div class="btnAnnulation">
                                    <a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn_majeur btn_noir"><span>Précédent</span></a>
                        </div>
                        <div class="btnValidation">
                                <span>&nbsp;</span>
                                <button class="btn_majeur btn_etape_suiv" type="submit">Valider</button>
                        </div>      
                    </div>   
                </form>
            </section>
        </section>
        <aside id="colonne">
        <?php include_partial('colonne', array('vrac' => $vrac)); ?>
        <?php include_partial('contratsSimilaires', array('vrac' => $vrac)); ?>
        </aside>
    </div>
</div>