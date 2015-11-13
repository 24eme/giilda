<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<!-- #principal -->
<section id="principal" class="drm">
    <div id="application_drm">
        <?php if (!$isTeledeclarationMode): ?>
            <?php include_partial('drm/header', array('drm' => $drm)); ?>
            <ul id="recap_infos_header">
                <li>
                    <label>Nom de l'opérateur : </label><?php echo $drm->getEtablissement()->nom ?><label style="float: right;">Période : <?php echo $drm->periode ?></label>
                </li>
            </ul>
        <?php endif; ?>

        <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_VALIDATION)); ?>

        <div id="contenu_etape">
            <?php if ($isTeledeclarationMode): ?>
                <p class="choix_produit_explication"><?php echo getHelpMsgText('drm_validation_texte1'); ?></p>
                <?php include_partial('drm_validation/coordonnees_operateurs', array('drm' => $drm, 'validationCoordonneesSocieteForm' => $validationCoordonneesSocieteForm, 'validationCoordonneesEtablissementForm' => $validationCoordonneesEtablissementForm)); ?>
            <?php endif; ?>

            <div style="padding-bottom: 20px">
                <?php include_partial('document_validation/validation', array('validation' => $validation)); ?>
            </div>

            <?php include_partial('drm_visualisation/recap_stocks_mouvements', array('drm' => $drm, 'mouvements' => $mouvements, 'no_link' => $no_link, 'isTeledeclarationMode' => $isTeledeclarationMode, 'visualisation' => false)); ?>

            <?php if ($isTeledeclarationMode): ?>
                <?php include_partial('drm_visualisation/recap_crds', array('drm' => $drm)); ?>
                <?php include_partial('drm_visualisation/recapAnnexes', array('drm' => $drm)) ?>
                <br/>
                <?php include_partial('drm_visualisation/recapDroits', array('drm' => $drm, 'recapCvo' => $recapCvo, 'isTeledeclarationMode' => $isTeledeclarationMode)) ?>
            <?php endif; ?>

            <form action="<?php echo url_for('drm_validation', $form->getObject()) ?>" method="post" id="drm_validation">
                <?php echo $form->renderHiddenFields(); ?>
                <?php if (!$isTeledeclarationMode): ?>
                    <h2><?php echo $form['commentaire']->renderLabel(); ?></h2>
                    <?php echo $form['commentaire']->renderError(); ?>
                    <?php echo $form['commentaire']->render(); ?>
                <?php endif; ?>
                <div class="btn_etape">
                    <a class="btn_etape_prec" href="<?php echo ($isTeledeclarationMode) ? url_for('drm_annexes', $drm) : url_for('drm_edition', $drm); ?>">
                        <span>Précédent</span>
                    </a>
                    <?php if (!$isTeledeclarationMode): ?>
                        <a href="<?php echo url_for('drm_etablissement', $drm->getEtablissement()); ?>" class="btn_brouillon btn_majeur">Enregistrer en brouillon</a>
                    <?php endif; ?>                   
                    <?php if ($isTeledeclarationMode): ?> 
                        <?php echo $form['email_transmission']->render(); ?>
                        <a id="signature_drm_popup" <?php if (!$validation->isValide()): ?>disabled="disabled"<?php endif; ?> href="#signature_drm_popup_content" class="btn_validation signature_drm<?php if ($validation->isValide()) echo '_popup'; ?>"><span>Valider</span></a>
                        <?php include_partial('drm_validation/signature_popup', array('drm' => $drm, 'societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'validationForm' => $form)); ?>

                        <a style="margin-left: 70px;" href="<?php echo url_for('drm_pdf', $drm); ?>" class="btn_majeur btn_pdf center" id="drm_pdf"><span>Vérifier votre PDF</span></a>

                    <?php else: ?>
                        <button type="submit" class="btn_etape_suiv" id="button_drm_validation" <?php if (!$validation->isValide()): ?>disabled="disabled"<?php endif; ?>><span>Valider</span></button>

                    <?php endif; ?>
                    <a class="drm_delete_lien" href="#drm_delete_popup"></a>
                </div>
            </form>
        </div>
    </div>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));
include_partial('drm/deleteDrmPopup', array('drm' => $drm, 'deleteForm' => $deleteForm));
?>