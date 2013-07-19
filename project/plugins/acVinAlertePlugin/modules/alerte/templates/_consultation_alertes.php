<div id="consultation_alerte">
	<h2>Consultation des alertes</h2>
	
	<form action="<?php echo url_for('alerte'); ?>" method="GET">
		<?php
		echo $form->renderHiddenFields();
		echo $form->renderGlobalErrors();
		?>
		
		<div class="bloc_form">
			<div class="ligne_form">
				<?php echo $form['identifiant']->renderError(); ?>
				<?php echo $form['identifiant']->renderLabel() ?>
				<?php echo $form['identifiant']->render() ?> 
			</div>
			<div class="ligne_form ligne_form_alt">
				<?php echo $form['region']->renderError(); ?>
				<?php echo $form['region']->renderLabel() ?>
				<?php echo $form['region']->render() ?> 
			</div>
			<div class="ligne_form">
				<?php echo $form['type_alerte']->renderError(); ?>
				<?php echo $form['type_alerte']->renderLabel() ?>
				<?php echo $form['type_alerte']->render() ?> 
			</div>
			<div class="ligne_form ligne_form_alt">
				<?php echo $form['statut_courant']->renderError(); ?>
				<?php echo $form['statut_courant']->renderLabel() ?>
				<?php echo $form['statut_courant']->render() ?> 
			</div>
			<div class="ligne_form">
				<?php echo $form['campagne']->renderError(); ?>
				<?php echo $form['campagne']->renderLabel() ?>
				<?php echo $form['campagne']->render() ?> 
			</div>
		</div>
			
		<div class="btn_form">
			<a href="<?php echo url_for('alerte'); ?>" class="btn_majeur btn_modifier">Réinitialisation</a>
			<button type="submit" id="alerte_search_valid" class="btn_majeur btn_valider">Rechercher</button>
		</div>	
	</form>
</div>