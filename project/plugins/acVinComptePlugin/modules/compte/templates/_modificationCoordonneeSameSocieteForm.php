<div class="form-group<?php if($form['adresse_societe']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $form['adresse_societe']->renderError(); ?>
        <?php echo $form['adresse_societe']->renderLabel('Même adresse que la société ?', array('class' => 'col-xs-4 control-label')); ?>
        <div class="col-xs-8"><?php echo $form['adresse_societe']->render(); ?></div>
        
</div>