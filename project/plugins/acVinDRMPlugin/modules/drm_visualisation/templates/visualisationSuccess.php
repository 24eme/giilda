<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>

<?php include_partial('drm/breadcrumb', array('drm' => $drm)); ?>

<?php if (!$isTeledeclarationMode): ?>
<div class="row" style="opacity: 0.7">
    <div class="col-xs-12">
         <?php include_component('drm', 'formEtablissementChoice', array('identifiant' => $drm->etablissement->_id)) ?>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-xs-12">
        <?php if ($isTeledeclarationMode): ?>
            <?php if ($drm->isTeledeclare()): ?>  
                <a href="<?php echo url_for('drm_pdf', $drm); ?>" class="btn btn-success pull-right"><span>Télécharger le PDF</span></a>
            <?php endif; ?>
        <?php endif; ?>

        <h3 style="margin-bottom: 30px">DRM <?php echo getFrPeriodeElision($drm->periode); ?> <?php if ($drm->isTeledeclare()): ?><small>(Validée le <?php echo format_date($drm->valide->date_signee, "dd/MM/yyyy", "fr_FR"); ?>)</small><?php endif; ?>
             <?php if (!$isTeledeclarationMode && $drm->isModifiable()): ?>
        <div class="pull-right">
            <a class="btn btn-warning" href="<?php echo url_for('drm_modificative', $drm) ?>">Modifier la DRM</a>
        </div>
        <?php endif; ?>
        </h3>


        <!--<div id="drm_validation_coordonnees">
            <div class="drm_validation_societe">    
                <?php //include_partial('drm_visualisation/societe_infos', array('drm' => $drm, 'isModifiable' => false)); ?>
            </div>
            <div class="drm_validation_etablissement">
                <?php //include_partial('drm_visualisation/etablissement_infos', array('drm' => $drm, 'isModifiable' => false)); ?>
            </div>
        </div>-->

        <?php if (!$isTeledeclarationMode): ?>
            <?php if ($drm_suivante && $drm_suivante->isRectificative() && !$drm_suivante->isValidee()):
                ?>
                <div class="vigilance_list">
                    <ul>
                        <li><?php echo MessagesClient::getInstance()->getMessage('msg_rectificatif_suivante') ?></li>
                    </ul>
                </div>
            <?php endif; ?>   

            <?php if (!$drm->isMaster()): ?>
                <div class="alert alert-warning">
                    Ce n'est pas la <a href="<?php echo url_for('drm_visualisation', $drm->getMaster()) ?>">dernière version</a> de la DRM, le tableau récapitulatif des stocks n'est donc pas à jour.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php include_partial('drm_visualisation/recap_stocks_mouvements', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'no_link' => $no_link, 'mouvementsByProduit' => $mouvementsByProduit, 'visualisation' => true)) ?>
    </div>
</div>

<?php if (!$isTeledeclarationMode && $drm->commentaire): ?>
    <div class="row">
        <div class="col-xs-12">
            <h4>Commentaire</h4>
        </div>
        <div class="col-xs-12">
            <div class="well">
                <?php echo $drm->commentaire; ?>
            </div>
        </div>
    </div>
    <br/>
<?php else: ?> 
    <?php include_partial('drm_visualisation/recap_crds', array('drm' => $drm)) ?>  
    <?php include_partial('drm_visualisation/recapAnnexes', array('drm' => $drm)) ?>  
<?php endif; ?>   
<?php include_partial('drm_visualisation/recapDroits', array('drm' => $drm, 'recapCvos' => $recapCvos, 'isTeledeclarationMode' => $isTeledeclarationMode)) ?>
<div class="row">
    <div class="col-xs-4">
        <a href="<?php echo url_for('drm_etablissement', array('identifiant' => $drm->identifiant)); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Retour à mon espace DRM</a>
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