<!-- #principal -->
<section id="principal" class="drm">
    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <h2>Déclaration Récapitulative Mensuelle</h2>
    <ul id="recap_infos_header">
        <li><span>Nom de l'opérateur :</span> <?php echo $drm->getEtablissement()->nom ?> </li>
        <li><span>Période :</span> <?php echo ucfirst($drm->getHumanPeriode()); ?></li>
        <li class="odd"><span>Numéro d'archive :</span> <?php echo $drm->numero_archive ?></li>
    </ul>

    <?php if ($drm_suivante && $drm_suivante->isRectificative() && !$drm_suivante->isValidee()): ?>
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

    <?php include_partial('drm_visualisation/recap', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode)) ?>

    <h2>Mouvement</h2>
    <?php include_partial('drm_visualisation/mouvements', array('mouvements' => $mouvements, 'hamza_style' => true, 'no_link' => $no_link)) ?>
    <br/>
    <table class="table_recap">
        <tr><th>Commentaire</th></tr>
        <tr><td><pre class="commentaire"><?php echo $drm->commentaire; ?></pre></td></tr>
    </table>
    <br />
    <div id="btn_etape_dr">
        <a href="<?php echo url_for('drm_etablissement', array('identifiant' => $drm->identifiant, 'campagne' => $drm->campagne)); ?>" class="btn_etape_prec" id="facture"><span>Retour à mon espace</span></a> 
    </div>
</section>
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('drm_etablissement', array('identifiant' => $drm->identifiant, 'campagne' => $drm->campagne)); ?>" class="btn_majeur btn_acces"><span>Retour au calendrier</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>
