<?php
slot('colFilEdition');
?>
<div class="bloc_col" id="drm_fil_edition">
    <h2>Edition des Produits</h2>

    <div class="contenu">
        <div class="text-center" style="text-align: center;">
            <?php foreach ($produits as $produit) : ?>
                <?php if ($produit->hasMovements()) : ?>
            <a href="#" class="drm_fil_edition_produit" id="<?php echo $produit->getHash() ?>">
                <p style="<?php echo ($produit->isEdited()) ? 'color:green;' : '' ?>"><?php echo $produit->getLibelle("%format_libelle%"); ?></p>
            </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php end_slot(); ?>
