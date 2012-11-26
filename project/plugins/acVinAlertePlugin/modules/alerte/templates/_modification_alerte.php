<div id="modification_alerte">	
	<h2>Modification de l'alerte</h2>
	
	<form action="<?php echo url_for('alerte_modification', array('type_alerte' => $alerte->type_alerte, 'id_document' => $alerte->id_document)); ?>" method="POST">
		<?php
		echo $form->renderHiddenFields();
		echo $form->renderGlobalErrors();
		?>
	
		<div class="bloc_form">
			<div class="ligne_form">
				<?php echo $form['statut']->renderError(); ?>
				<?php echo $form['statut']->renderLabel() ?>
				<?php echo $form['statut']->render() ?>
			</div>
			<div class="ligne_form ligne_form_alt">
				<?php echo $form['commentaire']->renderError(); ?>
				<?php echo $form['commentaire']->renderLabel() ?>
				<?php echo $form['commentaire']->render() ?> 
			</div>
		</div>
		
		<div class="btn_form">
			<button type="submit" id="alerte_valid" class="btn_majeur btn_modifier">Modifier</button>
		</div>
	</form>
</div>