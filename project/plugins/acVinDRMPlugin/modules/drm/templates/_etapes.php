<ol id="rail_etapes">
    <?php $cpt_etape = 1; ?>
    <?php if (isset($isTeledeclarationMode) && $isTeledeclarationMode) : ?> 
        <?php $actif = ($etape_courante == DRMClient::ETAPE_CHOIX_PRODUITS); ?>
        <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_CHOIX_PRODUITS, DRMClient::$drm_etapes))); ?>
        <li class="<?php echo ($past) ? 'passe' : '' ?> <?php echo ($actif) ? 'actif' : '' ?>">
            <a href="<?php echo url_for('drm_choix_produit', $drm); ?>">
                <strong><span style="cursor: default;"><?php echo $cpt_etape++; ?>&nbsp;</span>
                    Produits 
                </strong>  
            </a>    
        </li>
    <?php endif; ?>
    <?php $actif = ($etape_courante == DRMClient::ETAPE_SAISIE); ?>
    <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_SAISIE, DRMClient::$drm_etapes))); ?>
    <li class="<?php echo ($past) ? 'passe' : '' ?> <?php echo ($actif) ? 'actif' : '' ?>">
        <?php if ($past): ?><a href="<?php echo url_for('drm_edition', $drm); ?>"><?php endif; ?>
            <strong><span style="cursor: default;"><?php echo $cpt_etape++; ?>&nbsp;</span>
                Mouvements 
            </strong>   
            <?php if ($past): ?></a><?php endif; ?>
    </li>
    <?php if (isset($isTeledeclarationMode) && $isTeledeclarationMode) : ?> 
        <?php $actif = ($etape_courante == DRMClient::ETAPE_CRD); ?>
        <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_CRD, DRMClient::$drm_etapes))); ?>
        <li class="<?php echo ($past) ? 'passe' : '' ?> <?php echo ($etape_courante == DRMClient::ETAPE_CRD) ? 'actif' : '' ?>"> 
            <?php if ($past): ?><a href="<?php echo url_for('drm_crd', $drm); ?>"><?php endif; ?>
                <strong><span style="cursor: default;"><?php echo $cpt_etape++; ?>&nbsp;</span>
                    CRD 
                </strong>   
                <?php if ($past): ?></a><?php endif; ?>
        </li>
    <?php endif; ?>
    <?php if (isset($isTeledeclarationMode) && $isTeledeclarationMode) : ?> 
        <?php $actif = ($etape_courante == DRMClient::ETAPE_ADMINISTRATION); ?>
        <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_CRD, DRMClient::$drm_etapes))); ?>
        <li class="<?php echo ($past) ? 'passe' : '' ?> <?php echo ($etape_courante == DRMClient::ETAPE_ADMINISTRATION) ? 'actif' : '' ?>"> 
            <?php if ($past): ?><a href="<?php echo url_for('drm_administration', $drm); ?>"><?php endif; ?>
                <strong><span style="cursor: default;"><?php echo $cpt_etape++; ?>&nbsp;</span>
                    ADMINISTR.
                </strong>   
                <?php if ($past): ?></a><?php endif; ?>
        </li>
    <?php endif; ?>
    <?php $actif = ($etape_courante == DRMClient::ETAPE_VALIDATION); ?>
    <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_VALIDATION, DRMClient::$drm_etapes))); ?>
    <li class="<?php echo ($past) ? 'passe' : '' ?> <?php echo ($etape_courante == DRMClient::ETAPE_VALIDATION) ? 'actif' : '' ?>">
        <?php if ($past): ?><a href="<?php echo url_for('drm_validation', $drm); ?>"><?php endif; ?>
            <strong><span style="cursor: default;"><?php echo $cpt_etape; ?>&nbsp;</span>
                Validation  </strong>   
            <?php if ($past): ?></a><?php endif; ?>
    </li>
</ol>