<?php use_helper('DRM'); ?>
<?php use_helper('PointsAides'); ?>
<?php $multiEtablissement = $calendrier->isMultiEtablissement(); ?>
  <div class="row">
    <form method="post" class="form-inline col-xs-11" style="margin-top: 10px;">
        <?php echo $formCampagne->renderGlobalErrors() ?>
        <?php echo $formCampagne->renderHiddenFields() ?>
        <?php echo $formCampagne['campagne']->render(); ?>
        <input class="btn btn-default" type="submit" value="Changer"/>
        <?php echo getPointAideHtml('drm','changement_campagne') ?>
    </form>
    <div class="col-xs-1 text-right">

    </div>
  </div>
    <div class="row" style="margin-top: 20px;">
    <?php foreach ($calendrier->getPeriodes() as $periode): ?>
        <div class="<?php echo ($isTeledeclarationMode)? "col-xs-4" : "col-sm-3" ?>"   >
        <?php include_partial('drm/calendrierItem', array('calendrier' => $calendrier, 'periode' => $periode, 'etablissement' => $etablissement, 'isTeledeclarationMode' => $isTeledeclarationMode, 'multiEtablissement' => $multiEtablissement,'lastDrmToCompleteAndToStart' => $lastDrmToCompleteAndToStart[$etablissement->identifiant])); ?>
        </div>
    <?php endforeach; ?>
    </div>
<?php
if (isset($drmsToCreate)) {
    foreach ($drmsToCreate as $identifiantEtb => $periodeArray) {
        foreach ($periodeArray as $periode => $bool) {
            include_partial('drm/creationDrmPopup', array('periode' => $periode, 'identifiant' => $identifiantEtb, 'drmCreationForm' => $drmsToCreateForms[$identifiantEtb.'_'.$periode]));
        }
    }
}
?>
