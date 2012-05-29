<?php
/* Fichier : _marche_produitLabel.php
 * Description : Fichier php correspondant à la vue partielle de vrac/XXXXXXXXXXX/marche
 * Partie du formulaire permettant le choix du produit et du label
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
 ?>

<br>
<!--  Affichage des produits disponibles (en fonction de la transaction choisie  -->
<section id="produit">
<?php echo $form['produit']->renderError(); ?>
    <strong>   <?php echo $form['produit']->renderLabel() ?> </strong>
        <?php echo $form['produit']->render() ?>
</section>

<br>
<!--  Affichage des label disponibles -->
<section id="label">
<?php echo $form['label']->renderError(); ?>
    <strong> <?php echo $form['label']->renderLabel() ?> </strong>
        <?php echo $form['label']->render() ?>
</section>
<!--  
<br>

<section id="stock">
    <strong>Stocks disponibles</strong> 
        
        <?php 
       // echo "500 hl";
        ?>
</section>
        
Affichage du stock disponible pour ce produit WARNING TO AJAXIFY -->