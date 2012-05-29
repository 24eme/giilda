<?php
/* Fichier : _marcheRecapitulatif.php
 * Description : Fichier php correspondant à la vue partielle de /vrac/XXXXXXXXXXX/recapitulatif
 * Affichage du recapitulatif de la partie marche du contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
?>
<section id="marche_recapitulatif_produit">
        <span>Produit :</span>
        <span><?php echo implode(' ', $form->getProduitObject()->getLibelles()->getRawValue()); ?></span>
</section>
<section id="marche_recapitulatif_volume">
        <span>
            Volumes : 
        </span>
        <span>
   <?php echo $form['raisin_quantite']; ?>&nbsp;hl
        </span>
</section>
<section id="marche_recapitulatif_prixTotal">
        <span>
            Prix : 
        </span>
        <span><?php echo $form['prix_total'];?> &nbsp;€</span>
</section>
        