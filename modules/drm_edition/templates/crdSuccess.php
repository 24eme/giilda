<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<!-- #principal -->
<section id="principal" class="drm">


    <h2><?php echo getDrmTitle($drm); ?></h2>

    <?php include_partial('drm_edition/etapes', array('drm' => $drm, 'isTeledeclarationMode' => true, 'etape_courante' => DRMClient::ETAPE_CRD)); ?>

    <div id="application_dr">
        <div id="contenu_onglet">
           

                <?php
                include_partial('drm_edition/crdsList', array(
                    'allCrds' => $drm->getAllCrds(),
                    'crdsForms' => $crdsForms));
                ?>
               
            <?php include_partial('ajout_crds_popup', array('drm' => $drm, 'form' => $addCrdForm)); ?>
        </div>

    </div>
</section>
<?php
include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));
?>