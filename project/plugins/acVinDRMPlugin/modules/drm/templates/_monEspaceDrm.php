<fieldset id="espace_drm">
    <div id="mon_espace">
        <div class="block_teledeclaration espace_drm">
            <div class="title">ESPACE DRM</div>
            <div class="panel">
                <ul style="<?php if(!isset($btnAccess)): ?>height: auto<?php endif; ?>" class="etablissements_drms">
                    <?php foreach ($lastDrmToCompleteAndToStart as $etb => $drmsByEtb) : ?>
                        <li>
                            <div class="etablissement_drms">
                                <h2> <?php echo $drmsByEtb->nom . ' (' . $etb . ')'; ?></h2>
                                <ul>
                                    <?php if ($drmsByEtb->statut == DRMCalendrier::STATUT_EN_COURS): ?>
                                        <li class="statut_toFinish">
                                            <span>  DRM de <?php echo $drmsByEtb->periode; ?> en attente de validation </span>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($drmsByEtb->statut == DRMCalendrier::STATUT_NOUVELLE): ?>
                                        <li class="statut_toCreate">
                                            <span>  DRM de <?php echo $drmsByEtb->periode; ?> en attente de création
                                            </span>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($drmsByEtb->statut == DRMCalendrier::STATUT_VALIDEE): ?>
                                        <li class="statut_validee">
                                            <span>  Visualiser votre DRM de <?php echo $drmsByEtb->periode; ?>
                                            </span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php if(isset($btnAccess)): ?>
                <div class="acces">
                    <a href="<?php echo url_for('drm_societe', array('identifiant' => $etablissement->getIdentifiant())) ?>" class="btn_majeur">Accéder aux DRM</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</fieldset>