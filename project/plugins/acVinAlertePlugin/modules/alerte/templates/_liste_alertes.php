<form action="<?php echo url_for('alerte_modification_statuts'); ?>" method="post" >

	<?php
	include_partial('history_alertes',array('alertesHistorique' => $alertesHistorique, 'consultationFilter' => $consultationFilter, 'page' => $page, 'nbPage' => $nbPage, 'nbResult' => $nbResult, 'modificationStatutForm' => $modificationStatutForm));
	?>

	<div id="modification_alerte">	
		<h2>Modification des alertes sélectionnées</h2>
		 <?php
			echo $modificationStatutForm->renderHiddenFields();
			echo $modificationStatutForm->renderGlobalErrors();
		?>
			
		<div class="bloc_form">
			<div class="ligne_form">
				<?php echo $modificationStatutForm['statut_all_alertes']->renderError(); ?>
				<?php echo $modificationStatutForm['statut_all_alertes']->renderLabel() ?>
				<?php echo $modificationStatutForm['statut_all_alertes']->render() ?> 
			</div>
			<div class="ligne_form ligne_form_alt">
				<?php echo $modificationStatutForm['commentaire_all_alertes']->renderError(); ?>
				<?php echo $modificationStatutForm['commentaire_all_alertes']->renderLabel() ?>
				<?php echo $modificationStatutForm['commentaire_all_alertes']->render() ?> 
			</div>
		</div>
		
		<div class="btn_form">
			<button type="submit" id="alerte_valid" class="btn_majeur btn_modifier">Modifier</button>
		</div>
	</div>
</form>
