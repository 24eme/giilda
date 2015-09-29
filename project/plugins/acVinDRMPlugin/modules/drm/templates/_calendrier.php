<?php use_helper('DRM'); ?>
<?php $multiEtablissement = $calendrier->isMultiEtablissement(); ?>
<div class="section_label_maj <?php echo ($isTeledeclarationMode) ? 'section_label_maj_teledeclaration_drm' : '' ?>" id="calendrier_drm">
    <form method="POST">
        <?php echo $formCampagne->renderGlobalErrors() ?>
        <?php echo $formCampagne->renderHiddenFields() ?>
        <?php echo $formCampagne; ?> <input class="btn_majeur btn_vert" type="submit" value="changer"/>
    &nbsp;<a href="" class="msg_aide" data-msg="help_popup_drm_entrees" title="<?php echo "blabla"; ?>"></a>
    </form>
    <div class="bloc_form">
        <div class="ligne_form ligne_compose">
            <ul class="liste_mois">
                <?php foreach ($calendrier->getPeriodes() as $periode): ?>
                    <?php include_partial('drm/calendrierItem', array('calendrier' => $calendrier, 'periode' => $periode, 'etablissement' => $etablissement, 'isTeledeclarationMode' => $isTeledeclarationMode, 'multiEtablissement' => $multiEtablissement)); ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php
if ($isTeledeclarationMode) {
    foreach ($drmsToCreate as $identifiantEtb => $periodeArray) {
        foreach ($periodeArray as $periode => $bool) {
            include_partial('drm/creationDrmPopup', array('periode' => $periode, 'identifiant' => $identifiantEtb, 'drmCreationForm' => $drmsToCreateForms[$identifiantEtb.'_'.$periode]));
        }
    }
}
?>
