<nav class="navbar navbar-default ">
<ul class="nav navbar-nav">
    <?php $cpt_etape = 1; ?>
    <?php if (isset($isTeledeclarationMode) && $isTeledeclarationMode) : ?> 
        <?php $actif = ($etape_courante == DRMClient::ETAPE_CHOIX_PRODUITS); ?>
        <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_CHOIX_PRODUITS, DRMClient::$drm_etapes))); ?>
        <li class="<?php if($actif): ?>active<?php endif; ?> <?php if (!$past && !$actif): ?>disabled<?php endif; ?>">
            <a  href="<?php echo url_for('drm_choix_produit', $drm); ?>">
                <?php echo $cpt_etape++; ?>.&nbsp;&nbsp;Produits
            </a>
        </li>
    <?php endif; ?>
    <?php $actif = ($etape_courante == DRMClient::ETAPE_SAISIE); ?>
    <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_SAISIE, DRMClient::$drm_etapes))); ?>
    <li class="<?php if($actif): ?>active<?php endif; ?> <?php if (!$past && !$actif): ?>disabled<?php endif; ?>">
        <a href="<?php echo url_for('drm_edition', $drm); ?>">
            <?php echo $cpt_etape++; ?>.&nbsp;&nbsp;Mouvements
        </a>
    </li>
    <?php if (isset($isTeledeclarationMode) && $isTeledeclarationMode) : ?> 
        <?php $actif = ($etape_courante == DRMClient::ETAPE_CRD); ?>
        <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_CRD, DRMClient::$drm_etapes))); ?>
        <li class="<?php if($actif): ?>active<?php endif; ?> <?php if (!$past && !$actif): ?>disabled<?php endif; ?>"> 
           <a href="<?php echo url_for('drm_crd', $drm); ?>">
                <?php echo $cpt_etape++; ?>.&nbsp;&nbsp;CRD  
            </a>
        </li>
    <?php endif; ?>
    <?php if (isset($isTeledeclarationMode) && $isTeledeclarationMode) : ?> 
        <?php $actif = ($etape_courante == DRMClient::ETAPE_ADMINISTRATION); ?>
        <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_ADMINISTRATION, DRMClient::$drm_etapes))); ?>
        <li class="<?php if($actif): ?>active<?php endif; ?> <?php if (!$past && !$actif): ?>disabled<?php endif; ?>"> 
            <a href="<?php echo url_for('drm_annexes', $drm); ?>">
                <?php echo $cpt_etape++; ?>.&nbsp;&nbsp;Annexes
            </a>
        </li>
    <?php endif; ?>
    <?php $actif = ($etape_courante == DRMClient::ETAPE_VALIDATION); ?>
    <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_VALIDATION, DRMClient::$drm_etapes))); ?>
    <li class="<?php if($actif): ?>active<?php endif; ?> <?php if (!$past && !$actif): ?>disabled<?php endif; ?>">
        <a href="<?php echo url_for('drm_validation', $drm); ?>">
           <?php echo $cpt_etape; ?>.&nbsp;&nbsp;Validation
        </a>
    </li>
</ul>
</nav>