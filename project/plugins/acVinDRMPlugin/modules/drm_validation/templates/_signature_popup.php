<div id="signature_drm_popup" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Veuillez confirmer la validation de la DRM</h4>
            </div>
            <div class="modal-body">
                <p>Vous êtes sur le point de valider votre DRM, une fois votre déclaration validée, vous ne pourrez plus la modifier.</p>
                <p>Après validation vous receverez votre DRM par mail.</p>

                <?php if($compte->hasDroit(Roles::TELEDECLARATION_DOUANE) && ! $drm->crds->exist('COLLECTIFACQUITTE')): ?>
          	    <p>Si vous le souhaitez, en cliquant sur l'option ci-dessous, vous pouvez transmettre cette DRM directement sur le portail de la douane, qui apparaitra en mode brouillon sur le portail pro.douane.gouv.fr. Il vous restera alors à la valider en ligne sur le site web douanier.</p>
                <?php endif; ?>
      	        <p>Si vous décidez de transmettre le document par courrier postal ou par mail, n'oubliez pas que la DRM doit être signée manuellement pour être valable.</p>
                <?php if($compte->hasDroit("teledeclaration_douane")): ?>
                <?php if($drm->crds->exist('COLLECTIFACQUITTE')): ?>
                  <p><strong>Les douanes ne nous permettant pas de leur transmettre des CRD en régime acquitté, nous ne pouvons pas vous permettre de télédéclarer votre DRM sur le portail des douanes pour le moment.</strong></p>
                <?php else: ?>
                <p>
                  <div class="ligne_form">
                      <span>
                          <?php echo $validationForm['transmission_ciel']->renderLabel(null, array('id' => 'transmissionciellabel')); ?>
                          <?php echo $validationForm['transmission_ciel']->renderError(); ?>
                          <input id="drm_transmission_ciel_visible" type="checkbox"  value="1" checked="checked" />
                      </span>
                  </div>
                </p>
                <?php endif; endif; ?>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-xs-6 text-left"><button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button></div>
                    <div class="col-xs-6 text-right"><button type="submit" class="btn btn-success" id="signature_drm_popup_confirm">Valider la DRM</button></div>
                </div>
            </div>
        </div>
    </div>
</div>
