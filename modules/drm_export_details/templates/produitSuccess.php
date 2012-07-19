<div id="contenu" style="background: #fff;">
	<section id="principal">
		<form id="drm_export_details_form" method="post" action="<?php echo url_for('drm_export_details', $detail) ?>">
		<div id="drm_export_details_form_content" style="margin-bottom: 10px;">
		    <?php
                    include_partial('formContent',array('form' => $form, 'detail' => $detail));
                    ?>
		</div>
		<a href="<?php echo url_for('drm_edition_detail', $detail); ?>" id="drm_export_details_annuler" class="btn_majeur btn_annuler">Annuler</a>
		<a href="#" id="drm_export_details_addTemplate" class="btn_majeur btn_modifier">Ajouter</a>
		<button type="submit" class="btn_majeur btn_valider" >Valider</button>
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
		            
		            $('#drm_export_details_form').submit(function()
                            {
                                $.post($(this).attr('action'),
                                    $(this).serialize(),
                                    function(data)
                                    {
                                        if(!data.success)
                                        {
                                            $('#drm_export_details_form_content').html(data.content);
                                            $('.autocomplete').combobox();
                                        }
                                    }, "json");

                            return false;
                            });	
		     });
		        
		</script>

		<?php include_partial('templateItem', array('form' => $form->getFormTemplate(), 'detail' => $detail)); ?>
	</section>
</div>