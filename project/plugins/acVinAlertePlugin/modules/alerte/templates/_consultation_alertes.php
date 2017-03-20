<div id="consultation_alerte">
	<div class="row">
		<div class="col-xs-12">
			<h2>Consultation des alertes</h2>
		</div>
	</div>
		<form action="<?php echo url_for('alerte'); ?>" method="GET">
		<?php
		echo $form->renderHiddenFields();
		echo $form->renderGlobalErrors();
		?>

		<div class="row">
			<div class="col-xs-12">
				<?php echo $form['identifiant']->renderError(); ?>
				<?php echo $form['identifiant']->renderLabel() ?>
				<?php echo $form['identifiant']->render(array('class' => 'form-control select2autocomplete input-md', 'placeholder' => 'Rechercher')) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 ">
				<?php echo $form['region']->renderError(); ?>
				<?php echo $form['region']->renderLabel() ?>
				<?php echo $form['region']->render() ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<?php echo $form['type_alerte']->renderError(); ?>
				<?php echo $form['type_alerte']->renderLabel() ?>
				<?php echo $form['type_alerte']->render() ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 ">
				<?php echo $form['statut_courant']->renderError(); ?>
				<?php echo $form['statut_courant']->renderLabel() ?>
				<?php echo $form['statut_courant']->render() ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<?php echo $form['campagne']->renderError(); ?>
				<?php echo $form['campagne']->renderLabel() ?>
				<?php echo $form['campagne']->render() ?>
			</div>
		</div>
		<br/>
	<div class="row">
		<div class="col-xs-12">
			<a href="<?php echo url_for('alerte'); ?>" class="btn btn-default pull-left">Réinitialisation</a>
			<button type="submit" id="alerte_search_valid" class="btn btn-success pull-right">Rechercher</button>
		</div>
	</form>

</div>
