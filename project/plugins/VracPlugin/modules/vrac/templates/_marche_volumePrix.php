<?php
/* Fichier : _marche_volumePrix.php
 * Description : Fichier php correspondant à la vue partielle de vrac/XXXXXXXXXXX/marche
 * Partie du formulaire permettant le choix des volumes et des prix, affichage du total
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
 ?>

<br>
<!--  Affichage des volumes disponibles variables selon le type de transaction choisi  -->
<section id="volume">
        <strong><?php echo $form['bouteilles_quantite']->renderLabel() ?></strong>
        <?php echo $form['bouteilles_quantite']->render() ?>
</section>

<br>
<!--  Affichage des contenances disponibles (seulement s'il s'agit de vins en bouteilles)  -->
<section id="contenance">
       <strong> <?php echo $form['bouteilles_contenance']->renderLabel() ?></strong>
        <?php echo $form['bouteilles_contenance']->render() ?>
</section>

<br>
<!--  Affichage du prix unitaire variables selon le type de transaction choisi -->
<section id="prixUnitaire">
       <strong> <?php echo $form['prix_unitaire']->renderLabel() ?></strong>
        <?php echo $form['prix_unitaire']->render() ?>
</section>
                
<br>
<!--  Affichage du prix total (quantité x nbproduit)  -->
<section id="prixTotal">
    <strong> <?php echo $form['prix_total']->renderLabel() ?> </strong>
        <?php echo $form['prix_total']->render() ?>
</section>
                