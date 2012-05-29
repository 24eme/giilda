<?php
/* Fichier : _condition_prixvariable.php
 * Description : Fichier php correspondant à une vue partielle de vrac/XXXXXXXXXXX/condition
 * Formulaire concernant la parti prix variable pour les conditions du contrat
 * Affiché si et seulement si type de contrat = 'pluriannuel' et partie de prix variable = 'Oui'
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
 ?>
<br>
<h2>Prix variable</h2>
<br>
<!--  Affichage des la part variable sur la quantité du contrat  -->
<section id="part_variable">
        <?php echo $form['part_variable']->renderLabel() ?>
        <?php echo $form['part_variable']->render() ?>
</section>
<br>
<!--  Affichage du taux de variation des produits du contrat  -->
<section id="taux_variation">
        <?php echo $form['taux_variation']->renderLabel() ?>
        <?php echo $form['taux_variation']->render() ?>
</section>
<br>
<h2>CVO appliquée</h2>
<br>
<!--  Affichage de la nature du contrat  -->
<section id="cvo_nature">
        <?php echo $form['cvo_nature']->renderLabel() ?> 
        <?php echo $form['cvo_nature']->render() ?>
</section>
<br>
<!--  Affichage de la repartition (vendeur/acheteur) pour le paiement de la CVO  -->
<section id="taux_variation">
        <?php echo $form['cvo_repartition']->renderLabel() ?>
        <?php echo $form['cvo_repartition']->render() ?>
</section>