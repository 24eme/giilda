<?php use_helper('Date') ?>

<p id="fil_ariane"><a href="<?php echo url_for('drm') ?>">Page d'accueil</a> &gt; <a href="<?php echo url_for('drm_etablissement', $drm->getEtablissement()) ?>"><?php echo $drm->getEtablissement()->nom ?></a> &gt; <strong>DRM de <?php echo $drm->periode ?></strong></p>



