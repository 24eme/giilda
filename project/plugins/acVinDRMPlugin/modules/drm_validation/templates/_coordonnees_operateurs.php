<div id="drm_validation_coordonnees">
    <div class="drm_validation_societe">    
        <?php include_partial('drm_visualisation/societe_infos', array('drm' => $drm, 'isModifiable' => true)); ?>
    </div>
    <div class="drm_validation_etablissement">
        <?php include_partial('drm_visualisation/etablissement_infos', array('drm' => $drm, 'isModifiable' => true)); ?>
    </div>
</div>

