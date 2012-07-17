<form style="text-align: center;" id="form_produit_declaration" method="post" action="<?php echo url_for("drm_edition_produit_ajout", $drm) ?>">
	<?php echo $form->renderHiddenFields(); ?>
	<?php echo $form['hashref']->render(); ?>
</form>
<br />