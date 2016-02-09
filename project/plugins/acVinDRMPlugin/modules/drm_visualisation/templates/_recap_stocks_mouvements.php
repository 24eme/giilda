<h3>Stocks et Mouvements</h3>
<ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#stocks" aria-controls="stocks" role="tab" data-toggle="tab">Résumé des Stocks</a></li>
    <li><a href="#mouvements" aria-controls="mouvements" role="tab" data-toggle="tab">Détails des Mouvements</a></li>
</ul>
<div class="tab-content">
    <div id="stocks" role="tabpanel" class="tab-pane active">
        <?php include_partial('drm_visualisation/stock', array('drm' => $drm, 'no_link' => $no_link, 'isTeledeclarationMode' => $isTeledeclarationMode)) ?>
    </div>
    <div id="mouvements" role="tabpanel" class="tab-pane">
        <?php include_partial('drm_visualisation/mouvements', array('drm' => $drm,'mouvementsByProduit' => $mouvementsByProduit, 'no_link' => $no_link, 'isTeledeclarationMode' => $isTeledeclarationMode, 'visualisation' => $visualisation, 'hamza_style' => true)) ?>
    </div>
</div>
