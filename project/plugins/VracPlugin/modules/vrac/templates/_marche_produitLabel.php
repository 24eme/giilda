<?php
/* Fichier : _marche_produitLabel.php
 * Description : Fichier php correspondant à la vue partielle de vrac/XXXXXXXXXXX/marche
 * Partie du formulaire permettant le choix du produit et du label
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */


 $has_domaine = ! is_null($form->getObject()->domaine);
 ?>

<br>
<!--  Affichage des produits disponibles (en fonction de la transaction choisie  -->
<section id="produit">
<?php echo $form['produit']->renderError(); ?>
    <strong>   <?php echo $form['produit']->renderLabel() ?> </strong>
        <?php echo $form['produit']->render() ?>
</section>

<!--  Affichage des millésimes  -->
<section id="millesime">
<?php echo $form['millesime']->renderError(); ?>
    <strong>   <?php echo $form['millesime']->renderLabel() ?> </strong>
        <?php echo $form['millesime']->render() ?>
</section>

<!--  Affichage du type  -->
<section id="type">
           <strong><label for="generique">Générique</label> </strong>  
            <input type="radio" value="generique" name="type_produit" <?php echo ($has_domaine)? '' : 'checked="checked"'; ?> />
            
           <strong><label for="domaine">Domaine</label></strong>
            <input type="radio" value="domaine" name="type_produit" <?php echo ($has_domaine)? 'checked="checked"' : ''; ?> />
</section>


<!--  Affichage du type  -->
<section id="domaine">
<?php echo $form['domaine']->renderError(); ?>
    <strong>   <?php echo $form['domaine']->renderLabel() ?> </strong>
        <?php echo $form['domaine']->render() ?>
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