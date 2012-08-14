<?php use_helper('Date') ?>

<p id="fil_ariane"><a href="#">Page d'accueil</a> &gt; <a href="#">DRM/DRMA</a> &gt; <strong>Saisie DRM de <?php echo format_date($drm->getAnnee() . '-' . $drm->getMois() . '-01', 'MMMM yyyy', 'fr_FR') ?>
        <?php if ($drm->isRectificative()): ?>
            - <span style="color: #ff0000; text-transform: uppercase;">
                Rectificative n° <?php echo sprintf('%02d', $drm->rectificative) ?>
            </span>
        <?php endif; ?>
    </strong>
</p>


