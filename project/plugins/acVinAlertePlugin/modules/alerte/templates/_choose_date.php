<div id="modification_alerte">	
    <span>Utiliser la date suivante comme date courante</span>
	<form action="<?php echo url_for('alerte'); ?>" method="POST">
		<?php
		echo $dateForm->renderHiddenFields();
		echo $dateForm->renderGlobalErrors();
		?>
	
		<div class="bloc_form">
				<?php echo $dateForm['date']->renderError(); ?>
				<?php echo $dateForm['date']->renderLabel() ?>
				<?php echo $dateForm['date']->render() ?>                            
		</div>
            
            
		<div class="btn_form">
			<button type="submit" id="alerte_valid" class="btn_majeur btn_modifier">Enregistrer</button>
		</div>
	</form>
		<div class="bloc_form">
                    <a class="btn_majeur btn_nouveau" href="<?php echo url_for('alerte_generate_all'); ?>">
                        <span>Générer les alertes</span>
                   </a>
		</div>
</div>