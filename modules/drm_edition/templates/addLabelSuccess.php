<div id="contenu" style="background: #fff;">
	<section id="principal">
		<form method="POST">
		<div style="margin-bottom: 10px">
			<?php echo $form->renderHiddenFields(); ?>
			<?php echo $form->render(); ?>
		</div>
		<a href="<?php echo url_for('drm_edition_detail', $detail); ?>" id="drm_cooperative_details_annuler" class="btn_majeur btn_annuler">Annuler</a>
		<button type="submit" class="btn_majeur btn_valider" >Valider</button>
		</form>
	</section>
</div>