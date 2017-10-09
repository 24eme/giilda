<?php $interpro = sfConfig::get('app_teledeclaration_interpro'); ?>


<div class="well">
    <p>Pour adhérer au service de télétransmission de vos DRM à l’application CIEL du Portail des Douanes, vous devez télécharger le Bulletin d’Adhésion à CIEL en cliquant sur le bouton ci-dessous.</p>
    <div class="text-center"><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#convention_ciel">Adhésion à CIEL</button></div>
</div>

<div id="convention_ciel" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Adhésion à l’application CIEL du Portail des Douanes</h4>
      </div>
      <div class="modal-body">
        <p>Pour que nous puissions transmettre vos DRM aux Douanes par l’intermédiaire de l’application CIEL sue le Portail des Douanes,
          il est indispensable de préalablement compléter, signer et retourner au CIVA la <a href="<?php echo url_for('drm_convention', $etablissement) ?>">convention d’adhésion à CIEL</a>.</p>

        <p>Le CIVA se chargera de transmettre aux Douanes votre bulletin d’adhésion afin d’activer votre connexion au téléservice CIEL.</p>

        <p>Vous pouvez dès à présent <a href="<?php echo url_for('drm_convention', $etablissement) ?>">télécharger ce bulletin d’adhésion</a>
          et le retourner au CIVA complété et signé, même si vous ne souhaitez dématérialiser votre DRM que dans quelques mois.</p>

      </div>
      <div class="modal-footer text-center">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fermer</button>
          <a class="btn btn-info" href="<?php echo url_for('drm_convention', $etablissement) ?>">Télécharger la convention d'adhésion à CIEL</a>
      </div>
    </div>
  </div>
</div>
