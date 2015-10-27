<a class="legal_signature_popup" href="#legal_signature"></a>
<div style="display:none;">
     <div id="legal_signature" class="legal_signature_content">
        <form action="<?php echo url_for('drm_legal_signature', array('identifiant' => $etablissement->identifiant)) ?>" method="post">
            <?php echo $legalSignatureForm->renderHiddenFields(); ?>
            <?php echo $legalSignatureForm->renderGlobalErrors(); ?>
            <h2>Activation de votre espace DRM</h2>
            <br/>
     <p>Afin de vous permettre de télédéclarer vos DRM et de vous offrir la possibilité d'éditer un document envoyable aux douanes, Interloire a besoin que vous acceptiez les conditions du <a href="data/contrat_service_v1.pdf">contrat de service «&nbsp;télédéclaration DRM&nbsp;»</a>.</p>
<p>Pour activer votre espace DRM, nous vous invitons à en prendre connaissance et à en accepter les termes en cliquant sur le lien «&nbsp;j'accepte les termes du service télédéclartaion DRM&nbsp;».</p>
            <br/>
<p style="width: 100%"><a href="data/contrat_service_v1.pdf">Pour lire le contrat de service, cliquer ici</a></p>
<br/>
            <?php echo $legalSignatureForm['terms']->render(array('required' => 'true')); ?><label for="drm_legal_signature_terms">J'accepte les <a href="data/contrat_service_v1.pdf">contrat de service «&nbsp;télédéclaration DRM&nbsp;»</a></label>
            <br/>
            <div class="ligne_btn">
                <button id="popup_confirm" type="submit" class="btn_validation" style="float: right;" ><span>Activer mon espace</span></button>  
            </div>
        </form>
    </div>
</div>