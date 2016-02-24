<form action="<?php echo $url ?>" method="get" id="statistiques-form">
	<?php echo $form->renderHiddenFields(); ?>
	<?php echo $form->renderGlobalErrors(); ?>
	<div class="row">
		<div class="col-xs-10">
			<?php echo $form['q']->renderError() ?>
			<?php echo $form['q']->render(array('class' => 'form-control input-lg', 'placeholder' => 'Rechercher', 'autofocus' => 'autofocus')) ?>
			<a class="pull-right" style="margin: 5px 0;" role="button" data-toggle="collapse" href="#advanced-query" aria-expanded="false">[+] Avancé</a>
		</div>
	</div>
	<div class="row collapse<?php if($collapseIn): ?> in<?php endif; ?>" id="advanced-query">
		<div class="col-xs-12"><h4>Filtres</h4></div>
		<div class="col-xs-12" id="advancedFilters">
		<?php 
			foreach ($form['advanced'] as $key => $advancedFilterForm) {
	        	include_partial('advancedFilterForm', array('form' => $advancedFilterForm));
	        } 
		?> 
		</div>
		<div class="row">
			<div class="col-xs-7">&nbsp;</div>
			<div class="col-xs-1">
				<a href="javascript:void(0);" class="btn btn-success btn-xs btn_ajouter_ligne_template" data-container="#advancedFilters" data-template="#template_advancedFilterForm"><span class="glyphicon glyphicon-plus"></span></a>
			</div>
		</div>
		<script id="template_advancedFilterForm" type="text/x-jquery-tmpl">
			<?php echo include_partial('advancedFilterForm', array('form' => $form->getFormTemplate())); ?>
		</script>
	</div>
	<div class="row">
		<div class="col-xs-10">
    		<a id="statistiques-csv" href="<?php echo $urlCsv ?>" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-download"></span> CSV</a>
			<button type="submit" class="btn btn-default btn-lg pull-right">Filtrer</button>
		</div>
	</div>
</form>