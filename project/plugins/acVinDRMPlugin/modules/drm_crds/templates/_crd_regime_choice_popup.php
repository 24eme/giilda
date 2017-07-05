<?php
$args_url = array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion());
if (isset($retour) && ($retour == 'crds')) {
    $args_url = array_merge($args_url, array('retour' => $retour));
}
?>

<a class="crd_regime_choice_popup" href="#crd_choix_regime"></a>
<div style="display:none">
    <div id="crd_choix_regime" class="crd_choix_regime_content">
        <form action="<?php echo url_for('drm_choix_regime_crd', $args_url) ?>" method="post">
            <?php echo $crdRegimeForm->renderHiddenFields(); ?>
            <?php echo $crdRegimeForm->renderGlobalErrors(); ?>
            <h2>DRM suspendue : choisir un régime de CRD (Compte capsule)</h2>
            <br/>
            <p><span class="error_list">Votre chais «&nbsp;<?php echo $drm->getEtablissement()->nom; ?>&nbsp;» ne possède actuellement aucun régime de CRD.</span>
            Il est nécessaire pour la suite de la saisie de choisir ici le régime CRD. Une fois choisi ce message n'apparaîtra plus. <br/>
             Si vous achetez des CRD Acquitées auprès de votre ODG, sélectionnez «&nbsp;collectif acquitté&nbsp;». Si utilisez des CRD personnalisées, cliquez sur «&nbsp;personnalisé&nbsp;». Pour les autres cas, sélectionnez «&nbsp;collectif suspendu&nbsp;»</p>
            <br/>
            <p>Sur la DRM papier Interloire, le régime CRD est demandé dans le cadre dédié au stock capsules&nbsp;:</p>
            <center><img src="/images/visuels/regime_crd_papier.jpg" width="400" height="125" stype="padding: 10px;" ></center>
            <br/>
            <p><b>Votre régime CRD&nbsp;:</b></p>
            <?php echo $crdRegimeForm['crd_regime']->render(); ?>
            <br/>
            <div class="ligne_btn">
                <button id="popup_confirm" type="submit" class="btn_validation" style="float: right;" ><span>Valider ce choix de régime CRD</span></button>
            </div>
        </form>
    </div>
</div>
