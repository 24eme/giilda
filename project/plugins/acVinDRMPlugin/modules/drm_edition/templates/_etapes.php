<ol id="rail_etapes_drm">
    <?php $cpt_etape = 1; ?>
    <?php if (isset($isTeledeclarationMode) && $isTeledeclarationMode) : ?> 
        <?php $passed = (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_CHOIX_PRODUITS, DRMClient::$drm_etapes)); ?>
        <li class="<?php echo ($passed) ? 'passe' : '' ?> <?php echo ($etape_courante == DRMClient::ETAPE_CHOIX_PRODUITS) ? 'actif' : '' ?>">
            <a href="<?php echo url_for('drm_choix_produit', $drm); ?>">
                <strong><span style="cursor: default;"><?php echo $cpt_etape; ?>&nbsp;-</span>
                    Produits 
                </strong>  
            </a>    
        </li>
        <?php $cpt_etape++; ?>
    <?php endif; ?>
    <?php $passed = (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_SAISIE, DRMClient::$drm_etapes)); ?>
    <li class="<?php echo ($passed) ? 'passe' : '' ?> <?php echo ($etape_courante == DRMClient::ETAPE_SAISIE) ? 'actif' : '' ?>">
        <?php if ($passed): ?><a href="<?php echo url_for('drm_edition', $drm); ?>"><?php endif; ?>
            <strong><span style="cursor: default;"><?php echo $cpt_etape++; ?>&nbsp;-</span>
                Mouvements 
            </strong>   
            <?php if ($passed): ?></a><?php endif; ?>
    </li>
    <?php if (isset($isTeledeclarationMode) && $isTeledeclarationMode) : ?> 
        <?php $passed = (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_CRD, DRMClient::$drm_etapes)); ?>
        <li class="<?php echo ($passed) ? 'passe' : '' ?> <?php echo ($etape_courante == DRMClient::ETAPE_CRD) ? 'actif' : '' ?>"> 
            <?php if ($passed): ?><a href="<?php echo url_for('drm_crd', $drm); ?>"><?php endif; ?>
                <strong><span style="cursor: default;"><?php echo $cpt_etape++; ?>&nbsp;-</span>
                    CRD 
                </strong>   
                <?php if ($passed): ?></a><?php endif; ?>
        </li>
        <?php $cpt_etape++; ?>
    <?php endif; ?>
    <?php $passed = (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_VALIDATION, DRMClient::$drm_etapes)); ?>
    <li class="<?php echo ($passed) ? 'passe' : '' ?> <?php echo ($etape_courante == DRMClient::ETAPE_VALIDATION) ? 'actif' : '' ?>">
        <?php if ($passed): ?><a href="<?php echo url_for('drm_validation', $drm); ?>"><?php endif; ?>
            <strong><span style="cursor: default;"><?php echo $cpt_etape; ?>&nbsp;-</span>
                Validation  </strong>   
            <?php if ($passed): ?></a><?php endif; ?>
    </li>
</ol>