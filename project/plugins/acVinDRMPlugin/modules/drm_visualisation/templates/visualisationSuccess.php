<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<!-- #principal -->
<section id="principal" class="drm">
    <?php if (!$isTeledeclarationMode): ?>
        <?php include_partial('drm/header', array('drm' => $drm)); ?>
        <ul id="recap_infos_header">
            <li>
                <label>Nom de l'opérateur : </label><?php echo $drm->getEtablissement()->nom ?>
            </li>
            <li>
                <strong>
                  <label><?php if($drm->isTeledeclare()): ?>Télédéclarée<?php if($drm->isTeledeclareFacturee()): ?>&nbsp;(facturée)<?php endif; ?><?php if($drm->isTeledeclareNonFacturee()): ?>&nbsp;(non facturée)<?php endif; ?>
                  <?php else : ?>Saisie sur Vinsi<?php endif; ?>
                </label>
                    <?php if (!$isTeledeclarationMode && !$drm->isTeledeclare()): ?>
                        <label style="margin-left: 150px;"><?php echo 'Numéro d\'archive : ' . $drm->numero_archive; ?></label>
                    <?php endif; ?>
                    <label style="float: right;">Période : <?php echo $drm->periode ?></label></strong>
            </li>
        </ul>
    <?php else: ?>
        <h2><?php echo getDrmTitle($drm); ?> <small style="font-weight: normal; text-transform: none;">(Validée le <?php echo format_date($drm->valide->date_signee, "dd/MM/yyyy", "fr_FR"); ?>)</small> &nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_visualisation_aide1'); ?>"></a></h2>
        <?php if ($drm->isTeledeclare()): ?>
            <div id="btn_etape_dr" style="text-align: center;">
                <a href="<?php echo url_for('drm_pdf', $drm); ?>" class="btn_majeur btn_pdf center" id="drm_pdf"><span>Télécharger le PDF</span></a>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div id="drm_validation_coordonnees">
        <div class="drm_validation_societe">
            <?php include_partial('drm_visualisation/societe_infos', array('drm' => $drm, 'isModifiable' => false)); ?>
        </div>
        <div class="drm_validation_etablissement">
            <?php include_partial('drm_visualisation/etablissement_infos', array('drm' => $drm, 'isModifiable' => false)); ?>
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
                <a class="btn_majeur btn_modifier" href="<?php echo url_for('drm_modificative', $drm) ?>">Modificatrice de la DRM</a>
            </div>
        <?php elseif (($drm->isTeledeclareFacturee() || $drm->hasSuivante()) && !$drm->hasBeenTransferedToCiel()): ?>
            <div style="text-align: right;">
                <a class="btn_majeur btn_modifier" href="<?php echo url_for('drm_modificative', $drm) ?>">Modificatrice de la DRM</a>
            </div>
        <?php elseif ($drm->isTeledeclareNonFacturee() && !$drm->hasSuivante()): ?>
            <div style="text-align: right;">
                <a class="btn_majeur btn_modifier" href="<?php echo url_for('drm_reouvrir', $drm) ?>">Ré-ouvrir la DRM</a>
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

    <?php include_partial('drm_visualisation/recap_stocks_mouvements', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'no_link' => $no_link, 'mouvements' => $mouvements, 'visualisation' => true)) ?>

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
    <?php if ($drm->exist('transmission_douane')) : ?>
    <div id="contenu_onglet">
        <h2>Transmission Douane</h2>
        <table class="table_recap">
            <thead >
                <tr>
                    <th>Transmission sur le portail proDou@ane</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>
<?php if ($drm->transmission_douane->success) : ?>
    La transmission a été réalisée avec succès le <?php echo $drm->getTransmissionDate(); ?> avec l'accusé reception numéro <?php echo $drm->transmission_douane->id_declaration ?>.
<?php else: ?>
    La transmission a échouée. Le message d'erreur envoyé par le portail des douanes est « <?php echo $drm->getTransmissionErreur(); ?> ».
<?php endif; ?>
                </td></tr>
            </tbody>
        </table>
    </div>
    <br/>
    <?php endif; ?>
    <div id="btn_etape_dr">
        <a href="<?php echo url_for('drm_etablissement', array('identifiant' => $drm->identifiant)); ?>" class="btn_etape_prec"><span>Retour à mon espace</span></a>
        <?php if ($isTeledeclarationMode) : ?>
            <a style="margin-left: 70px;" href="<?php echo url_for('drm_pdf', $drm); ?>" class="btn_majeur btn_pdf center" id="drm_pdf"><span>Télécharger le PDF</span></a>
            <?php if($compte->hasDroit("teledeclaration_douane") && $isTeledeclarationMode): ?>
              <?php if (!$drm->transmission_douane->success) : ?>
                <a style="margin-left: 5px;" href="<?php echo url_for('drm_transmission', $drm); ?>" class="btn_majeur btn_vert" ><span>Transmettre la Drm sur CIEL</span></a>
              <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));
?>
