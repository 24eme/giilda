<?php
echo $form->renderHiddenFields();
echo $form->renderGlobalErrors();
?>
<table id="drm_vrac_details_table" class="table table-striped">
    <thead>
        <tr>
            <th class="col-xs-4">Produit</th>
            <th class="col-xs-4">Numéro contrat</th>
            <th class="col-xs-3">Volumes</th>            
            <?php if ($isTeledeclarationMode): ?>
                <th>Type de document</th>
                <th>Numéro de document</th> 
            <?php endif; ?>
            <th class="co-xs-1"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($form as $itemForm) {
            if ($itemForm instanceof sfFormFieldSchema) {
                include_partial('item', array('form' => $itemForm, 'detail' => $detail, 'isTeledeclarationMode' => $isTeledeclarationMode));
            } else {
                $itemForm->renderRow();
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"></td>
            <td class="lead text-right">
                <div class="input-group">
                    <div class="input-group-addon">&Sigma;</div>
                    <input type="text" class="form-control text-right" readonly="readonly" value="<?php echo $detail->sorties->vrac > 0 ? $detail->sorties->vrac : "0.00" ?>" />
                    <div class="input-group-addon">hl</div>
                </div>
            </td>
            <?php if ($isTeledeclarationMode) : ?>
            <td></td>
            <td></td>
            <?php endif; ?>
            <td><button type="button" data-container="#drm_vrac_details_table tbody" data-template="#template_vrac" class="btn btn-default dynamic-element-add"><span class="glyphicon glyphicon-plus"></span></a></td>
        </tr>
    </tfoot>
</table>
