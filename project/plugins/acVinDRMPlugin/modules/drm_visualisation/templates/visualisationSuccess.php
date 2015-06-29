<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<!-- #principal -->
<section id="principal" class="drm">
    <?php if (!$isTeledeclarationMode): ?>
        <?php include_partial('drm/header', array('drm' => $drm)); ?> 
        <ul id="recap_infos_header">
            <li>
                <label>Nom de l'opérateur : </label><?php echo $drm->getEtablissement()->nom ?><label style="float: right;">Période : <?php echo $drm->periode ?></label>
            </li>
        </ul>
    <?php else: ?>
        <h2><?php echo getDrmTitle($drm); ?></h2>
        <div id="btn_etape_dr">
            <a href="#" class="btn_majeur btn_pdf center" id="drm_pdf"><span>Télécharger le PDF</span></a>
        </div>   
    <?php endif; ?>
        
        <div id="drm_validation_coordonnees">
            <div class="drm_validation_societe">    
                <?php include_partial('drm_visualisation/societe_infos', array('drm' => $drm, 'isModifiable' => false)); ?>
            </div>
            <div class="drm_validation_etablissement">
                <?php include_partial('drm_visualisation/etablissement_infos', array('drm' => $drm, 'isModifiable' => false)); ?>
            </div>
        </div>

    <div><span class="success">Votre DRM est validée - Date de validation : <?php echo $drm->valide->date_signee; ?></span></div>


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
            <a class="btn_majeur btn_modifier" href="<?php echo url_for('drm_modificative', $drm) ?>">Modifier la DRM</a>
        <?php endif; ?>

        <?php if (!$drm->isMaster()): ?>
            <div id="points_vigilance">
                <ul>
                    <li class="warning">Ce n'est pas la <a href="<?php echo url_for('drm_visualisation', $drm->getMaster()) ?>">dernière version</a> de la DRM, le tableau récapitulatif n'est donc pas à jour.</a></li>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php include_partial('drm_visualisation/recap_stocks_mouvements', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'no_link' => $no_link, 'mouvements' => $mouvements, 'visualisation' => true)) ?>

    <?php if (!$isTeledeclarationMode): ?>
        <table class="table_recap">
            <tr><th>Commentaire</th></tr>
            <tr><td><pre class="commentaire"><?php echo $drm->commentaire; ?></pre></td></tr>
        </table>
    <?php else: ?> 
      <?php include_partial('drm_visualisation/recap_crds', array('drm' => $drm)) ?>  
        <?php include_partial('drm_visualisation/recapAnnexes', array('drm' => $drm)) ?>  
    <?php endif; ?>   
             <?php include_partial('drm_visualisation/recapDroits', array('drm' => $drm, 'recapCvo' => $recapCvo, 'isTeledeclarationMode'  => $isTeledeclarationMode)) ?>
    <br />
    <div id="btn_etape_dr">
        <a href="<?php echo url_for('drm_etablissement', array('identifiant' => $drm->identifiant, 'campagne' => $drm->campagne)); ?>" class="btn_etape_prec" id="facture"><span>Retour à mon espace</span></a> 
    </div>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode'  => $isTeledeclarationMode));
?>
