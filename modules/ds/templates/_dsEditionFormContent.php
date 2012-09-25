<?php
echo $form->renderHiddenFields();
echo $form->renderGlobalErrors();

?>

<fieldset id="dsEdition">
        <table id="ds_edition_table" class="drm_details_table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Appelation</th>
                <th>Volume DRM</th>
                <th>Volume Stock</th>
            </tr>
        </thead>
        <tbody class="ds_edition_tableBody">
            <?php
            foreach ($form as $itemForm){
                if($itemForm instanceof sfFormFieldSchema) {
                    include_partial('item',array('form' => $itemForm,'declarations' => $declarations));
                } else {
                    $itemForm->renderRow();
                }
            }
    ?>
            <tr id="ds_declaration_lastRow">
            <td class="ds_declaration_code"></td>
            <td class="ds_declaration_appelation">
                <a href="#" id="ds_declaration_new" class="btn_majeur btn_modifier ds_declaration_addTemplate">Ajouter un produit</a>
            </td>
            <td class="ds_declaration_volume_drm">
            </td>
            <td class="ds_declaration_volume_drm"></td>   
        </tr>
        </tbody>
        </table> 
</fieldset>



