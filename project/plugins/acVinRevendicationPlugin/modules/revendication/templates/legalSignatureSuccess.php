<?php if ((!$societe->exist('legal_signature') || !$societe->legal_signature->exist('drev'))): ?>
<a class="legal_signature_popup" href="#legal_signature"></a>
<div style="display:none;">
    <div id="legal_signature" class="legal_signature_content" style="width: 600px;">
        <form action="<?php echo url_for('drev_legal_signature', array('identifiant' => $compte->identifiant)) ?>" method="post">
            <?php echo $legalSignatureForm->renderHiddenFields(); ?>
            <?php echo $legalSignatureForm->renderGlobalErrors(); ?>
            <h2>Activation de votre espace DREV</h2>
            <br/>
            <p>
            InterLoire met à votre disposition des outils de simplification déclarative sur son portail professionnel sécurisé : « vinsvaldeloire.pro ».
            </p>
            <br/>
            <p>
            La dématérialisation de la déclaration de revendication (DREV) est disponible depuis le 15 octobre 2019.
            </p>
            <br/>
            <p>
            Cette déclaration permet de revendiquer les volumes de vins à commercialiser pour les opérateurs identifiés et habilités en AOP et IGP du Val de Loire.
            Ce service offre une procédure d’enregistrement dématérialisée simple et rapide grâce à une interface conviviale et à des éléments pré-renseignés.
            </p>
            <br/>
            <p>
             Pour activer cet espace de dématérialisation, vous devez prendre connaissance et accepter le contrat d’inscription à la télédéclaration de la DREV. Pour cela, <a href="/data/cgu_drev.pdf" style="text-decoration: underline;">cliquez ici</a>.
            </p>
            <br />
            <?php echo $legalSignatureForm['terms']->render(array('required' => 'true')); ?><label for="drev_legal_signature_terms">J’accepte le <a href="/data/cgu_drev.pdf">contrat d’inscription</a> à la télédéclaration de la DREV.</label>
            <br/>
            <div class="ligne_btn">
                <button id="popup_confirm" type="submit" class="btn_validation" style="float: right;" ><span>Activer mon espace</span></button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
