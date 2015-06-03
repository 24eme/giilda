<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<!-- #principal -->
<section id="principal" class="drm">
    <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => true, 'etape_courante' => DRMClient::ETAPE_CRD)); ?>
    <div id="application_dr">
        <div id="contenu_onglet">    
             <?php include_partial('drm_crds/crdsLists', array(
                    'allCrdsByRegimeAndByGenre' => $drm->getAllCrdsByRegimeAndByGenre(),
                    'drm' => $drm,
                    'crdsForms' => $crdsForms)); ?>
            <?php //include_partial('ajout_crds_popup', array('drm' => $drm, 'form' => $addCrdForm)); ?>
        </div>

    </div>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode'  => true));
?>