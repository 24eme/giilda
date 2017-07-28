<div style="display:none">
    <div id="signature_popup_content" class="popup_contenu">
        <h2>Veuillez confirmer la signature du contrat </h2>
        <div class="ligne_btn">
          <?php if($vrac->isBio() && $etablissementPrincipal->isNegociant()): ?>
            <input name="popup_validation_bio_ecocert" id="popup_validation_bio_ecocert" type="checkbox" style="margin:0; vertical-align: bottom;  position: relative; top: -1px">
            <label for="popup_validation_bio_ecocert" style="font-weight:bold;" >&nbsp;j'ai le certificat Ecocert du vendeur</label>
          <?php endif; ?>
          <?php if($vrac->isBio() && $etablissementPrincipal->isViticulteur() && (!$vrac->hasBioEcocert())): ?>
            <input name="engagement_bio_ecocert" id="engagement_bio_ecocert" type="checkbox" style="margin:0; vertical-align: bottom;  position: relative; top: -1px">
            <label for="engagement_bio_ecocert" style="font-weight:bold;" >&nbsp;Je m'engage à fournir à l'acheteur le certificat Ecocert</label>
          <?php endif; ?>
        </div>
        <br/>
        <div class="ligne_btn">
            <a id="signature_popup_close" class="btn_rouge btn_majeur annuler" style="float: left;" href="#" >Annuler</a>
            <?php if (isset($validation) && $validation): ?>
              <button id="signature_popup_confirm" type="submit" class="btn_validation" ><span>Signer le contrat</span></button>
            <?php else : ?>
              <?php if($vrac->isBio() && $etablissementPrincipal->isViticulteur() && (!$vrac->hasBioEcocert())) : ?>
                  <div class="ecocert_not_confirmed" >
                    <button class="btn_majeur" style="opacity:0.5; float: right;" disabled="disabled" ><span>Signer le contrat</span></button>
                  </div>
                  <div class="ecocert_confirmed" style="display:none;">
                    <a id="signature_popup_confirm" href="<?php echo url_for('vrac_signature', $vrac) ?>" class="btn_validation" ><span>Signer le contrat</span></a>
                  </div>
              <?php else : ?>
                <a id="signature_popup_confirm" data-lien="<?php echo url_for('vrac_signature', $vrac) ?>" href="<?php echo url_for('vrac_signature', $vrac) ?>" class="btn_validation <?php echo ($vrac->isBio() && $etablissementPrincipal->isNegociant())? "ecocert_negociant" : ""?>" ><span>Signer le contrat</span></a>
              <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
