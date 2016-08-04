<?php if($drm->isDouaneType($typeKey)): ?>
<h3 style="margin-top: 0;">Mouvements <?php echo DRMClient::$types_libelles[$typeDetailKey] ?>s</h3>

<ul class="nav nav-tabs" role="tablist">
    <li class="active"  tabindex="10"><a data-target="#stocks_<?php echo $typeKey ?>" href="#tab=stocks_<?php echo $typeKey ?>" aria-controls="stocks_<?php echo $typeKey ?>" role="tab">Résumé des Stocks</a></li>
    <li  tabindex="20" ><a data-target="#mouvements_<?php echo $typeKey ?>" href="#tab=mouvements_<?php echo $typeKey ?>" aria-controls="mouvements_<?php echo $typeKey ?>" role="tab">Détails des Mouvements</a></li>
</ul>
<div class="tab-content">
    <div id="stocks_<?php echo $typeKey ?>" role="tabpanel" class="tab-pane active">
        <?php include_partial('drm_visualisation/stock', array('drm' => $drm, 'no_link' => $no_link, 'isTeledeclarationMode' => $isTeledeclarationMode, 'typeKey' => $typeKey, 'typeDetailKey' => $typeDetailKey)) ?>
    </div>
    <div id="mouvements_<?php echo $typeKey ?>" role="tabpanel" class="tab-pane">
        <?php include_partial('drm_visualisation/mouvements', array('drm' => $drm,'mouvementsByProduit' => $mouvementsByProduit, 'no_link' => $no_link, 'isTeledeclarationMode' => $isTeledeclarationMode, 'visualisation' => $visualisation, 'hamza_style' => true, 'typeKey' => $typeKey, 'typeDetailKey' => $typeDetailKey)) ?>
    </div>
</div>
<?php endif; ?>
