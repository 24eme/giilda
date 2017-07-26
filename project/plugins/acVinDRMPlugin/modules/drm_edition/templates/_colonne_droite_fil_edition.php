<?php
slot('colFilEdition');
?>
<div class="bloc_col" id="drm_fil_edition">
    <h2>Edition des Produits</h2>

        <?php include_component('drm_edition', 'produitForm', array('drm' => $drm, 'config' => $config, 'detailsKey' => $detailsKey)) ?>
    <div class="contenu">
        <div class="text-center" style="text-align: center;">
            <ul class="drm_fil_edition_produit">
            <?php foreach ($produits as $produit) : ?>
                <?php if(!$produit->hasMovements()): continue; endif; ?>
                <li id="<?php echo $produit->getHash() ?>" <?php echo (!$produit->hasMovements())? 'style="display:none;"' : '' ?> class="<?php echo ($produit->isEdited()) ? 'edited' : '' ?>">
                    <a href="#">
                        <?php echo $produit->getLibelle("%format_libelle%"); ?>
                    </a>
                </li>
            <?php endforeach; ?>
            </ul>
<!--            <ul class="drm_legend_produit">
                <li class="edited">Complété</li>
                <li class="current">A compléter</li>
            </ul>-->
        </div>
    </div>
</div>
<?php end_slot(); ?>
