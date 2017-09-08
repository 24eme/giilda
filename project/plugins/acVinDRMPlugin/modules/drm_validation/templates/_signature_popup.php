<div style="display:none">
        <div id="signature_drm_popup_content" class="popup_contenu">
            <h2>Veuillez confirmer la validation de la DRM </h2>
            <br/>
            <p>
                Vous êtes sur le point de valider votre DRM. Une fois validée, vous recevrez votre DRM par mail et vous ne pourrez plus la modifier.<br /><br />
            </p>
        <?php if($compte->hasDroit(Roles::TELEDECLARATION_DOUANE) && ! $drm->crds->exist('COLLECTIFACQUITTE')): ?>
    	    <p>Si vous le souhaitez, en cliquant sur l'option ci-dessous, vous pouvez transmettre cette DRM directement sur le portail de la douane, qui apparaitra en mode brouillon sur le portail pro.douane.gouv.fr.</p><br/>
          <p>Il vous restera alors à la valider  une dernière fois en ligne sur le portail douanier.</p><br/>
        <?php endif; ?>
	    <p>Si vous décidez de transmettre le document par courrier postal ou par mail, n'oubliez pas que la DRM doit être signée manuellement pour être valable.</p>
        <?php if($compte->hasDroit("teledeclaration_douane")): ?>
          <p>
            <div class="ligne_form">
                <span>
                    <?php echo $validationForm['transmission_ciel']->renderLabel(null, array('id' => 'transmissionciellabel')); ?>
                    <?php echo $validationForm['transmission_ciel']->renderError(); ?>
                    <input id="drm_transmission_ciel_visible" type="checkbox"  value="1" checked="checked" />
                </span>
            </div>
          </p>
        <?php endif;?>
            <div class="ligne_btn">
                <a id="signature_drm_popup_close" class="btn_rouge btn_majeur annuler" style="float: left;" href="#" >Annuler</a>
                <a id="signature_drm_popup_confirm" class="btn_validation" ><span>Valider la DRM</span></a>
            </div>
        </div>
</div>
