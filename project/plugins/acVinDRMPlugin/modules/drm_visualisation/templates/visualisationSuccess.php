<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>

<ol class="breadcrumb">
    <li><a href="<?php echo url_for('drm') ?>">Page d'accueil</a></li>
    <li><a href="<?php echo url_for('drm_etablissement', array('identifiant' => $drm->identifiant)) ?>"><?php echo $drm->etablissement->nom ?></a></li>
    <li class="active"><a href=""><?php echo getDrmTitle($drm); ?></a></li>
</ol>

    <?php if (!$isTeledeclarationMode): ?>
        <?php include_partial('drm/header', array('drm' => $drm)); ?> 
        <ul id="recap_infos_header">
            <li>
                <label>Nom de l'opérateur : </label><?php echo $drm->getEtablissement()->nom ?>
            </li>
            <li>
                <strong><label><?php echo ($drm->isTeledeclare()) ? 'Télédéclarée' : 'Saisie sur Vinsi'; ?></label>
                    <?php if (!$isTeledeclarationMode && !$drm->isTeledeclare()): ?>
                        <label style="margin-left: 150px;"><?php echo 'Numéro d\'archive : ' . $drm->numero_archive; ?></label>
                    <?php endif; ?>
                    <label style="float: right;">Période : <?php echo $drm->periode ?></label></strong>
            </li>         
        </ul>
    <?php else: ?>
        <?php if ($drm->isTeledeclare()): ?>  
            <a href="<?php echo url_for('drm_pdf', $drm); ?>" class="btn btn-success pull-right"><span>Télécharger le PDF</span></a>
        <?php endif; ?>
        <h2><?php echo getDrmTitle($drm); ?> <small>(Validée le <?php echo format_date($drm->valide->date_signee, "dd/MM/yyyy", "fr_FR"); ?>)</small></h2>
    <?php endif; ?>

    <div id="drm_validation_coordonnees">
        <div class="drm_validation_societe">    
            <?php //include_partial('drm_visualisation/societe_infos', array('drm' => $drm, 'isModifiable' => false)); ?>
        </div>
        <div class="drm_validation_etablissement">
            <?php //include_partial('drm_visualisation/etablissement_infos', array('drm' => $drm, 'isModifiable' => false)); ?>
        </div>
    </div>

    <?php if (!$isTeledeclarationMode): ?>
        <?php if ($drm_suivante && $drm_suivante->isRectificative() && !$drm_suivante->isValidee()):
            ?>
            <div class="vigilance_list">
                <ul>
                    <li><?php echo MessagesClient::getInstance()->getMessage('msg_rectificatif_suivante') ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($drm->isModifiable()): ?>
            <div style="text-align: right;">
                <a class="btn_majeur btn_modifier" href="<?php echo url_for('drm_modificative', $drm) ?>">Modifier la DRM</a>
            </div>
        <?php endif; ?>

        <?php if (!$drm->isMaster()): ?>
            <div id="points_vigilance">
                <ul>
                    <li class="warning">Ce n'est pas la <a href="<?php echo url_for('drm_visualisation', $drm->getMaster()) ?>">dernière version</a> de la DRM, le tableau récapitulatif n'est donc pas à jour.</a></li>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php include_partial('drm_visualisation/recap_stocks_mouvements', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'no_link' => $no_link, 'mouvementsByProduit' => $mouvementsByProduit, 'visualisation' => true)) ?>

    <?php if (!$isTeledeclarationMode): ?>
        <br/>
        <table class="table_recap">
            <tr><th>Commentaire</th></tr>
            <tr><td><pre class="commentaire"><?php echo $drm->commentaire; ?></pre></td></tr>
        </table>
    <?php else: ?> 
        <?php include_partial('drm_visualisation/recap_crds', array('drm' => $drm)) ?>  
        <?php include_partial('drm_visualisation/recapAnnexes', array('drm' => $drm)) ?>  
    <?php endif; ?>   
    <?php include_partial('drm_visualisation/recapDroits', array('drm' => $drm, 'recapCvo' => $recapCvo, 'isTeledeclarationMode' => $isTeledeclarationMode)) ?>
    <br />
    <div class="row">
        <div class="col-xs-4">
            <a href="<?php echo url_for('drm_etablissement', array('identifiant' => $drm->identifiant)); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Retour à mon espace</a>
        </div>
        <?php if ($isTeledeclarationMode) : ?>
        <div class="col-xs-4 text-center">
            <a href="<?php echo url_for('drm_pdf', $drm); ?>" class="btn btn-success">Télécharger le PDF</a>
        </div>
        <?php endif; ?>
    </div>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));
?>
