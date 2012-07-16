<form method="post" action="<?php echo url_for('drm_vrac_details', $detail) ?>">
<div id="drm_vrac_details_form">
    <?php
    echo $form->renderHiddenFields();
    echo $form->renderGlobalErrors();
    foreach ($form as $itemForm){
        if($itemForm instanceof sfFormFieldSchema) {
            include_partial('item',array('form' => $itemForm));
        } else {
            $itemForm->renderRow();
        }
    }

    ?>
</div>
<input type="submit" value="Valider" />
<a href="#" id="drm_vrac_details_addTemplate" class="btn_majeur btn_orange">Ajouter</a>
</form>

<script type="text/javascript">
    $(document).ready( function()
    {
            $('#drm_vrac_details_addTemplate').click(function()
            {
                $($('#template_vrac').html().replace(/var---nbItem---/g, UUID.generate())).appendTo($('#drm_vrac_details_form'));
                $('.autocomplete').combobox();
                
            });
            
            $('.drm_vrac_details_remove').live('click',function()
            {
                $(this).parent().remove();
            });
            
            
     });
        
</script>

<?php include_partial('templateItem', array('form' => $form->getFormTemplate())); ?>
