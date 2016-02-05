<form action="<?php echo $url ?>" method="get">
	<?php echo $form->renderHiddenFields(); ?>
	<?php echo $form->renderGlobalErrors(); ?>
	<div class="row">
		<div class="col-xs-10">
			<?php echo $form['q']->renderError() ?>
			<?php echo $form['q']->render(array('class' => 'form-control input-lg', 'placeholder' => 'Rechercher')) ?>
			<a class="pull-right" role="button" data-toggle="collapse" href="#advanced-query" aria-expanded="false">[+] Avancé</a>
		</div>
		<div class="col-xs-2">
			<button type="submit" class="btn btn-default btn-lg">Filtrer</button>
		</div>
	</div>
	<div class="row collapse" id="advanced-query">
		<select multiple class="form-control">
			<?php foreach ($fields as $field): ?>
			<option><?php echo $field ?></option>
			<?php endforeach;?>
		</select>
	</div>
</form>