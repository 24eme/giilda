<fieldset id="espace_drm"> 
    <div id="mon_espace">

            <div class="block_teledeclaration espace_drm">
                <div class="title">ESPACE DRM</div>
                <div class="panel">
                    <ul class="etablissements_drms">
                        <?php foreach ($drmToCompleteAndToStart as $etb => $drmsByEtb) : ?>
                            <li> 
                                <div class="etablissement_drms">
                                    <h2> <?php echo $drmsByEtb->nom . ' (' . $etb . ')'; ?></h2>
                                    <ul>
                                        <?php if ($drmsByEtb->nb_drm_to_finish): ?> 
                                            <li  class="statut_toFinish"> 
                                                <button >&nbsp;</button>
                                                <span>  <?php echo $drmsByEtb->nb_drm_to_finish; ?> DRM en attente de validation
                                                </span>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($drmsByEtb->nb_drm_to_create): ?> 
                                            <li class="statut_toCreate"> 
                                                <button>&nbsp;</button>
                                                <span> <?php echo $drmsByEtb->nb_drm_to_create; ?> DRM en attente de création
                                                </span>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
    </div>
</fieldset>