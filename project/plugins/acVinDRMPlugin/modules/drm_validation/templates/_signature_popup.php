<div style="display:none">
        <div id="signature_drm_popup_content" class="popup_contenu">
            <h2>Veuillez confirmer la validation de la DRM </h2>
            <br/>
            <p>
                Vous êtes sur le point de valider votre DRM. Une fois validée, vous recevrez votre DRM par mail et vous ne pourrez plus la modifier.<br /><br />
            </p>
    	    <p>
    		Avant de la transmettre à la Douane, par courrier postal, ou par mail, la DRM doit être signée manuellement pour être valable.
    	    </p>
          <p>

            <?php echo $validationForm['transmission_ciel']->renderLabel(); ?>
            <?php echo $validationForm['transmission_ciel']->render(); ?>
          </p>
            <div class="ligne_btn">
                <a id="signature_drm_popup_close" class="btn_rouge btn_majeur annuler" style="float: left;" href="#" >Annuler</a>

                <a id="signature_drm_popup_confirm" class="btn_validation" ><span>Valider la DRM</span></a>

            </div>
        </div>
</div>
