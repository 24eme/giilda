<fieldset id="espace_drm">
    <div id="mon_espace">
        <div class="block_teledeclaration espace_drm">
            <div class="title">ESPACE DRM</div>
            <div class="panel">
                <ul style="<?php if(!isset($btnAccess)): ?>height: auto<?php endif; ?>" class="etablissements_drms">
                    <?php foreach ($drmToCompleteAndToStart as $etb => $drmsByEtb) : ?>
                        <li>
                            <div class="etablissement_drms">
                                <h2> <?php echo $drmsByEtb->nom . ' (' . $etb . ')'; ?></h2>
                                <ul>
                                    <?php if ($drmsByEtb->nb_drm_to_finish): ?>
                                        <li class="statut_toFinish">
                                            <span>  <?php echo $drmsByEtb->nb_drm_to_finish; ?> DRM en attente de validation
                                            </span>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($drmsByEtb->nb_drm_to_create): ?>
                                        <li class="statut_toCreate">
                                            <span> <?php echo $drmsByEtb->nb_drm_to_create; ?> DRM en attente de création
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