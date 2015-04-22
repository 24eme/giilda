<ol id="rail_etapes_drm">
    <?php $cpt_etape = 1 ; ?>
    <?php if(isset($isTeledeclarationMode) && $isTeledeclarationMode) : ?>    
    <li class="<?php echo ($drm->etape == DRMClient::ETAPE_CHOIX_PRODUITS)? 'actif' : '' ?>">
        <a href="<?php echo url_for('drm_choix_produit',$drm); ?>">
            <strong><span style="cursor: default;"><?php echo $cpt_etape; ?>&nbsp;-</span>
                Produits 
            </strong>    </a>    
    </li>
    <?php $cpt_etape++; ?>
    <?php endif; ?>
    <li class="<?php echo ($drm->etape == DRMClient::ETAPE_SAISIE)? 'actif' : '' ?>">
        <a href="<?php echo url_for('drm_edition',$drm); ?>">
            <strong><span style="cursor: default;"><?php echo $cpt_etape++; ?>&nbsp;-</span>
                Mouvements 
            </strong>    </a>    
    </li>
    <li class="<?php echo ($drm->etape == DRMClient::ETAPE_CRD)? 'actif' : '' ?>">
        <a href="<?php echo url_for('drm_edition',$drm); ?>">
            <strong><span style="cursor: default;"><?php echo $cpt_etape++; ?>&nbsp;-</span>
                CRD 
            </strong>    </a>    
    </li>
    <li class="<?php echo ($drm->etape == DRMClient::ETAPE_VALIDATION)? 'actif' : '' ?>">
        <a>
            <span style="cursor: default;"><?php echo $cpt_etape; ?>&nbsp;-</span>
            Validation 
        </a>    
    </li>
</ol>