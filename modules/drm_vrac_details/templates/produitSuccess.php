<div id="contenu" style="background: #fff;">
	<section id="principal" style="width:100%;">
		<form id="drm_vrac_details_form" class="drm_details_form" method="post" action="<?php echo url_for('drm_vrac_details', $detail) ?>">
                   <div id="drm_vrac_details_form_content" class="drm_details_form_content" style="margin-bottom: 10px;">
                        <?php
                        include_partial('formContent',array('form' => $form, 'detail' => $detail));
                        ?>
                    </div>
                    <a href="<?php echo url_for('drm_edition_detail', $detail); ?>" id="drm_vrac_details_annuler" class="btn_majeur btn_annuler drm_details_annuler">Annuler</a>
                    <button type="submit" class="btn_majeur btn_valider" >Valider</button>
		</form>
		<?php include_partial('templateItem', array('form' => $form->getFormTemplate(), 'detail' => $detail)); ?>
	</section>
</div>