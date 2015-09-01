<ol class="breadcrumb">
    <li><a href="<?php echo url_for('drm') ?>">Page d'accueil</a></li>
    <li><a href="<?php echo url_for('drm_etablissement', array('identifiant' => $etablissement->identifiant)) ?>" class="active"><?php echo $etablissement->nom ?></a></li>
</ol>


<div class="row">
    <div class="col-xs-10 col-xs-offset-1">
        <?php include_component('drm', 'formEtablissementChoice', array('identifiant' => $etablissement->_id)) ?>
    </div>

    <div class="col-xs-12">
        <?php if ($campagne == -1) : ?>
            <h2>Espace drm de <?php echo $societe->raison_sociale; ?> (<?php echo $societe->identifiant; ?>)</h2>
        <?php else: ?>
            <h2>Historique des drm de <?php echo $societe->raison_sociale; ?> (<?php echo $societe->identifiant; ?>)</h2>
        <?php endif; ?>

        <?php if ($etablissement->type_dr) : ?>
            <div class="alert alert-warning">
                Cet opérateur effectue des <?php echo $etablissement->type_dr; ?>
            </div>
        <?php endif; ?>

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