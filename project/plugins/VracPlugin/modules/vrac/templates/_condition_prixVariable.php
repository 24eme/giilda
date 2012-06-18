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
<section id="prix_variable" style="display: none;">
    <br>
    <h2>Prix variable</h2>
    <!--  Affichage des la part variable sur la quantité du contrat  -->
    <section id="part_variable">
            <?php echo $form['part_variable']->renderError() ?>
        <strong> <?php echo $form['part_variable']->renderLabel() ?> </strong>
            <?php echo $form['part_variable']->render() ?> <span>% (50% max)</span>
    </section>
    <!--  Affichage du taux de variation des produits du contrat  -->
    <section id="prixTotal_rappel">
        Prix total 
        <strong>
        <?php echo $form->getObject()->prix_unitaire ?> €/<?php echo showUnite($form->getObject()); ?>
        </strong>
        <?php if( $form->getObject()->type_transaction == "vin_bouteille")
         {
           echo "(soit ".$form->getObject()->bouteilles_quantite * (($form->getObject()->bouteilles_contenance)/10000)." €/hl)";
         }
        ?>      
    </section>
    <!--  Affichage du taux de variation des produits du contrat  -->
    <section id="taux_variation">
            <?php echo $form['taux_variation']->renderError() ?>
        <strong> <?php echo $form['taux_variation']->renderLabel() ?> </strong>
            <?php echo $form['taux_variation']->render() ?><span>%</span>
    </section>
</section>

<h2>CVO appliquée</h2>

<!--  Affichage de la nature du contrat  -->
<section id="cvo_nature">
        <?php echo $form['cvo_nature']->renderError() ?> 
    <strong>  <?php echo $form['cvo_nature']->renderLabel() ?> </strong>
        <?php echo $form['cvo_nature']->render() ?>
</section>

<!--  Affichage de la repartition (vendeur/acheteur) pour le paiement de la CVO  -->
<section id="taux_variation">
        <?php echo $form['cvo_repartition']->renderError() ?>
    <strong>  <?php echo $form['cvo_repartition']->renderLabel() ?> </strong>
        <?php echo $form['cvo_repartition']->render() ?>
</section>

<!-- CVO facturée vendeur  -->
<section id="cvo_facturee_vendeur">
    CVO facturée (vendeur)
    <span id="prix_facturee_vendeur">
        XX
    </span>
    €/<?php echo showUnite($form->getObject()); ?>    
    (soit <span  id="cvo_totale_vendeur"> xxx.xx €</span>)
</section>


<!-- CVO facturée acheteur -->
<section id="cvo_facturee_acheteur">
       CVO facturée (acheteur)
       <span  id="prix_facturee_acheteur">
         XX
       </span>
       €/<?php echo showUnite($form->getObject()); ?>
       (soit <span  id="cvo_totale_acheteur"> xxx.xx €</span>)
</section>