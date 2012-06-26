<?php
/* Fichier : _condition_prixvariable.php
 * Description : Fichier php correspondant à une vue partielle de vrac/XXXXXXXXXXX/condition
 * Formulaire concernant la parti prix variable pour les conditions du contrat
 * Affiché si et seulement si type de contrat = 'pluriannuel' et partie de prix variable = 'Oui'
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */

use_helper('Vrac');
 ?>
<div id="prix_variable" style="display: none;" class="section_label_maj">
    <label>Prix variable</label>
    <div class="bloc_form">
        <!--  Affichage des la part variable sur la quantité du contrat  -->
        <div id="part_variable" class="ligne_form">
                <span>
                    <?php echo $form['part_variable']->renderError() ?>
                    <?php echo $form['part_variable']->renderLabel() ?>
                    <?php echo $form['part_variable']->render() ?> <span>% (50% max)</span>
                </span>
        </div>
        <!--  Affichage du taux de variation des produits du contrat  -->
        <div id="prixTotal_rappel" class="ligne_form ligne_form_alt">
            <span>
                <label>Prix total</label>
                <?php echo $form->getObject()->prix_unitaire ?> €/<?php echo showUnite($form->getObject()); ?>
                <?php if( $form->getObject()->type_transaction == "vin_bouteille"){ echo "(soit ".$form->getObject()->bouteilles_quantite * (($form->getObject()->bouteilles_contenance_volume))." €/hl)"; } ?>      
            </span>
        </div>
        <!--  Affichage du taux de variation des produits du contrat  -->
        <div id="taux_variation" class="ligne_form">
            <span><?php echo $form['taux_variation']->renderError() ?>
            <?php echo $form['taux_variation']->renderLabel() ?>
            <?php echo $form['taux_variation']->render() ?><span>%</span>
            </span>
        </div>
    </div>
</div>
<div class="section_label_maj">
    <label>CVO appliquée</label>
    <div class="bloc_form">
        <!--  Affichage de la nature du contrat  -->
        <div id="cvo_nature" class="ligne_form" >
            <span>
                <?php echo $form['cvo_nature']->renderError() ?> 
                <?php echo $form['cvo_nature']->renderLabel() ?>
                <?php echo $form['cvo_nature']->render() ?>
            </span>   
        </div>

        <!--  Affichage de la repartition (vendeur/acheteur) pour le paiement de la CVO  -->
        <div id="taux_variation" class="ligne_form ligne_form_alt" >
            <span>
                <?php echo $form['cvo_repartition']->renderError() ?>
                <?php echo $form['cvo_repartition']->renderLabel() ?>
                <?php echo $form['cvo_repartition']->render() ?>
            </span>
        </div>

        <!-- CVO facturée vendeur  -->
        <div id="cvo_facturee_vendeur" class="ligne_form" >
            <span>
                <label>CVO facturée (vendeur)</label>
                <span id="prix_facturee_vendeur">XX</span>
                €/<?php echo showUnite($form->getObject()); ?>    
                (soit <span  id="cvo_totale_vendeur"> xxx.xx €</span>)
            </span>
        </div>


        <!-- CVO facturée acheteur -->
        <div id="cvo_facturee_acheteur" class="ligne_form ligne_form_alt" >
            <span>
                <label>CVO facturée (acheteur)</label>
                <span  id="prix_facturee_acheteur">
                    XX
                </span>
                €/<?php echo showUnite($form->getObject()); ?>
                (soit <span  id="cvo_totale_acheteur"> xxx.xx €</span>)
            </span>
        </div>
    </div>
</div>