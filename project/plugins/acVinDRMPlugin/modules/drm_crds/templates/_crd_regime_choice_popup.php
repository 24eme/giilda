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
            <p><span class="error_list">Votre chai «&nbsp;<?php echo $drm->getEtablissement()->nom; ?>&nbsp;» ne possède actuellement aucun régime de CRD.</span></p>
            <p>Le choix de votre régime CRD a un impact sur le paiement ou non de vos droits de circulation pour les sorties bouteilles et BIB en fin de DRM</p>
            <p>Si vous payez les capsules ainsi que les droits de circulation auprès de votre organismes répartiteur au moment de l'achat des capsules, cochez « collectif acquittés (DA) » (Les droits de circultation sont réglés aux douanes au moment de l'achat des capsules). Si vous ne payez que les capsules auprès de votre organisme répartiteur, cochez « collectif suspendu (DS) » (Vos droits de circulation seront payés aux douanes sur la base des éléments de la DRM). Si vous payez uniquement vos capsules auprsè d'un fournisseur privé, coché « personnalisé (P) » (les droits de circultation sont payés sur la base des éléments de la DRM)</p>
            <br/>
            <p>Une fois renseigné, ce message n'apparaîtra plus. </p>.
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
