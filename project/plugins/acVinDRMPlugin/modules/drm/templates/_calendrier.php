<?php use_helper('DRM'); ?>
<?php $multiEtablissement = $calendrier->isMultiEtablissement(); ?>
    <form method="post">
        <?php echo $formCampagne->renderGlobalErrors() ?>
        <?php echo $formCampagne->renderHiddenFields() ?>
        <?php echo $formCampagne; ?> <input class="btn_majeur btn_vert" type="submit" value="changer"/>
    </form>
    <?php foreach ($calendrier->getPeriodes() as $periode): ?>
        <div class="col-sm-3">
        <?php include_partial('drm/calendrierItem', array('calendrier' => $calendrier, 'periode' => $periode, 'etablissement' => $etablissement, 'isTeledeclarationMode' => $isTeledeclarationMode, 'multiEtablissement' => $multiEtablissement)); ?>
        </div>
    <?php endforeach; ?>
<?php
if ($isTeledeclarationMode) {
    foreach ($drmsToCreate as $identifiantEtb => $periodeArray) {
        foreach ($periodeArray as $periode => $bool) {
            include_partial('drm/creationDrmPopup', array('periode' => $periode, 'identifiant' => $identifiantEtb, 'drmCreationForm' => $drmsToCreateForms[$identifiantEtb.'_'.$periode]));
        }
    }
}
?>
