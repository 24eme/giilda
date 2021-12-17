<div class="modal modal-page" id="modalMandatSepa" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
      <h3 class="modal-title">Mandat SEPA n°<?php echo $mandatSepa->getReference(false) ?></h3>
    </div>
    <div class="modal-body">
        <p>
          Vous venez de compléter vos coordonnées bancaires.
        </p>
        <p>
          Pour terminer votre inscription au prélèvement automatique vous devez télécharger le mandat de prélèvement SEPA.
        </p>
        <p>
          Une fois téléchargé, il vous suffit de le signer et de nous le renvoyer par voie postale à l'adresse suivante :</p>
        </p>
        <p class="text-center">
          <?php echo $mandatSepa->creancier->nom; ?>
          <br />
          <?php echo $mandatSepa->creancier->adresse; ?>
          <br />
          <?php echo $mandatSepa->creancier->code_postal; ?>&nbsp;<?php echo $mandatSepa->creancier->commune; ?>
        </p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fermer</button>
      <a href="<?php echo url_for('mandatsepa_pdf', $mandatSepa) ?>" class="btn btn-warning">Télécharger le document</a>
    </div>
  </div>
</div>
</div>
