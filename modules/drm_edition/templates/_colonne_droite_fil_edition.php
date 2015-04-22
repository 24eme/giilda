<?php
slot('colFilEdition');
?>
<div class="bloc_col" id="drm_fil_edition">
    <h2>Edition des Produits</h2>

        <?php include_component('drm_edition', 'produitForm', array('drm' => $drm, 'config' => $config)) ?>
    <div class="contenu">
        <div class="text-center" style="text-align: center;">
            <?php foreach ($produits as $produit) : ?>
            <a href="#" class="drm_fil_edition_produit" id="<?php echo $produit->getHash() ?>" <?php echo (!$produit->hasMovements())? 'style="display:none;"' : '' ?> >
                <p class="<?php echo ($produit->isEdited()) ? 'edited' : '' ?>"><?php echo $produit->getLibelle("%format_libelle%"); ?></p>
            </a>               
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php end_slot(); ?>
