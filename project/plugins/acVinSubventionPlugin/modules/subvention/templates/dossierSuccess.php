<ol class="breadcrumb">
    <li class="active"><a href="<?php echo "#" ?>">Subvention</a></li>
</ol>

<form class="form-horizontal" role="form" action="<?php echo url_for('subvention_dossier', array('identifiant' => $subvention->identifiant,'operation' => $subvention->operation)) ?>" method="post" enctype="multipart/form-data">
<div class="form-group">
	<div class="col-xs-8 col-xs-offset-4">
		<?php echo $form['file']->renderError() ?>
	</div>
	<div class="col-xs-3 col-xs-offset-1">
		<?php echo $form['file']->renderLabel() ?>
	</div>
	<div class="col-xs-6">
		<?php echo $form['file']->render() ?>
	</div>
  <div class="col-xs-6 text-right">
      <button type="submit" class="btn btn-default btn-lg btn-upper"><?php if($subvention->isNew()): ?>Ajouter<?php else: ?>Modifier<?php endif; ?></button>
  </div>
</div>
</form>
