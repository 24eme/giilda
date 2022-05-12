<div class="modal modal-autoshow">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">Ajouter un produit hors du catalogue produit</h4>
            </div>
            <form class="form-horizontal" action="" method="post">
              <div class="modal-body">
                <?php echo $form->renderHiddenFields(); ?>
                <?php echo $form->renderGlobalErrors(); ?>
                <div class="form-group">
                  <?php echo $form['inao']->renderError(); ?>
                  <?php echo $form['inao']->renderLabel(null, array('class' => 'control-label col-sm-4')); ?>
                  <div class="col-sm-4 text-left">
                  <?php echo $form['inao']->render(array('class' => 'form-control')); ?>
                  </div>
                </div>
                <div class="form-group">
                  <?php echo $form['denomination_complementaire']->renderError(); ?>
                  <?php echo $form['denomination_complementaire']->renderLabel(null, array('class' => 'control-label col-sm-4')); ?>
                  <div class="col-sm-8 text-left">
                  <?php echo $form['denomination_complementaire']->render(array('class' => 'form-control')); ?>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="<?php echo url_for('drm_choix_produit', $drm) ?>" class="btn btn-default pull-left">Abandonner</a>
                <button type="submit" class="btn btn-success">Ajouter le produit</button>
            </div>
            </form>
        </div>
    </div>
</div>
