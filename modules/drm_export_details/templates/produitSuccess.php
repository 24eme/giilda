<style>
#drm_export_details_table th {
    background: none repeat scroll 0 0 #ECEBEB;
    border: 1px solid #E5E4E4;
    vertical-align: middle;
    }
    
#drm_export_details_table td {  
    border: 1px solid #E5E4E4;
    padding: 10px;
    }    
</style>

<form method="post" action="<?php echo url_for('drm_export_details', $detail) ?>">
<div id="drm_export_details_form">
    <?php    
        echo $form->renderHiddenFields();
        echo $form->renderGlobalErrors();
    ?>
    <table id="drm_export_details_table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Numéro contrat</th>
                <th>Destination</th>
                <th>Volumes</th>
                <th>Dates</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="drm_export_details_tableBody">
        <?php
        foreach ($form as $itemForm){
            if($itemForm instanceof sfFormFieldSchema) {
                include_partial('item',array('form' => $itemForm));
            } else {
                $itemForm->renderRow();
            }
        }
        ?>
        </tbody>
    </table>
</div>
<input type="submit" value="Valider" />
<a href="#" id="drm_export_details_addTemplate" class="btn_majeur btn_orange">Ajouter</a>
</form>

<script type="text/javascript">
    $(document).ready( function()
    {
            $('#drm_export_details_addTemplate').click(function()
            {
                $($('#template_export').html().replace(/var---nbItem---/g, UUID.generate())).appendTo($('#drm_export_details_tableBody'));
                $('.autocomplete').combobox();
                
            });
            
            $('.drm_export_details_remove').live('click',function()
            {
                $(this).parent().parent().remove();
            });
            
            
     });
        
</script>

<?php include_partial('templateItem', array('form' => $form->getFormTemplate())); ?>
