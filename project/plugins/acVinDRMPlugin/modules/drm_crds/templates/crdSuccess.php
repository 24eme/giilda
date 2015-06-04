<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<?php $allCrdsByRegimeAndByGenre = $drm->getAllCrdsByRegimeAndByGenre(); ?>
<!-- #principal -->
<section id="principal" class="drm">
    <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => true, 'etape_courante' => DRMClient::ETAPE_CRD)); ?>
    <div id="application_dr">
        <div id="contenu_onglet">    
            <?php
            include_partial('drm_crds/crdsLists', array(
                'allCrdsByRegimeAndByGenre' => $allCrdsByRegimeAndByGenre,
                'drm' => $drm,
                'crdsForms' => $crdsForms));
            ?>
            <?php foreach($allCrdsByRegimeAndByGenre as $regime => $crdsNodes): ?>
            <?php include_partial('ajout_crds_popups', array('drm' => $drm, 'form' => $addCrdForm, 'regime' => $regime)); ?>
            <?php endforeach; ?>
        </div>

    </div>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => true));
?>