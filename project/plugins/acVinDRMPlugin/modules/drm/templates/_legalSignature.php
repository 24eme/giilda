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
            <p>
                Depuis le 1er novembre 2015, la télédéclaration de la DRM ou de la DRA est disponible.
            </p>
              <br/> 
            <p>
                Ce service permet l’enregistrement de la DRM de façon simple et rapide grâce à une interface conviviale. 
            </p>
          
            <p>
                Le document pdf récapitulatif généré en fin d’enregistrement peut, à votre convenance, être envoyé aux services locaux de la Douane par voie postale ou électronique.
            </p>
            <br/> 
            <p>
                Pour activer votre espace DRM, vous devez prendre connaissance et accepter le contrat d’inscription à la télédéclaration de la DRM. Pour cela, cliquez <a href="/data/contrat_service_v1.pdf">ici</a>.
            </p>
            <br/>
            <?php echo $legalSignatureForm['terms']->render(array('required' => 'true')); ?><label for="drm_legal_signature_terms">J’accepte le <a href="/data/contrat_service_v1.pdf">contrat d’inscription</a> à la télédéclaration de la DRM.</label>
            <br/>
            <div class="ligne_btn">
                <button id="popup_confirm" type="submit" class="btn_validation" style="float: right;" ><span>Activer mon espace</span></button>  
            </div>
        </form>
    </div>
</div>