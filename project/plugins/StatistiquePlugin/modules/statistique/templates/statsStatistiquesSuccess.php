<ol class="breadcrumb">
    <li><a href="<?php echo url_for('statistiques') ?>">Statistique</a></li>
    <li><a href="<?php echo url_for('statistiques_stats') ?>" class="active">Statistiques</a></li>
</ol>

<div class="row" id="statistiques">
    <div class="col-xs-12">
	    <form class="popup_form form_delay form-horizontal" id="form_ajout" action="<?php echo url_for('statistiques_stats') ?>" method="post">
			<?php echo $form->renderGlobalErrors() ?>
			<?php echo $form->renderHiddenFields() ?>
	
			<div class="form-group">
				<span class="error has-error"><?php echo $form['doc.mouvements.appellation']->renderError() ?></span>
		        <?php echo $form['doc.mouvements.appellation']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
		        <div class="col-xs-8"><?php echo $form['doc.mouvements.appellation']->render(); ?></div>
			</div>
			<div class="form-group">
				<span class="error has-error"><?php echo $form['doc.declarant.famille']->renderError() ?></span>
		        <?php echo $form['doc.declarant.famille']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
		        <div class="col-xs-8"><?php echo $form['doc.declarant.famille']->render(); ?></div>
			</div>
	
			<div class="form-group">
				<span class="error has-error"><?php echo $form['doc.mouvements.region']->renderError() ?></span>
		        <?php echo $form['doc.mouvements.region']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
		        <div class="col-xs-8"><?php echo $form['doc.mouvements.region']->render(); ?></div>
			</div>
	
			<div class="form-group">
				<span class="error has-error"><?php echo $form['doc.mouvements.date']->renderError() ?></span>
		        <?php echo $form['doc.mouvements.date']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
		        <div class="col-xs-8"><?php echo $form['doc.mouvements.date']->render(); ?></div>
			</div>
	
			<div class="form-group">
				<span class="error has-error"><?php echo $form['lastyear']->renderError() ?></span>
		        <span class="text-muted"><?php echo $form['lastyear']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?></span>
		        <div class="col-xs-8"><?php echo $form['lastyear']->render(array('disabled' => 'disabled')); ?></div>
			</div>
	
			<div class="form-group">
				<span class="error has-error"><?php echo $form['statistiques']->renderError() ?></span>
		        <?php echo $form['statistiques']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
		        <div class="col-xs-8"><?php echo $form['statistiques']->render(); ?></div>
			</div>
			
			<div class="form_ligne_btn" style="margin-top:20px;">
				<div class="col-xs-6"><a class="btn btn-default" href="<?php echo url_for('statistiques_stats') ?>">Annuler</a></div>
				<div class="col-xs-6 text-right"><button name="valider" class="btn btn-success" type="submit">Valider</button></div>
			</div>
		</form>
    	
    	<hr />
    		
    		
    </div>
</div>
