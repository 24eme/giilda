<div style="display:none">
    <div id="signature_popup_content" class="popup_contenu">
        <h2>Veuillez confirmer la signature du contrat </h2>
        <br/>
        <div class="ligne_btn">
            <a id="signature_popup_close" class="btn_rouge btn_majeur annuler" style="float: left;" href="#" >Annuler</a>
            <?php if (isset($validation) && $validation): ?> 
                <button id="signature_popup_confirm" type="submit" class="btn_validation" ><span>Signer le contrat</span></button>     
            <?php else : ?>
                <a id="signature_popup_confirm" href="<?php echo url_for('vrac_signature', $vrac) ?>" class="btn_validation"><span>Signer le contrat</span></a>     
            <?php endif; ?>
        </div>
    </div>
</div>