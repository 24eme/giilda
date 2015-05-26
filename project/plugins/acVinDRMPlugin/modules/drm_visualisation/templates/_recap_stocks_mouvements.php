<h2>STOCKS/MOUVEMENTS</h2>
<fieldset id="validation_drm_mvts_stocks"> 
    <nav>
        <ul>
            <li class="actif onglet" id="drm_visualisation_stock_onglet"><span>Stock</span></li>
            <li class="onglet" id="drm_visualisation_mouvements_onglet"><a>Mouvements</a></li>
        </ul>
    </nav>
    <div id="drm_visualisation_stock" class="section_label_maj">
        <?php include_partial('drm_visualisation/stock', array('drm' => $drm, 'no_link' => $no_link, 'isTeledeclarationMode' => $isTeledeclarationMode)) ?>
    </div>
    <div id="drm_visualisation_mouvements" class="section_label_maj" style="display: none;">
        <?php include_partial('drm_visualisation/mouvements', array('mouvements' => $mouvements, 'no_link' => $no_link, 'isTeledeclarationMode' => $isTeledeclarationMode, 'visualisation' => $visualisation)) ?>
    </div>
</fieldset>