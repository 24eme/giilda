<?php
$args_url = array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion());
if (isset($retour) && ($retour == 'crds')) {
    $args_url = array_merge($args_url, array('retour' => $retour));
}
  $contactInterpro = EtablissementClient::getInstance()->buildInfosContact($drm->getEtablissement());
?>

<div id="drm_choix_regime_crd_popup"  class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <form action="<?php echo url_for('drm_choix_regime_crd', $args_url) ?>" method="post">
              <?php echo $crdRegimeForm->renderHiddenFields(); ?>
              <?php echo $crdRegimeForm->renderGlobalErrors(); ?>
      <div class="modal-content">
          <div class="modal-header">
              <h2>Choisir un régime de CRD (Compte capsule)</h2>
          </div>
          <div class="modal-body">
            <br/>
            <p><span class="text-danger">Votre chais «&nbsp;<?php echo $drm->getEtablissement()->nom; ?>&nbsp;» ne possède actuellement aucun régime de CRD.</span></p><br/>

            <p>Il est nécessaire pour la suite de la saisie de choisir ici le régime CRD. Une fois choisi ce message n'apparaîtra plus.</p><br/>
             Si vous achetez des CRD Acquitées auprès de votre ODG, sélectionnez «&nbsp;collectif acquitté&nbsp;». Si utilisez des CRD personnalisées, cliquez sur «&nbsp;personnalisé&nbsp;». Pour les autres cas, sélectionnez «&nbsp;collectif suspendu&nbsp;»</p>
            <br/>
            <p>Sur la DRM papier de <?php echo $contactInterpro->interpro; ?>, le régime CRD est demandé dans le cadre dédié au stock capsules&nbsp;:</p>
            <center><img src="/images/visuels/regime_crd_papier.jpg" width="400" height="125" stype="padding: 10px;" ></center>
            <br/>
            <p><b>Votre régime CRD&nbsp;:</b></p>

              <br/>
                  <?php echo $crdRegimeForm['crd_regime']->render(); ?>
              <br/>

          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Annuler</button>
              <button id="popup_confirm" type="submit" class="btn btn-success pull-right" ><span>Valider ce choix de régime CRD</span></button>
          </div>
      </div>
      </form>
  </div>
</div>
