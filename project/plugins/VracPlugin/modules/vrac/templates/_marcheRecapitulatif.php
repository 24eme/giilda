<?php
/* Fichier : _marcheRecapitulatif.php
 * Description : Fichier php correspondant à la vue partielle de /vrac/XXXXXXXXXXX/recapitulatif
 * Affichage du recapitulatif de la partie marche du contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
$hasDomaine = is_null($vrac->domaine);
?>
<section id="marche_recapitulatif_original">
        <span>Original fourni :</span>
        <span><?php echo $vrac->original; ?></span>
</section>
<section id="marche_recapitulatif_produit">
        <span>Produit :</span>
        <span><?php echo implode(' ', $vrac->getProduitObject()->getLibelles()->getRawValue()); ?></span>
</section>

<section id="marche_recapitulatif_millesime">
        <span>
            Millésime : 
        </span>
        <span>
           <?php echo $vrac->millesime; ?>
        </span>
</section>

<section id="marche_recapitulatif_type">
        <span>
            Type : 
        </span>
        <span>
           <?php echo ($hasDomaine)? 'Générique' : 'Domaine'; ?>
        </span>
</section>

<?php
if($hasDomaine)
{
?>
<section id="marche_recapitulatif_domaine">
        <span>
            Type : 
        </span>
        <span>
           <?php echo $vrac->domaine; ?>
        </span>
</section>
<?php
}
?>


<section id="marche_recapitulatif_volumePropose">
        <span>
            Volumes proposés: 
        </span>
        <span>
           <?php
           echo $vrac->showRecapVolume(); 
           ?>
        </span>
</section>
<section id="marche_recapitulatif_prixTotal">
        <span>
            Prix : 
        </span>
        <span><?php echo $vrac->prix_total;?>&nbsp;€</span>
</section>
        