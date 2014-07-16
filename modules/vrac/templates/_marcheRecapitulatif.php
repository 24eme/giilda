<?php
/* Fichier : _marcheRecapitulatif.php
 * Description : Fichier php correspondant à la vue partielle de /vrac/XXXXXXXXXXX/recapitulatif
 * Affichage du recapitulatif de la partie marche du contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
use_helper('Vrac');
use_helper('Float');
?>
<div class="bloc_form bloc_form_condensed">
    <div id="marche_recapitulatif_original" class="ligne_form">
            <label>En attente de l'original :</label>
            <span><?php echo ($vrac->attente_original)? 'Oui' : 'Non'; ?></span>
    </div>
    <div id="marche_recapitulatif_typeTransaction" class="ligne_form ligne_form_alt">
            <label>Type de transaction :</label>
            <span><?php echo showType($vrac); ?></span>
    </div>
    <div id="marche_recapitulatif_produit" class="ligne_form ">
            <label>Produit :</label>
            <span><?php echo $vrac->produit_libelle; ?></span>
    </div>

    <div id="marche_recapitulatif_millesime" class="ligne_form ligne_form_alt">
            <label>
                <?php echo $vrac->getMillesimeLabel().' :'; ?>
            </label>
            <span>
            <?php echo $vrac->millesime; ?>
            </span>
    </div>

    <div id="marche_recapitulatif_type" class="ligne_form ">
            <label>
                Type : 
            </label>
            <span>
            <?php echo $vrac->categorie_vin; ?>
            </span>
    </div>

    <?php
    if($vrac->categorie_vin == VracClient::CATEGORIE_VIN_DOMAINE)
    {
    ?>
    <div id="marche_recapitulatif_domaine" class="ligne_form ligne_form_alt">
            <label>
                Domaine : 
            </label>
            <span>
            <?php echo $vrac->domaine; ?>
            </span>
    </div>
    <?php
        $alt= "";
    }else
        $alt= "ligne_form_alt";
    ?>


    <div id="marche_recapitulatif_volumePropose" class="ligne_form <?php echo $alt; ?>">
            <label>
                Volumes proposés: 
            </label>
            <span>
            <?php
            echo showRecapVolumePropose($vrac); 
            ?>
            <?php if(!$vrac->isValidee() && $vrac->isVin()): ?>
            (stock commercialisable <?php echoFloat($vrac->getStockCommercialisable()) ?> hl)
            <?php endif; ?>
            </span>
    </div>
    <?php 
        if($alt == "ligne_form_alt") $alt= "ligne_form";
        else $alt= "ligne_form_alt";
    ?>    
    <div id="marche_recapitulatif_volumeEnleve" class="ligne_form <?php echo $alt; ?>">
            <label>
                Volumes enlevé: 
            </label>
            <span>
            <?php            
            echo (is_null($vrac->volume_enleve))? '0 hl' : ($vrac->volume_enleve.' hl');
            ?>
            </span>
    </div>
    
    <?php 
        if($alt == "ligne_form_alt") $alt= "ligne_form";
        else $alt= "ligne_form_alt";
    ?>
    <div id="marche_recapitulatif_prixUnitaire" class="ligne_form <?php echo $alt; ?>">
            <label>
                Prix unitaire: 
            </label>
            <span>
            <?php echo showRecapPrixUnitaire($vrac); ?>
            </span>
    </div>
    <?php 
        if($alt == "ligne_form_alt") $alt= "ligne_form";
        else $alt= "ligne_form_alt";
    ?>
    <div id="marche_recapitulatif_prixTotal" class="ligne_form <?php echo $alt; ?>">
            <label>
                Prix : 
            </label>
        <span><?php echo showRecapPrixTotal($vrac); ?></span>
    </div>
        
</div>