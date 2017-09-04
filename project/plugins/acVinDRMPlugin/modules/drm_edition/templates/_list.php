<?php use_helper('DRM'); ?>
<div id="forms_errors" style="color: red;">
    <?php include_partial('drm_edition/itemFormErrors', array('form' => $form)) ?>
</div>

<div id="colonnes_dr">
    <?php
    include_partial('drm_edition/itemHeader', array('config' => $config,
        'drm' => $drm,
        'favoris' => $favoris,
        'formFavoris' => $formFavoris,
        'isTeledeclarationMode' => $isTeledeclarationMode,
        'detailsNodes' => $detailsNodes,
        'detail' => $detail,
        'saisieSuspendu' => $saisieSuspendu));
    ?>
    <div id="col_saisies">
        <script type="text/javascript">
            /* Colonne avec le focus par défaut */
            var colFocusDefaut = <?php echo getNumberOfFirstProduitWithMovements($produits); ?>;

        </script>
        <div id="col_saisies_cont" class="section_label_maj">
            <?php $first = true; ?>
            <?php foreach ($produits as $hash => $produit): ?>
                <?php if(!$produit->hasMovements()): continue; endif; ?>
                <?php

                include_component('drm_edition', 'itemForm', array(
                    'config' => $config,
                    'drm' => $drm,
                    'detail' => $produit,
                    'detailsKey' => $detailsKey,
                    'active' => ($detail && $detail->getHash() == $produit->getHash()),
                    'form' => $form,
                    'favoris' => $favoris,
                    'isTeledeclarationMode' => $isTeledeclarationMode,
                    'saisieSuspendu' => $saisieSuspendu));
                ?>
                <?php $first = $first && !$produit->hasMovements(); ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
