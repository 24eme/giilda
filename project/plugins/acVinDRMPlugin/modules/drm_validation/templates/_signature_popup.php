<div id="signature_drm_popup" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Veuillez confirmer la validation de la DRM</h4>
            </div>
            <div class="modal-body">
                <p>Vous êtes sur le point de valider votre DRM, une fois votre déclaration validée, vous ne pourrez plus la modifier.</p>
                <p>Après validation vous recevrez votre DRM par mail.</p>
                <?php if(!isset($validationForm['transmission_ciel'])): ?>
                <p>Après avoir validé, vous allez être invité.e à télécharger votre DRM au format XML afin de l'importer en DTI+ sur le site de la douane.</p>
                <?php else: ?>
                <p>Si vous le souhaitez, en cliquant sur l'option ci-dessous, vous pouvez transmettre cette DRM directement sur le portail de la douane, qui apparaitra en mode brouillon sur le portail douane.gouv.fr. Il vous restera alors à la valider en ligne sur le site web douanier.</p>
                <p>
                  <div class="ligne_form">
                      <div class="checkbox">
                          <?php echo $validationForm['transmission_ciel']->renderError(); ?>
                          <label>
                              <input id="drm_transmission_ciel_visible" type="checkbox"  value="1" checked="checked"/>
                              <strong>Transmission pour préremplissage de votre DRM electronique sur le portail douane.gouv.fr</strong>
                          </label>
                      </div>
                  </div>
                </p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-xs-6 text-left"><button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button></div>
                    <div class="col-xs-6 text-right"><button type="submit" data-loading-text="Validation en cours ..." class="btn btn-success btn-loading" id="signature_drm_popup_confirm">Valider la DRM</button></div>
                </div>
            </div>
        </div>
    </div>
</div>
