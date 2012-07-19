<?php
echo $form->renderHiddenFields();
echo $form->renderGlobalErrors();
?>
<table id="drm_vrac_details_table">
    <thead>
        <tr>
            <th>Produit</th>
            <th>Numéro contrat</th>
            <th>Volumes</th>
            <th>Dates</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="drm_vrac_details_tableBody">
    <?php
    foreach ($form as $itemForm){
        if($itemForm instanceof sfFormFieldSchema) {
            include_partial('item',array('form' => $itemForm, 'detail' => $detail));
        } else {
            $itemForm->renderRow();
        }
    }
    ?>
    </tbody>
</table>