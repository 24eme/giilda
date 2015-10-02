<?php use_helper('DRM'); ?>

<span style="font-weight: bold; padding-left: 10px;"><?php echo getHelpMsgText('drm_mouvements_detail_export_texte1'); ?></span> 
     
<div id="contenu">
	<section id="principal">
		<form id="drm_export_details_form" class="drm_details_form" method="post" action="<?php echo url_for('drm_export_details', $detail) ?>">
		<div id="drm_export_details_form_content" class="drm_details_form_content" style="margin-bottom: 10px;">
		    <?php
                    include_partial('formContent',array('form' => $form, 'detail' => $detail, 'isTeledeclarationMode' => $isTeledeclarationMode));
                    ?>
		</div>
		<a href="<?php echo url_for('drm_edition_detail', $detail); ?>" id="drm_export_details_annuler" class="btn_majeur btn_annuler drm_details_annuler">Abandonner</a>
		<button type="submit" class="btn_majeur btn_valider" >Valider</button>
		</form>
                <?php include_partial('templateItem', array('form' => $form->getFormTemplate(), 'detail' => $detail, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>
	</section>
</div>