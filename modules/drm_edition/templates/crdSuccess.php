<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<!-- #principal -->
<section id="principal" class="drm">


    <h2><?php echo getDrmTitle($drm); ?></h2>

    <?php include_partial('drm_edition/etapes', array('drm' => $drm, 'isTeledeclarationMode' => true, 'etape_courante' => DRMClient::ETAPE_CRD)); ?>

    <div id="application_dr">
        <h2>Saisie des CRD</h2>

        <div id="contenu_onglet">
        </div>
    </div>
    <form action="<?php echo url_for('drm_crd', $form->getObject()) ?>" method="post">
        <?php echo $form->renderHiddenFields(); ?>
        <div id="btn_etape_dr">
            <a class="btn_etape_prec" href="<?php echo url_for('drm_edition', $drm); ?>">
                <span>Précédent</span>
            </a>
            <button class="btn_etape_suiv" id="button_drm_validation"><span>Suivant</span></button> 
        </div>
    </form>

</section>
<?php
include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));
?>