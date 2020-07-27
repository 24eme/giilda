<div class="modal fade" aria-labelledby="Validation du dossier" id="statuerForm">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" action="<?php echo url_for('subvention_visualisation', $subvention) ?>" role="form" class="form-horizontal">
				<div class="modal-header">
					<a href="" class="close" aria-hidden="true">&times;</a>
					<h4 class="modal-title">Approuver le dossier</h4>
				</div>
				<div class="modal-body">
                    <?php echo $form->renderHiddenFields(); ?>
                    <?php echo $form->renderGlobalErrors(); ?>
                    <div class="form-group <?php if($form['statut']->hasError()): ?>has-error<?php endif; ?>">
                        <?php echo $form['statut']->renderError() ?>
                        <?php echo $form['statut']->renderLabel(null, array("class" => "col-sm-3 control-label")); ?>
                        <div class="col-sm-5">
                            <?php echo $form['statut']->render(array('class' => 'form-control', 'placeholder' => "")) ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($form['commentaire']->hasError()): ?>has-error<?php endif; ?>">
                        <?php echo $form['commentaire']->renderError() ?>
                        <?php echo $form['commentaire']->renderLabel(null, array("class" => "col-sm-3 control-label")); ?>
                        <div class="col-sm-9">
                            <?php echo $form['commentaire']->render(array('class' => 'form-control', 'placeholder' => "")) ?>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Annuler</button>
					<button type="submit" class="btn btn-success btn pull-right">Valider</button>
				</div>
			</form>
		</div>
	</div>
</div>
