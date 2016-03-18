<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<!-- #principal -->

<?php include_partial('drm/breadcrumb', array('drm' => $drm)); ?>

<?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_VALIDATION)); ?>

<form action="<?php echo url_for('drm_validation', $form->getObject()) ?>" method="post" id="drm_validation">
    <div class="row">
        <div class="col-xs-12">
            <?php if ($isTeledeclarationMode): ?>
                <?php //include_partial('drm_validation/coordonnees_operateurs', array('drm' => $drm, 'validationCoordonneesSocieteForm' => $validationCoordonneesSocieteForm, 'validationCoordonneesEtablissementForm' => $validationCoordonneesEtablissementForm)); ?>
            <?php endif; ?>
            <?php include_partial('drm_visualisation/recap_stocks_mouvements', array('drm' => $drm, 'mouvements' => $mouvements, 'no_link' => $no_link, 'isTeledeclarationMode' => $isTeledeclarationMode, 'visualisation' => false, 'mouvementsByProduit' => $mouvementsByProduit)); ?>

            <?php if ($isTeledeclarationMode): ?>
                <?php include_partial('drm_visualisation/recap_crds', array('drm' => $drm)); ?>
                <?php include_partial('drm_visualisation/recapAnnexes', array('drm' => $drm)) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">  
                <?php echo $form->renderHiddenFields(); ?>
                <?php if (!$isTeledeclarationMode): ?>
                    <?php echo $form['commentaire']->renderLabel(); ?>
                    <?php echo $form['commentaire']->renderError(); ?>
                    <?php echo $form['commentaire']->render(array('tabindex'=>"30")); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?php include_partial('document_validation/validation', array('validation' => $validation)); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4 text-left">
            <a tabindex="-1" href="<?php echo ($isTeledeclarationMode) ? url_for('drm_annexes', $drm) : url_for('drm_edition', $drm); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Etape précédente</a>
        </div>
        <div class="col-xs-4 text-center">
            <?php /*if ($isTeledeclarationMode) : ?>
                <a tabindex="-1" class="btn btn-danger" href="<?php echo url_for('drm_etablissement', $vrac); ?>">Supprimer le brouillon</a>
            <?php endif;*/ ?>
        </div>
        <div class="col-xs-4 text-right">
                <?php if ($isTeledeclarationMode): ?> 
                        <?php echo $form['email_transmission']->render(); ?>
                        <button <?php if (!$validation->isValide()) : ?>disabled="disabled"<?php endif; ?> type="submit" id="signature_drm_popup" <?php if (!$validation->isValide()): ?>disabled="disabled"<?php endif; ?> href="#signature_drm_popup_content" class="btn btn-success"><span>Valider</span></button>
                        <?php include_partial('drm_validation/signature_popup', array('drm' => $drm, 'societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'validationForm' => $form)); ?>
                <?php else: ?>
                        <button <?php if (!$validation->isValide()) : ?>disabled="disabled"<?php endif; ?>class="btn btn-success" type="submit" tabindex="40" >Terminer la saisie <span class="glyphicon glyphicon-ok"></span></button>
                <?php endif; ?>
            
        </div>
    </div>
</form>