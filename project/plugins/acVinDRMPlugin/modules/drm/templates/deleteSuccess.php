<?php use_helper('Date'); ?>
<?php use_helper('DRM'); ?>
<?php use_helper('Orthographe'); ?>
<section id="principal" class="drm_delete drm">
    <div id="application_drm">
        <?php if (!$isTeledeclarationMode): ?>
            <?php include_partial('drm/header', array('drm' => $drm)); ?>
            <ul id="recap_infos_header">
                <li>
                    <label>Nom de l'opérateur : </label><?php echo $drm->getEtablissement()->nom ?><label style="float: right;">Période : <?php echo $drm->periode ?></label>
                </li>
            </ul>
        <?php endif; ?>

        <h2>Suppression de la DRM <?php echo getFrPeriodeElision($drm->periode); ?></h2>
        <form method="POST" class="drm_validation_societe_form">
            <p>Etes vous sur(e) de vouloir supprimer <?php echo $drm->_id; ?> ?</p>
            <div id="btn_etape_dr">
                <input class="btn_majeur btn_annuler" type="submit" name="confirm" value="Oui"/>
                <a id="drm_validation_societe_annuler_btn" style="float: left;" class="btn_majeur btn_annuler" href="#"><span>annuler</span></a>
                
            </div>
        </form>
    </div>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));
?>