<a class="legal_signature_popup" href="#legal_signature"></a>
<div style="display:none;">
    <div id="legal_signature" class="legal_signature_content">
        <form action="<?php echo url_for('drm_legal_signature', array('identifiant' => $etablissement->identifiant)) ?>" method="post">
            <?php echo $legalSignatureForm->renderHiddenFields(); ?>
            <?php echo $legalSignatureForm->renderGlobalErrors(); ?>
            <h2>Activation de votre espace DRM</h2>
            <br/>
            <p>
                InterLoire met à votre disposition des outils de simplification administrative sur le système d’identification sécurisé : « vinsvaldeloire.pro ».
            </p>
            <br/>
            <p>
                Pour activer votre espace DRM, vous devez prendre connaissance et accepter le contrat d’inscription à la télédéclaration de la DRM. Pour cela, <a href="/data/contrat_service_v2.pdf" style="text-decoration: underline;">cliquez ici</a>.
            </p>
            <br/>
            <?php echo $legalSignatureForm['terms']->render(array('required' => 'true')); ?><label for="drm_legal_signature_terms">J’accepte le <a href="/data/contrat_service_v2.pdf">contrat d’inscription</a> à la télédéclaration de la DRM.</label>
            <br/>
            <div class="ligne_btn">
                <button id="popup_confirm" type="submit" class="btn_validation" style="float: right;" ><span>Activer mon espace</span></button>
            </div>
        </form>
    </div>
</div>
