<div class="modal modal-autoshow">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">Ajouter un type de CRD</h4>
            </div>
            <form class="form-horizontal" action="<?php echo url_for('drm_ajout_crd', $form->getObject()); ?>" method="post">
            <div class="modal-body">
                <?php echo $form->renderHiddenFields(); ?>
                <?php echo $form->renderGlobalErrors(); ?>
                <div class="form-group">
                    <div class="col-sm-12">
                        <?php echo $form['couleur_crd_'.$regime]->renderError(); ?>
                        <?php echo $form['couleur_crd_'.$regime]->renderLabel() ?>    
                        <?php echo $form['couleur_crd_'.$regime]->render(array('class' => 'couleur_crd_choice')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <?php echo $form['litrage_crd_'.$regime]->renderError(); ?>
                        <?php echo $form['litrage_crd_'.$regime]->renderLabel() ?>    
                        <?php echo $form['litrage_crd_'.$regime]->render(); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <?php echo $form['stock_debut_'.$regime]->renderError(); ?>
                        <?php echo $form['stock_debut_'.$regime]->renderLabel() ?>    
                        <?php echo $form['stock_debut_'.$regime]->render(); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <?php echo $form['genre_crd_'.$regime]->renderError(); ?>
                        <?php echo $form['genre_crd_'.$regime]->renderLabel() ?>    
                        <?php echo $form['genre_crd_'.$regime]->render(); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Abandonner</button>
                <button type="submit" class="btn btn-success">Ajouter une ligne CRD</button>
            </div>
            </form>
        </div>
    </div>
</div>