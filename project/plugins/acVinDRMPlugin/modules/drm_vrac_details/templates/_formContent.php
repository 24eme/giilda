<?php use_helper('DRM'); ?>
<?php
echo $form->renderHiddenFields();
echo $form->renderGlobalErrors();
?>
<table id="drm_vrac_details_table" class="drm_details_table">
    <thead>
        <tr>
            <th>Produit</th>
            <th>Numéro contrat&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_mouvements_detail_contrat_aide1'); ?>"  style="float: right; padding: 0 10px 0 0;"></a></th>
            <th>Volumes&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_mouvements_detail_contrat_aide2'); ?>" style="float: right; padding: 0 10px 0 0;"></a></th>            
            <?php if ($isTeledeclarationMode): ?>
                <th>Type de document&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_mouvements_detail_contrat_aide3'); ?>" style="float: right; padding: 0 10px 0 0;"></a></th>
                <th>Numéro de document&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_mouvements_detail_contrat_aide4'); ?>" style="float: right; padding: 0 10px 0 0;"></a></th> 
            <?php endif; ?>
            <th></th>
        </tr>
    </thead>
    <tbody id="drm_vrac_details_tableBody" class="drm_details_tableBody">
        <?php
        foreach ($form as $itemForm) {
            if ($itemForm instanceof sfFormFieldSchema) {
                include_partial('item', array('form' => $itemForm, 'detail' => $detail, 'isTeledeclarationMode' => $isTeledeclarationMode));
            } else {
                $itemForm->renderRow();
            }
        }
        ?>
        <tr id="drm_details_lastRow">
            <td class="vrac_detail_produit"></td>
            <td class="vrac_detail_numero_contrat">
                <a href="#" id="drm_vrac_details_addTemplate" class="btn_majeur btn_modifier drm_details_addTemplate">Ajouter un contrat</a>
            </td>
            <td class="vrac_detail_volume">
                <div id="drm_details_vrac_volume_total">
                    <strong>
                        <span class="drm_details_volume_somme">&Sigma;</span>
                        <span class="drm_details_volume_total"><?php echo $detail->sorties->vrac > 0 ? $detail->sorties->vrac : "0.00" ?></span>
                        <span class="drm_details_volume_unite unite">hl</span>
                    </strong>
                </div>      
            </td>            
            <?php if ($isTeledeclarationMode): ?>
                <td class="vrac_detail_type_document"></td>  
                <td class="vrac_detail_numero_document"></td>  
            <?php endif; ?>
            <td class="vrac_detail_remove"></td>
        </tr>
    </tbody>
</table>
