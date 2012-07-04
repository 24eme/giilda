<?php
/* Fichier : _marcheRecapitulatif.php
 * Description : Fichier php correspondant à la vue partielle de /vrac/XXXXXXXXXXX/recapitulatif
 * Affichage du recapitulatif de la partie marche du contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
use_helper('Vrac');
$hasDomaine = is_null($vrac->domaine);
?>
<div class="bloc_form">
    <div id="marche_recapitulatif_original" class="ligne_form">
            <label>Original fourni :</label>
            <span><?php echo ($vrac->original)? 'Oui' : 'Non'; ?></span>
    </div>
    <div id="marche_recapitulatif_typeContrat" class="ligne_form ligne_form_alt">
            <label>Type de contrat :</label>
            <span><?php echo showType($vrac); ?></span>
    </div>
    <div id="marche_recapitulatif_produit" class="ligne_form ">
            <label>Produit :</label>
            <span><?php echo implode(' ', $vrac->getProduitObject()->getLibelles()->getRawValue()); ?></span>
    </div>

    <div id="marche_recapitulatif_millesime" class="ligne_form ligne_form_alt">
            <label>
                Millésime : 
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
            <?php echo ($hasDomaine)? 'Générique' : 'Domaine'; ?>
            </span>
    </div>

    <?php
    if($hasDomaine && $vrac->domaine=="domaine")
    {
    ?>
    <div id="marche_recapitulatif_domaine" class="ligne_form ligne_form_alt">
            <label>
                Type : 
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
            echo showRecapVolume($vrac); 
            ?>
            </span>
        <?php
        if(!is_null($vrac->valide->statut))
        {
        ?>
        <a class="btn_majeur btn_gris" href="<?php echo url_for('vrac_volumeEnleve',$vrac); ?>">Enlever du volume</a>
        <?php 

        }
        ?>
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
            <?php
            echo showRecapPrixUnitaire($vrac);
            ?>
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
            <span><?php echo $vrac->prix_total;?>&nbsp;€</span>
    </div>
        
</div>