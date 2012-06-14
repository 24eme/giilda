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
    <h2>
    <?php echo $form['produit']->renderLabel() ?>
    <?php echo $form['produit']->render() ?>
    </h2>
</section>

<!--  Affichage des millésimes  -->
<section id="millesime">
    <?php echo $form['millesime']->renderError(); ?>
    <h2> 
    <?php echo $form['millesime']->renderLabel() ?> 
    <?php echo $form['millesime']->render() ?>
    </h2>
</section>

<!--  Affichage du type  -->
<section id="type">
    <h2> Type </h2>
    
        <input type="radio" value="generique" name="type_produit" <?php echo ($has_domaine)? '' : 'checked="checked"'; ?> />
        <label for="generique">Générique</label>   

        <input type="radio" value="domaine" name="type_produit" <?php echo ($has_domaine)? 'checked="checked"' : ''; ?> />
        <label for="domaine">Domaine</label>
</section>


<!--  Affichage du type  -->
<section id="domaine">
<?php echo $form['domaine']->renderError(); ?>
    <h2>
        <?php echo $form['domaine']->renderLabel() ?> 
        <?php echo $form['domaine']->render() ?>
    </h2>
</section>


<br>
<!--  Affichage des label disponibles -->
<section id="label">

    <h2>
        <?php echo $form['label']->renderLabel() ?> 
    </h2>
        <?php echo $form['label']->renderError(); ?>
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