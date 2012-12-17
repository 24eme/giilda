<div id="consultation_alerte">
	<h2>Consultation des alertes</h2>
	
	<form action="<?php echo url_for('alerte'); ?>" method="POST">
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
				<?php echo $form['region_alerte']->renderError(); ?>
				<?php echo $form['region_alerte']->renderLabel() ?>
				<?php echo $form['region_alerte']->render() ?> 
			</div>
			<div class="ligne_form">
				<?php echo $form['type_alerte']->renderError(); ?>
				<?php echo $form['type_alerte']->renderLabel() ?>
				<?php echo $form['type_alerte']->render() ?> 
			</div>
			<div class="ligne_form ligne_form_alt">
				<?php echo $form['statut_alerte']->renderError(); ?>
				<?php echo $form['statut_alerte']->renderLabel() ?>
				<?php echo $form['statut_alerte']->render() ?> 
			</div>
			<div class="ligne_form">
				<?php echo $form['campagne_alerte']->renderError(); ?>
				<?php echo $form['campagne_alerte']->renderLabel() ?>
				<?php echo $form['campagne_alerte']->render() ?> 
			</div>
		</div>
			
		<div class="btn_form">
			<button type="submit" id="alerte_search_valid" class="btn_majeur btn_valider">Rechercher</button>
		</div>	
	</form>
</div>