<?php use_helper('DRM'); ?>
<?php    
    echo $form->renderHiddenFields();
    echo $form->renderGlobalErrors();
?>
<table id="drm_export_details_table" class="drm_details_table">
    <colgroup>
        <col id="col_produit">
        <col>
        <col>
        <col>
        <col>
    </colgroup>
    <thead>
        <tr>
            <th>Produit</th>
            <th>Pays&nbsp;<a href="" class="msg_aide_drm icon-msgaide" title="<?php echo getHelpMsgText('drm_mouvements_detail_export_aide1'); ?>" style="float: right; padding: 0 10px 0 0;"></a></th>
            <th>Volumes&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_mouvements_detail_export_aide2'); ?>" style="float: right; padding: 0 10px 0 0;"></a></th>
            <?php if($isTeledeclarationMode): ?>
            <th>Type de document&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_mouvements_detail_export_aide3'); ?>" style="float: right; padding: 0 10px 0 0;"></a><br/>(facultatif)</th>
            <th>Numéro de document&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_mouvements_detail_export_aide4'); ?>" style="float: right; padding: 0 10px 0 0;"></a><br/>(facultatif)</th>  
            <?php endif; ?>
            <th></th>
        </tr>
    </thead>
    <tbody id="drm_export_details_tableBody" class="drm_details_tableBody">
    <?php
    foreach ($form as $itemForm){
        if($itemForm instanceof sfFormFieldSchema) {
            include_partial('item',array('form' => $itemForm,'detail' => $detail,'isTeledeclarationMode' => $isTeledeclarationMode));
        } else {
            $itemForm->renderRow();
        }
    }
    ?>
        <tr id="drm_details_lastRow">
            <td class="export_detail_produit"></td>
            <td class="export_detail_destination">
                <a href="#" id="drm_export_details_addTemplate" class="btn_majeur btn_modifier drm_details_addTemplate">Ajouter un pays</a>
            </td>
            <td class="export_detail_volume">
                <div id="drm_details_export_volume_total">
                    <strong>
                        <span class="drm_details_volume_somme">&Sigma;</span>
                        <span class="drm_details_volume_total"><?php echo $detail->sorties->export > 0 ? $detail->sorties->export : "0.00" ?></span>
                        <span class="drm_details_volume_unite unite">hl</span>
                    </strong>
                </div>      
            </td>
            <td class="export_detail_numero_document"></td>   
            <td class="export_detail_remove"></td>
        </tr>
    </tbody>
</table>
