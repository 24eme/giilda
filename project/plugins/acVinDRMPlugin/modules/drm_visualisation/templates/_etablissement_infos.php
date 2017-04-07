<div class="drm_validation_etablissement_info">
    <div class="title"><span class="text">VOTRE CHAI</span><?php if($isModifiable): ?>&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_validation_aide2'); ?>"></a><?php endif; ?></div>
    <div class="panel">
        <p style="text-align: center"><strong><?php echo $drm->declarant->nom; ?></strong></p>
        <br />
        <strong>N° CVI</strong> : <?php echo $drm->declarant->cvi; ?><br />
        <strong>N° Accises</strong> : <?php echo $drm->declarant->no_accises; ?><br />
        <br />
        <strong>Adresse :</strong><br />
        <?php echo $drm->declarant->adresse; ?><br />
        <?php echo $drm->declarant->code_postal; ?> <?php echo $drm->declarant->commune; ?><br />
        <br />
        <strong>Lieu de la comptabilité matière :</strong><br />
        <?php if($drm->declarant->adresse_compta): ?>
        <?php echo $drm->declarant->adresse_compta; ?><br />
        <?php else: ?>
            <?php echo $drm->declarant->adresse; ?>, <?php echo $drm->declarant->code_postal; ?> <?php echo $drm->declarant->commune; ?><br />
        <?php endif; ?>
        <br />
        <?php if ($drm->declarant->exist('caution')):
          $index = EtablissementClient::CAUTION_DISPENSE;
          if($drm->declarant->caution){
            $index = EtablissementClient::CAUTION_CAUTION;
          }
          ?>
        <strong>Caution :</strong> <?php if($drm->declarant->caution): ?><?php echo EtablissementClient::$caution_libelles[$index]; ?> <?php if($drm->declarant->raison_sociale_cautionneur): ?>(<?php echo $drm->declarant->raison_sociale_cautionneur; ?>)<?php endif; ?><?php else: ?>Non défini<?php endif; ?><br />
        <?php endif; ?>
        <?php if ($isModifiable): ?>
            <div id="btn_etape_dr">
                <a href="<?php echo url_for('drm_validation_update_etablissement', $drm); ?>" class="btn_majeur btn_modifier" style="float: right;" id="drm_validation_etablissement_info_btn"><span>modifier</span></a>
            </div>
        <?php endif; ?>
    </div>
</div>
