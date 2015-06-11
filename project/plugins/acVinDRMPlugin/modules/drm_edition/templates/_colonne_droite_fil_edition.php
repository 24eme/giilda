<?php
slot('colFilEdition');
?>
<div class="bloc_col" id="drm_fil_edition">
    <h2>Edition des Produits</h2>

        <?php include_component('drm_edition', 'produitForm', array('drm' => $drm, 'config' => $config)) ?>
    <div class="contenu">
        <div class="text-center" style="text-align: center;">
            <ul>
            <?php foreach ($produits as $produit) : ?>
                <li id="<?php echo $produit->getHash() ?>" <?php echo (!$produit->hasMovements())? 'style="display:none;"' : '' ?> class="drm_fil_edition_produit <?php echo ($produit->isEdited()) ? 'edited' : '' ?>">
                    <a href="#">
                        <?php echo $produit->getLibelle("%format_libelle%"); ?>
                    </a>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
        <div class="drm_legend">
            <ul>
                <li class="drm_fil_edition_produit edited">Complété</li>
                <li class="drm_fil_edition_produit current">A compléter</li>
            </ul>
        </div>
    </div>
</div>
<?php end_slot(); ?>
