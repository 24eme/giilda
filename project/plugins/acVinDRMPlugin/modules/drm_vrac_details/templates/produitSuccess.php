<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title text-center">Détails des contrats<br /><span class="text-muted"><?php echo $detail->getLibelle(ESC_RAW); ?></span></h4>
        </div>
        <?php if($detail->hasContratVrac() || true): ?>
        <form data-related-element="#input_<?php echo $catKey ?>_<?php echo $key ?>_<?php echo $detail->getHashForKey() ?>" class="form-horizontal form-ajax-modal" data-content=".ajax-content" method="post" action="<?php echo url_for('drm_vrac_details', array('sf_subject' => $detail, 'cat_key' => $catKey, 'key' => $key)) ?>">
            <div class="modal-body">
                <div class="ajax-content">
                    <?php include_partial('formContent',array('form' => $form, 'detail' => $detail, 'isTeledeclarationMode' => $isTeledeclarationMode, 'catKey' => $catKey, 'key' => $key)); ?>
                </div>
                <?php include_partial('templateItem', array('form' => $form->getFormTemplate(), 'detail' => $detail, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>
            </div>
            <div class="modal-footer">
                <button tabindex="-1" type="button" class="btn btn-default pull-left" data-dismiss="modal">Abandonner</button>
                <button type="submit" class="btn btn-success btn-loading btn-dynamic-element-submit">Valider</button>
            </div>
        </form>
        <?php else: ?>
            <div class="modal-body">
                <div class="text-center alert alert-warning">Il n'existe aucun contrat pour ce produit</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        <?php endif; ?>
    </div>
</div>
