<?php if($drm->isDouaneType($typeKey)): ?>
<br/>
<fieldset class="validation_drm_tables" id="fieldset_<?php echo $typeKey ?>">
<h2>Synthèse de la saisie des mouvements <?php echo strtolower(DRMClient::$types_libelles[$typeDetailKey]) ?>s</h2>
<nav>
        <ul>
            <li class="actif onglet" id="drm_visualisation_stock_<?php echo $typeKey ?>_onglet"><span >Résumé des Stocks&nbsp;<span  style="display: inline-block;" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_validation_aide3'); ?>"></span></span></li>
            <li class="onglet" id="drm_visualisation_mouvements_<?php echo $typeKey ?>_onglet"><a >Détails des Mouvements&nbsp;<span  style="display: inline-block;" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_validation_aide4'); ?>"></span></a></li>
        </ul>
    </nav>
    <div id="drm_visualisation_stock_<?php echo $typeKey ?>" class="section_label_maj">
        <?php include_partial('drm_visualisation/stock', array('drm' => $drm, 'no_link' => $no_link, 'isTeledeclarationMode' => $isTeledeclarationMode, 'typeKey' => $typeKey, 'typeDetailKey' => $typeDetailKey)) ?>
    </div>
    <div id="drm_visualisation_mouvements_<?php echo $typeKey ?>" class="section_label_maj" style="display: none;">
        <?php include_partial('drm_visualisation/mouvements', array('drm' => $drm,'mouvementsByProduit' => $mouvementsByProduit, 'no_link' => $no_link, 'isTeledeclarationMode' => $isTeledeclarationMode, 'visualisation' => $visualisation, 'hamza_style' => true, 'typeKey' => $typeKey, 'typeDetailKey' => $typeDetailKey)) ?>
    </div>
</fieldset>
<?php endif; ?>
