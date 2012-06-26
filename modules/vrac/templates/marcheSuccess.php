<?php
/* Fichier : marcheSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/marche
 * Formulaire d'enregistrement de la partie marche d'un contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : ${date}
 */
?>

<div id="contenu">
    <div id="rub_contrats">
        <section id="principal">
        <?php include_partial('headerVrac', array('vrac' => $form->getObject(),'actif' => 2)); ?>
            <div id="contenu_etape">  
                <form id="vrac_marche" method="post" action="<?php echo url_for('vrac_marche',$vrac) ?>">    
                    <?php echo $form->renderHiddenFields() ?>
                    <?php echo $form->renderGlobalErrors() ?>
                <div id="marche">

                <!--  Affichage des l'option original  -->
                    <div id="original" class="original section_label_strong">
                        <?php echo $form['original']->renderLabel() ?>
                        <?php echo $form['original']->render() ?>        
                        <?php echo $form['original']->renderError(); ?>
                    </div>

                    <!--  Affichage des transactions disponibles  -->
                    <div id="type_transaction" class="type_transaction section_label_maj">
                        <?php echo $form['type_transaction']->renderLabel() ?>
                        <?php echo $form['type_transaction']->renderError(); ?>                    
                        <?php echo $form['type_transaction']->render() ?>        
                    </div>

                <!--  Affichage des produits, des labels et du stock disponible  -->
                    <div id="vrac_marche_produitLabel" class="section_label_maj">
                        <?php include_partial('marche_produitLabel', array('form' => $form)); ?>
                    </div>

                <!--  Affichage des volumes et des prix correspondant  -->
                    <div id="vrac_marche_volumePrix" class="section_label_maj">
                        <?php include_partial('marche_volumePrix', array('form' => $form)); ?>
                    </div>

                </div>
                <div id="ligne_btn">
                    
                    <div class="btnAnnulation">
                          <a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn_majeur btn_noir"><span>Précédent</span></a>
                    </div>
                    <div class="btnValidation">
                        <span>&nbsp;</span>
                        <button class="btn_majeur btn_etape_suiv" type="submit">Etape Suivante</button>
                    </div>       
                </div>
            </form>
            </div>      
        </div>
        <aside id="colonne">
        <?php include_partial('colonne', array('vrac' => $form->getObject())); ?>
        </aside>
    </div>
</div>
    
