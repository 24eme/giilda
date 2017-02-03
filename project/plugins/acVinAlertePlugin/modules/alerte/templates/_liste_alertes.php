<form action="<?php echo url_for('alerte_modification_statuts'); ?>" method="post" >

	<?php
	include_partial('history_alertes',array('alertesHistorique' => $alertesHistorique, 'consultationFilter' => $consultationFilter, 'page' => $page, 'nbPage' => $nbPage, 'nbResult' => $nbResult, 'modificationStatutForm' => $modificationStatutForm));
	?>

	<div  id="modification_alerte">
		<div class="row">
			<div class="col-xs-12">
					<h3>Modification des alertes sélectionnées</h3>
				</div>
			</div>
		 <?php
			echo $modificationStatutForm->renderHiddenFields();
			echo $modificationStatutForm->renderGlobalErrors();
		?>
		<div class="row">
			<div class="col-xs-12">
				<?php echo $modificationStatutForm['statut_all_alertes']->renderError(); ?>
				<?php echo $modificationStatutForm['statut_all_alertes']->renderLabel() ?>
				<?php echo $modificationStatutForm['statut_all_alertes']->render() ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<?php echo $modificationStatutForm['commentaire_all_alertes']->renderError(); ?>
				<?php echo $modificationStatutForm['commentaire_all_alertes']->renderLabel() ?>
				<?php echo $modificationStatutForm['commentaire_all_alertes']->render() ?>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-xs-12">
			<button type="submit" id="alerte_valid" class="btn btn-success btn_modifier pull-right">Modifier</button>
		</div>
	</div>
</form>
