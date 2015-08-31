<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>

<!-- #principal -->
<section id="principal" class="drm">

    <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_ADMINISTRATION)); ?>
    <?php include_partial('drm/controlMessage'); ?>
    <div id="application_drm">
         <?php include_partial('drm_annexes/annexes', array('drm' => $drm, 'annexesForm' => $annexesForm)); ?>
    </div>
    
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));
include_partial('drm/deleteDrmPopup', array('drm' => $drm, 'deleteForm' => $deleteForm));
?>
