<a class="crd_regime_choice_popup" href="#crd_choix_regime"></a>
<div style="display:none">
    <div id="crd_choix_regime" class="crd_choix_regime_content">
        <form action="<?php echo url_for('drm_choix_regime_crd', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion())) ?>" method="post">
            <?php echo $crdRegimeForm->renderHiddenFields(); ?>
            <?php echo $crdRegimeForm->renderGlobalErrors(); ?>
            <h2>Choisir un régime de CRD (Compte capsule)</h2>
            <br/>
            <p class="error_list">Votre établissement <?php echo $drm->getEtablissement()->nom; ?> ne possède actuellement aucun régime de CRD</p>
            <br/>
            <p>Il est nécessaire pour la suite de la saisie de choisir ici le régime CRD. Une fois choisi ce message n'apparaîtra plus</p>
            <br/>
                <?php echo $crdRegimeForm['crd_regime']->render(); ?>
            <br/>
            <div class="ligne_btn">
                <button id="popup_confirm" type="submit" class="btn_validation" style="float: right;" ><span>Valider ce choix de régime CRD</span></button>  
            </div>
        </form>
    </div>
</div>