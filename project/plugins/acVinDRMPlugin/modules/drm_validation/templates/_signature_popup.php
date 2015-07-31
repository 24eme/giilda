<div style="display:none">
        <div id="signature_drm_popup_content" class="popup_contenu">
            <h2>Veuillez confirmer la validation de la DRM </h2>
            <br/>
            <p>
                Vous êtes sur le point de valider votre DRM, une fois votre déclaration validée, vous ne pourrez plus la modifier.
            </p>
            <p>
                Après validation vous receverez votre DRM par mail et vous avez la possibilité de la transmettre ci dessous à un email supplémentaire.
            </p>
            <div class="ligne_form">       
                <span>
                    <?php echo $validationForm['email_transmission']->renderLabel(); ?>
                    <?php echo $validationForm['email_transmission']->renderError(); ?>
                    <input id="drm_email_transmission_visible" type="text"  value="<?php echo ($drm->exist('email_transmission') && $drm->email_transmission)? $drm->email_transmission : ''; ?>">
                </span>
            </div>
            <div class="ligne_btn">
                <a id="signature_drm_popup_close" class="btn_rouge btn_majeur annuler" style="float: left;" href="#" >Annuler</a>

                <a id="signature_drm_popup_confirm" class="btn_validation" ><span>Valider la DRM</span></a>     

            </div>
        </div>    
</div>