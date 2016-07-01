<ol class="breadcrumb">
    <li><a href="<?php echo url_for('drm') ?>">DRM</a></li>
    <li><a href="<?php echo url_for('drm_etablissement', array('identifiant' => $etablissement->identifiant)) ?>"><?php echo $etablissement->nom ?> (<?php echo $etablissement->identifiant ?>)</a></li>
    <li><a href="<?php echo url_for('drm_etablissement', array('identifiant' => $etablissement->identifiant)) ?>">Stocks</a></li>
    <li><a href="" class="active"><?php echo ($campagne == -1) ? "Les derniers mois" : $campagne ?></a></li>
</ol>

<div class="row">
    <div class="col-xs-12">
        <?php include_component('drm', 'formEtablissementChoice', array('identifiant' => $etablissement->_id)) ?>
    </div>

    <div class="col-xs-12">
        <?php if ($campagne == -1) : ?>
            <h3>Espace DRM</h3>
        <?php else: ?>
            <h3>Historique des DRM</h3>
        <?php endif; ?>
        <?php if ($isTeledeclarationMode) : if ($campagne == -1) : ?>
            <?php include_component('drm', 'monEspaceDrm', array('etablissement' => $etablissement, 'campagne' => $campagne, 'isTeledeclarationMode' => $isTeledeclarationMode,'accueil_drm' => true, 'calendrier' => $calendrier)); ?>
        <?php endif; endif; ?>

        <ul class="nav nav-tabs">
            <li><a href="<?php echo url_for('drm_etablissement', array('identifiant' => $etablissement->getIdentifiant(), 'campagne' => $campagne)); ?>">Vue calendaire</a></li>
            <li class="active"><a href="">Vue stock</a></li>
        </ul>

        <?php include_component('drm', 'stocks', array('etablissement' => $etablissement, 'campagne' => $campagne, 'formCampagne' => $formCampagne, 'hamza_style' => false)); ?>
    </div>
</div>