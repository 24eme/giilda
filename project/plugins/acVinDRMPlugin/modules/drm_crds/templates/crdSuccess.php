<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<?php $allCrdsByRegimeAndByGenre = $drm->getAllCrdsByRegimeAndByGenre(); ?>
<!-- #principal -->
<section id="principal" class="drm">

    <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => true, 'etape_courante' => DRMClient::ETAPE_CRD)); ?>
    <div id="application_drm">
        <div id="contenu_etape">
            <div id="contenu_onglet">    
                <?php
                include_partial('drm_crds/crdsLists', array(
                    'allCrdsByRegimeAndByGenre' => $allCrdsByRegimeAndByGenre,
                    'drm' => $drm,
                    'crdsForms' => $crdsForms));
                ?>

                <?php if(isset($addCrdForm) && isset($addCrdRegime)): ?>
                    <a class="btn_majeur ajout_crds_popup" style="display: none;" href="#add_crds_<?php echo $addCrdRegime ?>">Ajouter CRD</a>
                    <?php include_partial('ajout_crds_popups', array('form' => $addCrdForm, 'regime' => $addCrdRegime)); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => true));
?>