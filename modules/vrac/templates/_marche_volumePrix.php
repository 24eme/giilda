<?php
/* Fichier : _marche_volumePrix.php
 * Description : Fichier php correspondant à la vue partielle de vrac/XXXXXXXXXXX/marche
 * Partie du formulaire permettant le choix des volumes et des prix, affichage du total
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
 ?>

<script>
var value2bouteilleContenance = <?php echo json_encode(VracClient::$contenance); ?>;
function getBouteilleContenance($value) {
    return value2bouteilleContenance[$value];
}
</script>

<div id="vrac_marche_volumePropose" class="section_label_maj">
    <label>Volumes proposés</label>
    <!--  Affichage des contenances disponibles (seulement s'il s'agit de vins en bouteilles)  -->
    <div class="bloc_form" >
        <div id="contenance" class="bouteilles_contenance_libelle ligne_form">
            <span><?php echo $form['bouteilles_contenance_libelle']->renderLabel() ?> </span>
            <?php echo $form['bouteilles_contenance_libelle']->render() ?>
            <?php echo $form['bouteilles_contenance_libelle']->renderError() ?>
        </div>

        <!--  Affichage des volumes disponibles variables selon le type de transaction choisi  -->
        <div id="volume" class="ligne_form">
            <div class="bouteilles_quantite ">
                <?php echo $form['bouteilles_quantite']->renderError() ?>
                <span><?php echo $form['bouteilles_quantite']->renderLabel() ?></span>
                <?php echo $form['bouteilles_quantite']->render() ?>
                <span id="volume_unite_total" class="unite"></span>
            </div>
            <div class="jus_quantite ">
                <?php echo $form['jus_quantite']->renderError() ?>
                <span>  <?php echo $form['jus_quantite']->renderLabel() ?></span>
                <?php echo $form['jus_quantite']->render() ?>
                <span id="volume_unite_total" class="unite"></span>
            </div>
            <div class="raisin_quantite ">
                <?php echo $form['raisin_quantite']->renderError() ?>
                <span><?php echo $form['raisin_quantite']->renderLabel() ?></span>
                <?php echo $form['raisin_quantite']->render() ?>
                <span id="volume_unite_total" class="unite"></span>
            </div>
            <div>
                <input type="hidden" id="volume_total"/>
            </div>
        </div>
    </div>
</div>
<!--  Affichage du prix unitaire variables selon le type de transaction choisi -->
<div id="prixUnitaire" class="section_label_maj">
    <?php echo $form['prix_unitaire']->renderError(); ?>
    <?php echo $form['prix_unitaire']->renderLabel() ?>
    <?php echo $form['prix_unitaire']->render() ?>       
    <span id="prix_unitaire_unite" class="unite"></span>
    <span id="prix_unitaire_hl" class="small"></span>
</div>
                

<!--  Affichage du prix total (quantité x nbproduit)  -->
<div class="bloc_form" >
    <div id="prixTotal" class="ligne_form">
        <span><label>Prix total</label>
        <span id="vrac_prix_total" class="unite"></span>
        <span id="prix_unite" class="small">€</span>  
        </span>
    </div>
</div>
                