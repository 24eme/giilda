
    <div id="signature_popup_content"  class="modal fade" role="dialog">
       <div class="modal-dialog">
         <div class="modal-content">
     <div class="modal-header">
        <h2>Veuillez confirmer la signature du contrat </h2>
      </div>
   <div class="modal-body">
        <div class="row">
          <div class="col-xs-12">
            <a id="signature_popup_close" class="btn btn-danger" style="float: left;" href="#" >Annuler</a>
            <?php if (isset($validation) && $validation): ?>
                <button id="signature_popup_confirm" type="submit" class="btn btn-success" ><span>Signer le contrat</span></button>
            <?php else : ?>
                <a id="signature_popup_confirm" href="<?php echo url_for('vrac_signature', $vrac) ?>" class="btn btn-success pull-right"><span>Signer le contrat</span></a>
            <?php endif; ?>
        </div>
        </div>
    </div>
  </div>

</div>
