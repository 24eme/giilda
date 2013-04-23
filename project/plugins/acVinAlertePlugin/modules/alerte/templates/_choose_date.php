<div id="modification_alerte">	
	<form action="<?php echo url_for('alerte'); ?>" method="POST">
		<?php
		echo $dateForm->renderHiddenFields();
		echo $dateForm->renderGlobalErrors();
		?>
	
		<div class="bloc_form">
				<?php echo $dateForm['date']->renderError(); ?>
				<?php echo $dateForm['date']->renderLabel() ?>
				<?php echo $dateForm['date']->render() ?>
                            
				<?php echo $dateForm['debug']->renderError(); ?>
				<?php echo $dateForm['debug']->renderLabel() ?>
				<?php echo $dateForm['debug']->render() ?> 
		</div>
		
		<div class="btn_form">
			<button type="submit" id="alerte_valid" class="btn_majeur btn_modifier">Modifier</button>
		</div>
	</form>
</div>