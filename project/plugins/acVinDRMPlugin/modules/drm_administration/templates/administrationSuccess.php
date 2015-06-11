<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>

<!-- #principal -->
<section id="principal" class="drm">

    <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_ADMINISTRATION)); ?>
    <?php include_partial('drm/controlMessage'); ?>
    <div id="application_drm">
         <?php include_component('drm_administration','administration', array('drm' => $drm)); ?>
    </div>
    
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));
?>
