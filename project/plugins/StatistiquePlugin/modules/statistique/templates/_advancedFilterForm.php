<div class="row advancedFilter" style="margin-bottom: 10px;">
	<div class="col-xs-3">
		<?php echo $form['fk']->renderError(); ?>
		<?php echo $form['fk']->render(array('class' => 'form-control input-xs')); ?>
	</div>
	<div class="col-xs-1">
		<?php echo $form['fo']->renderError(); ?>
		<?php echo $form['fo']->render(array('class' => 'form-control input-xs')); ?>
	</div>
	<div class="col-xs-3">
		<?php echo $form['fv']->renderError(); ?>
		<?php echo $form['fv']->render(array('class' => 'form-control input-xs')); ?>
	</div>
	<div class="col-xs-1">
		<a href="javascript:initCollectionDeleteTemplate();" class="btn btn-danger btn-xs btn_rm_ligne_template" data-container="div.advancedFilter"><span class="glyphicon glyphicon-remove"></span></a>
	</div>
</div>