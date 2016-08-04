<ol class="breadcrumb">
    <li><a href="<?php echo url_for('drm') ?>">DRM</a></li>
    <li><a href="<?php echo url_for('drm_etablissement', array('identifiant' => $etablissement->identifiant)) ?>"><?php echo $etablissement->nom ?> (<?php echo $etablissement->identifiant ?>)</a></li>
    <li><a href="<?php echo url_for('drm_etablissement', array('identifiant' => $etablissement->identifiant)) ?>">Calendrier</a></li>
    <li><a href="" class="active"><?php echo ($campagne == -1) ? "Les derniers mois" : $campagne ?></a></li>
</ol>

<div class="row">
    <?php if (!$isTeledeclarationMode): ?>
    <div class="col-xs-12">
        <?php include_component('drm', 'formEtablissementChoice', array('identifiant' => $etablissement->_id)) ?>
    </div>
  <?php endif; ?>
    <div class="col-xs-12">
        <?php if ($campagne == -1) : ?>
          <div class="row">
            <div class="col-xs-1 text-right" style="padding-left:10px;">
              <span class="icon-drm" style="font-size: 50px;"></span>
            </div>
            <div class="col-xs-11">
            <h3>Espace DRM de <?php echo $societe->raison_sociale; ?> (<?php echo $societe->identifiant; ?>)</h3>
          </div>
          </div>
        <?php else: ?>
            <h3>Historique des DRM</h3>
        <?php endif; ?>
        <?php if (!$etablissement->hasLegalSignature()) { include_component('drm', 'legalSignature', array('etablissement' => $etablissement)); } ?>
        <?php if ($isTeledeclarationMode) : if ($campagne == -1) : ?>
            <?php include_component('drm', 'monEspaceDrm', array('etablissement' => $etablissement, 'campagne' => $campagne, 'isTeledeclarationMode' => $isTeledeclarationMode,'accueil_drm' => true, 'calendrier' => $calendrier)); ?>
        <?php endif; endif; ?>

        <?php if (!$isTeledeclarationMode): ?>
            <ul class="nav nav-tabs">
                <li class="active"><a href="">Vue calendaire</a></li>
                <li><a href="<?php echo url_for('drm_etablissement_stocks', array('identifiant' => $etablissement->getIdentifiant(), 'campagne' => $campagne)); ?>">Vue stock</a></li>
            </ul>
        <?php endif; ?>

        <?php include_component('drm', 'calendrier', array('etablissement' => $etablissement, 'campagne' => $campagne, 'formCampagne' => $formCampagne, 'isTeledeclarationMode' => $isTeledeclarationMode, 'calendrier' => $calendrier)); ?>
    </div>
</div>
