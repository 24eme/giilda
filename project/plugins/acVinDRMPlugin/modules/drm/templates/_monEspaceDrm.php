<?php use_helper('Date'); ?>
<?php use_helper('DRM'); ?>
<?php use_helper('Orthographe'); ?>
<fieldset id="espace_drm">
    <div id="mon_espace">
        <div class="block_teledeclaration  espace_drm">
            <div class="title <?php echo ($accueil_drm)? 'title_espace' : ''; ?>">ESPACE DRM</div>
            <div class="panel">
                <ul style="<?php if (!isset($btnAccess)): ?>height: auto<?php endif; ?>" class="etablissements_drms">
                    <?php foreach ($lastDrmToCompleteAndToStart as $etb => $drmsByEtb) : ?>
                        <li>
                            <div class="etablissement_drms">
                                <h2> <?php echo $drmsByEtb->nom . ' (' . $etb . ')'; ?></h2>
                                <ul class="block_drm_espace">
                                    <?php if ($drmsByEtb->statut == DRMCalendrier::STATUT_EN_COURS): ?>
                                        <li class="statut_toFinish">                                            
                                            <a href="<?php echo url_for('drm_redirect_etape', $drmsByEtb->drm); ?>" ><span>Finir la DRM <?php echo getFrPeriodeElision($drmsByEtb->periode); ?></span></a>
                                        </li>
                                    <?php endif; ?>
                                        <?php if ($drmsByEtb->statut == DRMCalendrier::STATUT_EN_COURS_NON_TELEDECLARE): ?>
                                        <li class="statut_toFinish_non_teledeclare">                                            
                                            <span>La DRM <?php echo getFrPeriodeElision($drmsByEtb->periode); ?> est en cours de saisie à Interloire</span>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($drmsByEtb->statut == DRMCalendrier::STATUT_NOUVELLE): ?>
                                        <li class="statut_toCreate">
                                            <a href="<?php echo url_for('drm_nouvelle', array('identifiant' => $etb, 'periode' => $drmsByEtb->periode)); ?>"><span>Créer la DRM <?php echo getFrPeriodeElision($drmsByEtb->periode); ?></span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($drmsByEtb->statut == DRMCalendrier::STATUT_VALIDEE): ?>
                                        <li class="statut_validee">
                                            <a href="<?php echo url_for('drm_visualisation', $drmsByEtb->drm); ?>"> <span>Visualiser votre DRM <?php echo getFrPeriodeElision($drmsByEtb->periode); ?>
                                                </span></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php if (isset($btnAccess)): ?>
                    <div class="acces">
                        <a href="<?php echo url_for('drm_societe', array('identifiant' => $etablissement->getIdentifiant())) ?>" class="btn_majeur">Accéder aux DRM</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</fieldset>