<form class="popup_form form_delay form-horizontal" id="form_ajout" action="<?php echo url_for('produit_nouveau') ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<div class="form-group">
		<span class="error has-error"><?php echo $form['certifications']->renderError() ?></span>
		<?php echo $form['certifications']->renderLabel(null, array("class" => "col-xs-4 control-label")) ?>
		<div class="col-xs-8"><?php echo $form['certifications']->render() ?></div>
	</div>
	<div class="form-group">
		<span class="error has-error"><?php echo $form['genres']->renderError() ?></span>
		<?php echo $form['genres']->renderLabel(null, array("class" => "col-xs-4 control-label")) ?>
		<div class="col-xs-8"><?php echo $form['genres']->render() ?></div>
	</div>
	<div class="form-group">
		<span class="error has-error"><?php echo $form['appellations']->renderError() ?></span>
        <?php echo $form['appellations']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $form['appellations']->render(); ?></div>
	</div>
	<div class="form-group">
		<span class="error has-error"><?php echo $form['mentions']->renderError() ?></span>
		<?php echo $form['mentions']->renderLabel(null, array("class" => "col-xs-4 control-label")) ?>
		<div class="col-xs-8"><?php echo $form['mentions']->render() ?></div>
	</div>
	<div class="form-group">
		<span class="error has-error"><?php echo $form['lieux']->renderError() ?></span>
		<?php echo $form['lieux']->renderLabel(null, array("class" => "col-xs-4 control-label")) ?>
		<div class="col-xs-8"><?php echo $form['lieux']->render() ?></div>
	</div>
	<div class="form-group">
		<span class="error has-error"><?php echo $form['couleurs']->renderError() ?></span>
		<?php echo $form['couleurs']->renderLabel(null, array("class" => "col-xs-4 control-label")) ?>
		<div class="col-xs-8"><?php echo $form['couleurs']->render() ?></div>
	</div>
	<div class="form-group">
		<span class="error has-error"><?php echo $form['cepages']->renderError() ?></span>
		<?php echo $form['cepages']->renderLabel(null, array("class" => "col-xs-4 control-label")) ?>
		<div class="col-xs-8"><?php echo $form['cepages']->render() ?></div>
	</div>
	<div class="form_ligne_btn" style="margin-top:20px;">
		<div class="col-xs-6"><a class="btn btn-default" href="<?php echo url_for('produits') ?>">Annuler</a></div>
		<div class="col-xs-6 text-right"><button name="valider" class="btn btn-success" type="submit">Valider</button></div>
	</div>
</form>
