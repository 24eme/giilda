<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Détail des contrats</h4>
</div>
<?php if($detail->hasContratVrac()): ?>
<form class="form-horizontal form-ajax-modal" data-content=".ajax-content" method="post" action="<?php echo url_for('drm_vrac_details', $detail) ?>">
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
<?php else: ?>
    <div class="modal-body">
        <div class="text-center alert alert-warning">Il n'existe aucun contrat pour ce produit</div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
    </div>
<?php endif; ?>