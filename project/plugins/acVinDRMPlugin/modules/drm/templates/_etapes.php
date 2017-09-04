<?php $hasAcquitte = $drm->hasEtablissementDroitsAcquittes() && $drm->getConfig()->declaration->hasAcquitte(); ?>
<ol id="rail_etapes">
    <?php $cpt_etape = 1; ?>
    <?php if (isset($isTeledeclarationMode) && $isTeledeclarationMode) : ?>
        <?php $actif = ($etape_courante == DRMClient::ETAPE_CHOIX_PRODUITS); ?>
        <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_CHOIX_PRODUITS, DRMClient::$drm_etapes))); ?>
        <li class="<?php echo ($past) ? 'passe' : '' ?> <?php echo ($actif) ? 'actif' : '' ?> <?php echo ($hasAcquitte)? 'acquitte' : '' ?>">
            <a href="<?php echo url_for('drm_choix_produit', $drm); ?>">
                <strong><?php echo $cpt_etape++; ?>.&nbsp;&nbsp;Produits</strong>
            </a>
        </li>
    <?php endif; ?>
    <?php $actif = ($etape_courante == DRMClient::ETAPE_SAISIE_SUSPENDU || $etape_courante == DRMClient::ETAPE_SAISIE); ?>
    <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_SAISIE, DRMClient::$drm_etapes))); ?>
    <li class="<?php echo ($past) ? 'passe' : '' ?> <?php echo ($actif) ? 'actif' : '' ?> <?php echo ($hasAcquitte)? 'acquitte' : '' ?>">
        <?php if ($past): ?><a href="<?php echo url_for('drm_edition', $drm); ?>"><?php endif; ?>
            <strong><?php echo $cpt_etape++; ?>.&nbsp;&nbsp;Mvts Susp.</strong>
            <?php if ($past): ?></a><?php endif; ?>
    </li>
    <?php if (isset($isTeledeclarationMode) && $isTeledeclarationMode): ?>
      <?php if ($hasAcquitte): ?>
      <?php $actif = ($etape_courante == DRMClient::ETAPE_SAISIE_ACQUITTE); ?>
      <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_SAISIE_ACQUITTE, DRMClient::$drm_etapes))); ?>
        <?php $isDouaneTypeAcquitte = $drm->isDouaneType(DRMClient::TYPE_DRM_ACQUITTE); ?>
        <li class="<?php echo ($past) ? 'passe' : '' ?> <?php echo ($actif) ? 'actif' : '' ?> <?php echo ($hasAcquitte)? 'acquitte' : '' ?>">
            <?php if ($past): ?><a href="<?php echo url_for('drm_edition_details', array('sf_subject' => $drm, 'details' => DRM::DETAILS_KEY_ACQUITTE)); ?>"><?php endif; ?>
                <strong><?php echo $cpt_etape++; ?>.&nbsp;&nbsp;Mvts. Acq.</strong>
                <?php if ($past): ?></a><?php endif; ?>
        </li>
      <?php endif; ?>
        <?php $actif = ($etape_courante == DRMClient::ETAPE_CRD); ?>
        <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_CRD, DRMClient::$drm_etapes))); ?>
        <li class="<?php echo ($past) ? 'passe' : '' ?> <?php echo ($etape_courante == DRMClient::ETAPE_CRD) ? 'actif' : '' ?> <?php echo ($hasAcquitte)? 'acquitte' : '' ?>">
            <?php if ($past): ?><a href="<?php echo url_for('drm_crd', $drm); ?>"><?php endif; ?>
                <strong><?php echo $cpt_etape++; ?>.&nbsp;&nbsp;CRD</strong>
                <?php if ($past): ?></a><?php endif; ?>
        </li>
    <?php endif; ?>
    <?php if (isset($isTeledeclarationMode) && $isTeledeclarationMode) : ?>
        <?php $actif = ($etape_courante == DRMClient::ETAPE_ADMINISTRATION); ?>
        <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_ADMINISTRATION, DRMClient::$drm_etapes))); ?>
        <li class="<?php echo ($past) ? 'passe' : '' ?> <?php echo ($etape_courante == DRMClient::ETAPE_ADMINISTRATION) ? 'actif' : '' ?> <?php echo ($hasAcquitte)? 'acquitte' : '' ?>">
            <?php if ($past): ?><a href="<?php echo url_for('drm_annexes', $drm); ?>"><?php endif; ?>
                <strong><?php echo $cpt_etape++; ?>.&nbsp;&nbsp;Annexes</strong>
                <?php if ($past): ?></a><?php endif; ?>
        </li>
    <?php endif; ?>
    <?php $actif = ($etape_courante == DRMClient::ETAPE_VALIDATION); ?>
    <?php $past = ((!$actif) && (array_search($drm->etape, DRMClient::$drm_etapes) >= array_search(DRMClient::ETAPE_VALIDATION, DRMClient::$drm_etapes))); ?>
    <li class="<?php echo ($past) ? 'passe' : '' ?> <?php echo ($etape_courante == DRMClient::ETAPE_VALIDATION) ? 'actif' : '' ?> <?php echo ($hasAcquitte)? 'acquitte' : '' ?>">
        <?php if ($past): ?><a href="<?php echo url_for('drm_validation', $drm); ?>"><?php endif; ?>
            <strong><?php echo $cpt_etape; ?>.&nbsp;&nbsp;Validation</strong>
            <?php if ($past): ?></a><?php endif; ?>
    </li>
</ol>
