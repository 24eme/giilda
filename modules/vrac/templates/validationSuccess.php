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
    <div id="rub_contrats" >
        <section id="principal">
        <?php include_partial('headerVrac', array('vrac' => $vrac,'actif' => 4)); ?>        
            <div id="contenu_etape"> 
                <form id="vrac_validation" method="post" action="<?php echo url_for('vrac_validation',$vrac) ?>">

                    <div id="titre"><span class="style_label">Récapitulatif de la saisie</span></div>

                    <?php include_partial('showContrat', array('vrac' => $vrac)); ?>
                    <div id="ligne_btn">
                        <div class="btnAnnulation">
                             <a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn_majeur btn_noir"><span>Précédent</span></a>
                        </div>
                        <div class="btnValidation">
                                <span>&nbsp;</span>
                                <button class="btn_majeur btn_etape_suiv" type="submit">Valider</button>
                        </div>      
                    </div>   
                </form>
            </div>
        </section>
        <aside id="colonne">
        <?php include_partial('colonne', array('vrac' => $vrac)); ?>
        </aside>
    </div>
</div>