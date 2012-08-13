<section id="contenu" style="background: #fff; padding: 0 10px;">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php /*include_partial('etapes', array('drm' => $drm, 
                                                   'etape' => 'mouvements', 
                                                   'pourcentage' => '10'));*/ ?>
    <?php include_partial('drm/controlMessage'); ?>

    <?php if ($drm->isRectifiable()): ?>
        <a href="<?php echo url_for('drm_rectificative', $drm) ?>">Soumettre une DRM rectificative</a>
    <?php endif; ?>

    <?php if ($drm->isModifiable()): ?>
        <a href="<?php echo url_for('drm_modificative', $drm) ?>">Soumettre une DRM modificative</a>
    <?php endif; ?>

    <!-- #principal -->
    <section id="principal" style="width: auto;">

        <?php if ($drm_suivante && $drm_suivante->isRectificative() && !$drm_suivante->isValidee()): ?>
            <div class="vigilance_list">
                <ul>
                    <li><?php echo MessagesClient::getInstance()->getMessage('msg_rectificatif_suivante') ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <?php include_partial('drm/recap', array('drm' => $drm)) ?>
        <?php include_partial('drm/mouvements', array('mouvements' => $drm->mouvements)) ?>

        <div id="btn_etape_dr">
            <?php if ($drm_suivante && $drm_suivante->isRectificative()): ?>
                <a href="<?php echo url_for('drm_init', array('identifiant' => $drm->getEtablissement(), 'periode_version' => $drm_suivante->getPeriodeAndVersion())) ?>" class="btn_suiv">
                    <span>Passer à la DRM suivante</span>
                </a>
            <?php else: ?>
                <a href="<?php echo url_for('drm_etablissement', $drm->getEtablissement()) ?>" class="btn_suiv">
                    <span>Retour à mon espace</span>
                </a>
            <?php endif; ?>
        </div>
    </section>

</section>
