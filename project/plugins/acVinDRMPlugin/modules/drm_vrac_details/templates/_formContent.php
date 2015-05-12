<?php
echo $form->renderHiddenFields();
echo $form->renderGlobalErrors();
?>
<table id="drm_vrac_details_table" class="drm_details_table">
    <thead>
        <tr>
            <th>Produit</th>
            <th>Numéro contrat</th>
            <th>Volumes</th>
            <th>Dates</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="drm_vrac_details_tableBody" class="drm_details_tableBody">
    <?php
    foreach ($form as $itemForm){
        if($itemForm instanceof sfFormFieldSchema) {
            include_partial('item',array('form' => $itemForm, 'detail' => $detail));
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
            <td class="vrac_detail_date_enlevement"></td>   
            <td class="vrac_detail_remove"></td>
        </tr>
    </tbody>
</table>
