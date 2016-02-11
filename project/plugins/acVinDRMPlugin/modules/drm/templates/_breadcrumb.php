<?php use_helper('DRM'); ?>
<?php use_helper('Orthographe'); ?>

<ol class="breadcrumb">
    <li><a href="<?php echo url_for('drm') ?>">DRM</a></li>
    <li><a href="<?php echo url_for('drm_etablissement', array('identifiant' => $drm->getEtablissementObject()->identifiant)) ?>"><?php echo $drm->getEtablissementObject()->nom ?> (<?php echo $drm->getEtablissementObject()->identifiant ?>)</a></li>
    <li><a class="active" href="">DRM <?php echo getFrPeriodeElision($drm->periode) ?></a></li>
</ol>