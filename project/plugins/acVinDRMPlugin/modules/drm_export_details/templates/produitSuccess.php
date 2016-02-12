<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title text-center">Détails des exports<br /><span class="text-muted"><?php echo $detail->getLibelle(ESC_RAW); ?></span></h4>
        </div>
        <form data-related-element="#input_sortie_export_<?php echo $detail->getHashForKey() ?>" class="form-horizontal form-ajax-modal" data-content=".ajax-content" method="post" action="<?php echo url_for('drm_export_details', $detail) ?>">
            <div class="modal-body">
                <div class="ajax-content">
                    <?php include_partial('formContent',array('form' => $form, 'detail' => $detail, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>
                </div>
                <?php include_partial('templateItem', array('form' => $form->getFormTemplate(), 'detail' => $detail, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Abandonner</button>
                <button type="submit" class="btn btn-success" >Valider</button>
            </div>
        </form>
    </div>
</div>
