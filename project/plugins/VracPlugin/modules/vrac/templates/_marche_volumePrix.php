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
<h2>Volumes</h2>
<br>
<!--  Affichage des volumes disponibles variables selon le type de transaction choisi  -->
<section id="volume" >
        <div class="bouteilles_quantite">
        <?php echo $form['bouteilles_quantite']->renderLabel() ?>
        <?php echo $form['bouteilles_quantite']->render() ?>
            <span id="volume_unite_total" class="unite"></span>
        </div>
        <div class="jus_quantite">
        <?php echo $form['jus_quantite']->renderLabel() ?>
        <?php echo $form['jus_quantite']->render() ?>
            <span id="volume_unite_total" class="unite"></span>
        </div>
        <div class="raisin_quantite">
        <?php echo $form['raisin_quantite']->renderLabel() ?>
        <?php echo $form['raisin_quantite']->render() ?>
            <span id="volume_unite_total" class="unite"></span>
        </div>    
</section>

<br>
<!--  Affichage des contenances disponibles (seulement s'il s'agit de vins en bouteilles)  -->
<section id="contenance" class="bouteilles_contenance">
       <?php echo $form['bouteilles_contenance']->renderLabel() ?>
        <?php echo $form['bouteilles_contenance']->render() ?>
</section>

<br>
<!--  Affichage du prix unitaire variables selon le type de transaction choisi -->
<section id="prixUnitaire">
       <strong> <?php echo $form['prix_unitaire']->renderLabel() ?></strong>
        <?php echo $form['prix_unitaire']->render() ?>
       <span id="prix_unitaire_unite" class="unite"></span>
       <span id="prix_unitaire_hl" class="small"></span>
</section>
                
<br>
<!--  Affichage du prix total (quantité x nbproduit)  -->
<section id="prixTotal">
    <span id="vrac_prix_total_label"><strong>Prix total</strong></span>
    <span id="vrac_prix_total" class="unite"></span>
    <span id="prix_unite" class="small">€</span>
       
</section>
                