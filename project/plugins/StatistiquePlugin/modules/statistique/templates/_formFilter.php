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
	<div class="row collapse<?php if($collapseIn): ?> in<?php endif; ?>" id="advanced-query">
		<div class="col-xs-12"><h4>Filtres <a href="javascript:void(0);" class="btn btn-default btn-xs btn_ajouter_ligne_template" data-container="#advancedFilters" data-template="#template_advancedFilterForm"><span class="glyphicon glyphicon-plus"></span></a></h4></div>
		<div class="col-xs-12" id="advancedFilters">
		<?php 
			foreach ($form['advanced'] as $key => $advancedFilterForm) {
	        	include_partial('advancedFilterForm', array('form' => $advancedFilterForm));
	        } 
		?> 
		</div>
		<script id="template_advancedFilterForm" type="text/x-jquery-tmpl">
			<?php echo include_partial('advancedFilterForm', array('form' => $form->getFormTemplate())); ?>
		</script>
	</div>
</form>